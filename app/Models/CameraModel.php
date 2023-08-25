<?php
    class CameraModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function verificaIp($ip, $camera_id = null) {
            try {
                if($camera_id == null){
                    $this->db->query("SELECT id FROM cameras WHERE endereco_ip = :ip");
                }else{
                    $this->db->query("SELECT id FROM cameras WHERE endereco_ip = :ip and id <> :id");
                    $this->db->bind("id", $camera_id);
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

        public function cadastrarCamera($dados, $dataHora)
        {
            try {
                $this->db->query("INSERT INTO cameras(descricao, endereco_ip, created_at) VALUES (:descricao, :endereco_ip, :created_at)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
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

        public function alterarCamera($dados, $dataHora)
        {
            try {
                $this->db->query("UPDATE cameras SET descricao = :descricao, endereco_ip = :endereco_ip, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
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

        public function listaCamerasDisponiveis()
        {
            try {
                $this->db->query("SELECT c.* FROM cameras c LEFT JOIN camera_has_portaria cp ON cp.camera_id = c.id WHERE cp.camera_id IS NULL order by c.descricao");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }  
        }

        public function inserePortariaCamera($camera_id, $portaria_id, $tipo)
        {
            try {
                $this->db->query("INSERT INTO camera_has_portaria(camera_id, portaria_id, entrada_saida) VALUES (:camera, :portaria, :tipo)");
                $this->db->bind("portaria", $portaria_id);
                $this->db->bind("camera", $camera_id);
                $this->db->bind("tipo", $tipo);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function listaCameras($attr = null)
        {
            try {
                $this->db->query("SELECT c.*, p.descricao as portaria FROM cameras c LEFT JOIN camera_has_portaria cp ON c.id = cp.camera_id LEFT JOIN portoes p ON cp.portaria_id = p.id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaCamerasPorFiltro($filtro)
        {
            try {
                $filter = "c.descricao like '%". $filtro . "%' or c.endereco_ip like '%" . $filtro . "%'";
                $this->db->query("SELECT c.*, p.descricao as portaria FROM cameras c LEFT JOIN camera_has_portaria cp ON c.id = cp.camera_id LEFT JOIN portoes p ON cp.portaria_id = p.id WHERE $filter");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function ativarInativarCamera($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE cameras SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarCamera($id)
        {
            try {
                $this->db->query("DELETE FROM cameras WHERE id = :id");
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

        public function removeCamerasPortaria($portaria_id)
        {
            try {
                $this->db->query("DELETE FROM camera_has_portaria WHERE portaria_id = :id");
                $this->db->bind("id", $portaria_id);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return false;
            }  
        }

        public function listaCamerasPortaria($portaria_id, $tipo = null)
        {
            try {
                if($tipo == null or $tipo == "emergencia"){
                    $this->db->query("SELECT c.*, cp.entrada_saida FROM cameras c LEFT JOIN camera_has_portaria cp ON c.id = cp.camera_id WHERE cp.portaria_id = :portaria ORDER BY c.id ASC");
                }else if($tipo == "entrada"){
                    $this->db->query("SELECT c.*, cp.entrada_saida FROM cameras c LEFT JOIN camera_has_portaria cp ON c.id = cp.camera_id WHERE cp.portaria_id = :portaria AND cp.entrada_saida = 'E' ORDER BY c.id ASC");
                }else if($tipo == "saida"){
                    $this->db->query("SELECT c.*, cp.entrada_saida FROM cameras c LEFT JOIN camera_has_portaria cp ON c.id = cp.camera_id WHERE cp.portaria_id = :portaria AND cp.entrada_saida = 'S' ORDER BY c.id ASC");
                }
                $this->db->bind("portaria", $portaria_id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }  
        }
    }
?>