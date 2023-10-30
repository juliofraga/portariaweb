<?php

    use Shuchkin\SimpleXLSX;

    class Importador extends Controller{

        public $helper;
        public $log;
        public $SimpleXLSX;
        public $importErrorMessage;
        public $importContError;
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $rotina = 'import';

        public function __construct()
        {
            require "../public/vendor/simplexlsx/src/SimpleXLSX.php";
            $this->helper = new Helpers();
            $this->log = new Logs();
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
                    if(count($arquivo->rows()[$i]) != 11){
                        $this->importContError++;
                        $this->importErrorMessage .= "<li>Estão faltando ou sobrando colunas no arquivo, verifique-o novamente!</li>";
                        break;
                    }
                    if($i == 0) continue;
                    if(!$dataEntrada = $this->validaData($arquivo->rows()[$i][0], $i+1)) continue;
                }
                if($this->importContError == 0){
                    $mensagem = 'Importação concluída com sucesso!';
                    $mensagem .= '<br>Resumo da Importação:';
                    $mensagem .= '<br>==================================';
                    $mensagem .= "<li>Linhas Processadas: " . (count($arquivo->rows()) - 1) . "</li>";
                    $mensagem .= "<li>Linhas Importadas com Sucesso: " . (count($arquivo->rows()) - 1) . "</li>";
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotina
                    );
                }else{
                    $mensagem = "Foram encontrados " . $this->importContError . " erros na importação do arquivo.<br>";
                    $mensagem .= $this->importErrorMessage;
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        $mensagem,
                        $this->rotina
                    );
                }
                $this->helper->redirectPage("/importador/");
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        private function validaData($data, $linha, $skip = false)
        {
            if($skip == false and empty($data)){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Não foi informada data na linha $linha, ajuste e tente novamente.</li>";
                return false;
            }else if(strpos($data, "/") == false){
                $this->importContError++;
                $this->importErrorMessage .= "<li>Formato de data inválido na linha $linha, ajuste e tente novamente. Valor informado: $data</li>";
                return false;
            }else{
                $array = explode("/", $data);
                if(strlen($array[0]) != 2 or strlen($array[1]) != 2 or strlen($array[2]) != 4){
                    $this->importContError++;
                    $this->importErrorMessage .= "<li>Formato de data inválido na linha $linha, ajuste e tente novamente. Valor informado: $data</li>";
                    return false;
                }
            }
        }

    }

?>