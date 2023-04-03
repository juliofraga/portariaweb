<?php

    class Home extends Controller{

        public function __construct()
        {

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