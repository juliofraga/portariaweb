<?php
    class PortariaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function cadastrarPortaria($dados, $dataHora)
        {
            try {
                $placa = null;
                if(!empty($dados["placa"])){
                    $placa = $dados["placa"];
                }
                $this->db->query("INSERT INTO portoes(descricao, placas_id, created_at) VALUES (:descricao, :placas_id, :created_at)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("created_at", $dataHora);
                $this->db->bind("placas_id", $placa);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                return null;
            }   
        }

        public function listaPortarias($attr = null)
        {
            try {
                if($attr == null){
                    $this->db->query("SELECT * FROM portoes order by descricao ASC");
                }else{
                    $this->db->query("SELECT p.*, pl.descricao as placa, pl.endereco_ip as ip_placa, c.descricao as camera, c.endereco_ip as ip_camera FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN cameras c ON p.id = c.portoes_id");
                }
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaPortariasPorFiltro($filtro)
        {
            try {
                $filter = "p.descricao like '%". $filtro . "%'";
                $this->db->query("SELECT p.*, pl.descricao, c.descricao as camera FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN cameras c ON p.id = c.portoes_id WHERE $filter order by p.descricao ASC");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }
    }
?>