<?php
    class VeiculoModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function cadastrarVeiculo($dados, $dataHora)
        {
            try {
                $portao = null;
                if(!empty($dados["portaria"])){
                    $portao = $dados["portaria"];
                }
                $this->db->query("INSERT INTO veiculos() VALUES ()");
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

        public function alterarVeiculo($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE veiculos SET updated_at = :updated_at WHERE id = :id");
                $this->db->bind("updated_at", $dataHora);
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

        public function listaVeiculos($attr = null)
        {
            try {
                $this->db->query("SELECT * FROM veiculos");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaVeiculosPorFiltro($filtro)
        {
            try {
                $filter = "";
                $this->db->query("SELECT * FROM veiculos WHERE $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarVeiculo($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE veiculos SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarVeiculo($id)
        {
            try {
                $this->db->query("DELETE FROM veiculos WHERE id = :id");
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