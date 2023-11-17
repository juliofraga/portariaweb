<?php

    class Operacao extends Controller{
        public $helper;
        public $empresa;
        public $veiculo;
        public $motorista;
        public $operacaoModel;
        public $log;
        public $camera;
        public $configuracaoModel;

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
            $this->configuracaoModel = $this->model('ConfiguracaoModel');
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
            if(isset($_POST["ehImport"]) and $_POST["ehImport"] == true){
                $ehImport = true;
            }else{
                $ehImport = false;
            }
            if($this->helper->sessionValidate() or $ehImport){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    if(!$ehImport){
                        if(!$this->empresa->verificaEmpresa($form["cnpj"])){
                            $form["empresa"] = $this->empresa->cadastrar($form["cnpj"], $form["empresa"], "registro");
                        }
                        if(!$this->veiculo->verificaPlaca($form["placa"])){
                            $form["placa"] = $this->veiculo->cadastrar($form["placa"], $form["descricao"], $form["tipo"], $form["empresa"], "registro");
                        }else{
                            $form["placa"] = $this->veiculo->retornaIDPOrPlaca($form["placa"]);
                        }

                        if(!$this->motorista->verificaMotorista($form["motorista"])){
                            $form["motorista"] = $this->motorista->cadastrar($form["motorista"], $form["cpfMotorista"], $form["placa"]);
                        }
                        $dateTime = $this->helper->returnDateTime();
                    }else{
                        $dateTime = $form["dataHoraEntrada"];
                        $form["placa"] = $this->veiculo->retornaIDPOrPlaca($form["placa"], $ehImport);
                    }
                    $lastInsertId = $this->operacaoModel->registrarOperacao($dateTime, $form["usuario"], $form["placa"], $form["motorista"], $form["portaria"]);
                    if($lastInsertId != null){
                        if(isset($form["ehImport"])){
                            $userId = $form["session"]["pw_id"];
                        }else{
                            $userId = $_SESSION['pw_id'];
                        }
                        $this->log->registraLog($userId, "Operação", $lastInsertId, 0, $dateTime);
                        $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $userId, "Operação - Entrada de Veículo");
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                        echo "<idOperacao>$lastInsertId</idOperacao>";
                        if(!$ehImport){
                            for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                                $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $lastInsertId);
                            }
                            $_SESSION['infoCapturaImagem'] = null;
                            $_SESSION["contImagens"] = null;
                        }
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
            if(isset($_POST["ehImport"]) and $_POST["ehImport"] == true){
                $ehImport = true;
            }else{
                $ehImport = false;
            }
            if($this->helper->sessionValidate() or $ehImport){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    if($ehImport){
                        $dateTime = $form["dataHoraSaida"];
                        $userId = $form["session"]["pw_id"];
                    }else{
                        $dateTime = $this->helper->returnDateTime();
                        $userId = $_SESSION['pw_id'];
                    }
                    if($this->operacaoModel->registrarSaida($form["idRegistro"], $dateTime, $form["portaria_id"])){
                        $this->log->registraLog($userId, "Operação", $form["idRegistro"], 0, $dateTime);
                        $this->log->gravaLog($dateTime, $form["idRegistro"], "Adicionou", $userId, "Operação - Saída de Veículo");
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                        if(!$ehImport){
                            for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                                $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $form["idRegistro"]);
                            }
                            $_SESSION['infoCapturaImagem'] = null;
                            $_SESSION["contImagens"] = null;
                        }
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
                    $id = $form["operacao"];
                    if($this->operacaoModel->fechaCancela($id, $dateTime, $tipo)){
                        $this->log->registraLog($_SESSION['pw_id'], "Operação", $id, 0, $dateTime);
                        $this->log->gravaLog($dateTime, $id, "Adicionou", $_SESSION['pw_id'], "Operação - Fechamento de Cancela");
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                        if($this->configuracaoModel->opcaoAtiva(4) == true){
                            for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                                $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $id);
                            }
                            $_SESSION['infoCapturaImagem'] = null;
                            $_SESSION["contImagens"] = null;
                        }
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
            if(isset($_POST["ehImport"]) and $_POST["ehImport"] == true){
                $ehImport = true;
            }else{
                $ehImport = false;
            }
            if($this->helper->sessionValidate() or $ehImport){
                $retornoRegistro = "<registroOperacao>ERRO</registroOperacao>";
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($form == null or !isset($form)){
                    echo $retornoRegistro;
                }else{
                    if($ehImport){
                        $dateTime = $form["dataHoraEntrada"];
                    }else{
                        $dateTime = $this->helper->returnDateTime();
                    }
                    $lastInsertId = $this->operacaoModel->registraOperacaoEmergencia($form, $dateTime);
                    if($lastInsertId){
                        if(isset($form["ehImport"])){
                            $userId = $form["session"]["pw_id"];
                        }else{
                            $userId = $_SESSION['pw_id'];
                        }
                        $this->log->registraLog($userId, "Operação Emergencial", $lastInsertId, 0, $dateTime, $ehImport);
                        $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $userId, "Operação - Emergência", $ehImport);
                        echo "<registroOperacao>SUCESSO</registroOperacao>";
                        echo "<idOperacaoEmergencia>" . $lastInsertId . "</idOperacaoEmergencia>";
                        if(!$ehImport){
                            for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                                $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $lastInsertId);
                            }
                            $_SESSION['infoCapturaImagem'] = null;
                            $_SESSION["contImagens"] = null;
                        }
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
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $dateTime = $this->helper->returnDateTime();
                $id = $form['idOperacao'];
                if($this->operacaoModel->fechaCancelaEmergencia($id, $dateTime)){
                    $this->log->gravaLog($dateTime, $id, "Adicionou", $_SESSION['pw_id'], "Operação - Fechamento de Cancela (Emergência)");
                    echo "<registroOperacao>SUCESSO</registroOperacao>";
                    if($this->configuracaoModel->opcaoAtiva(4) == true){
                        for($x = 0; $x < $_SESSION["contImagens"]; $x++){
                            $this->operacaoModel->salvaImagemOperacao($_SESSION['infoCapturaImagem'][$x]['path'], $dateTime, $_SESSION['infoCapturaImagem'][$x]['abreFecha'], $_SESSION['infoCapturaImagem'][$x]['tipo'], $id);
                        }
                        $_SESSION['infoCapturaImagem'] = null;
                        $_SESSION["contImagens"] = null;
                    }
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
                    $portarias = [];
                    $portariaLigada1 = [];
                    $portariaLigada2 = [];
                    $portarias[] = $form["portaria"];
                    $buscaPort1 = $this->operacaoModel->buscaPortariasLigadas($form["portaria"], 1);
                    foreach($buscaPort1 as $port1){
                        $portariaLigada1[] = $port1->portaria_id_2;
                    }
                    $buscaPort2 = $this->operacaoModel->buscaPortariasLigadas($form["portaria"], 2);
                    foreach($buscaPort2 as $port2){
                        $portariaLigada2[] = $port2->portaria_id_1;
                    }
                    $portarias = array_merge($portarias, $portariaLigada1, $portariaLigada2);
                    $operacoes = $this->operacaoModel->buscaVeiculosParaSaida($portarias);
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

        public function consultaOperacoes($portaria = null, $operador = null, $tipo = null, $empresa = null, $veiculo = null, $motorista = null, $dataDe = null, $dataAte = null, $id = null)
        {
            if($this->helper->sessionValidate()){
                return $this->operacaoModel->consultaOperacoes($portaria, $operador, $tipo, $empresa, $veiculo, $motorista, $dataDe, $dataAte, $id);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consultaOperacaoPorId($id)
        {
            if($this->helper->sessionValidate()){
                $tipoOperacao = $this->operacaoModel->retornaTipoOperacao($id);
                if($tipoOperacao){
                    $tipoOperacao = $tipoOperacao[0]->tipo;
                }                
                return $this->operacaoModel->consultaOperacaoPorId($id, $tipoOperacao);
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
                $operacao = $form['operacao'];
                $capturaFechamentoCancPerm = $this->configuracaoModel->opcaoAtiva(4);
                if($operacao == 0 or ($operacao == 1 and $capturaFechamentoCancPerm == true)){
                    $cameras = $this->camera->listaCamerasPortaria($form["portaria"], $form["tipo"]);
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
                            'abreFecha' => $operacao,
                            'tipo' => $tipo
                        ];
                        $x++;
                        $this->log->gravaLog($this->helper->returnDateTime(), $c->id, "Adicionou", $_SESSION['pw_id'], "Imagem - Câmera");
                    }
                    $_SESSION["contImagens"] = $x;
                }
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }
        
        public function buscaPortariaSaidaPorId($id)
        {
            if($this->helper->sessionValidate()){              
                return $this->operacaoModel->buscaPortariaSaidaPorId($id);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function verificaSeOperacaoEmergenciaJaRegistrada($dataEntrada, $horaEntrada, $portariaEntrada)
        {
            if($this->helper->sessionValidate()){   
                $dataHoraEntrada = $this->helper->formataDataHoraDBMode($dataEntrada, $horaEntrada);
                return $this->operacaoModel->verificaSeOperacaoEmergenciaJaRegistrada($dataHoraEntrada, $portariaEntrada);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function verificaSeOperacaoEntradaJaRegistrada($dataEntrada, $horaEntrada, $placaVeiculo, $motorista, $portariaEntrada)
        {
            if($this->helper->sessionValidate()){
                $dataHoraEntrada = $this->helper->formataDataHoraDBMode($dataEntrada, $horaEntrada);
                $veiculo = $this->veiculo->retornaIDPOrPlaca($placaVeiculo, true);
                return $this->operacaoModel->verificaSeOperacaoEntradaJaRegistrada($dataHoraEntrada, $veiculo, $motorista, $portariaEntrada);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function verificaSeOperacaoJaRegistrada($dataEntrada, $horaEntrada, $dataSaida, $horaSaida, $placaVeiculo, $motorista, $portariaEntrada, $portariaSaida)
        {
            if($this->helper->sessionValidate()){   
                $dataHoraEntrada = $this->helper->formataDataHoraDBMode($dataEntrada, $horaEntrada);
                $dataHoraSaida = $this->helper->formataDataHoraDBMode($dataSaida, $horaSaida);
                $veiculo = $this->veiculo->retornaIDPOrPlaca($placaVeiculo, true);
                return $this->operacaoModel->verificaSeOperacaoJaRegistrada($dataHoraEntrada, $dataHoraSaida, $veiculo, $motorista, $portariaEntrada, $portariaSaida);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

    }

?>