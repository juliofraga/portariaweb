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
                return null;
            }   
        }
    }
?>