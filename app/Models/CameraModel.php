<?php
    class CameraModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function verificaIp($ip) {
            try {
                $this->db->query("SELECT id FROM cameras WHERE endereco_ip = :ip");
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
    }
?>