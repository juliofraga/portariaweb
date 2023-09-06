<?php 

    class PageNotFound extends Controller{

        public function __construct()
        {

        }

        public function index(){
            $this->view('pagenotfound');
        }
        
    }

?>