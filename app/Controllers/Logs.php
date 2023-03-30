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
            if($this->helper->sessionValidate() or $this->helper->sessionValidateCliente()){
                $this->logModel->registraLog($usuario, $classe, $id_classe, $acao, $dateTime);
            }else{
                if($_SESSION['dbg_tipo_perfil'] == md5("admin")){
                    echo "<script>window.location.href='../login';</script>";
                }else{
                    echo "<script>window.location.href='".URL."/cliente/login';</script>";
                }
            }
        }
    }

?>