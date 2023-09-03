<?php

    class Usuario extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'usuario';
        private $rotinaAlt = 'usuario';
        public $helper;
        public $usuarioModel;
        public $log;
        public $configuracoes;

        public function __construct()
        {
            require "Configuracoes.php";
            $this->helper = new Helpers();
            $this->usuarioModel = $this->model('UsuarioModel');
            $this->configuracoes = new Configuracoes();
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
                'complexidade' => $this->configuracoes->complexidadeSenhaAtivo(),
            ];
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Novo Usuário");
                $this->view('usuario/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function cadastrar()
        {
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($this->helper->validateFields($form)){
                    if(!$this->usuarioModel->verificaLogin($form["login"])){
                        if($form["senha"] == $form["repetesenha"]){
                            if(strlen($form["senha"]) >= 6){
                                $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                                $lastInsertId = $this->usuarioModel->cadastrarUsuario($form, $dateTime);
                                if($lastInsertId != null){
                                    $this->helper->setReturnMessage(
                                        $this->tipoSuccess,
                                        'Usuário cadastrado com sucesso!',
                                        $this->rotinaCad
                                    );
                                    $this->log->registraLog($_SESSION['pw_id'], "Usuário", $lastInsertId, 0, $dateTime);
                                    $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Usuário");
                                    $this->helper->redirectPage("/usuario/consulta");
                                }else{
                                    $this->helper->setReturnMessage(
                                        $this->tipoError,
                                        'Não foi possível cadastrar o usuário, tente novamente!',
                                        $this->rotinaCad
                                    );
                                    $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Usuário", "Erro ao gravar no banco de dados");
                                    $this->helper->redirectPage("/usuario/novo");
                                }
                            }else{
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    'A senha deve ter no minimo 6 caracteres, tente novamente!',
                                    $this->rotinaCad
                                );
                                $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Usuário", "Senha com menos de 6 caracteres");
                                $this->helper->redirectPage("/usuario/novo");
                            }
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'As senhas não conferem, tente novamente!',
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Usuário", "Senha não conferem");
                            $this->helper->redirectPage("/usuario/novo");
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível cadastrar o usuário, já existe um usuário cadastrado no sistema com este login, tente novamente informando outro login!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Usuário", "Já existe usuário cadastrado no sistema com o mesmo login");
                        $this->helper->redirectPage("/usuario/novo");
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
                    $this->log->gravaLog($dateTime, null, "Tentou adicionar, mas sem sucesso", $_SESSION['pw_id'], "Usuário", "Aguns campos não foram preenchidos");
                    $this->helper->redirectPage("/usuario/novo");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Usuário");
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_usuario_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaUsuarios(),
                        'nome' => null,
                    ];
                }else{
                    if($_SESSION["pw_usuario_consulta"] == null or isset($form["nome_usuario"])){
                        $nome = $form["nome_usuario"]; 
                    }else{
                        $nome = $_SESSION["pw_usuario_consulta"];
                    }
                    $_SESSION["pw_usuario_consulta"] = $nome;
                    $dados = [
                        'dados' =>  $this->listaUsuarioPorNome($nome),
                        'nome' => $nome,
                    ];
                }
                $this->view('usuario/consulta', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaUsuarios($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->usuarioModel->listaUsuarios($attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaUsuarioPorNome($nome)
        {
            if($this->helper->sessionValidate()){
                return $this->usuarioModel->listaUsuarioPorNome($nome);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar($tipo = null){
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updateUsuario($form, $dateTime, $tipo))
                        $this->log->registraLog($_SESSION['pw_id'], "Usuário", $form["id"], 1, $dateTime);
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarUsuario($form, "inativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Usuário", $form["id"], 1, $dateTime);
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarUsuario($form, "ativar", $dateTime))
                        $this->log->registraLog($_SESSION['pw_id'], "Usuário", $form["id"], 1, $dateTime);
                }
                if($tipo == null){
                    $this->helper->redirectPage("/usuario/consulta");
                }else if($tipo == "perfil"){
                    $this->helper->redirectPage("/usuario/perfil");
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterarsenha(){
            $login = $_SESSION["pw_user_altsen"];
            $id = $_SESSION["pw_id_altsen"];
            $dados = [
                'id' => $id,
                "login" => $login,
                'complexidade' => $this->configuracoes->complexidadeSenhaAtivo(),
            ];
            $this->view('usuario/alterarsenha', $dados);
        }

        public function atualizarSenha(){
            $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if($form){
                if(empty($form["senha"]) or empty($form["repetesenha"])){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, existem campos que não foram preenchidos, tente novamente!',
                        $this->rotinaAlt
                    );
                    $this->helper->redirectPage("/usuario/alterarsenha/");
                }else if($form["senha"] != $form["repetesenha"]){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, elas não conferem, tente novamente!',
                        $this->rotinaAlt
                    );
                    $this->helper->redirectPage("/usuario/alterarsenha/");
                }else if(strlen($form["senha"]) < 6){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, elas não possuem o mínimo de 6 caracteres!',
                        $this->rotinaAlt
                    );
                    $this->helper->redirectPage("/usuario/alterarsenha/");
                }else if(strlen($form["senha"]) >= 6 and $form["senha"] == $form["repetesenha"]){
                    $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                    $dateTime = $this->helper->returnDateTime();
                    if($this->usuarioModel->alteraUsuario($form, "senha_update", $dateTime)){
                        $this->log->registraLog($form["id"], "Usuário", $form["id"], 1, $dateTime);
                        $this->helper->redirectPage("/login/validaLogin/atualizaSenha");
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Erro ao atualizar a senha, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                            $this->rotinaAlt
                        );
                        $this->helper->redirectPage("/usuario/alterarsenha/");
                    }
                }
            }else{
                $this->view('pagenotfound');
            }
        }

        public function perfil()
        {
            if($this->helper->sessionValidate()){
                $dados = [
                    'complexidade' => $this->configuracoes->complexidadeSenhaAtivo(),
                    'usuario' => $this->buscaUsuarioPorId($_SESSION['pw_id']),
                ];
                $this->view('usuario/perfil', $dados);
            }else{
                $this->view('pagenotfound');
            }
        }

        private function buscaUsuarioPorId($id)
        {
            if($this->helper->sessionValidate()){
                return $this->usuarioModel->buscaUsuarioPorId($id);
            }else{
                $this->view('pagenotfound');
            }
        }

        private function updateUsuario($form, $dateTime, $tipo){
            $retorno = false;
            if(!empty($form["nome"])){
                $info = $this->usuarioModel->buscaUsuarioPorId($form["id"]);
                if(($form["nome"] == $info[0]->nome and $form["perfil"] == $info[0]->perfil) and (empty($form["senha"]) and empty($form["repetesenha"]))){
                    $this->helper->setReturnMessage(
                        $this->tipoWarning,
                        'Não foi necessária nenhuma alteração no cadastro do usuário',
                        $this->rotinaAlt
                    );
                }else if(($form["nome"] != $info[0]->nome or $form["perfil"] != $info[0]->perfil) and (empty($form["senha"]) and empty($form["repetesenha"]))){
                    if($this->usuarioModel->alteraUsuario($form, "nome-perfil", $dateTime)){
                        $this->helper->setReturnMessage(
                            $this->tipoSuccess,
                            'Usuário alterado com sucesso!',
                            $this->rotinaAlt
                        );
                        $retorno = true;
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Erro ao alterar usuário, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                            $this->rotinaAlt
                        );
                    }
                }else if((!empty($form["senha"]) and !empty($form["repetesenha"])) or
                        (empty($form["senha"]) and !empty($form["repetesenha"])) or 
                        (!empty($form["senha"]) and empty($form["repetesenha"])))
                {
                    if($form["senha"] != $form["repetesenha"]){
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível alterar o cadastro, as senhas não conferem, tente novamente!',
                            $this->rotinaAlt
                        );
                    }else{
                        if($this->configuracoes->complexidadeSenhaAtivo()){
                            if(preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/', $form["senha"])){
                                $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                                if($tipo == null){
                                    if($this->usuarioModel->alteraUsuario($form, "senha", $dateTime)){
                                        $this->helper->setReturnMessage(
                                            $this->tipoSuccess,
                                            'Usuário alterado com sucesso!',
                                            $this->rotinaAlt
                                        );
                                    }else{
                                        $this->helper->setReturnMessage(
                                            $this->tipoError,
                                            'Erro ao alterar usuário, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                                            $this->rotinaAlt
                                        );
                                    }
                                }else if($tipo == "perfil"){
                                    if($this->usuarioModel->alteraUsuario($form, "senha-nome", $dateTime)){
                                        $this->helper->setReturnMessage(
                                            $this->tipoSuccess,
                                            'Usuário alterado com sucesso!',
                                            $this->rotinaAlt
                                        );
                                    }else{
                                        $this->helper->setReturnMessage(
                                            $this->tipoError,
                                            'Erro ao alterar usuário, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                                            $this->rotinaAlt
                                        );
                                    }
                                }
                            }else{
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    'Não foi possível alterar a senha pois ela não atende os requisitos de complexidade, tente novamente!',
                                    $this->rotinaAlt
                                );
                            }
                        }else{
                            if(strlen($form["senha"]) < 6){
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    'Não foi possível alterar o cadastro, as senhas não possuem o mínimo de 6 caracteres!',
                                    $this->rotinaAlt
                                );
                            }else if(strlen($form["senha"]) >= 6 and $form["senha"] == $form["repetesenha"]){
                                $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                                if($tipo == null){
                                    if($this->usuarioModel->alteraUsuario($form, "senha", $dateTime)){
                                        $this->helper->setReturnMessage(
                                            $this->tipoSuccess,
                                            'Usuário alterado com sucesso!',
                                            $this->rotinaAlt
                                        );
                                    }else{
                                        $this->helper->setReturnMessage(
                                            $this->tipoError,
                                            'Erro ao alterar usuário, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                                            $this->rotinaAlt
                                        );
                                    }
                                }else if($tipo == "perfil"){
                                    if($this->usuarioModel->alteraUsuario($form, "senha-nome", $dateTime)){
                                        $this->helper->setReturnMessage(
                                            $this->tipoSuccess,
                                            'Usuário alterado com sucesso!',
                                            $this->rotinaAlt
                                        );
                                    }else{
                                        $this->helper->setReturnMessage(
                                            $this->tipoError,
                                            'Erro ao alterar usuário, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                                            $this->rotinaAlt
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $this->helper->setReturnMessage(
                    $this->tipoError,
                    'Nome não está preenchido, não foi possível alterar o usuário, tente novamente!',
                    $this->rotinaCad
                );  
            }
            return $retorno;
        }
        
        // Executa a rotina de ativação e inativação do usuário
        private function ativarInativarUsuario($form, $acao, $dateTime){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->usuarioModel->ativaInativaUsuario($form["id"], $acao, $dateTime)){
                    if($acao == "inativar")
                        $mensagem = 'Usuário inativado com sucesso!';
                    else if($acao == "ativar")
                        $mensagem = 'Usuário ativado com sucesso!';
                    $this->helper->setReturnMessage(
                        $this->tipoSuccess,
                        $mensagem,
                        $this->rotinaCad
                    );
                    $retorno = true;
                }else{
                    if($acao == "inativar")
                        $mensagem = 'Não foi possível inativar este usuário, tente novamente mais tarde!';
                    else if($acao == "ativar")
                        $mensagem = 'Não foi possível ativar este usuário, tente novamente mais tarde!';
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

    }

?>