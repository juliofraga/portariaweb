<?php

    class Importador extends Controller{

        public $helper;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->log = new Logs();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Importador");
                $this->view('ferramentas/importador');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function importaArquivo(){
            if($this->helper->sessionValidate()){
                $temp_file = $_FILES["arquivo"]["tmp_name"];
                
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

    }

?>