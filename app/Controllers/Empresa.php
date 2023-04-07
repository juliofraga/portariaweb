<?php

    class Empresa extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'empresa';
        private $rotinaAlt = 'empresa';
        public $helper;
        public $empresaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->empresaModel = $this->model('EmpresaModel');
            $this->log = new Logs();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function nova()
        {
            if($this->helper->sessionValidate()){
                $this->view('empresa/nova');
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_empresa_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaEmpresas(),
                        'filtro' => null,
                    ];
                }else{
                    if($_SESSION["pw_empresa_consulta"] == null or isset($form["cnpj_nome"])){
                        $filtro = $form["cnpj_nome"]; 
                    }else{
                        $filtro = $_SESSION["pw_empresa_consulta"];
                    }
                    $_SESSION["pw_empresa_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaEmpresasPorFiltro($filtro),
                        'filtro' => $filtro,
                    ];
                }
                $this->view('empresa/consulta', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(!empty($form["cnpj"]) and !empty($form["nome_fantasia"])){
                    if(!$this->empresaModel->verificaEmpresa($form["cnpj"])){
                        $dateTime = $this->helper->returnDateTime();
                        $lastInsertId = $this->empresaModel->cadastrarEmpresa($form, $dateTime);
                        if($lastInsertId != null){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Empresa cadastrada com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->registraLog($_SESSION['pw_id'], "Empresa", $lastInsertId, 0, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível cadastrar a empresa, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível cadastrar a empresa, já existe outra empresa cadastrada com esse CNPJ / CPF(".$form["cnpj"].")",
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'CNPJ/CPF e Nome Fantasia são de preenchimento obrigatórios, tente novamente!',
                        $this->rotinaCad
                    );
                }
                $this->helper->redirectPage("/empresa/nova");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaEmpresas($attr = null)
        {
            if($this->helper->sessionValidate()){
                if($attr == "listaPainel"){
                    $empresas = $this->empresaModel->listaEmpresas("ativas");
                    if($empresas){
                        foreach($empresas as $empresa){
                            echo "<empresa>";
                            echo "<id>".$empresa->id;
                            echo "</id>";
                            echo "<cnpj>".$empresa->cnpj;
                            echo "</cnpj>";
                            echo "<nome_fantasia>".$empresa->nome_fantasia;
                            echo "</nome_fantasia>";
                            echo "</empresa>";
                        }
                    }
                }else{
                    return $this->empresaModel->listaEmpresas($attr);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaEmpresasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->empresaModel->listaEmpresasPorFiltro();
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
                    if($this->updateEmpresa($form, $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Empresa", $form["id"], 1, $dateTime);
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarEmpresa($form["id"], "inativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Empresa", $form["id"], 1, $dateTime);
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarEmpresa($form["id"], "ativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Empresa", $form["id"], 1, $dateTime);
                }
                $this->helper->redirectPage("/empresa/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaCnpjCpf($empresa_id)
        {
            if($this->helper->sessionValidate()){
                $cnpjCpf = "";
                $cnpjCpf = $this->empresaModel->retornaCnpjCpf($empresa_id);
                if($cnpjCpf){
                    $cnpjCpf = $cnpjCpf[0]->cnpj;
                }
                echo "<cnpjcpf>" . $cnpjCpf . "</cnpjcpf>";
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateEmpresa($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if(!empty($form["cnpj"]) and !empty($form["nome_fantasia"])){
                    if(!$this->empresaModel->verificaEmpresa($form["cnpj"], $form["id"])){
                        $dateTime = $this->helper->returnDateTime();
                        if($this->empresaModel->alterarEmpresa($form, $dateTime)){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Empresa alterada com sucesso!',
                                $this->rotinaCad
                            );
                            $retorno = true;
                            $this->log->registraLog($_SESSION['pw_id'], "Empresa", $form["id"], 1, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível alterar a empresa, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível alterar a empresa, já existe outra empresa cadastrada com esse CNPJ / CPF(".$form["cnpj"].")",
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'CNPJ/CPF e Nome Fantasia são de preenchimento obrigatórios, tente novamente!',
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarEmpresa($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->empresaModel->ativarInativarEmpresa($id, $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Empresa inativada com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Empresa ativada com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar esta empresa, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar esta empresa, tente novamente mais tarde!';
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