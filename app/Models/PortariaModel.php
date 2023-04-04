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

        public function alterarPortaria($dados, $dataHora)
        {
            try {
                $placa = null;
                if(!empty($dados["placa"])){
                    $placa = $dados["placa"];
                }
                $this->db->query("UPDATE portoes SET descricao = :descricao, placas_id = :placas_id, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("updated_at", $dataHora);
                $this->db->bind("placas_id", $placa);
                $this->db->bind("id", $dados["id"]);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaPortarias($attr = null)
        {
            try {
                if($attr == null){
                    $this->db->query("SELECT * FROM portoes order by descricao ASC");
                }else if($attr == "ativo"){
                    $this->db->query("SELECT * FROM portoes WHERE situacao = :situacao order by descricao ASC");
                    $this->db->bind("situacao", 0);
                }else{
                    $this->db->query("SELECT p.*, pl.descricao as placa, pl.endereco_ip as ip_placa, c.descricao as camera, c.endereco_ip as ip_camera, c.id as camera_id FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN cameras c ON p.id = c.portoes_id order by p.descricao ASC");
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
                $this->db->query("SELECT p.*, pl.descricao as placa, pl.endereco_ip as ip_placa, c.descricao as camera, c.endereco_ip as ip_camera, c.id as camera_id FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN cameras c ON p.id = c.portoes_id WHERE $filter order by p.descricao ASC");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarPortaria($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE portoes SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarPortaria($id)
        {
            try {
                $this->db->query("DELETE FROM portoes WHERE id = :id");
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
    }
?>