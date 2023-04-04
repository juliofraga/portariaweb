<?php

    class Portaria extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'portaria';
        private $rotinaAlt = 'portaria';
        public $helper;
        public $portariaModel;
        public $log;
        public $placa;
        public $camera;
        public $usuario;

        public function __construct()
        {
            require "Placa.php";
            require "Camera.php";
            require "Usuario.php";
            $this->helper = new Helpers();
            $this->portariaModel = $this->model('PortariaModel');
            $this->log = new Logs();
            $this->placa = new Placa();
            $this->camera = new Camera();
            $this->usuario = new Usuario();
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
                $dados = [
                    "placas" => $this->placa->listaPlacasDisponiveis(),
                    "cameras" => $this->camera->listaCamerasDisponiveis(),
                ];
                $this->view('portaria/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(!empty($form["descricao"])){
                    $dateTime = $this->helper->returnDateTime();
                    $lastInsertId = $this->portariaModel->cadastrarPortaria($form, $dateTime);
                    if(isset($form["camera"]) and $form["camera"] != NULL){
                        $this->ligaPortariaCamera($form["camera"], $lastInsertId, $dateTime);
                    }
                    if($lastInsertId != null){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Portaria cadastrada com sucesso!',
                            $this->rotinaCad
                        );
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $lastInsertId, 0, $dateTime);
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível cadastrar a portaria, tente novamente!',
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'A descrição deve ser informada, tente novamente!',
                        $this->rotinaCad
                    );
                }
                $this->helper->redirectPage("/portaria/novo");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_portaria_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaPortarias(true),
                        'filtro' => null,
                        "placas" => $this->placa->listaPlacasDisponiveis(),
                        "cameras" => $this->camera->listaCamerasDisponiveis(),
                    ];
                }else{
                    if($_SESSION["pw_portaria_consulta"] == null or isset($form["descricao"])){
                        $filtro = $form["descricao"]; 
                    }else{
                        $filtro = $_SESSION["pw_portaria_consulta"];
                    }
                    $_SESSION["pw_portaria_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaPortariasPorFiltro($filtro),
                        'filtro' => $filtro,
                        "placas" => $this->placa->listaPlacasDisponiveis(),
                        "cameras" => $this->camera->listaCamerasDisponiveis(),
                    ];
                }
                $this->view('portaria/consulta', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPortarias($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->portariaModel->listaPortarias($attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaPortariasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->portariaModel->listaPortariasPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar(){
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updatePortaria($form, $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarPortaria($form["id"], "inativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarPortaria($form["id"], "ativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                }else if(isset($form["deletar"])){
                    if($this->deletarPortaria($form["id"]))
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 2, $dateTime);
                }
                $this->helper->redirectPage("/portaria/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function portaria_usuario()
        {
            if($this->helper->sessionValidate()){
                $dados = [
                    "portarias" => $this->listaPortarias("ativo"),
                    "usuarios" => $this->usuario->listaUsuarios("operador"),
                ];
                $this->view("portaria/portaria_usuario", $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function updatePortaria($form, $dateTime)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if(!empty($form["descricao"])){
                    $dateTime = $this->helper->returnDateTime();
                    if($this->portariaModel->alterarPortaria($form, $dateTime)){
                        $this->removeCamerasPortaria($form["id"]);
                        $this->ligaPortariaCamera($form["camera"], $form["id"], $dateTime);
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Portaria alterada com sucesso!',
                            $this->rotinaCad
                        );
                        $retorno = true;
                        $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível alterar a portaria, tente novamente!',
                            $this->rotinaCad
                        );
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'O campo descrição deve ser informado, tente novamente!',
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ativarInativarPortaria($id, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->portariaModel->ativarInativarPortaria($id, $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Portaria inativada com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Portaria ativada com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar esta portaria, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar esta portaria, tente novamente mais tarde!';
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

        private function deletarPortaria($id)
        {
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->portariaModel->deletarPortaria($id)){
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        "Portaria deletada com sucesso!",
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        "A portaria não pode ser deletada porque existem câmeras atreladas à ela, remova-a destas câmeras e tente deletá-la novamente",
                        $this->rotinaCad
                    );
                }
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function ligaPortariaCamera($cameras, $portaria_id, $dateTime)
        {
            if($this->helper->sessionValidate()){
                foreach($cameras as $camera){
                    $this->camera->inserePortariaCamera($camera, $portaria_id, $dateTime);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function removeCamerasPortaria($portaria_id)
        {
            if($this->helper->sessionValidate()){
                $this->camera->removeCamerasPortaria($portaria_id);
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>