<?php

    class Operacao extends Controller{
        public $helper;
        public $empresa;
        public $veiculo;
        public $motorista;
        public $operacaoModel;
        public $log;

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

        public function consultaOperacoes()
        {
            if($this->helper->sessionValidate()){
                return $this->operacaoModel->consultaOperacoes();
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>