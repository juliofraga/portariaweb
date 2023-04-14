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

        public function buscaVeiculosParaSaida($portaria_id)
        {
            try {
                echo $portaria_id;
                $this->db->query("SELECT o.id, v.placa, p.nome_completo FROM operacoes o, veiculos v, pessoas p WHERE o.veiculos_id = v.id AND o.pessoas_id = p.id AND portaria_id = :portaria_id AND hora_abre_cancela_saida IS NULL");
                $this->db->bind("portaria_id", $portaria_id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }
    }
?>