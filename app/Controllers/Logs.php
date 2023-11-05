<?php 

    class Logs extends Controller{
        public $logModel;
        public $helper;

        public function __construct()
        {
            $this->logModel = $this->model('LogModel');
            $this->helper = new Helpers(); 
        }

        public function index(){
            if($this->helper->sessionValidate()){
                if($_SESSION['pw_tipo_perfil'] == md5("Superadmin") or ($_SESSION['pw_tipo_perfil'] == md5("Administrador") and $_SESSION['pw_exibe_logs_basicos_admin'] == true)){
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $dtInicio = $form['dataDe'];
                    $dtFim = $form['dataAte'];
                    if(isset($form["limpar"])){
                        $dtInicio = null;
                        $dtFim = null;
                    }
                    $dados = [
                        'dados' => $this->listaLogs($dtInicio, $dtFim),
                        'dataDe' => $dtInicio,
                        'dataAte' => $dtFim
                    ];
                    $this->view('/logs/index', $dados);
                }else{
                    $this->view('pagenotfound');
                }
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function listaLogs($dtInicio = null, $dtFim = null){
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    return $this->logModel->listaLogs($dtInicio, $dtFim, $this->helper->returnDate());
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
    }

?>