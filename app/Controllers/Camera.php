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
            $dados = [
                'portoes' => $this->portariaModel->listaPortarias(),
            ];
            if($this->helper->sessionValidate()){
                $this->view('camera/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($this->helper->validateFields($form, "portaria")){
                    $form["endereco_ip"] = $this->helper->handleEnderecoIp($form["endereco_ip"]);
                    if(!$this->cameraModel->verificaIp($form["endereco_ip"])){
                        $dateTime = $this->helper->returnDateTime();
                        $lastInsertId = $this->cameraModel->cadastrarCamera($form, $dateTime);
                        if($lastInsertId != null){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Câmera cadastrada com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $lastInsertId, 0, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível cadastrar a câmera, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível cadastrar a câmera, já existe outra câmera cadastrada com esse endereço IP (".$form["endereco_ip"]."), tente novamente informando outro endereço IP!",
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
                }
                $this->helper->redirectPage("/camera/novo");
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

        public function inserePortariaCamera($camera_id, $portaria_id, $dateTime)
        {
            if($this->helper->sessionValidate()){
                if($this->cameraModel->inserePortariaCamera($camera_id, $portaria_id, $dateTime)){
                    $this->log->registraLog($_SESSION['pw_id'], "Câmera", $camera_id, 1, $dateTime);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_camera_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaCameras(),
                        'filtro' => null,
                        'portoes' => $this->portariaModel->listaPortarias(),
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
                        'portoes' => $this->portariaModel->listaPortarias(),
                    ];
                }
                $this->view('camera/consulta', $dados);
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

        public function alterar(){
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updateCamera($form, $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarCamera($form["id"], "inativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarCamera($form["id"], "ativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                }else if(isset($form["deletar"])){
                    if($this->deletarCamera($form["id"]))
                        $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 2, $dateTime);
                }
                $this->helper->redirectPage("/camera/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updateCamera($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->helper->validateFields($form, "portaria")){
                    $form["endereco_ip"] = $this->helper->handleEnderecoIp($form["endereco_ip"]);
                    if(!$this->cameraModel->verificaIp($form["endereco_ip"], $form["id"])){
                        $dateTime = $this->helper->returnDateTime();
                        if($this->cameraModel->alterarCamera($form, $dateTime)){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Câmera alterada com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->registraLog($_SESSION['pw_id'], "Câmera", $form["id"], 1, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível alterar a câmera, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível alterar a câmera, já existe outra câmera cadastrada com esse endereço IP (".$form["endereco_ip"]."), tente novamente informando outro endereço IP!",
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
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
                        $mensagem = 'Câmera inativada com sucesso!';
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