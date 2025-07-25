<?php
    class LogModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
            
        }

        // Efetua o registro dos logs
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime){
            try{
                $this->db->query("INSERT INTO logs (usuario_id, classe, id_classe, acao, created_at) VALUES (:usuario, :classe, :id_classe, :acao, :dateTime)");
                $this->db->bind("usuario", $usuario);
                $this->db->bind("classe", $classe);
                $this->db->bind("id_classe", $id_classe);
                $this->db->bind("acao", $acao);
                $this->db->bind("dateTime", $dateTime);
                $this->db->execQuery();
            } catch (Throwable $th) {
                return null;
            }
        }

        // Retorna os logs registrados
        public function listaLogs($dtInicio = null, $dtFim = null, $today, $pag){
            try {
                $numReg = NUM_REG_PAGINA;
                $filtroData = " AND l.created_at >= '" . $today . " 00:00:01'";
                if($dtInicio != null and $dtFim != null){
                    $filtroData = " AND l.created_at >= '" . $dtInicio . "' AND l.created_at <= '" . $dtFim . "'";
                }else if($dtInicio != null and $dtFim == null){
                    $filtroData = " AND l.created_at >= '" . $dtInicio . "'";
                }else if($dtInicio == null and $dtFim != null){
                    $filtroData = " AND l.created_at <= '" . $dtFim . "'";
                }
                $this->db->query("SELECT l.*, u.login, c.descricao as camera, conf.titulo, p.nome_completo as motorista, e.razao_social, v.placa, pl.descricao as placa_desc, pl.endereco_ip as placa_ip, por.descricao as portaria, usu.login as usu_login
                FROM logs l 
                INNER JOIN usuarios u ON l.usuario_id = u.id 
                LEFT JOIN cameras c on l.id_classe = c.id 
                LEFT JOIN configuracoes conf ON l.id_classe = conf.id 
                LEFT JOIN pessoas p ON l.id_classe = p.id 
                LEFT JOIN empresas e ON l.id_classe = e.id 
                LEFT JOIN veiculos v ON l.id_classe = v.id 
                LEFT JOIN placas pl ON l.id_classe = pl.id 
                LEFT JOIN portoes por ON l.id_classe = por.id 
                LEFT JOIN usuarios usu ON l.id_classe = usu.id
                WHERE l.id > 0 $filtroData
                ORDER BY l.created_at DESC
                LIMIT $pag, $numReg");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            }
        }

        public function retornaQuantidadeAcessosDia($dia)
        {
            try {
                $dia_inicio = $dia." 00:00:01";
                $dia_fim = $dia." 23:59:59";
                $this->db->query("SELECT count(id) as qtd FROM logs WHERE created_at >= :hojeInicio and created_at <= :hojeFim and classe = :login");
                $this->db->bind("hojeInicio", $dia_inicio);
                $this->db->bind("hojeFim", $dia_fim);
                $this->db->bind("login", "Login-Cliente");
                return $this->db->results();
            }catch (Throwable $th) {
                return null;
            } 
        }

        public function retornaQuantidadeAcessoMes($mesInicio, $mesFim)
        {
            try {
                $mesInicio = $mesInicio." 00:00:01";
                $mesFim = $mesFim." 23:59:59";
                $this->db->query("SELECT count(id) as qtd FROM logs WHERE created_at >= :mesInicio and created_at <= :mesFim and classe = :login");
                $this->db->bind("mesInicio", $mesInicio);
                $this->db->bind("mesFim", $mesFim);
                $this->db->bind("login", "Login-Cliente");
                return $this->db->results();
            }catch (Throwable $th) {
                return null;
            } 
        }

        public function numeroTotalLogs($dtInicio = null, $dtFim = null, $today)
        {
            try {
                $filtroData = " AND created_at >= '" . $today . " 00:00:01'";
                if($dtInicio != null and $dtFim != null){
                    $filtroData = " AND created_at >= '" . $dtInicio . "' AND created_at <= '" . $dtFim . "'";
                }else if($dtInicio != null and $dtFim == null){
                    $filtroData = " AND created_at >= '" . $dtInicio . "'";
                }else if($dtInicio == null and $dtFim != null){
                    $filtroData = " AND created_at <= '" . $dtFim . "'";
                }
                $this->db->query("SELECT count(id) as totalLogs FROM logs WHERE id > 0 $filtroData");
                return $this->db->results();
            } catch (Throwable $th) {
                return false;
            } 
        }
    }
?>