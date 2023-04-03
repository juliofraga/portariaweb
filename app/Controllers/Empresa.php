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
                $this->view('empresa/consulta');
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
                
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaEmpresasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar()
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateEmpresa($form, $dateTime)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarEmpresa($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function deletarEmpresa($id)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }
    }

?>