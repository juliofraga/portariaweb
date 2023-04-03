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

        public function novo()
        {
            $dados = [
                "empresas" => $this->empresa->listaEmpresas("ativas"),
            ];
            if($this->helper->sessionValidate()){
                $this->view('veiculo/novo', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->view('veiculo/consulta');
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function listaVeiculos($attr = null)
        {
            if($this->helper->sessionValidate()){
                
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaVeiculosPorFiltro($filtro)
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

        private function updateVeiculo($form, $dateTime)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarVeiculo($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function deletarVeiculo($id)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>