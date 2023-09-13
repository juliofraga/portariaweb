<?php 

    class Configuracoes extends Controller{
        public $helper;
        public $configuracaoModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->configuracaoModel = $this->model('ConfiguracaoModel');
            $this->log = new Logs();
        }

        public function complexidadeSenhaAtivo(){
            return $this->configuracaoModel->opcaoAtiva(1);
        }

        public function bloqueioContaAtivo(){
            return $this->configuracaoModel->opcaoAtiva(2);
        }
        
        public function operadorEmergencia(){
            return $this->configuracaoModel->opcaoAtiva(3);
        }

        public function operadorVisualizaConsultas(){
            return $this->configuracaoModel->opcaoAtiva(5);
        }

        public function ativaLogsBackend(){
            return $this->configuracaoModel->opcaoAtiva(6);
        }

        public function ativaLogsFrontend(){
            return $this->configuracaoModel->opcaoAtiva(7);
        }

        public function ativaLogsErrosDB(){
            return $this->configuracaoModel->opcaoAtiva(8);
        }

        public function ativaLogsErrosPHP(){
            return $this->configuracaoModel->opcaoAtiva(9);
        }

        public function exibeLogsBasicosAdmin(){
            return $this->configuracaoModel->opcaoAtiva(10);
        }
        
        public function index(){
            if($this->helper->sessionValidate()){
                $dados = [
                    'configuracoes' => $this->listaConfiguracoes(),
                ];
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Configurações");
                $this->view('configuracoes', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaConfiguracoes(){
            if($this->helper->sessionValidate()){
                return $this->configuracaoModel->listaConfiguracoes();
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function atualizaConfiguracao($id, $valor){
            if($this->helper->sessionValidate()){
                if($valor == "Ativado"){
                    $valor = 0;
                }else if($valor == "Desativado"){
                    $valor = 1;
                }
                $dateTime = $this->helper->returnDateTime();
                if($this->configuracaoModel->AtualizaConfiguracao($id, $valor, $dateTime)){
                    $this->log->gravaLog($dateTime, $id, "Alterou", $_SESSION['pw_id'], "Configurações", null, null);
                    $this->log->registraLog($_SESSION['pw_id'], "Configurações", $id, 1, $dateTime);
                    $this->atualizaSessions();
                    return true;
                }else{
                    return false;
                }              
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function atualizaSessions(){
            $_SESSION['pw_grava_logs_be'] = $this->ativaLogsBackend();
            $_SESSION['pw_grava_logs_fe'] = $this->ativaLogsFrontend();
            $_SESSION['pw_grava_logs_erros_db'] = $this->ativaLogsErrosDB();
            $_SESSION['pw_grava_logs_erros_php'] = $this->ativaLogsErrosPHP();
        }
        
    }

?>