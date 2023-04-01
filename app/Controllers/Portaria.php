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
        public $placa;
        public $camera;

        public function __construct()
        {
            require "Placa.php";
            require "Camera.php";
            $this->helper = new Helpers();
            $this->portariaModel = $this->model('PortariaModel');
            $this->log = new Logs();
            $this->placa = new Placa();
            $this->camera = new Camera();
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
                    "placas" => $this->placa->listaPlacasDisponiveis(),
                    "cameras" => $this->camera->listaCamerasDisponiveis(),
                ];
                $this->view('portaria/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>