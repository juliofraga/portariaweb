<?php

    class Camera extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'camera';
        private $rotinaAlt = 'camera';
        public $helper;
        public $cameraModel;
        public $log;
        public $portariaModel;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->cameraModel = $this->model('CameraModel');
            $this->portariaModel = $this->model('PortariaModel');
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

        public function novo()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dados = [
                        'portoes' => $this->portariaModel->listaPortarias(),
                    ];
                    if($this->helper->sessionValidate()){
                        $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Nova Câmera");
                        $this->view('camera/novo', $dados);
                    }else{
                        $this->helper->loginRedirect();
                    }
                }
            }else{
                $this->helper->redirectPage("/login/");
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $dateTime = $this->helper->returnDateTime();
                    if($this->helper->validateFields($form)){
                        $form["endereco_ip"] = $this->formataEnderecoCamera($form["endereco_ip"], $form["porta"]);
                        $lastInsertId = $this->cameraModel->cadastrarCamera($form, $dateTime);
                        if($lastInsertId != null){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Câmera cadastrada com sucesso!',
                                $this->rotinaCad
                            );
                            if($this->criaArquivoCamera($lastInsertId, $form['endereco_ip']) === false){
                                $this->helper->setReturnMessage(
                                    $this->tipoWarning,
                                    'Câmera cadastrada com sucesso, porém parece que o endereço informado não está correto e a captura de imagens pode não funcionar corretamente. Verifique se foi informado http:// ou https:// no endereço da câmera.',
                                    $this->rotinaCad
                                );
                            }
                            $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Câmera");
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $lastInsertId, 0, $dateTime);
                            $this->helper->redirectPage("/camera/consulta");
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível cadastrar a câmera, tente novamente!',
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Câmera", "Erro ao gravar no banco de dados");
                            $this->helper->redirectPage("/camera/novo");
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Existem campos que não foram preenchidos, verifique novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Câmera", "Alguns campos não foram preenchidos");
                        $this->helper->redirectPage("/camera/novo");
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaCamerasDisponiveis()
        {
            if($this->helper->sessionValidate()){
                return $this->cameraModel->listaCamerasDisponiveis();
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaCamerasPortaria($portaria_id, $tipo = null)
        {
            if($this->helper->sessionValidate()){
                return $this->cameraModel->listaCamerasPortaria($portaria_id, $tipo);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function inserePortariaCamera($camera_id, $portaria_id, $dateTime, $tipo)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    if($this->cameraModel->inserePortariaCamera($camera_id, $portaria_id, $tipo)){
                        $this->log->gravaLog($dateTime, $camera_id, "Adicionou", $_SESSION['pw_id'], "câmera a portaria $portaria_id");
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $camera_id, 1, $dateTime);
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Câmera");
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if((!isset($_SESSION["pw_camera_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                        $dados = [
                            'dados' =>  $this->listaCameras(),
                            'filtro' => null,
                        ];
                    }else{
                        if($_SESSION["pw_camera_consulta"] == null or isset($form["descricao_ip"])){
                            $filtro = $form["descricao_ip"]; 
                        }else{
                            $filtro = $_SESSION["pw_camera_consulta"];
                        }
                        $_SESSION["pw_camera_consulta"] = $filtro;
                        $dados = [
                            'dados' =>  $this->listaCamerasPorFiltro($filtro),
                            'filtro' => $filtro,
                        ];
                    }
                    $this->view('camera/consulta', $dados);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaCameras($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->cameraModel->listaCameras($attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaCamerasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->cameraModel->listaCamerasPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function removeCamerasPortaria($portaria_id)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    if($portaria_id == null){
                        $this->view('pagenotfound');
                    }else{
                        $this->cameraModel->removeCamerasPortaria($portaria_id);
                        $this->log->gravaLog($this->helper->returnDateTime(), $portaria_id, "Removeu", $_SESSION['pw_id'], "Câmera");
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar(){
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if(isset($form["update"])){
                        if($this->updateCamera($form, $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Alterou", $_SESSION['pw_id'], "Câmera", null, null);
                        }
                    }else if(isset($form["inativar"])){
                        if($this->ativarInativarCamera($form["id"], "inativar", $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Inativou", $_SESSION['pw_id'], "Câmera", null, null);
                        }
                    }else if(isset($form["ativar"])){
                        if($this->ativarInativarCamera($form["id"], "ativar", $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Ativou", $_SESSION['pw_id'], "Câmera", null, null);
                        }
                    }else if(isset($form["deletar"])){
                        if($this->deletarCamera($form["id"])){
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 2, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Deletou", $_SESSION['pw_id'], "Câmera", null, null);
                        }
                    }
                    $this->helper->redirectPage("/camera/consulta");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function formataEnderecoCamera($ip, $porta){
            if($this->helper->sessionValidate()){
                $novoEndereco = ENDERECO_CAMERA;
                $novoEndereco = str_replace('IP', $ip, $novoEndereco);
                $novoEndereco = str_replace('PORTA', $porta, $novoEndereco);
                return $novoEndereco;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function criaArquivoCamera($camera_id, $endereco){
            if($this->helper->sessionValidate()){
                $arquivo = fopen('camera_' . $camera_id . '.php','w'); 
                if ($arquivo == false) die('Não foi possível criar o arquivo.'); 
                $array = explode("http://", $endereco);
                if($array[0] == $endereco){
                    $array = explode("https://", $endereco);
                    if(count($array) == 1){
                        return false;
                    }
                    $endereco = "https://".CREDENCIAIS_CAMERA.$array[1];
                }else{
                    $endereco = "http://".CREDENCIAIS_CAMERA.$array[1];
                }
                $texto = '<iframe src="'.$endereco.'" height="100%" width="100%" allowfullscreen></iframe>'; 
                fwrite($arquivo, $texto);
                $this->log->gravaLog($this->helper->returnDateTime(), $camera_id, "Adicionou", $_SESSION['pw_id'], "Arquivo câmera", null, null);
                fclose($arquivo);
                return true;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateCamera($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->validateFields($form, "portaria")){
                    $dateTime = $this->helper->returnDateTime();
                    $form["endereco_ip"] = $this->formataEnderecoCamera($form["endereco_ip"], $form["porta"]);
                    if(!$this->cameraModel->verificaIp($form["endereco_ip"])){
                        exec('del camera_' . $form["id"] . '.php');
                        $this->criaArquivoCamera($form["id"], $form["endereco_ip"]);
                    }
                    if($this->cameraModel->alterarCamera($form, $dateTime)){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Câmera alterada com sucesso!',
                            $this->rotinaCad
                        );
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                        return true;
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível alterar a câmera, tente novamente!',
                            $this->rotinaCad
                        );
                        return false;
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
                    return false;
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarCamera($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->cameraModel->ativarInativarCamera($id, $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Câmera inativada com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Câmera ativada com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar esta Câmera, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar esta Câmera, tente novamente mais tarde!';
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        $mensagem,
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function deletarCamera($id)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->cameraModel->deletarCamera($id)){
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        "Câmera deletada com sucesso!",
                        $this->rotinaCad
                    );
                    exec('del camera_' . $id . '.php');
                    $retorno = true;
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        "Erro ao deletar a câmera, tente novamente",
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

    }
?>