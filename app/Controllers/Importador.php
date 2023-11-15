<?php

    use Shuchkin\SimpleXLSX;

    class Importador extends Controller{

        public $helper;
        public $log;
        public $SimpleXLSX;
        public $importErrorMessage;
        public $importContError;
        public $usuario;
        public $motorista;
        public $portaria;
        public $empresa;
        public $veiculo;
        public $operacao;
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $rotina = 'import';

        public function __construct()
        {
            require "../public/vendor/simplexlsx/src/SimpleXLSX.php";
            require "Portaria.php";
            require "Operacao.php";
            $this->helper = new Helpers();
            $this->log = new Logs();
            $this->operacao = new Operacao();
            $this->importErrorMessage = '';
            $this->importContError = 0;
            $this->motorista = new Motorista();
            $this->portaria = new Portaria();
            $this->usuario = $this->portaria->usuario;
            $this->veiculo = $this->operacao->veiculo;
            $this->empresa = new Empresa();
            
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Importador");
                $this->view('ferramentas/importador');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function importaArquivo(){
            if($this->helper->sessionValidate()){
                $temp_file = $_FILES["arquivo"]["tmp_name"];
                $arquivo = SimpleXLSX::parse($temp_file);
                for($i = 0; $i < count($arquivo->rows()); $i++){
                    if(count($arquivo->rows()[$i]) != 16){
                        $this->importContError++;
                        $this->importErrorMessage .= "<li>Estão faltando ou sobrando colunas no arquivo, verifique-o novamente!</li>";
                        break;
                    }
                    if($i == 0) continue;
                    
                    if(!$dataEntrada = $this->validaData($arquivo->rows()[$i][0], $i+1)) continue;
                    if(!$horaEntrada = $this->validaHora($arquivo->rows()[$i][1], $i+1)) continue;
                    if(!$tipoOperacao = $this->validaTipoOperacao($arquivo->rows()[$i][14], $i+1)) continue;
                    if(!$dataSaida = $this->validaData($arquivo->rows()[$i][2], $i+1, true)) continue;
                    if(!$horaSaida = $this->validaHora($arquivo->rows()[$i][3], $i+1, true)) continue;
                    if(!$this->validaDataHoraSaidaEntrada($dataEntrada, $horaEntrada, $dataSaida, $horaSaida, $i+1)) continue;
                    if(!$usuarioId = $this->validaLogin($arquivo->rows()[$i][4], $i+1)) continue;
                    if(!$placaVeiculo = $this->validaPlacaVeiculo($arquivo->rows()[$i][5], $i+1, $tipoOperacao)) continue;
                    if(!$descricaoVeiculo = $this->validaDescricaoVeiculo($arquivo->rows()[$i][6], $i+1, $tipoOperacao)) continue;
                    if(!$tipoVeiculo = $this->validaTipoVeiculo($arquivo->rows()[$i][7], $i+1, $tipoOperacao)) continue;
                    if(!$empresa = $this->validaEmpresa($arquivo->rows()[$i][10], $arquivo->rows()[$i][11], $i+1, $tipoOperacao)) continue;
                    if(!$this->validaVeiculo($placaVeiculo, $descricaoVeiculo, $tipoVeiculo, $empresa, $i+1, $tipoOperacao)) continue;
                    if(!$motorista = $this->validaMotorista($arquivo->rows()[$i][8], $arquivo->rows()[$i][9], $i+1, $tipoOperacao, $placaVeiculo)) continue;
                    if(!$portariaEntrada = $this->validaPortaria($arquivo->rows()[$i][12], 'entrada', $i+1)) continue;
                    if(!$portariaSaida = $this->validaPortaria($arquivo->rows()[$i][13], 'saida', $i+1, $portariaEntrada, $dataSaida, $tipoOperacao)) continue;
                    if(!$obsEmergencia = $this->validaObsEmergencia($tipoOperacao, $arquivo->rows()[$i][15], $i+1)) continue;
                    if($tipoOperacao == "E"){
                        if(!$this->operacao->verificaSeOperacaoEmergenciaJaRegistrada($dataEntrada, $horaEntrada, $portariaEntrada)){
                            $this->registrarOperacaoEmergencia($dataEntrada, $horaEntrada, $usuarioId, $portariaEntrada, $obsEmergencia);
                        }else{
                            $this->importContError++;
                            $this->importErrorMessage .= "<li>Operação da linha " . ($i+1). " já foi registrada anteriormente, esta linha foi desconsiderada</li>";
                        }
                    }else if($tipoOperacao == "N"){
                        if(strpos($dataSaida, "/") == false){
                            if($this->operacao->verificaSeOperacaoEntradaJaRegistrada($dataEntrada, $horaEntrada, $placaVeiculo, $motorista, $portariaEntrada)){
                                $this->importContError++;
                                $this->importErrorMessage .= "<li>Operação da linha " . ($i+1). " já foi registrada anteriormente, esta linha foi desconsiderada</li>";
                                continue;
                            }
                        }else{
                            if($this->operacao->verificaSeOperacaoJaRegistrada($dataEntrada, $horaEntrada, $dataSaida, $horaSaida, $placaVeiculo, $motorista, $portariaEntrada, $portariaSaida)){
                                $this->importContError++;
                                $this->importErrorMessage .= "<li>Operação da linha " . ($i+1). " já foi registrada anteriormente, esta linha foi desconsiderada</li>";
                                continue;
                            }
                        }
                        $operacao_id = $this->registraOperacaoEntrada($dataEntrada, $horaEntrada, $empresa, $placaVeiculo, $descricaoVeiculo, $tipoVeiculo, $motorista, $usuarioId, $portariaEntrada, $i+1);
                        if(strpos($dataSaida, "/") != false and $operacao_id != false){
                            $this->registraOperacaoSaida($operacao_id, $dataSaida, $horaSaida, $portariaSaida, $i+1);
                        }
                    }
                }
                if($this->importContError == 0){
                    $mensagem = 'Importação concluída com sucesso!';
                    $tipo = $this->tipoSuccess;
                }else{
                    $mensagem = "Foram encontrados " . $this->importContError . " erros na importação do arquivo.<br>";
                    $mensagem .= $this->importErrorMessage;
                    $tipo = $this->tipoError;
                }
                $mensagem .= '<br>Resumo da Importação:';
                $mensagem .= '<br>==================================';
                $mensagem .= "<li>Linhas processadas: " . (count($arquivo->rows()) - 1) . "</li>";
                $mensagem .= "<li>Linhas importadas com sucesso: " . ((count($arquivo->rows()) - 1) - $this->importContError) . "</li>";
                $mensagem .= "<li>Linhas não importadas: " . $this->importContError . "</li>";
                $this->helper->setReturnMessage(
                    $tipo,
                    $mensagem,
                    $this->rotina
                );
                $this->helper->redirectPage("/importador/");
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        private function registraOperacaoSaida($operacao_id, $dataSaida, $horaSaida, $portariaSaida, $linha)
        {
            $dataHoraSaida = $this->helper->formataDataHoraDBMode($dataSaida, $horaSaida);
            $session = [
                'pw_session_id' => $_SESSION['pw_session_id'],
                'pw_id' => $_SESSION['pw_id']
            ];
            $postData = http_build_query(array(
                'dataHoraSaida' => $dataHoraSaida,
                'idRegistro' => $operacao_id,
                'portaria_id' => $portariaSaida,
                'session' => $session,
                'ehImport' => true
            ));          
            $ch = curl_init();
            $url = URL . '/operacao/registrarSaida';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            if(strpos($server_output, "SUCESSO") == false){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi possível salvar no banco de dados o registro da linha $linha referente a operação de saída, tente novamente.</li>";
                return false;
            }
            curl_close ($ch);
        }

        private function registraOperacaoEntrada($dataEntrada, $horaEntrada, $empresa, $placaVeiculo, $descricaoVeiculo, $tipoVeiculo, $motorista, $usuarioId, $portariaEntrada, $linha)
        {
            $dataHoraEntrada = $this->helper->formataDataHoraDBMode($dataEntrada, $horaEntrada);
            $session = [
                'pw_session_id' => $_SESSION['pw_session_id'],
                'pw_id' => $_SESSION['pw_id']
            ];
            $postData = http_build_query(array(
                'dataHoraEntrada' => $dataHoraEntrada,
                'cnpj' => $empresa['cnpj'],
                'empresa' => $empresa['id'],
                'placa' => $placaVeiculo,
                'descricao' => $descricaoVeiculo,
                'tipo' => $tipoVeiculo,
                'motorista' => $motorista,
                'usuario' => $usuarioId,
                'portaria' => $portariaEntrada,
                'session' => $session,
                'ehImport' => true
            ));          
            $ch = curl_init();
            $url = URL . '/operacao/registrarEntrada';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            if(strpos($server_output, "SUCESSO") != false){
                $retorno = explode("<idOperacao>", $server_output);
                $retorno = explode("</idOperacao>", $retorno[1]);
                return $retorno[0];
            }else{
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi possível salvar o registro da linha $linha no banco de dados, tente novamente.</li>";
                return false;
            }
            curl_close ($ch);
        }

        private function registrarOperacaoEmergencia($dataEntrada, $horaEntrada, $usuarioId, $portariaEntrada, $obsEmergencia)
        {
            $dataHoraEntrada = $this->helper->formataDataHoraDBMode($dataEntrada, $horaEntrada);
            $session = [
                'pw_session_id' => $_SESSION['pw_session_id'],
                'pw_id' => $_SESSION['pw_id']
            ];
            $postData = http_build_query(array(
                'dataHoraEntrada' => $dataHoraEntrada,
                'portaria_id' => $portariaEntrada,
                'usuario_id' => $usuarioId,
                'observacao' => $obsEmergencia,
                'session' => $session,
                'ehImport' => true
            ));          
            $ch = curl_init();
            $url = URL . '/operacao/registrarOperacaoEmergencia';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close ($ch);
        }

        private function validaVeiculo($placaVeiculo, $descricaoVeiculo, $tipoVeiculo, $empresa, $linha, $ehEmergencia)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            if(!$this->veiculo->verificaPlaca($placaVeiculo)){
                $placaVeiculo = $this->veiculo->cadastrar($placaVeiculo, $descricaoVeiculo, $tipoVeiculo, $empresa["id"], "registro");
                if(!$placaVeiculo){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Veículo inválido na linha $linha, tente novamente.</li>";
                    return false;
                }
            }
            return true;
        }

        private function validaEmpresa($cpfcnpj, $nome, $linha, $ehEmergencia)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            $empresa = [];
            if(empty($cpfcnpj)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informado CPF ou CNPJ da empresa na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            if((strlen($cpfcnpj) != 11 and strlen($cpfcnpj) != 14) or !is_numeric($cpfcnpj)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Formato de CPF/CNPJ inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpfcnpj</b></li>";
                return false;
            }
            $cpfcnpj = $this->helper->formata_cpf_cnpj($cpfcnpj);
            $empresaDados = $this->empresa->listaEmpresasPorFiltro("cnpj = '$cpfcnpj'");
            if($empresaDados == null and empty($nome)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informado o nome da empresa na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            if($empresaDados == null){
                $this->empresa->cadastrar($cpfcnpj, $nome, "registro");
                $empresaDados = $this->empresa->listaEmpresasPorFiltro("cnpj = '$cpfcnpj'");
            }
            $empresa["id"] = $empresaDados[0]->id;
            $empresa["cnpj"] = $empresaDados[0]->cnpj;
            return $empresa;
        }

        private function validaDataHoraSaidaEntrada($dataEntrada, $horaEntrada, $dataSaida = false, $horaSaida = false, $linha)
        {
            $entrada = $dataEntrada . " " . $horaEntrada;
            if($dataSaida != 1) {
                $saida = $dataSaida . " " . $horaSaida;
                if($entrada >= $saida){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Datas e Horas inválidas na linha $linha. Data de Saída não pode ser menor ou igual a Data de Entrada. Valores informados, Data de Entrada: <b>$entrada</b>, Data de Saída: <b>$saida</b></li>";
                    return false;
                }
            }
            return true;
        }

        private function validaPortaria($portaria, $tipo, $linha, $portariaEntrada = false, $dataSaida = false, $ehEmergencia = false)
        {
            if($tipo == "entrada"){
                if(empty($portaria)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Não foi informada portaria de entrada na linha $linha, ajuste e tente novamente.</li>";
                    return false;
                }
            }else if($tipo == "saida"){
                if($dataSaida == 1 and empty($portaria)){
                    return true;
                }
                if($ehEmergencia == 'E'){
                    return true;
                }
                if($dataSaida != 1 and empty($portaria)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Não foi informada portaria de saída na linha $linha, ajuste e tente novamente.</li>";
                    return false;
                }
            }
            $portaria = trim($portaria);
            $portariaDados = $this->portaria->listaPortariasPorFiltro($portaria, true);
            if($portariaDados == null){
                $this->importContError++;
                $this->importErrorMessage .= "<li>A portaria  informada na linha $linha não existe, a portaria informada foi <b>$portaria</b>, ajuste e tente novamente</li>";
                return false;
            }
            if(!$this->portaria->checkPortariasLigadas($portariaEntrada, $portariaDados[0]->id) and $portariaDados[0]->id != $portariaEntrada and $tipo == "saida"){
                $this->importContError++;
                $this->importErrorMessage .= "<li>A portaria de saída informada na linha $linha é inválida, um veículo não tem permissão para sair nessa portaria quando entrar na portaria de entrada informada nesta mesma linha, ajuste e tente novamente</li>";
                return false;
            }
            return $portariaDados[0]->id;
        }

        private function validaObsEmergencia($tipo, $obs, $linha)
        {
            if($tipo == "E" and empty($obs)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada observação da emergência na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            if($tipo == "N"){
                return true;
            }
            return $obs;
        }

        private function validaTipoOperacao($tipo, $linha)
        {
            $tipos = [
                'Normal' => 'N',
                'Emergência' => 'E',
                'Emergencia' => 'E',
                'E' => 'E',
                'N' => 'N'
            ];
            if(empty($tipo)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informado tipo de operação na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            $tipo = ucfirst($tipo);
            if(!isset($tipos[$tipo])){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Formato de tipo de operação inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$tipo</b>, valores permitidos: <b> Normal, Emergência</b></li>";
                return false;
            }
            return $tipos[$tipo];
        }

        private function validaMotorista($cpf, $nome, $linha, $ehEmergencia, $placaVeiculo)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            if(empty($cpf)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informado CPF do motorista na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            if(strpos($cpf, ".") != false){
                if(strlen($cpf) != 14){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                    return false;
                }else{
                    if(strpos($cpf, "-") == false){
                        $this->importContError++;
                        $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                        return false;
                    }else{
                        $array = explode('.', $cpf);
                        if(strlen($array[0]) != 3 or strlen($array[1]) != 3 or strlen($array[2]) != 6){
                            $this->importContError++;
                            $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                            return false;
                        }else{
                            $array2 = explode('-', $array[2]);
                            if(strlen($array2[0]) != 3){
                                $this->importContError++;
                                $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                                return false;
                            }
                            if(strlen($array2[1]) != 2){
                                $this->importContError++;
                                $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                                return false;
                            }
                            if(!is_numeric($array[0]) or !is_numeric($array[1]) or !is_numeric($array2[0]) or !is_numeric($array2[1])){
                                $this->importContError++;
                                $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                                return false;
                            }
                        }
                    }
                }
            }else{
                if(strlen($cpf) != 11){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                    return false;
                }else{
                    if(!is_numeric($cpf)){
                        $this->importContError++;
                        $this->importErrorMessage .= "<li>Formato do CPF do motorista inválido na linha $linha, ajuste e tente novamente. Valor informado: <b>$cpf</b></li>";
                        return false;
                    }else{
                        $cpf = $this->helper->formata_cpf_cnpj($cpf);
                    }
                }
            }
            $motoristaDados = $this->motorista->buscaMotoristaPorCpf($cpf);
            if($motoristaDados == null){
                if(empty($nome)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Não foi informado nome do motorista na linha $linha, ajuste e tente novamente.</li>";
                    return false;
                }
                $veiculo_id = $this->veiculo->retornaIDPOrPlaca($placaVeiculo, true);
                $motorista_id = $this->motorista->cadastrar($nome, $cpf, $veiculo_id);
            }else{
                $motorista_id = $motoristaDados[0]->id;
            }
            return $motorista_id;
        }

        private function validaTipoVeiculo($tipo, $linha, $ehEmergencia)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            $tipos = [
                'Carro' => 1,
                'Caminhão' => 2,
                'Moto' => 3,
                'Outro' => 4
            ];
            $tipo = ucfirst($tipo);
            if(empty($tipo)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informado tipo do veículo na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            if(!isset($tipos[$tipo])){
                $this->importContError++;
                $this->importErrorMessage .= "<li>O tipo do veículo é inválido na linha $linha, os tipos permitidos são: Carro, Caminhão, Moto, Outro. O tipo informado foi <b>$tipo</b>, ajuste e tente novamente</li>";
                return false;
            }
            return $tipos[$tipo];
        }

        private function validaDescricaoVeiculo($descricao, $linha, $ehEmergencia)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            if(empty($descricao)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada descrição do veículo na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            return $descricao;
        }

        private function validaPlacaVeiculo($placa, $linha, $ehEmergencia)
        {
            if($ehEmergencia == 'E'){
                return true;
            }
            if(empty($placa)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada placa do veículo na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }else{
                if(strpos($placa, "-") == false){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>2 Formato da placa do veículo inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $placa</b></li>";
                    return false;
                }
                if(strlen($placa) != 8){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>3 Formato da placa do veículo inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $placa</b></li>";
                    return false;
                }
                $array = explode("-", $placa);
                if(strlen($array[0]) != 3 or strlen($array[1]) != 4 or $this->helper->contains_number($array[0])){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>4 Formato da placa do veículo inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $placa</b></li>";
                    return false;
                }
            }
            return $placa;
        }

        private function validaLogin($login, $linha)
        {
            $usuario = $this->usuario->buscaUsuarioPorLogin($login);
            if(!$usuario){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Login inválido na linha $linha, esse usuário não existe no sistema, verifique novamente. Login informado:<b> $login</b></li>";
                return false;
            }
            $usuario_id = $usuario[0]->id;
            return $usuario_id;
        }

        private function validaHora($hora, $linha, $skip = false)
        {
            $array = explode(":", $hora);
            if($skip == false and empty($hora)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada hora na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }else if(strpos($hora, ":") == false){
                if(!empty($hora)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato de hora inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $hora</b></li>";
                    return false;
                }
            }else{
                if(strlen($array[0]) != 2 or strlen($array[1]) != 2 or (isset($array[2]) and strlen($array[2]) != 2)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato de hora inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $hora</b></li>";
                    return false;
                }
            }
            if(!isset($array[2])){
                $hora = $hora . ":00";
            }
            if($skip == true and empty($hora)){
                return true;
            }
            return $hora;            
        }

        private function validaData($data, $linha, $skip = false)
        {
            if($skip == false and empty($data)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada data na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }else if(strpos($data, "/") == false){
                if(!empty($data)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato de data inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $data</b></li>";
                    return false;
                }
            }else{
                $array = explode("/", $data);
                if(strlen($array[0]) != 2 or strlen($array[1]) != 2 or strlen($array[2]) != 4){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato de data inválido na linha $linha, ajuste e tente novamente. Valor informado:<b> $data</b></li>";
                    return false;
                }
            }
            if($skip == true and empty($data)){
                return true;
            }
            return $data;
        }

        
    }

?>