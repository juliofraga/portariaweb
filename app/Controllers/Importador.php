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
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $rotina = 'import';

        public function __construct()
        {
            require "../public/vendor/simplexlsx/src/SimpleXLSX.php";
            require "Motorista.php";
            require "Portaria.php";
            $this->helper = new Helpers();
            $this->log = new Logs();
            $this->importErrorMessage = '';
            $this->importContError = 0;
            $this->motorista = new Motorista();
            $this->portaria = new Portaria();
            $this->usuario = $this->portaria->usuario;
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
                    if(!$dataSaida = $this->validaData($arquivo->rows()[$i][2], $i+1, true)) continue;
                    if(!$horaSaida = $this->validaHora($arquivo->rows()[$i][3], $i+1, true)) continue;
                    if(!$loginUsuario = $this->validaLogin($arquivo->rows()[$i][4], $i+1)) continue;
                    if(!$placaVeiculo = $this->validaPlacaVeiculo($arquivo->rows()[$i][5], $i+1)) continue;
                    if(!$descricaoVeiculo = $this->validaDescricaoVeiculo($arquivo->rows()[$i][6], $i+1)) continue;
                    if(!$tipoVeiculo = $this->validaTipoVeiculo($arquivo->rows()[$i][7], $i+1)) continue;
                    if(!$motorista = $this->validaMotorista($arquivo->rows()[$i][8], $arquivo->rows()[$i][9], $i+1)) continue;
                    //if(!$empresa = $this->validaEmpresa($arquivo->rows()[$i][10], $arquivo->rows()[$i][11], $i+1)) continue;
                    if(!$portariaEntrada = $this->validaPortaria($arquivo->rows()[$i][12], 'entrada', $i+1)) continue;
                    //if(!$portariaSaida = $this->validaPortaria($arquivo->rows()[$i][13], 'saida', $i+1)) continue;
                    if(!$tipoOperacao = $this->validaTipoOperacao($arquivo->rows()[$i][14], $i+1)) continue;
                    if(!$obsEmergencia = $this->validaObsEmergencia($tipoOperacao, $arquivo->rows()[$i][15], $i+1)) continue;
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

        private function validaPortaria($portaria, $tipo, $linha)
        {
            if($tipo == "entrada"){
                if(empty($portaria)){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Não foi informada portaria na linha $linha, ajuste e tente novamente.</li>";
                    return false;
                }
                $portaria = trim($portaria);
                $portariaDados = $this->portaria->listaPortariasPorFiltro($portaria, true);
                if($portariaDados == null){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>A portaria  informada na linha $linha não existe, a portaria informada foi <b>$portaria</b>, ajuste e tente novamente</li>";
                    return false;
                }
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

        private function validaMotorista($cpf, $nome, $linha)
        {
            $motorista = [];
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
                array_push($motorista, null);
            }else{
                array_push($motorista, $motoristaDados[0]->id);
            }
            array_push($motorista, $nome);
            return $motorista;
        }

        private function validaTipoVeiculo($tipo, $linha)
        {
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

        private function validaDescricaoVeiculo($descricao, $linha)
        {
            if(empty($descricao)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada descrição do veículo na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }
            return $descricao;
        }

        private function validaPlacaVeiculo($placa, $linha)
        {
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