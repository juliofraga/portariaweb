<?php
    class MotoristaModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
        }

        public function cadastrarMotorista($nomeMotorista, $cpfMotorista, $dateTime)
        {
            try {
                $this->db->query("INSERT INTO pessoas(nome_completo, cpf, created_at) VALUES (:nome_completo, :cpf, :created_at)");
                $this->db->bind("nome_completo", $nomeMotorista);
                $this->db->bind("cpf", $cpfMotorista);
                $this->db->bind("created_at", $dateTime);
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

        public function ligaMotoristaVeiculo($motorista_id, $veiculo_id)
        {
            try {
                $this->db->query("INSERT INTO pessoas_has_veiculos(pessoas_id, veiculos_id) VALUES (:motorista, :veiculo)");
                $this->db->bind("motorista", $motorista_id);
                $this->db->bind("veiculo", $veiculo_id);
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

        public function alterarMotorista($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE pessoas SET nome_completo = :nome_completo, cpf = :cpf, rg = :rg, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("nome_completo", $dados["nome_completo"]);
                $this->db->bind("cpf", $dados["cpf"]);
                $this->db->bind("rg", $dados["rg"]);
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

        public function listaMotoristas($attr = null)
        {
            try {
                $this->db->query("SELECT * FROM pessoas");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaMotoristasPorFiltro($filtro)
        {
            try {
                $filter = "nome_completo like '%".$filtro."%' or cpf like '%".$filtro."%'";
                $this->db->query("SELECT * FROM pessoas WHERE $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        
        public function retornaMotoristaPorEmpresa($empresa_id)
        {
            try {
                $this->db->query("SELECT p.id, p.nome_completo FROM pessoas p, pessoas_has_veiculos pv, veiculos v, empresas e WHERE p.id = pv.pessoas_id AND pv.veiculos_id = v.id AND v.empresas_id = e.id AND e.id = :empresa_id");
                $this->db->bind("empresa_id", $empresa_id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function retornaCpfMotorista($motorista_id)
        {
            try {
                $this->db->query("SELECT cpf FROM pessoas WHERE id = :id");
                $this->db->bind("id", $motorista_id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function verificaMotorista($motorista_id)
        {
            try {
                $this->db->query("SELECT id FROM pessoas WHERE id = :id");
                $this->db->bind("id", $motorista_id);
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

        public function validaCpf($motorista_id, $cpf)
        {
            try {
                $this->db->query("SELECT id FROM pessoas WHERE cpf = :cpf and id <> :id");
                $this->db->bind("id", $motorista_id);
                $this->db->bind("cpf", $cpf);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return false;
                else
                    return true;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function validaRg($motorista_id, $rg)
        {
            try {
                $this->db->query("SELECT id FROM pessoas WHERE rg = :rg and id <> :id");
                $this->db->bind("id", $motorista_id);
                $this->db->bind("rg", $rg);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return false;
                else
                    return true;
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }
    }
?>