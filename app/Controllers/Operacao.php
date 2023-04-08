<?php

    class Operacao extends Controller{

        public $helper;
        public function __construct()
        {
            $this->helper = new Helpers();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function registrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                var_dump($form);
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>