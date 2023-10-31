<?php

    use Shuchkin\SimpleXLSX;

    class Importador extends Controller{

        public $helper;
        public $log;
        public $SimpleXLSX;
        public $importErrorMessage;
        public $importContError;
        public $usuario;
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $rotina = 'import';

        public function __construct()
        {
            require "../public/vendor/simplexlsx/src/SimpleXLSX.php";
            require "Usuario.php";
            $this->helper = new Helpers();
            $this->log = new Logs();
            $this->usuario = new Usuario();
            $this->importErrorMessage = '';
            $this->importContError = 0;
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
                    if(count($arquivo->rows()[$i]) != 14){
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
                    //continuar da descrição, não está finalizada ainda
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

        private function validaDescricaoVeiculo($descricao, $linha){
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
                if(strlen($array[0]) != 3 or strlen($array[1]) != 4 or $this->contains_number($array[0])){
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
            return $data;
        }

        private function contains_number($string) {
            return is_numeric(filter_var($string, FILTER_SANITIZE_NUMBER_INT));
         }

    }

?>