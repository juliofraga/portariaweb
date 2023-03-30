<?php 

    class Logs extends Controller{

        public function __construct()
        {
            $this->logModel = $this->model('LogModel');
        }


        // Registra logs
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime){
            $this->logModel->registraLog($usuario, $classe, $id_classe, $acao, $dateTime);
        }
    }

?>