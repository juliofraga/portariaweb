<?php
    class EmpresaModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
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
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }

        public function cadastrarEmpresa($dados, $dataHora, $tipo = null)
        {
            try {
                if($tipo == null){
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
                }else if($tipo == "registro"){
                    $this->db->query("INSERT INTO empresas(cnpj, razao_social, nome_fantasia, created_at) VALUES (:cnpj, :razao_social, :nome_fantasia, :created_at)");
                    $this->db->bind("cnpj", $dados["cnpj"]);
                    $this->db->bind("razao_social", $dados["nome_fantasia"]);
                    $this->db->bind("nome_fantasia", $dados["nome_fantasia"]);
                    $this->db->bind("created_at", $dataHora);
                }
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

        public function alterarEmpresa($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE empresas SET cnpj = :cnpj, razao_social = :razao_social, nome_fantasia = :nome_fantasia, logradouro = :logradouro, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, complemento = :complemento, updated_at = :updated_at WHERE id = :id");
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

        public function listaEmpresas($attr = null)
        {
            try {
                if($attr == null){
                    $this->db->query("SELECT * FROM empresas ORDER BY id");
                }else if($attr == "ativas"){
                    $this->db->query("SELECT * FROM empresas WHERE situacao = :situacao ORDER BY id");
                    $this->db->bind("situacao", 0);
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaEmpresasPorFiltro($filtro = null)
        {
            try {
                $filter = "";
                if($filtro != null){
                    $filter = $filtro;
                }
                $this->db->query("SELECT * FROM empresas WHERE $filter ORDER BY id");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
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
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }

        public function retornaCnpjCpf($empresa_id)
        {
            try {
                $this->db->query("SELECT cnpj FROM empresas WHERE id = :id and situacao = :situacao");
                $this->db->bind("situacao", 0);
                $this->db->bind("id", $empresa_id);
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

    }
?>