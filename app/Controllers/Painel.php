<?php

    class Painel extends Controller{
        public $helper;
        public $portaria;
        public $camera;
        public $empresa;
        public $placa;
        public $usuario;
        public $configuracoes;

        public function __construct()
        {
            require "Portaria.php";
            $this->helper = new Helpers();
            $this->portaria = new Portaria();
            $this->camera = new Camera();
            $this->placa = new Placa();
            $this->configuracoes = new Configuracoes();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $portarias_usuario = $this->portaria->listaPortariasPorUsuario($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']);
                if($portarias_usuario){
                    if(!isset($_POST["portaria"]) and isset($_SESSION["pw_portaria"])){
                        $portaria = $_SESSION["pw_portaria"];
                    }else{
                        $portaria = isset($_POST["portaria"]) ? $_POST["portaria"] : $this->portaria->retornaPortariaPadrao($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']);
                    }
                    $_SESSION["pw_portaria"] = $portaria;
                    $dados = [
                        "portarias" => $portarias_usuario,
                        "portaria_selecionada" => $portaria,
                        //"cameras" => $this->camera->listaCamerasPortaria($portaria),
                        "reles" => $this->placa->listaReles($portaria),
                        "emergencia" => $this->configuracoes->operadorEmergencia(),
                    ];
                    $this->view('painel', $dados);
                }else{
                    $this->view('painel-error');
                }
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>