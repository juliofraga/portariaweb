<?php
    class PortariaModel
    {
        private $db;
        public $log;

        public function __construct()
        {
            $this->db = new Database();
            $this->log = new Logs();
        }

        public function cadastrarPortaria($dados, $dataHora)
        {
            try {
                $placa = null;
                if(!empty($dados["placa"])){
                    $placa = $dados["placa"];
                }
                $this->db->query("INSERT INTO portoes(descricao, placas_id, created_at) VALUES (:descricao, :placas_id, :created_at)");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("created_at", $dataHora);
                $this->db->bind("placas_id", $placa);
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

        public function alterarPortaria($dados, $dataHora)
        {
            try {
                $placa = null;
                if(!empty($dados["placa"])){
                    $placa = $dados["placa"];
                }
                $this->db->query("UPDATE portoes SET descricao = :descricao, placas_id = :placas_id, updated_at = :updated_at WHERE id = :id");
                $this->db->bind("descricao", $dados['descricao']);
                $this->db->bind("updated_at", $dataHora);
                $this->db->bind("placas_id", $placa);
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

        public function listaPortarias($attr = null)
        {
            try {
                
                if($attr == null){
                    $this->db->query("SELECT * FROM portoes order by descricao ASC");
                }else if($attr == "ativo"){
                    $this->db->query("SELECT * FROM portoes WHERE situacao = :situacao order by descricao ASC");
                    $this->db->bind("situacao", 0);
                }else if($attr == "todos"){
                    $this->db->query("SELECT p.*, pl.descricao as placa, pl.endereco_ip as ip_placa, c.descricao as camera, c.endereco_ip as ip_camera, c.id as camera_id, cp.entrada_saida FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN camera_has_portaria cp ON cp.portaria_id = p.id LEFT JOIN cameras c ON cp.camera_id = c.id order by p.descricao ASC");
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaPortariasUsuarios()
        {
            try {
                $this->db->query("SELECT * FROM portoes_pessoas");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function listaPortariasPorFiltro($filtro)
        {
            try {
                $filter = "p.descricao like '%". $filtro . "%'";
                $this->db->query("SELECT p.*, pl.descricao as placa, pl.endereco_ip as ip_placa, c.descricao as camera, c.endereco_ip as ip_camera, c.id as camera_id, cp.entrada_saida FROM portoes p LEFT JOIN placas pl ON p.placas_id = pl.id LEFT JOIN camera_has_portaria cp ON cp.portaria_id = p.id LEFT JOIN cameras c ON cp.camera_id = c.id WHERE $filter order by p.descricao ASC");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function ativarInativarPortaria($id, $acao, $dateTime){
            try {
                $situacao = $acao == "inativar" ? 1 : 0;
                $this->db->query("UPDATE portoes SET situacao = :situacao, updated_at = :dataHora WHERE id = :id");
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

        public function deletarPortaria($id)
        {
            try {
                $this->db->query("DELETE FROM portoes WHERE id = :id");
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

        public function removePortariaUsuarioPorId($portaria_id)
        {
            try {
                $this->db->query("DELETE FROM portoes_pessoas WHERE portoes_id = :id");
                $this->db->bind("id", $portaria_id);
                $this->db->execQuery();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }

        public function ligaUsuarioPortaria($portaria_id, $usuario_id)
        {
            try {
                $this->db->query("INSERT INTO portoes_pessoas(portoes_id, usuarios_id)VALUES(:portoes_id, :usuarios_id)");
                $this->db->bind("portoes_id", $portaria_id);
                $this->db->bind("usuarios_id", $usuario_id);
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

        public function listaPortariasPorUsuario($usuario_id, $attr = false)
        {
            try {
                if($attr == true){
                    $this->db->query("SELECT id, descricao FROM portoes WHERE situacao = :situacao and placas_id IS NOT NULL order by descricao ASC");
                    $this->db->bind("situacao", 0);
                }else{
                    $this->db->query("SELECT p.id, p.descricao FROM portoes p, portoes_pessoas pp WHERE p.id = pp.portoes_id and pp.usuarios_id = :usuario_id and placas_id IS NOT NULL");
                    $this->db->bind("usuario_id", $usuario_id);
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function retornaPortariaPadrao($usuario_id, $attr = false){
            try {
                if($attr == true){
                    $this->db->query("SELECT id, descricao FROM portoes WHERE situacao = :situacao order by id ASC limit 1");
                    $this->db->bind("situacao", 0);
                }else{
                    $this->db->query("SELECT p.id, p.descricao FROM portoes p, portoes_pessoas pp WHERE p.id = pp.portoes_id and pp.usuarios_id = :usuario_id order by p.id LIMIT 1");
                    $this->db->bind("usuario_id", $usuario_id);
                }
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return null;
            }
        }

        public function ligaPortariaPortaria($dados, $tipo)
        {
            try {
                $this->db->query("INSERT INTO portaria_ligacao_portaria(portaria_id_1, portaria_id_2, tipo)VALUES(:portaria_id_1, :portaria_id_2, :tipo)");
                $this->db->bind("portaria_id_1", $dados["portaria_0"]);
                $this->db->bind("portaria_id_2", $dados["portaria_1"]);
                $this->db->bind("tipo", $tipo);
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

        public function verificaSeLigacaoExiste($dados)
        {
            try {
                $this->db->query("SELECT * FROM portaria_ligacao_portaria WHERE (portaria_id_1 = :portaria_id_1 AND portaria_id_2 = :portaria_id_2) or (portaria_id_1 = :portaria_id_2 AND portaria_id_2 = :portaria_id_1)");
                $this->db->bind("portaria_id_1", $dados["portaria_0"]);
                $this->db->bind("portaria_id_2", $dados["portaria_1"]);
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

        public function listaPortariasLigadas()
        {
            try{
                $this->db->query("SELECT * FROM portaria_ligacao_portaria");
                return $this->db->results();
            } catch (Throwable $th) {
                $this->log->gravaLogDBError($th);
                return false;
            } 
        }
    }
?>