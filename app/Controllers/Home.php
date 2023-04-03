<?php

    class Home extends Controller{
        public $helper;

        public function __construct()
        {
            $this->helper = new Helpers();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('home');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

    }

?>