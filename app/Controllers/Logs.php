<?php 

    class Logs extends Controller{
        public $logModel;
        public $helper;

        public function __construct()
        {
            $this->logModel = $this->model('LogModel');
            $this->helper = new Helpers(); 
        }

        public function index($pag = 1){
            if($this->helper->sessionValidate()){
                $pag = (int)$pag;
                $iniReg = (($pag - 1) * NUM_REG_PAGINA) + 1;
                $iniReg--;
                if($_SESSION['pw_tipo_perfil'] == md5("Superadmin") or ($_SESSION['pw_tipo_perfil'] == md5("Administrador") and $_SESSION['pw_exibe_logs_basicos_admin'] == true)){
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $dtInicio = $form['dataDe'];
                    $dtFim = $form['dataAte'];
                    if($dtInicio == null and $_SESSION["pw_log_dtDe"] != null){
                        $dtInicio = $_SESSION["pw_log_dtDe"];
                    }
                    if($dtFim == null and $_SESSION["pw_log_dtAte"] != null){
                        $dtFim = $_SESSION["pw_log_dtAte"];
                    }
                    if(isset($form["limpar"])){
                        $dtInicio = null;
                        $dtFim = null;
                    }
                    $_SESSION["pw_log_dtDe"] = $dtInicio;
                    $_SESSION["pw_log_dtAte"] = $dtFim;
                    $dados = [
                        'dados' => $this->listaLogs($dtInicio, $dtFim, $iniReg),
                        'dataDe' => $dtInicio,
                        'dataAte' => $dtFim,
                        'totalLogs' => $this->numeroTotalLogs($dtInicio, $dtFim),
                        'paginaAtual' => $pag
                    ];
                    $this->view('/logs/index', $dados);
                }else{
                    $this->view('pagenotfound');
                }
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function listaLogs($dtInicio = null, $dtFim = null, $pag){
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    return $this->logModel->listaLogs($dtInicio, $dtFim, $this->helper->returnDate(), $pag);
                }
            }else{
                $this->helper->redirectPage("/login/");
            }
        }
        
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime, $ehImport = false){
            if($this->helper->sessionValidate() or $ehImport){
                $this->logModel->registraLog($usuario, $classe, $id_classe, $acao, $dateTime);
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function gravaLogFrontEnd(){
            if($this->helper->sessionValidate()){
                if($_SESSION['pw_grava_logs_fe']){
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $dateTime = $this->helper->returnDateTime();
                    $texto = "[$dateTime] - Usuário ID " . $_SESSION['pw_id'] . " chamou a função " .$form["mensagem"]."()\n";
                    $arquivo = LOGS;
                    $fp = fopen($arquivo, "a+");
                    fwrite($fp, $texto);
                    fclose($fp);
                }
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        private function numeroTotalLogs($dtInicio = null, $dtFim = null)
        {
            if($this->helper->sessionValidate()){
                $num = $this->logModel->numeroTotalLogs($dtInicio, $dtFim, $this->helper->returnDate());
                return $num[0]->totalLogs;
            }else{
                $this->helper->loginRedirect();
            }
        }
    }

?>