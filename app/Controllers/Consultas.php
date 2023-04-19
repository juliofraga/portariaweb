<?php 

    class Consultas extends Controller{
        public $helper;

        public function __construct()
        {
            $this->helper = new Helpers();

        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $dados = [

                ];
                $this->view('consultas', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>