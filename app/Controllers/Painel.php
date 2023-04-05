<?php

    class Painel extends Controller{
        public $helper;
        public $portaria;

        public function __construct()
        {
            require "Portaria.php";
            $this->helper = new Helpers();
            $this->portaria = new Portaria();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $portaria = isset($_POST["portaria"]) ? $_POST["portaria"] : $this->portaria->retornaPortariaPadrao($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']);
                $dados = [
                    "portarias" => $this->portaria->listaPortariasPorUsuario($_SESSION["pw_id"], $_SESSION['pw_tipo_perfil']),
                    "portaria_selecionada" => $portaria,
                ];
                $this->view('painel', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>