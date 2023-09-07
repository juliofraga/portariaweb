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

        public function gravaLogFrontEnd(){
            $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $dateTime = $this->helper->returnDateTime();
            $texto = "[$dateTime] - Usuário ID " . $_SESSION['pw_id'] . " chamou a função " .$form["mensagem"]."()\n";
            $arquivo = "logs/".date('M_Y').".txt";
            $fp = fopen($arquivo, "a+");
            fwrite($fp, $texto);
            fclose($fp);
        }
    }

?>