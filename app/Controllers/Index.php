<?php

    class Index extends Controller{

        public $helper;
        public function __construct()
        {
            $this->helper = new Helpers();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('index');
            }else{
                $this->helper->redirectPage("/login/");
            }
            
        }

    }

?>