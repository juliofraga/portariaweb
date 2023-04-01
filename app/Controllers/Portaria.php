<?php

    class Portaria extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'portaria';
        private $rotinaAlt = 'portaria';
        public $helper;
        public $portariaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->portariaModel = $this->model('PortariaModel');
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
                $dados = [
                    "placas" => null,
                    "cameras" => null,
                ];
                $this->view('portaria/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>