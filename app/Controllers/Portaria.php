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

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(!empty($form["descricao"])){
                    $dateTime = $this->helper->returnDateTime();
                    $lastInsertId = $this->portariaModel->cadastrarPortaria($form, $dateTime);
                    if(isset($form["camera"]) and $form["camera"] != NULL){
                        $this->ligaPortariaCamera($form["camera"], $lastInsertId, $dateTime);
                    }
                    if($lastInsertId != null){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Portaria cadastrada com sucesso!',
                            $this->rotinaCad
                        );
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $lastInsertId, 0, $dateTime);
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível cadastrar a portaria, tente novamente!',
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'A descrição deve ser informada, tente novamente!',
                        $this->rotinaCad
                    );
                }
                $this->helper->redirectPage("/portaria/novo");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_portaria_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaPortarias(true),
                        'filtro' => null,
                    ];
                }else{
                    if($_SESSION["pw_portaria_consulta"] == null or isset($form["descricao"])){
                        $filtro = $form["descricao"]; 
                    }else{
                        $filtro = $_SESSION["pw_portaria_consulta"];
                    }
                    $_SESSION["pw_portaria_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaPortariasPorFiltro($filtro),
                        'filtro' => $filtro,
                    ];
                }
                $this->view('portaria/consulta', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPortarias($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->portariaModel->listaPortarias($attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPortariasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->portariaModel->listaPortariasPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ligaPortariaCamera($cameras, $portaria_id, $dateTime)
        {
            if($this->helper->sessionValidate()){
                foreach($cameras as $camera){
                    $this->camera->inserePortariaCamera($camera, $portaria_id, $dateTime);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>