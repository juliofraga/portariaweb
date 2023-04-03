<?php
    class EmpresaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function cadastrarEmpresa($dados, $dataHora)
        {
            try {
                $portao = null;
                if(!empty($dados["portaria"])){
                    $portao = $dados["portaria"];
                }
                $this->db->query("INSERT INTO empresas() VALUES ()");
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

        public function alterarEmpresa($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE empresas SET updated_at = :updated_at WHERE id = :id");
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

        public function listaEmpresas($attr = null)
        {
            try {
                $this->db->query("SELECT * FROM empresas");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaEmpresasPorFiltro($filtro)
        {
            try {
                $filter = "";
                $this->db->query("SELECT * FROM empresas WHERE $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarEmpresa($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE empresas SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarEmpresa($id)
        {
            try {
                $this->db->query("DELETE FROM empresas WHERE id = :id");
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