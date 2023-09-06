<?php
    class ConfiguracaoModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
        }

        public function listaConfiguracoes()
        {
            try {
                $this->db->query("SELECT * FROM configuracoes order by id ASC");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function atualizaConfiguracao($id, $valor, $dataHora)
        {
            try {
                $this->db->query("UPDATE configuracoes SET valor = :valor, updated_at = :dataHora WHERE id = :id");
                $this->db->bind("valor", $valor);
                $this->db->bind("dataHora", $dataHora);
                $this->db->bind("id", $id);
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

        public function opcaoAtiva($id)
        {
            try {
                $this->db->query("SELECT valor FROM configuracoes WHERE id = :id AND valor = :valor");
                $this->db->bind("id", $id);
                $this->db->bind("valor", 0);
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
    }
?>