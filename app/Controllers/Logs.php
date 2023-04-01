<?php 

    class Logs extends Controller{
        public $logModel;
        public $helper;

        public function __construct()
        {
            $this->logModel = $this->model('LogModel');
            $this->helper = new Helpers(); 
        }

        // Registra logs

        public function index(){
            $this->view('pagenotfound');
        }
        
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $this->logModel->registraLog($usuario, $classe, $id_classe, $acao, $dateTime);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }
    }

?>