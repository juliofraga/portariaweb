<?php
    class PlacaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function verificaIp($ip) {
            try {
                $this->db->query("SELECT id FROM placas WHERE endereco_ip = :ip");
                $this->db->bind("ip", $ip);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            } 
        }

        public function cadastrarPlaca($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO placas(descricao, endereco_ip, rele1, rele2, rele3, rele4, created_at) VALUES (:descricao, :endereco_ip, :rele1, :rele2, :rele3, :rele4, :created_at)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
                $this->db->bind("rele1", $dados['rele1']);
                $this->db->bind("rele2", $dados['rele2']);
                $this->db->bind("rele3", $dados['rele3']);
                $this->db->bind("rele4", $dados['rele4']);
                $this->db->bind("created_at", $dataHora);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                return null;
            }   
        }

        public function listaPlacasDisponiveis()
        {
            try {
                $this->db->query("SELECT p.* FROM placas p LEFT JOIN portoes po ON p.id = po.placas_id WHERE po.placas_id IS NULL");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }  
        }

    }
?>