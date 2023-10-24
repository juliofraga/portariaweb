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
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dados = [
                        "placas" => $this->placa->listaPlacasDisponiveis(),
                        "cameras" => $this->camera->listaCamerasDisponiveis(),
                    ];
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Nova Portaria");
                    $this->view('portaria/novo', $dados);
                }
            }else{
                $this->helper->loginRedirect();
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
                    if(!empty($form["descricao"])){
                        $lastInsertId = $this->portariaModel->cadastrarPortaria($form, $dateTime);
                        if(isset($form["cameraEntrada"]) and $form["cameraEntrada"] != NULL){
                            $this->ligaPortariaCamera($form["cameraEntrada"], $lastInsertId, $dateTime, "E");
                        }
                        if(isset($form["cameraSaida"]) and $form["cameraSaida"] != NULL){
                            $this->ligaPortariaCamera($form["cameraSaida"], $lastInsertId, $dateTime, "S");
                        }
                        if($lastInsertId != null){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Portaria cadastrada com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Portaria");
                            $this->log->registraLog($_SESSION['pw_id'], "Portaria", $lastInsertId, 0, $dateTime);
                            $this->helper->redirectPage("/portaria/consulta");
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Não foi possível cadastrar a portaria, tente novamente!',
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Portaria", "Erro ao gravar no banco de dados");
                            $this->helper->redirectPage("/portaria/novo");
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'A descrição deve ser informada, tente novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Portaria", "O campo descrição não foi preenchido");
                        $this->helper->redirectPage("/portaria/novo");
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
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Portaria");
                    if((!isset($_SESSION["pw_portaria_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                        $dados = [
                            'dados' =>  $this->listaPortarias("todos"),
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
                }
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
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if(isset($form["update"])){
                        if($this->updatePortaria($form, $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Alterou", $_SESSION['pw_id'], "Portaria", null, null);
                        }
                    }else if(isset($form["inativar"])){
                        if($this->ativarInativarPortaria($form["id"], "inativar", $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Inativou", $_SESSION['pw_id'], "Portaria", null, null);
                        }
                    }else if(isset($form["ativar"])){
                        if($this->ativarInativarPortaria($form["id"], "ativar", $dateTime)){
                            $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Ativou", $_SESSION['pw_id'], "Portaria", null, null);
                        }
                    }else if(isset($form["deletar"])){
                        if($this->deletarPortaria($form["id"])){
                            $this->log->registraLog($_SESSION['pw_id'], "Portaria", $form["id"], 2, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Deletou", $_SESSION['pw_id'], "Portaria", null, null);
                        }
                    }
                    $this->helper->redirectPage("/portaria/consulta");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function portaria_usuario()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dados = [
                        "portarias" => $this->listaPortarias("ativo"),
                        "usuarios" => $this->usuario->listaUsuarios("operador"),
                        "portaria_usuarios"=> $this->listaPortariasUsuarios()
                    ];
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Ligação Portaria x Usuário");
                    $this->view("portaria/portaria_usuario", $dados);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function ligar_portaria_usuario()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    $error = false;
                    $this->portariaModel->removePortariaUsuarioPorId($form["portaria_id"]);
                    $this->log->gravaLog($dateTime, $form["portaria_id"], "Removeu", $_SESSION['pw_id'], "Ligação de usuários com a portaria");
                    if(isset($form["usuario"])){
                        foreach($form["usuario"] as $usuario){
                            if(!$this->portariaModel->ligaUsuarioPortaria($form["portaria_id"], $usuario)){
                                $error = true;
                            }
                        }
                    }
                    if($error == false){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            "Ligação Portaria x Usuario concluída com sucesso!",
                            $this->rotinaCad
                        );
                        if(!isset($usuario)){
                            $usuario = '';
                        }
                        $this->log->gravaLog($dateTime, $form["portaria_id"], "Adicionou", $_SESSION['pw_id'], "Ligação do usuário $usuario com a portaria");
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            "Ocorreu um problema na ligação Portaria x Usuario, tente novamente!",
                            $this->rotinaCad
                        );
                    }
                    $this->helper->redirectPage("/portaria/portaria_usuario");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function ligacao_portaria()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dados = [
                        "portarias" => $this->listaPortarias("ativo"),
                    ];
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Ligação Portaria x Portaria");
                    $this->view("portaria/ligacao_portaria", $dados);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function ligar_portaria_portaria()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil'])){
                    $this->view('pagenotfound');
                }else{
                    $dateTime = $this->helper->returnDateTime();
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if($this->helper->validateFields($form) and (isset($form["tipo_0"]) or isset($form["tipo_1"]))){
                        if(!$this->portariaModel->verificaSeLigacaoExiste($form)){
                            if(isset($form["tipo_0"]) and isset($form["tipo_1"])){
                                $tipo = 2;
                            }else if(!isset($form["tipo_0"]) and isset($form["tipo_1"])){
                                $tipo = 1;
                            }else if(isset($form["tipo_0"]) and !isset($form["tipo_1"])){
                                $tipo = 0;
                            }
                            if($this->portariaModel->ligaPortariaPortaria($form, $tipo)){
                                $this->helper->setReturnMessage(
                                    $this->tipoSuccess,
                                    "Ligação Portaria x Portaria concluída com sucesso!",
                                    $this->rotinaCad
                                );
                                $this->log->gravaLog($dateTime, $form["portaria_0"], "Adicionou", $_SESSION['pw_id'], "Ligação da portaria " . $form["portaria_0"] . " com a portaria " . $form["portaria_1"]);
                            }else{
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    "Ocorreu um problema na ligação Portaria x Usuario, tente novamente!",
                                    $this->rotinaCad
                                );
                                $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Ligação Portaria x Portaria", "Erro ao gravar no banco de dados");
                            }
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Já existe uma ligação para as portarias selecionadas, verifique novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Existem campos que não foram preenchidos, verifique novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Ligação Portaria x Portaria", "Alguns campos não foram preenchidos");
                    }
                    $this->helper->redirectPage("/portaria/ligacao_portaria");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }


        public function listaPortariasPorUsuario($usuario_id, $perfil)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isAdministrador($perfil) or $this->helper->isSuperadmin($perfil)){
                    return $this->portariaModel->listaPortariasPorUsuario($usuario_id, true);
                }else{
                    return $this->portariaModel->listaPortariasPorUsuario($usuario_id, false);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaPortariaPadrao($usuario_id, $perfil)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isAdministrador($perfil) or $this->helper->isSuperadmin($perfil)){
                    $portaria = $this->portariaModel->retornaPortariaPadrao($usuario_id, true);
                }else{
                    $portaria = $this->portariaModel->retornaPortariaPadrao($usuario_id, false);
                }
                return $portaria[0]->id;
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
                        $this->ligaPortariaCamera($form["cameraEntrada"], $form["id"], $dateTime, "E");
                        $this->ligaPortariaCamera($form["cameraSaida"], $form["id"], $dateTime, "S");
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

        private function listaPortariasUsuarios()
        {
            if($this->helper->sessionValidate()){
                return $this->portariaModel->listaPortariasUsuarios();
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

        private function ligaPortariaCamera($cameras, $portaria_id, $dateTime, $tipo = null)
        {
            if($this->helper->sessionValidate()){
                foreach($cameras as $camera){
                    $this->camera->inserePortariaCamera($camera, $portaria_id, $dateTime, $tipo);
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