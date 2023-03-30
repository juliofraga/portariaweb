<?php
    class UsuarioModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
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
                return false;
            }   
        }      

        // Cadastrar usuario
        public function cadastrarUsuario($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO usuarios(nome, login, senha, situacao, created_at) VALUES (:nome, :login, :senha, :ativo, :dataHora)");
                $this->db->bind("nome", $dados['nome']);
                $this->db->bind("login", $dados['login']);
                $this->db->bind("senha", $dados['senha']);
                $this->db->bind("ativo", 0);
                $this->db->bind("dataHora", $dataHora);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                return null;
            }   
        }

        //Verificar se dados de acesso são validos
        public function validaLogin($login)
        {
            try {
                $this->db->query("SELECT id, nome, senha, situacao, login, alterar_senha, primeiro_acesso FROM usuarios WHERE login = :login");
                $this->db->bind("login", $login);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return $this->db->results();
                else
                    return null;
            } catch (Throwable $th) {
                return null;
            }   
        }

        //Retorna todos os usuários cadastrados
        public function listaUsuarios($pi, $attr = null)
        {
            try {
                if($attr == null){
                    $this->db->query("SELECT * FROM usuarios order by nome ASC LIMIT $pi, 10");
                }else{
                    $this->db->query("SELECT * FROM usuarios order by nome ASC");
                }
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaUsuarioPorNome($nome, $pi)
        {
            try {
                $filter = "nome like '%". $nome . "%'";
                $this->db->query("SELECT * FROM usuarios WHERE $filter order by nome ASC LIMIT $pi, 10");
                return $this->db->results();
            } catch (Throwable $th) {
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
                return null;
            }
        }

        //Retorna usuário por id
        public function buscaUsuarioPorId($id)
        {
            try {
                $this->db->query("SELECT * FROM usuarios WHERE id = :id");
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }   
        }

        //Alterar usuário
        public function alteraUsuario($dados, $rotina, $dateTime)
        {
            try {
                if($rotina == "senha"){
                    $this->db->query("UPDATE usuarios SET nome = :nome, senha = :senha, updated_at = :dataHora, alterar_senha = :alterar_senha, situacao = :situacao, login_error = :login_error WHERE id = :id");
                    $this->db->bind("nome", $dados["nome"]);
                    $this->db->bind("senha", $dados["senha"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("id", $dados["id"]);
                    $this->db->bind("alterar_senha", 'S');
                    $this->db->bind("situacao", 0);
                    $this->db->bind("login_error", 0);
                }else if($rotina == "nome"){
                    $this->db->query("UPDATE usuarios SET nome = :nome, updated_at = :dataHora WHERE id = :id");
                    $this->db->bind("nome", $dados["nome"]);
                    $this->db->bind("dataHora", $dateTime);
                    $this->db->bind("id", $dados["id"]);
                }else if ($rotina == "senha_update"){
                    $this->db->query("UPDATE usuarios SET senha = :senha, updated_at = :dataHora, alterar_senha = :alterar_senha WHERE id = :id");
                    $this->db->bind("senha", $dados["senha"]);
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
                return false;
            }
        }

        // Ativar ou inativar usuário
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
                return false;
            }  
        }

        public function registraCookie($usuario_id, $nome, $valor, $hostname, $dataHora, $tipo)
        {
            try {
                if($tipo == "admin"){
                    $this->db->query("INSERT INTO cookie(usuario_id, nome, valor, hostname, created_at) VALUES (:usuario, :nome, :valor, :hostname, :dataHora)");
                }else if($tipo == "cliente"){
                    $this->db->query("INSERT INTO cookie(cliente_id, nome, valor, hostname, created_at) VALUES (:usuario, :nome, :valor, :hostname, :dataHora)");
                }
                $this->db->bind("usuario", $usuario_id);
                $this->db->bind("nome", $nome);
                $this->db->bind("valor", $valor);
                $this->db->bind("hostname", $hostname);
                $this->db->bind("dataHora", $dataHora);
                $this->db->execQuery();
            } catch (Throwable $th) {
                return null;
            }   
        }

        public function buscaUsuarioCookie($valor, $hostname, $tipo)
        {
            try {
                if($tipo == "admin"){
                    $this->db->query("SELECT u.login FROM usuarios u, cookie c WHERE u.id = c.usuario_id and c.valor = :valor and c.hostname = :hostname");
                }else if($tipo == "cliente"){
                    $this->db->query("SELECT cli.cpfcnpj FROM clientes cli, cookie c WHERE cli.id = c.cliente_id and c.valor = :valor and c.hostname = :hostname");
                }
                $this->db->bind("valor", $valor);
                $this->db->bind("hostname", $hostname);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function removeCookie($usuario_id, $valor, $tipo)
        {
            try {
                if($tipo == "admin"){
                    $this->db->query("DELETE FROM cookie WHERE usuario_id = :usuario and valor = :valor");
                    $this->db->bind("usuario", $usuario_id);
                }else if($tipo == "cliente"){
                    $this->db->query("DELETE FROM cookie WHERE cliente_id = :cliente and valor = :valor");
                    $this->db->bind("cliente", $usuario_id);
                }
                $this->db->bind("valor", $valor);
                $this->db->execQuery();
            } catch (\Throwable $th) {
                return null;
            }
        }

/*

        public function buscaCookie($hostname)
        {
            try {
                $this->db->query("SELECT * FROM usuario_cookie WHERE hostname = :hostname");
                $this->db->bind("hostname", $hostname);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return $this->db->results();
                else
                    return false;
            } catch (\Throwable $th) {
                return false;
            }
        }

        public function buscaInfoLogin($hostname)
        {
            try {
                $this->db->query("SELECT * FROM usuario_cookie where hostname = :hostname");
                $this->db->bind("hostname", $hostname);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }
     

        public function alteraSenhaUsuario($senha, $id, $dateTime)
        {
            try {
                $this->db->query("UPDATE usuarios SET senha = :senha, updated_at = :dataHora WHERE id = :id");
                $this->db->bind("senha", $senha);
                $this->db->bind("dataHora", $dateTime);
                $this->db->bind("id", $id);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            }  
        }
  
        public function alteraNomeUsuario($nome, $id, $dateTime)
        {
            try {
                $this->db->query("UPDATE usuarios SET nome = :nome, updated_at = :dataHora WHERE id = :id");
                $this->db->bind("nome", $nome);
                $this->db->bind("dataHora", $dateTime);
                $this->db->bind("id", $id);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            }  
        }*/
    } 
?>