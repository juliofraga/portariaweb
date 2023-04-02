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
                $portao = null;
                if(!empty($dados["portaria"])){
                    $portao = $dados["portaria"];
                }
                $this->db->query("INSERT INTO cameras(descricao, endereco_ip, url_foto, url_video, created_at, portoes_id) VALUES (:descricao, :endereco_ip, :url_foto, :url_video, :created_at, :portoes_id)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
                $this->db->bind("url_foto", $dados['url_foto']);
                $this->db->bind("url_video", $dados['url_video']);
                $this->db->bind("created_at", $dataHora);
                $this->db->bind("portoes_id", $portao);
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
                $portao = null;
                if(!empty($dados["portaria"])){
                    $portao = $dados["portaria"];
                }
                $this->db->query("UPDATE cameras SET descricao = :descricao, endereco_ip = :endereco_ip, url_foto = :url_foto, url_video = :url_video, updated_at = :updated_at, portoes_id = :portoes_id WHERE id = :id");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("endereco_ip", $dados['endereco_ip']);
                $this->db->bind("url_foto", $dados['url_foto']);
                $this->db->bind("url_video", $dados['url_video']);
                $this->db->bind("updated_at", $dataHora);
                $this->db->bind("portoes_id", $portao);
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
                $this->db->query("SELECT * FROM cameras WHERE portoes_id IS NULL order by descricao");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }  
        }

        public function inserePortariaCamera($camera_id, $portaria_id, $dateTime)
        {
            try {
                $this->db->query("UPDATE cameras SET portoes_id = :portaria, updated_at = :dateTime WHERE id = :id");
                $this->db->bind("portaria", $portaria_id);
                $this->db->bind("dateTime", $dateTime);
                $this->db->bind("id", $camera_id);
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
                $this->db->query("SELECT c.*, p.descricao as portaria FROM cameras c LEFT JOIN portoes p on c.portoes_id = p.id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function listaCamerasPorFiltro($filtro)
        {
            try {
                $filter = "c.descricao like '%". $filtro . "%' or c.endereco_ip like '%" . $filtro . "%'";
                $this->db->query("SELECT c.*, p.descricao as portaria FROM cameras c LEFT JOIN portoes p on c.portoes_id = p.id WHERE $filter order by c.descricao ASC");
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
    }
?>