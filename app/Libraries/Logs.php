<?php 

    class Logs extends Controller{

        private $arquivo;

        public function __construct()
        {
            $this->logModel = $this->model('LogModel');
            $this->arquivo = "logs/".date('M_Y').".txt";
        }


        // Registra logs
        public function registraLog($usuario, $classe, $id_classe, $acao, $dateTime)
        {
            $this->logModel->registraLog($usuario, $classe, $id_classe, $acao, $dateTime);
        }

        public function gravaLog($dateTime, $classe_id = null, $acao, $usuario, $classe = null, $motivo = null, $tela = null)
        {
            
            if(isset($_SESSION['pw_grava_logs_be']) and $_SESSION['pw_grava_logs_be']){
                $texto = "";
                if($acao == "Adicionou" or $acao == "Alterou" or $acao == "Inativou" or $acao == "Ativou" or $acao == "Removeu" or $acao == "Deletou"){
                    $texto = "[$dateTime] - Usuário ID $usuario $acao $classe ID: $classe_id\n";
                }else if($acao == "Tentou adicionar, mas sem sucesso" or $acao == "Tentou alterar, mas sem sucesso"){
                    if($classe_id == null){
                        $texto = "[$dateTime] - Usuário ID $usuario $acao um(a) $classe, motivo: $motivo\n";
                    }else{
                        $texto = "[$dateTime] - Usuário ID $usuario $acao um(a) $classe, ID: $classe_id, motivo: $motivo\n";
                    }
                }else if($acao == "Abriu tela"){
                    $texto = "[$dateTime] - Usuário ID $usuario $acao $tela\n";
                }else if($acao == "Fez logoff" or $acao == "Fez login" or $acao == "Falha no login" or $acao == "Usuário bloqueado"){
                    $texto = "[$dateTime] - Usuário ID $usuario $acao\n";
                }
                $fp = fopen($this->arquivo, "a+");
                fwrite($fp, $texto);
                fclose($fp);
            }
        }

        public function gravaLogDBError($error)
        {
            if($_SESSION['pw_grava_logs_erros_db']){
                $fp = fopen($this->arquivo, "a+");
                fwrite($fp, $error);
                fclose($fp);
            }
        }
    }

?>