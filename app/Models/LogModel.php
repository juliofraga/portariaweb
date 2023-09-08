<?php
    class LogModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        // Efetua o registro dos logs
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime){
            try{
                $this->db->query("INSERT INTO logs (usuario_id, classe, id_classe, acao, created_at) VALUES (:usuario, :classe, :id_classe, :acao, :dateTime)");
                $this->db->bind("usuario", $usuario);
                $this->db->bind("classe", $classe);
                $this->db->bind("id_classe", $id_classe);
                $this->db->bind("acao", $acao);
                $this->db->bind("dateTime", $dateTime);
                $this->db->execQuery();
            } catch (Throwable $th) {
                echo $th;
                return null;
            }
        }

        // Retorna os logs registrados
        public function listaLogs(){
            try {
                $this->db->query("SELECT * FROM logs ORDER BY created_at DESC");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function retornaQuantidadeAcessosDia($dia)
        {
            try {
                $dia_inicio = $dia." 00:00:01";
                $dia_fim = $dia." 23:59:59";
                $this->db->query("SELECT count(id) as qtd FROM logs WHERE created_at >= :hojeInicio and created_at <= :hojeFim and classe = :login");
                $this->db->bind("hojeInicio", $dia_inicio);
                $this->db->bind("hojeFim", $dia_fim);
                $this->db->bind("login", "Login-Cliente");
                return $this->db->results();
            }catch (Throwable $th) {
                return null;
            } 
        }

        public function retornaQuantidadeAcessoMes($mesInicio, $mesFim)
        {
            try {
                $mesInicio = $mesInicio." 00:00:01";
                $mesFim = $mesFim." 23:59:59";
                $this->db->query("SELECT count(id) as qtd FROM logs WHERE created_at >= :mesInicio and created_at <= :mesFim and classe = :login");
                $this->db->bind("mesInicio", $mesInicio);
                $this->db->bind("mesFim", $mesFim);
                $this->db->bind("login", "Login-Cliente");
                return $this->db->results();
            }catch (Throwable $th) {
                return null;
            } 
        }
    }
?>