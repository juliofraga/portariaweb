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

        public function consultaOperacoes($portarias = null, $operadores = null, $tipos = null, $empresas = null, $veiculos = null, $motoristas = null, $dataDe = null, $dataAte = null)
        {
            try {
                // Filtrar Portaria
                $portaria = "";
                if($portarias != null){
                    $portaria = $this->formataWhereClause($portarias, "p.id");
                }
                
                // Filtrar Operador
                $operador = "";
                if($operadores != null){
                    $operador = $this->formataWhereClause($operadores, "u.id");
                }
                // Filtrar Tipo
                $tipo = "";
                if($tipos != null){
                    $tipo = $this->formataWhereClause($tipos, "o.tipo");
                }

                // Filtrar Empresa
                $empresa = "";
                if($empresas != null){
                    $empresa = $this->formataWhereClause($empresas, "v.empresas_id");
                }

                // Filtrar Veículo
                $veiculo = "";
                if($veiculos != null){
                    $veiculo = $this->formataWhereClause($veiculos, "v.id");
                }

                // Filtrar Motorista
                $motorista = "";
                if($motoristas != null){
                    $motorista = $this->formataWhereClause($motoristas, "o.pessoas_id");
                }

                // Filtrar Data Dê
                $dataDeFiltro = "";
                if($dataDe != null){
                    $dataDeFiltro = " AND o.hora_abre_cancela_entrada >= '$dataDe 00:00:00'";
                }

                // Filtrar Data Até
                $dataAteFiltro = "";
                if($dataAte != null){
                    $dataAteFiltro = " AND o.hora_abre_cancela_entrada <= '$dataAte 23:59:59'";
                }

                $this->db->query("SELECT o.*, v.placa, p.descricao, pe.nome_completo FROM operacoes o INNER JOIN usuarios u ON o.usuarios_id = u.id  LEFT JOIN veiculos v ON o.veiculos_id = v.id INNER JOIN portoes p ON o.portaria_id = p.id LEFT JOIN pessoas pe ON o.pessoas_id = pe.id WHERE o.id > 0 $portaria $operador $tipo $empresa $veiculo $motorista $dataDeFiltro $dataAteFiltro ORDER BY o.id DESC");
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function consultaOperacaoPorId($id, $tipo)
        {
            try {
                if($tipo == 'N'){
                    $this->db->query("SELECT o.*, pe.nome_completo, pe.cpf, e.razao_social, e.cnpj, v.placa, v.descricao, v.tipo, o.tipo as tipo_operacao, po.descricao as portaria_descricao, o.hora_abre_cancela_entrada, o.hora_fecha_cancela_entrada, o.hora_abre_cancela_saida, o.hora_fecha_cancela_saida, o.obs_emergencia, u.nome, u.login FROM operacoes o LEFT JOIN pessoas pe ON o.pessoas_id = pe.id INNER JOIN pessoas_has_veiculos phv ON o.pessoas_id = phv.pessoas_id INNER JOIN veiculos v ON phv.veiculos_id = v.id INNER JOIN empresas e ON e.id = v.empresas_id INNER JOIN portoes po ON o.portaria_id = po.id INNER JOIN usuarios u ON o.usuarios_id = u.id WHERE o.id = :id");
                }else if($tipo == "E"){
                    $this->db->query("SELECT o.*, o.tipo as tipo_operacao, po.descricao as portaria_descricao, o.hora_abre_cancela_entrada, o.hora_fecha_cancela_entrada, o.hora_abre_cancela_saida, o.hora_fecha_cancela_saida, o.obs_emergencia, u.nome, u.login FROM operacoes o INNER JOIN portoes po ON o.portaria_id = po.id INNER JOIN usuarios u ON o.usuarios_id = u.id WHERE o.id = :id");
                }
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function buscaImagensOperacaoPorId($id)
        {
            try {
                $this->db->query("SELECT id, url_imagem FROM imagens WHERE operacoes_id = :id");
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function salvaImagemOperacao($path, $dateTime, $abreFecha, $tipo, $operacao_id)
        {
            try {
                $this->db->query("INSERT INTO imagens(url_imagem, created_at, tipo, tipo_operacao, operacoes_id) VALUES (:url_imagem, :created_at, :tipo, :tipo_operacao, :operacoes_id)");
                $this->db->bind("url_imagem", $path);
                $this->db->bind("created_at", $dateTime);
                $this->db->bind("tipo", $abreFecha);
                $this->db->bind("tipo_operacao", $tipo);
                $this->db->bind("operacoes_id", $operacao_id);
                $this->db->execQuery();
            } catch (Throwable $th) {
                return null;
            } 
        }

        public function retornaTipoOperacao($id)
        {
            try {
                $this->db->query("SELECT tipo FROM operacoes WHERE id = :id");
                $this->db->bind("id", $id);
                return $this->db->results();
            } catch (Throwable $th) {
                return null;
            } 
        }

        private function formataWhereClause($valores, $field)
        {
            $retorno = "AND $field IN (";
            for($i = 0; $i < count($valores); $i++){
                $retorno .= "'$valores[$i]',";
            }
            $retorno = substr($retorno, 0, -1);
            $retorno .= ")";
            return $retorno;
        }
    }
?>