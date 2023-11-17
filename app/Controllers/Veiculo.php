<?php

    class Veiculo extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'veiculo';
        private $rotinaAlt = 'veiculo';
        public $helper;
        public $veiculoModel;
        public $log;
        public $empresa;

        public function __construct()
        {
            require "Empresa.php";
            $this->helper = new Helpers();
            $this->veiculoModel = $this->model('VeiculoModel');
            $this->log = new Logs();
            $this->empresa = new Empresa();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function cadastrar($placa = null, $descricao = null, $tipoCarro = null, $empresa = null, $tipo = null)
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $dateTime = $this->helper->returnDateTime();
                if($this->helper->validateFields($form) or $tipo == "registro"){
                    if($tipo == "registro"){
                        $form = [
                            "placaVeiculo" => $placa,
                            "descricao" => $descricao,
                            "tipo" => $tipoCarro,
                            "empresa" => $empresa,
                        ];
                    }
                    $form["placaVeiculo"] = strtoupper($form["placaVeiculo"]);
                    if(!$this->verificaPlaca($form["placaVeiculo"])){
                        $lastInsertId = $this->veiculoModel->cadastrarVeiculo($form, $dateTime, $tipo);
                        if($lastInsertId != null){
                            if($tipo == null){
                                $this->helper->setReturnMessage(
                                    $this->tipoSuccess,
                                    'Veículo cadastrado com sucesso!',
                                    $this->rotinaCad
                                );
                            }
                            $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Veículo");
                            $this->log->registraLog($_SESSION['pw_id'], "Veículo", $lastInsertId, 0, $dateTime);
                            if($tipo == "registro"){
                                return $lastInsertId;
                            }else if($tipo == null){
                                $this->helper->redirectPage("/veiculo/consulta");
                            }
                        }else{
                            if($tipo == null){
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    'Não foi possível cadastrar o veículo, tente novamente!',
                                    $this->rotinaCad
                                );
                                $this->helper->redirectPage("/veiculo/novo");
                            }
                        }
                    }else{
                        if($tipo == null){
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                "Não foi possível cadastrar o veículo, já existe outro veículo cadastrado com essa placa(".$form["placaVeiculo"].")",
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Veículo", "Erro ao gravar no banco de dados");
                            $this->helper->redirectPage("/veiculo/novo");
                        }
                    }
                }else{
                    if($tipo == null){
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Existem campos que não foram preenchidos, tente novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Veículo", "Alguns campos não foram preenchidos");
                        $this->helper->redirectPage("/veiculo/novo");
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function verificaPlaca($placa, $veiculo_id = null)
        {
            if($this->helper->sessionValidate()){
                return $this->veiculoModel->verificaPlaca($placa, $veiculo_id);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function verificaVeiculoPorId($veiculo_id){
            if($this->helper->sessionValidate()){
                $retorno = $this->veiculoModel->verificaVeiculoPorId($veiculo_id);
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaIDPOrPlaca($placa, $ehImport = false)
        {
            if($this->helper->sessionValidate() or $ehImport){
                $veiculo = $this->veiculoModel->retornaIDPOrPlaca($placa);
                if($veiculo){
                    return $veiculo[0]->id;
                }else{
                    return null;
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function novo()
        {
            $dados = [
                "empresas" => $this->empresa->listaEmpresas("ativas"),
            ];
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Novo Veículo");
                $this->view('veiculo/novo', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Veículo");
                if((!isset($_SESSION["pw_veiculo_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaVeiculos(),
                        'filtro' => null,
                        'empresas' => $this->empresa->listaEmpresas("ativas"),
                    ];
                }else{
                    if($_SESSION["pw_veiculo_consulta"] == null or isset($form["descricao_placa"])){
                        $filtro = $form["descricao_placa"]; 
                    }else{
                        $filtro = $_SESSION["pw_veiculo_consulta"];
                    }
                    $_SESSION["pw_veiculo_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaVeiculosPorFiltro($filtro),
                        'filtro' => $filtro,
                        'empresas' => $this->empresa->listaEmpresas("ativas"),
                    ];
                }
                $this->view('veiculo/consulta', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function listaVeiculos($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->veiculoModel->listaVeiculos();
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaVeiculosPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->veiculoModel->listaVeiculosPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar()
        {
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updateVeiculo($form, $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Veículo", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Alterou", $_SESSION['pw_id'], "Veículo", null, null);
                    }
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarVeiculo($form["id"], "inativar", $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Veículo", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Inativou", $_SESSION['pw_id'], "Veículo", null, null);
                    }
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarVeiculo($form["id"], "ativar", $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Veículo", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Ativou", $_SESSION['pw_id'], "Veículo", null, null);
                    }
                }
                $this->helper->redirectPage("/veiculo/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaVeiculosPorEmpresa($empresa_id)
        {
            if($this->helper->sessionValidate()){
                $veiculos = "";
                $veiculos = $this->veiculoModel->retornaVeiculosPorEmpresa($empresa_id);
                if($veiculos){
                    foreach($veiculos as $veiculo){
                        echo "<veiculo>";
                        echo "<id>".$veiculo->id;
                        echo "</id>";
                        echo "<placa>".$veiculo->placa;
                        echo "</placa>";
                        echo "</veiculo>";
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaDescricaoTipoVeiculo($veiculo_placa)
        {
            if($this->helper->sessionValidate()){
                $veiculo_id = $this->retornaIDPOrPlaca($veiculo_placa);
                if(!$this->veiculoModel->veiculoPodeEntrar($veiculo_id)){
                    $veiculo = $this->veiculoModel->retornaDescricaoTipoVeiculo($veiculo_id);
                    if($veiculo){
                        echo "<veiculo>".$veiculo[0]->descricao;
                        echo "</veiculo>";
                        echo "<tipoVeiculo>".$veiculo[0]->tipo;
                        echo "</tipoVeiculo>";
                    }
                    echo "<veiculoPodeEntrar>Sim</veiculoPodeEntrar>";
                }else{
                    echo "<veiculoPodeEntrar>Nao</veiculoPodeEntrar>";
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateVeiculo($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->helper->validateFields($form)){
                    $form["placaVeiculo"] = strtoupper($form["placaVeiculo"]);
                    if(!$this->veiculoModel->verificaPlaca($form["placaVeiculo"], $form["id"])){
                        $dateTime = $this->helper->returnDateTime();
                        if($this->veiculoModel->alterarVeiculo($form, $dateTime)){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Veículo alterado com sucesso!',
                                $this->rotinaCad
                            );
                            $retorno = true;
                            $this->log->registraLog($_SESSION['pw_id'], "Veículo", $form["id"], 0, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível alterar o veículo, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível alterar o veículo, já existe outro veículo cadastrado com essa placa(".$form["placaVeiculo"].")",
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, tente novamente!',
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarVeiculo($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->veiculoModel->ativarInativarVeiculo($id, $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Veículo inativado com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Veículo ativado com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar este Veículo, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar este Veículo, tente novamente mais tarde!';
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        $mensagem,
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>