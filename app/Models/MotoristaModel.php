<?php
    class MotoristaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
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
                return null;
            }   
        }

        public function alterarPessoa($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE pessoas SET updated_at = :updated_at WHERE id = :id");
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

        public function listaPessoas($attr = null)
        {
            try {
                $this->db->query("SELECT * FROM pessoas");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaPessoasPorFiltro($filtro)
        {
            try {
                $filter = "";
                $this->db->query("SELECT * FROM pessoas WHERE $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarPessoa($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE pessoas SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarPessoa($id)
        {
            try {
                $this->db->query("DELETE FROM pessoas WHERE id = :id");
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
        
        public function retornaMotoristaPorEmpresa($empresa_id)
        {
            try {
                $this->db->query("SELECT p.id, p.nome_completo FROM pessoas p, pessoas_has_veiculos pv, veiculos v, empresas e WHERE p.id = pv.pessoas_id AND pv.veiculos_id = v.id AND v.empresas_id = e.id AND e.id = :empresa_id");
                $this->db->bind("empresa_id", $empresa_id);
                return $this->db->results();
            } catch (Throwable $th) {
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
                return null;
            }
        }
    }
?>