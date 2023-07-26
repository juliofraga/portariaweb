<?php

    class Operacao extends Controller{
        public $helper;
        public $empresa;
        public $veiculo;
        public $motorista;
        public $operacaoModel;
        public $log;
        public $camera;

        public function __construct()
        {
            require "Motorista.php";
            require "Veiculo.php";
            $this->helper = new Helpers();
            $this->log = new Logs();
            $this->veiculo = new Veiculo();
            $this->empresa = new Empresa();
            $this->motorista = new Motorista();
            $this->operacaoModel = $this->model('OperacaoModel');
            $this->camera = $this->model('CameraModel');
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function registrarEntrada()
        {
            if($this->helper->sessionValidate()){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    if(!$this->empresa->verificaEmpresa($form["cnpj"])){
                        $form["empresa"] = $this->empresa->cadastrar($form["cnpj"], $form["empresa"], "registro");
                    }
                    if(!$this->veiculo->verificaVeiculoPorId($form["placa"])){
                        $form["placa"] = $this->veiculo->cadastrar($form["placa"], $form["descricao"], $form["tipo"], $form["empresa"], "registro");
                    }

                    if(!$this->motorista->verificaMotorista($form["motorista"])){
                        $form["motorista"] = $this->motorista->cadastrar($form["motorista"], $form["cpfMotorista"], $form["placa"]);
                    }
                    // $form["empresa"] - ID da empresa
                    // $form["placa"] - ID do veículo
                    // $form["motorista"] - ID da pessoa
                    $dateTime = $this->helper->returnDateTime();
                    $lastInsertId = $this->operacaoModel->registrarOperacao($dateTime, $form["usuario"], $form["placa"], $form["motorista"], $form["portaria"]);
                    if($lastInsertId != null){
                        $this->log->registraLog($_SESSION['pw_id'], "Operação", $lastInsertId, 0, $dateTime);
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                        echo "<idOperacao>$lastInsertId</idOperacao>";
                        for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                            $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $lastInsertId);
                        }
                        $_SESSION['infoCapturaImagem'] = null;
                        $_SESSION["contImagens"] = null;
                    }else{
                        echo "<registroOperacao>ERRO</registroOperacao>";
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function registrarSaida()
        {
            if($this->helper->sessionValidate()){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    if($this->operacaoModel->registrarSaida($form["idRegistro"], $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Operação", $$form["idRegistro"], 0, $dateTime);
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                    }else{
                        echo "<registroOperacao>ERRO</registroOperacao>";
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function registrarFechamentoCancela($tipo)
        {
            if($this->helper->sessionValidate()){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    if($this->operacaoModel->fechaCancela($form["operacao"], $dateTime, $tipo)){
                        $this->log->registraLog($_SESSION['pw_id'], "Operação", $form["operacao"], 0, $dateTime);
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                    }else{
                        echo "<registroOperacao>ERRO</registroOperacao>";
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function registrarOperacaoEmergencia()
        {
            if($this->helper->sessionValidate()){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    $lastInsertId = $this->operacaoModel->registraOperacaoEmergencia($form, $dateTime);
                    if($lastInsertId){
                        $this->log->registraLog($_SESSION['pw_id'], "Operação Emergencial", $lastInsertId, 0, $dateTime);
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                    }else{
                        echo "<registroOperacao>ERRO</registroOperacao>";
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function fechaCancelaEmergencia()
        {
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                if($this->operacaoModel->fechaCancelaEmergencia($dateTime)){
                    echo "<registroOperacao>SUCESSO</registroOperacao>";
                }else{
                    echo "<registroOperacao>ERRO</registroOperacao>";
                }
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function buscaVeiculosParaSaida()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo "<registroOperacao>ERRO</registroOperacao>";
                }else{
                    $operacoes = $this->operacaoModel->buscaVeiculosParaSaida($form["portaria"]);
                    foreach($operacoes as $operacao){
                        echo "<registroOperacao>";
                        echo "<id>".$operacao->id;
                        echo "</id>";
                        echo "<placa>".$operacao->placa;
                        echo "</placa>";
                        echo "<nome_completo>".$operacao->nome_completo;
                        echo "</nome_completo>";
                        echo "</registroOperacao>";
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function consultaOperacoes($portaria = null, $operador = null, $tipo = null, $empresa = null, $veiculo = null, $motorista = null, $dataDe = null, $dataAte = null)
        {
            if($this->helper->sessionValidate()){
                return $this->operacaoModel->consultaOperacoes($portaria, $operador, $tipo, $empresa, $veiculo, $motorista, $dataDe, $dataAte);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consultaOperacaoPorId($id)
        {
            if($this->helper->sessionValidate()){
                return $this->operacaoModel->consultaOperacaoPorId($id);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function buscaImagensOperacaoPorId($id)
        {
            if($this->helper->sessionValidate()){
                return $this->operacaoModel->buscaImagensOperacaoPorId($id);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function capturaImagem()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $cameras = $this->camera->listaCamerasPortaria($form["portaria"]);
                if($form["tipo"] == "entrada"){
                    $tipo = 0;
                }else if($form["tipo"] == "saida"){
                    $tipo = 1;
                }else if($form["tipo"] == "emergencia"){
                    $tipo = 2;
                }
                $x = 0;
                foreach($cameras as $c){
                    $name = time();
                    $endereco = URL."/public/camera_".$c->id.".php";
                    $comando = WKHTMLTOIMAGE_INSTALACAO." --height 1100 --width 1800 " . $endereco . " ".DIR_CAPTURA_IMAGENS.$name.".jpg";
                    exec($comando);
                    $_SESSION['infoCapturaImagem'][$x] = [
                        'path' => DIR_CAPTURA_IMAGENS.$name.".jpg",
                        'abreFecha' => 0,
                        'tipo' => $tipo
                    ];
                    $x++;
                }
                $_SESSION["contImagens"] = $x;
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

    }

?>