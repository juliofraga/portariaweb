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
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Nova Placa");
                $this->view('placa/novo');
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $dateTime = $this->helper->returnDateTime();
                if($this->helper->validateFields($form)){
                    $form["endereco_ip"] = $this->helper->handleEnderecoIp($form["endereco_ip"]);
                    $lastInsertId = $this->placaModel->cadastrarPlaca($form, $dateTime);
                    if($lastInsertId != null){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Placa cadastrada com sucesso!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Placa");
                        $this->log->registraLog($_SESSION['pw_id'], "Placa", $lastInsertId, 0, $dateTime);
                        $this->helper->redirectPage("/placa/consulta");
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível cadastrar a placa, tente novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Placa", "Erro ao gravar no banco de dados");
                        $this->helper->redirectPage("/placa/novo");
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
                    $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Placa", "Alguns campos não foram preenchidos");
                    $this->helper->redirectPage("/placa/novo");
                }
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

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Placa");
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_placa_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaPlacas(),
                        'filtro' => null,
                    ];
                }else{
                    if($_SESSION["pw_placa_consulta"] == null or isset($form["descricao_ip"])){
                        $filtro = $form["descricao_ip"]; 
                    }else{
                        $filtro = $_SESSION["pw_placa_consulta"];
                    }
                    $_SESSION["pw_placa_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaPlacasPorFiltro($filtro),
                        'filtro' => $filtro,
                    ];
                }
                $this->view('placa/consulta', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPlacas($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->placaModel->listaPlacas($attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPlacasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->placaModel->listaPlacasPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaReles($placa_id)
        {
            if($this->helper->sessionValidate()){
                return $this->placaModel->listaReles($placa_id);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar(){
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updatePlaca($form, $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Placa", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Alterou", $_SESSION['pw_id'], "Placa", null, null);
                    }
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarPlaca($form["id"], "inativar", $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Placa", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Inativou", $_SESSION['pw_id'], "Placa", null, null);
                    }
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarPlaca($form["id"], "ativar", $dateTime)){
                        $this->log->registraLog($_SESSION['pw_id'], "Placa", $form["id"], 1, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Ativou", $_SESSION['pw_id'], "Placa", null, null);
                    }
                }else if(isset($form["deletar"])){
                    if($this->deletarPlaca($form["id"])){
                        $this->log->registraLog($_SESSION['pw_id'], "Placa", $form["id"], 2, $dateTime);
                        $this->log->gravaLog($dateTime, $form["id"], "Deletou", $_SESSION['pw_id'], "Placa", null, null);
                    }
                }
                $this->helper->redirectPage("/placa/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updatePlaca($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->helper->validateFields($form)){
                    $form["endereco_ip"] = $this->helper->handleEnderecoIp($form["endereco_ip"]);
                    if(!$this->placaModel->verificaIp($form["endereco_ip"], $form["id"])){
                        $dateTime = $this->helper->returnDateTime();
                        if($this->placaModel->alterarPlaca($form, $dateTime)){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Placa alterada com sucesso!',
                                $this->rotinaCad
                            );
                            $retorno = true;
                            $this->log->registraLog($_SESSION['pw_id'], "Placa", $form["id"], 1, $dateTime);
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível alterar a placa, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Não foi possível alterar a placa, já existe outra placa cadastrada com esse endereço IP (".$form["endereco_ip"]."), tente novamente informando outro endereço IP!",
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
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarPlaca($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->placaModel->ativarInativarPlaca($id, $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Placa inativada com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Placa ativada com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar esta placa, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar esta placa, tente novamente mais tarde!';
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

        private function deletarPlaca($id)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->placaModel->deletarPlaca($id)){
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        "Placa deletada com sucesso!",
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        "A placa não pode ser deletada porque ela está em uso, remova-a da portaria a qual ela está ligada e tente deletá-la novamente",
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