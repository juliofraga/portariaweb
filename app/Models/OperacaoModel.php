<?php
    class OperacaoModel
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function registrarOperacao($dateTime, $usuario_id, $veiculo_id, $pessoa_id, $portaria_id)
        {
            try {
                $this->db->query("INSERT INTO operacoes(hora_abre_cancela_entrada, usuarios_id, veiculos_id, pessoas_id, portaria_id) VALUES (:hora_abre_cancela_entrada, :usuario, :veiculo, :pessoa, :portaria)");
                $this->db->bind("usuario", $usuario_id);
                $this->db->bind("hora_abre_cancela_entrada", $dateTime);
                $this->db->bind("veiculo", $veiculo_id);
                $this->db->bind("pessoa", $pessoa_id);
                $this->db->bind("portaria", $portaria_id);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                echo $th;
                return null;
            } 
        }

        public function fechaCancela($operacao_id, $dateTime, $tipo)
        {
            try {
                if($tipo == "entrada"){
                    $this->db->query("UPDATE operacoes SET hora_fecha_cancela_entrada = :dateTime WHERE id = :id");
                }else if($tipo == "saida"){
                    $this->db->query("UPDATE operacoes SET hora_fecha_cancela_saida = :dateTime WHERE id = :id");
                }
                $this->db->bind("id", $operacao_id);
                $this->db->bind("dateTime", $dateTime);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function registrarSaida($operacao_id, $dateTime)
        {
            try {
                $this->db->query("UPDATE operacoes SET hora_abre_cancela_saida = :dateTime WHERE id = :id");
                $this->db->bind("id", $operacao_id);
                $this->db->bind("dateTime", $dateTime);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function registraOperacaoEmergencia($dados, $dateTime)
        {
            try {
                $this->db->query("INSERT INTO operacoes(hora_abre_cancela_entrada, usuarios_id, portaria_id, tipo, obs_emergencia)VALUES(:hora_abre_cancela_entrada, :usuarios_id, :portaria_id, :tipo, :obs_emergencia)");
                $this->db->bind("hora_abre_cancela_entrada", $dateTime);
                $this->db->bind("usuarios_id", $dados["usuario_id"]);
                $this->db->bind("portaria_id", $dados["portaria_id"]);
                $this->db->bind("tipo", "E");
                $this->db->bind("obs_emergencia", $dados["observacao"]);
                if($this->db->execQuery()){
                    return $this->db->lastInsertId();
                }else{
                    return null;
                }
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function fechaCancelaEmergencia($dateTime)
        {
            try {
                $this->db->query("UPDATE operacoes SET hora_fecha_cancela_entrada = :dateTime WHERE hora_fecha_cancela_entrada IS NULL and tipo = :tipo ORDER BY :id ASC LIMIT :limit");
                $this->db->bind("id", "id");
                $this->db->bind("limit", 1);
                $this->db->bind("tipo", "E");
                $this->db->bind("dateTime", $dateTime);
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    return true;
                else
                    return false;
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function buscaVeiculosParaSaida($portaria_id)
        {
            try {
                echo $portaria_id;
                $this->db->query("SELECT o.id, v.placa, p.nome_completo FROM operacoes o, veiculos v, pessoas p WHERE o.veiculos_id = v.id AND o.pessoas_id = p.id AND o.portaria_id = :portaria_id AND o.hora_abre_cancela_saida IS NULL AND o.tipo = :tipo");
                $this->db->bind("portaria_id", $portaria_id);
                $this->db->bind("tipo", 'N');
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function consultaOperacoes()
        {
            try {
                $this->db->query("SELECT o.*, v.placa, u.nome FROM operacoes o, veiculos v, usuarios u WHERE o.veiculos_id = v.id and o.usuarios_id = u.id");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }
    }
?>