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

        public function novo()
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