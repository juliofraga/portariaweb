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
                $this->db->query("INSERT INTO veiculos(placa, descricao, tipo, created_at, empresas_id) VALUES (:placa, :descricao, :tipo, :created_at, :empresa)");
                $this->db->bind("placa", $dados["placaVeiculo"]);
                $this->db->bind("descricao", $dados["descricao"]);
                $this->db->bind("tipo", $dados["tipo"]);
                $this->db->bind("empresa", $dados["empresa"]);
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

        public function verificaPlaca($placa, $veiculo_id = null)
        {
            try {
                if($veiculo_id == null){
                    $this->db->query("SELECT id FROM veiculos WHERE placa = :placa");
                }else{
                    $this->db->query("SELECT id FROM veiculos WHERE placa = :placa and id <> :id");
                    $this->db->bind("id", $veiculo_id);
                }
                $this->db->bind("placa", $placa);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            } 
        }

        public function alterarVeiculo($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE veiculos SET placa = :placa, descricao = :descricao, tipo = :tipo, empresas_id = :empresa, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("placa", $dados["placaVeiculo"]);
                $this->db->bind("descricao", $dados["descricao"]);
                $this->db->bind("tipo", $dados["tipo"]);
                $this->db->bind("empresa", $dados["empresa"]);
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
                $this->db->query("SELECT v.*, e.nome_fantasia FROM veiculos v, empresas e WHERE v.empresas_id = e.id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaVeiculosPorFiltro($filtro)
        {
            try {
                $filter = "and (v.descricao like '%".$filtro."%' or v.placa like '%".$filtro."%')";
                $this->db->query("SELECT v.*, e.nome_fantasia FROM veiculos v, empresas e WHERE v.empresas_id = e.id $filter");
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

        public function retornaVeiculosPorEmpresa($empresa_id)
        {
            try {
                $this->db->query("SELECT id, placa FROM veiculos WHERE empresas_id = :empresa_id and situacao = :situacao");
                $this->db->bind("situacao", 0);
                $this->db->bind("empresa_id", $empresa_id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function retornaDescricaoVeiculo($veiculo_id)
        {
            try {
                $this->db->query("SELECT descricao FROM veiculos WHERE id = :veiculo_id");
                $this->db->bind("veiculo_id", $veiculo_id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }
    }
?>