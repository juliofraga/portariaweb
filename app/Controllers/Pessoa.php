<?php

    class Pessoa extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'pessoa';
        private $rotinaAlt = 'pessoa';
        public $helper;
        public $pessoaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->pessoaModel = $this->model('PessoaModel');
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
                $this->view('pessoa/nova');
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->view('pessoa/consulta');
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function listaPessoas($attr = null)
        {
            if($this->helper->sessionValidate()){
                
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPessoasPorFiltro($filtro)
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

        private function updatePessoa($form, $dateTime)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarPessoa($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function deletarPessoa($id)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>