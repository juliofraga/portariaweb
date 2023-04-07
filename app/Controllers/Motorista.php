<?php

    class Motorista extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'motorista';
        private $rotinaAlt = 'motorista';
        public $helper;
        public $motoristaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->motoristaModel = $this->model('MotoristaModel');
            $this->log = new Logs();
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->view('motorista/consulta');
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function listaMotoristas($attr = null)
        {
            if($this->helper->sessionValidate()){
                
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaMotoristasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaMotoristaPorEmpresa($empresa_id)
        {
            if($this->helper->sessionValidate()){
                $motoristas = $this->motoristaModel->retornaMotoristaPorEmpresa($empresa_id);
                if($motoristas){
                    foreach($motoristas as $motorista){
                        echo "<motorista>";
                        echo "<id>".$motorista->id;
                        echo "</id>";
                        echo "<nome_completo>".$motorista->nome_completo;
                        echo "</nome_completo>";
                        echo "</motorista>";
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar()
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateMotorista($form, $dateTime)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarMotorista($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

        private function deletarMotorista($id)
        {
            if($this->helper->sessionValidate()){

            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>