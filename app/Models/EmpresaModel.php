<?php
    class EmpresaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function verificaEmpresa($cnpj, $empresa_id = null)
        {
            try {
                if($empresa_id == null){
                    $this->db->query("SELECT id FROM empresas WHERE cnpj = :cnpj");
                }else{
                    $this->db->query("SELECT id FROM empresas WHERE cnpj = :cnpj and id <> :id");
                    $this->db->bind("id", $empresa_id);
                }
                $this->db->bind("cnpj", $cnpj);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            } 
        }

        public function cadastrarEmpresa($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO empresas(cnpj, razao_social, nome_fantasia, logradouro, numero, bairro, cidade, estado, cep, complemento, created_at) VALUES (:cnpj, :razao_social, :nome_fantasia, :logradouro, :numero, :bairro, :cidade, :estado, :cep, :complemento, :created_at)");
                $this->db->bind("cnpj", $dados["cnpj"]);
                $this->db->bind("razao_social", $dados["razao_social"]);
                $this->db->bind("nome_fantasia", $dados["nome_fantasia"]);
                $this->db->bind("logradouro", $dados["logradouro"]);
                $this->db->bind("numero", $dados["numero"]);
                $this->db->bind("bairro", $dados["bairro"]);
                $this->db->bind("cidade", $dados["cidade"]);
                $this->db->bind("estado", $dados["estado"]);
                $this->db->bind("cep", $dados["cep"]);
                $this->db->bind("complemento", $dados["complemento"]);
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
            //CONTINUAR DAQUI
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
                $this->db->query("SELECT * FROM empresas ORDER BY id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaEmpresasPorFiltro($filtro)
        {
            try {
                $filter = "";
                $this->db->query("SELECT * FROM empresas WHERE $filter ORDER BY id");
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