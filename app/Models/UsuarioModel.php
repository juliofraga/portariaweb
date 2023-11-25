<?php
    class UsuarioModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
        }

        //Verificar se login informado para cadastro já está cadastrado no sistema
        public function verificaLogin($login)
        {
            try {
                $this->db->query("SELECT id FROM usuarios WHERE login = :login");
                $this->db->bind("login", $login);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            }   
        }

        public function cadastrarUsuario($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO usuarios(nome, login, senha, perfil, created_at) VALUES (:nome, :login, :senha, :perfil, :dataHora)");
                $this->db->bind("nome", $dados['nome']);
                $this->db->bind("login", $dados['login']);
                $this->db->bind("senha", $dados['senha']);
                $this->db->bind("perfil", $dados['perfil']);
                $this->db->bind("dataHora", $dataHora);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }   
        }

        public function buscaUsuarioCookie($valor, $hostname)
        {
            try {
                $this->db->query("SELECT u.login FROM usuarios u, cookie c WHERE u.id = c.usuario_id and c.valor = :valor and c.hostname = :hostname");
                $this->db->bind("valor", $valor);
                $this->db->bind("hostname", $hostname);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            } 
        }

        public function validaLogin($login)
        {
            try {
                $this->db->query("SELECT id, nome, senha, situacao, login, alterar_senha, primeiro_acesso, perfil FROM usuarios WHERE login = :login");
                $this->db->bind("login", $login);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return $this->db->results();
                else
                    return null;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }   
        }

        public function registraCookie($usuario_id, $nome, $valor, $hostname, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO cookie(usuario_id, nome, valor, hostname, created_at) VALUES (:usuario, :nome, :valor, :hostname, :dataHora)");
                $this->db->bind("usuario", $usuario_id);
                $this->db->bind("nome", $nome);
                $this->db->bind("valor", $valor);
                $this->db->bind("hostname", $hostname);
                $this->db->bind("dataHora", $dataHora);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }   
        }

        public function registraPrimeiroAcesso($id, $dataHora)
        {
            try {
                $this->db->query("UPDATE usuarios SET primeiro_acesso = :acesso WHERE id = :id");
                $this->db->bind("id", $id);
                $this->db->bind("acesso", $dataHora);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function registraAcesso($id, $dataHora)
        {
            try {
                $this->db->query("UPDATE usuarios SET ultimo_acesso = :acesso, login_error = :zero WHERE id = :id");
                $this->db->bind("id", $id);
                $this->db->bind("zero", 0);
                $this->db->bind("acesso", $dataHora);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function registraErroLogin($id)
        {
            try {
                $this->db->query("UPDATE usuarios SET login_error = login_error+1 WHERE id = :id");
                $this->db->bind("id", $id);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function retornaLoginError($id)
        {
            try {
                $this->db->query("SELECT login_error FROM usuarios WHERE id = :id");
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function bloqueiaUsuario($id)
        {
            try {
                $this->db->query("UPDATE usuarios SET situacao = :situacao WHERE id = :id");
                $this->db->bind("id", $id);
                $this->db->bind("situacao", 2);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function removeCookie($usuario_id, $valor)
        {
            try {
                $this->db->query("DELETE FROM cookie WHERE usuario_id = :usuario and valor = :valor");
                $this->db->bind("usuario", $usuario_id);
                $this->db->bind("valor", $valor);
                $this->db->execQuery();
            } catch (\Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaUsuarios($attr = null, $pag = null, $origem = null)
        {
            try {
                if($attr == null){
                    $limite = "";
                    if($origem == "consulta"){
                        $numReg = NUM_REG_PAGINA;
                        $limite = "LIMIT $pag, $numReg";
                    }
                    $this->db->query("SELECT * FROM usuarios WHERE perfil <> 'Superadmin' order by nome ASC $limite");
                }else if($attr == "todos"){
                    $this->db->query("SELECT * FROM usuarios order by nome ASC");
                }else if($attr == "operador"){
                    $this->db->query("SELECT * FROM usuarios WHERE perfil = :operador and situacao = :situacao order by nome ASC");
                    $this->db->bind("operador", "Operador");
                    $this->db->bind("situacao", 0);
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaUsuarioPorNome($nome, $pag)
        {
            try {
                $numReg = NUM_REG_PAGINA;
                $filter = "nome like '%". $nome . "%'";
                $this->db->query("SELECT * FROM usuarios WHERE perfil <> 'Superadmin' and $filter order by nome ASC LIMIT $pag, $numReg");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function buscaUsuarioPorId($id)
        {
            try {
                $this->db->query("SELECT * FROM usuarios WHERE id = :id");
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }   
        }

        public function alteraUsuario($dados, $rotina, $dateTime)
        {
            try {
                if($rotina == "senha"){
                    $this->db->query("UPDATE usuarios SET nome = :nome, perfil = :perfil, senha = :senha, updated_at = :dataHora, alterar_senha = :alterar_senha, situacao = :situacao, login_error = :login_error WHERE id = :id");
                    $this->db->bind("nome", $dados["nome"]);
                    $this->db->bind("senha", $dados["senha"]);
                    $this->db->bind("perfil", $dados["perfil"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("id", $dados["id"]);
                    $this->db->bind("alterar_senha", 'S');
                    $this->db->bind("situacao", 0);
                    $this->db->bind("login_error", 0);
                }else if($rotina == "nome-perfil"){
                    $this->db->query("UPDATE usuarios SET nome = :nome, perfil = :perfil, updated_at = :dataHora WHERE id = :id");
                    $this->db->bind("nome", $dados["nome"]);
                    $this->db->bind("perfil", $dados["perfil"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("id", $dados["id"]);
                }else if ($rotina == "senha_update"){
                    $this->db->query("UPDATE usuarios SET senha = :senha, updated_at = :dataHora, alterar_senha = :alterar_senha WHERE id = :id");
                    $this->db->bind("senha", $dados["senha"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("alterar_senha", "N");
                    $this->db->bind("id", $dados["id"]);
                }else if ($rotina == "senha-nome"){
                    $this->db->query("UPDATE usuarios SET nome = :nome, senha = :senha, updated_at = :dataHora, alterar_senha = :alterar_senha WHERE id = :id");
                    $this->db->bind("senha", $dados["senha"]);
                    $this->db->bind("nome", $dados["nome"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("alterar_senha", "N");
                    $this->db->bind("id", $dados["id"]);
                }
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            }
        }

        public function ativaInativaUsuario($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE usuarios SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
                $this->db->bind("situacao", $situacao);
                $this->db->bind("dataHora", $dateTime);
                $this->db->bind("id", $id);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            }  
        }

        public function buscaUsuarioPorLogin($login)
        {
            try {
                $this->db->query("SELECT * FROM usuarios WHERE login = :login AND situacao = :situacao");
                $this->db->bind("login", $login);
                $this->db->bind("situacao", 0);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }   
        }

        public function numeroTotalUsuarios($filtro = null)
        {
            try {
                $filter = '';
                if($filtro != null){
                    $filter = "WHERE nome like '%". $filtro . "%'";
                }
                $this->db->query("SELECT count(id) as totalUsuarios FROM usuarios $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }
    }
?>