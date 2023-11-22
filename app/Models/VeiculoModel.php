<?php
    class VeiculoModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
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
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }

        public function verificaVeiculoPorId($veiculo_id)
        {
            try {
                $this->db->query("SELECT id FROM veiculos WHERE id = :id");
                $this->db->bind("id", $veiculo_id);
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
                $this->log->gravaLogDBError($th);
                return null;
            }  
        }

        public function listaVeiculos($attr = null, $pag = null, $origem = null)
        {
            try {
                $numReg = NUM_REG_PAGINA;
                if($origem == null){
                    $this->db->query("SELECT v.*, e.nome_fantasia FROM veiculos v, empresas e WHERE v.empresas_id = e.id");
                }else if($origem == 'consulta'){
                    $this->db->query("SELECT v.*, e.nome_fantasia FROM veiculos v, empresas e WHERE v.empresas_id = e.id LIMIT $pag, $numReg");
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaVeiculosPorFiltro($filtro, $pag = null)
        {
            try {
                $numReg = NUM_REG_PAGINA;
                $filter = "and (v.descricao like '%".$filtro."%' or v.placa like '%".$filtro."%')";
                $this->db->query("SELECT v.*, e.nome_fantasia FROM veiculos v, empresas e WHERE v.empresas_id = e.id $filter LIMIT $pag, $numReg");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function retornaDescricaoTipoVeiculo($veiculo_id)
        {
            try {
                $this->db->query("SELECT descricao, tipo FROM veiculos WHERE id = :veiculo_id");
                $this->db->bind("veiculo_id", $veiculo_id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function veiculoPodeEntrar($veiculo_id)
        {
            try {
                $this->db->query("SELECT id FROM operacoes WHERE veiculos_id = :veiculo_id and tipo = :tipo and hora_abre_cancela_saida IS NULL");
                $this->db->bind("veiculo_id", $veiculo_id);
                $this->db->bind("tipo", 'N');
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function retornaIDPOrPlaca($placa)
        {
            try {
                $this->db->query("SELECT id FROM veiculos WHERE placa = :placa");
                $this->db->bind("placa", $placa);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function numeroTotalVeiculos($filtro = null)
        {
            try {
                $filter = '';
                if($filtro != null){
                    $filter = "WHERE descricao like '%". $filtro . "%' or placa like '%" . $filtro . "%'";
                }
                $this->db->query("SELECT count(id) as totalVeiculos FROM veiculos $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }
    }
?>