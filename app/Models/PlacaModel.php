<?php
    class PlacaModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function verificaIp($ip, $placa_id = null) {
            try {
                if($placa_id == null){
                    $this->db->query("SELECT id FROM placas WHERE endereco_ip = :ip");
                }else{
                    $this->db->query("SELECT id FROM placas WHERE endereco_ip = :ip and id <> :id");
                    $this->db->bind("id", $placa_id);
                }
                $this->db->bind("ip", $ip);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            } 
        }

        public function cadastrarPlaca($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO placas(descricao, endereco_ip, rele1, rele2, rele3, rele4, created_at) VALUES (:descricao, :endereco_ip, :rele1, :rele2, :rele3, :rele4, :created_at)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
                $this->db->bind("rele1", $dados['rele1']);
                $this->db->bind("rele2", $dados['rele2']);
                $this->db->bind("rele3", $dados['rele3']);
                $this->db->bind("rele4", $dados['rele4']);
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

        public function alterarPlaca($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE placas SET descricao = :descricao, endereco_ip = :endereco_ip, rele1 = :rele1, rele2 = :rele2, rele3 = :rele3, rele4 = :rele4, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
                $this->db->bind("rele1", $dados['rele1']);
                $this->db->bind("rele2", $dados['rele2']);
                $this->db->bind("rele3", $dados['rele3']);
                $this->db->bind("rele4", $dados['rele4']);
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

        public function listaPlacasDisponiveis()
        {
            try {
                $this->db->query("SELECT p.* FROM placas p LEFT JOIN portoes po ON p.id = po.placas_id WHERE po.placas_id IS NULL");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }  
        }

        public function listaPlacas($attr = null)
        {
            try {
                $this->db->query("SELECT pl.*, p.descricao as portaria FROM placas pl LEFT JOIN portoes p on p.placas_id = pl.id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaReles($portaria_id)
        {
            try {
                $this->db->query("SELECT pl.rele1, pl.rele2, pl.rele3, pl.rele4 FROM placas pl, portoes p WHERE p.placas_id = pl.id and p.id = :portaria_id");
                $this->db->bind("portaria_id", $portaria_id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaPlacasPorFiltro($filtro)
        {
            try {
                $filter = "pl.descricao like '%". $filtro . "%' or pl.endereco_ip like '%" . $filtro . "%'";
                $this->db->query("SELECT pl.*, p.descricao as portaria FROM placas pl LEFT JOIN portoes p on p.placas_id = pl.id WHERE $filter order by pl.descricao ASC");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarPlaca($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE placas SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarPlaca($id)
        {
            try {
                $this->db->query("DELETE FROM placas WHERE id = :id");
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