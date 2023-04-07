<?php

    class Painel extends Controller{
        public $helper;
        public $portaria;
        public $camera;
        public $empresa;

        public function __construct()
        {
            require "Portaria.php";
            require "Empresa.php";
            $this->helper = new Helpers();
            $this->portaria = new Portaria();
            $this->camera = new Camera();
            $this->empresa = new Empresa();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                if(!isset($_POST["portaria"]) and isset($_SESSION["pw_portaria"])){
                    $portaria = $_SESSION["pw_portaria"];
                }else{
                    $portaria = isset($_POST["portaria"]) ? $_POST["portaria"] : $this->portaria->retornaPortariaPadrao($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']);
                }
                $_SESSION["pw_portaria"] = $portaria;
                $dados = [
                    "portarias" => $this->portaria->listaPortariasPorUsuario($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']),
                    "portaria_selecionada" => $portaria,
                    "cameras" => $this->camera->listaCamerasPortaria($portaria),
                    "empresas" => $this->empresa->listaEmpresas("ativas"),
                ];
                $this->view('painel', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>