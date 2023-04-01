<?php

    class Placa extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'placa';
        private $rotinaAlt = 'placa';
        public $helper;
        public $placaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->placaModel = $this->model('placaModel');
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
                $this->view('placa/novo');
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($this->helper->validateFields($form)){
                    $form["endereco_ip"] = $this->helper->handleEnderecoIp($form["endereco_ip"]);
                    if(!$this->placaModel->verificaIp($form["endereco_ip"])){
                        $dateTime = $this->helper->returnDateTime();
                        $lastInsertId = $this->placaModel->cadastrarPlaca($form, $dateTime);
                        if($lastInsertId != null){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Placa cadastrada com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->registraLog($_SESSION['pw_id'], "Placa", $lastInsertId, 0, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível cadastrar a placa, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível cadastrar a placa, já existe outra placa cadastrada com esse endereço IP (".$form["endereco_ip"]."), tente novamente informando outro endereço IP!",
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
                $this->helper->redirectPage("/placa/novo");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPlacasDisponiveis()
        {
            if($this->helper->sessionValidate()){
                return $this->placaModel->listaPlacasDisponiveis();
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>