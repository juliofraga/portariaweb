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

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->cameraModel = $this->model('CameraModel');
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
                'portoes' => null,
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
    }
?>