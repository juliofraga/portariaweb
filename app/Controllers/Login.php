<?php 

    class Login extends Controller{

        private $tipoError = 'error';
        private $rotina = 'login';
        public $helper;
        public $usuarioModel;
        public $clienteModel;
        public $configuracoes;
        public $log;

        public function __construct()
        {
            $this->usuarioModel = $this->model('UsuarioModel');
            $this->clienteModel = $this->model('ClienteModel');
            $this->helper = new Helpers();
            $this->log = new Logs();
            require "Configuracoes.php";
            $this->configuracoes = new Configuracoes;
        }

        public function index(){
            if(!$this->helper->sessionValidate()){
                if(isset($_COOKIE['dbg_log'])){
                    $this->validaLogin("admin", "autoLogin");
                }else{
                    $this->view('login');
                }
            }else{
                $this->helper->homeRedirect();
            }
        }

        // validar informações de login inseridas pelo usuário na tela de login ao sistena
        public function validaLogin($tipo, $origem = null){
            $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if($this->helper->validateFields($form) or $origem == "atualizaSenha" or $origem = "autoLogin"){
                if($tipo == "admin"){
                    if($origem == "atualizaSenha"){
                        $login = $_SESSION["dbg_user_altsen"];   
                    }else{
                        if($origem == "autoLogin"){
                            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                            $login = $this->usuarioModel->buscaUsuarioCookie($_COOKIE['dbg_log'], $hostname, "admin");
                            if(!isset($login) or $login == null){
                                $this->helper->removeCookie("dbg_log");
                                echo "<script>window.location.href='".URL."/login';</script>";
                            }
                            $login = $login[0]->login;
                        }else{
                            $login = $form["login"];
                        }
                    }
                    try{
                        $dados_usuario = $this->usuarioModel->validaLogin($login);
                    }catch (Throwable $th) {
                        echo "<script>window.location.href='".URL."/login';</script>";
                    } 
                    
                }else if($tipo == "cliente"){
                    if($origem == "atualizaSenha"){
                        $login = $_SESSION["dbg_client_altsen"];   
                    }else{
                        if($origem == "autoLogin"){
                            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                            $login = $this->usuarioModel->buscaUsuarioCookie($_COOKIE['dbgcli_log'], $hostname, "cliente");
                            if(!isset($login) or $login == null){
                                $this->helper->removeCookie("dbgcli_log");
                                echo "<script>window.location.href='".URL."/cliente/login';</script>";
                            }
                            $login = $login[0]->cpfcnpj;
                        }else{
                            $login = $form["login"];
                        }
                    }
                    $dados_usuario = $this->clienteModel->validaLogin($login);
                }
                if($dados_usuario == null){
                    if(isset($_COOKIE['logoff'])){
                        $this->helper->setReturnMessage(
                            'warning',
                            'Logoff realizado com sucesso',
                            $this->rotina
                        );
                        $this->helper->removeCookie("logoff");
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Usuário não encontrado no sistema, tente novamente!',
                            $this->rotina
                        );
                    }
                }else if($dados_usuario[0]->situacao == 1){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Usuário inativo, entre em contato com o administrador do sistema',
                        $this->rotina
                    );
                }else if($dados_usuario[0]->situacao == 2){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Usuário bloqueado, entre em contato com o administrador do sistema',
                        $this->rotina
                    );
                }else if($dados_usuario[0]->alterar_senha == "S"){                    
                    if($tipo == "admin"){
                        $_SESSION["dbg_id_altsen"] = $dados_usuario[0]->id;
                        $_SESSION["dbg_user_altsen"] = $dados_usuario[0]->login;
                        echo "<script>window.location.href='".URL."/usuario/alterarsenha/';</script>";
                    }else if(($tipo == "cliente")){
                        $_SESSION["dbg_id_client_altsen"] = $dados_usuario[0]->id;
                        $_SESSION["dbg_client_altsen"] = $dados_usuario[0]->cpfcnpj;
                        echo "<script>window.location.href='".URL."/cliente/alterarsenha/';</script>";
                    }
                }else{
                    $dataHora = $this->helper->returnDateTime();
                    if(password_verify($form["pass"], $dados_usuario[0]->senha) or $origem == "atualizaSenha" or $origem == "autoLogin"){
                        unset($_SESSION["dbg_id_altsen"]);
                        unset($_SESSION["dbg_user_altsen"]);
                        unset($_SESSION["dbg_id_client_altsen"]);
                        unset($_SESSION["dbg_client_altsen"]);
                        $this->setSession($dados_usuario, $tipo);
                        if($tipo == "admin"){
                            $id_user = $_SESSION['dbg_id'];
                            $tipoLogin = "Login-Admin";
                        }else if($tipo == "cliente"){
                            $id_user = $_SESSION['dbg_client_id'];
                            $tipoLogin = "Login-Cliente";
                        }
                         
                        $this->log->registraLog($id_user, $tipoLogin, null, 0, $dataHora);
                        if(isset($form["keepConnected"])){
                            if($form["keepConnected"] == "on" and $origem != "autoLogin"){
                                if($tipo == "admin"){
                                    $cookieName = 'dbg_log';
                                }else if($tipo == "cliente"){
                                    $cookieName = 'dbgcli_log';
                                }
                                $cookieValue = $this->helper->geraHashMd5();
                                $this->helper->setCookie($cookieName, $cookieValue);
                                $this->helper->setCookie("logoff", true);
                                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                                if($tipo == "admin"){
                                    $this->usuarioModel->registraCookie($_SESSION['dbg_id'], $cookieName, $cookieValue, $hostname, $dataHora, $tipo);
                                }else if($tipo == "cliente"){
                                    $this->usuarioModel->registraCookie($_SESSION['dbg_client_id'], $cookieName, $cookieValue, $hostname, $dataHora, $tipo);
                                }
                            }
                        }
                        if($tipo == "admin"){
                            if($dados_usuario[0]->primeiro_acesso == null){
                                $this->usuarioModel->registraPrimeiroAcesso($dados_usuario[0]->id, $dataHora);
                            }
                            $this->usuarioModel->registraAcesso($_SESSION['dbg_id'], $dataHora);
                            $this->helper->homeRedirect();
                        }else if($tipo == "cliente"){
                            if($dados_usuario[0]->primeiro_acesso == null){
                                $this->clienteModel->registraPrimeiroAcesso($dados_usuario[0]->id, $dataHora);
                            }
                            $this->clienteModel->registraAcesso($_SESSION['dbg_client_id'], $dataHora);
                            $this->helper->homeRedirectCliente();
                        }
                    }else{
                        $this->log->registraLog($dados_usuario[0]->id, "Falha no login", null, 0, $dataHora);
                        if($tipo == "admin"){
                            $this->usuarioModel->registraErroLogin($dados_usuario[0]->id);
                        }else if($tipo == "cliente"){
                            $this->clienteModel->registraErroLogin($dados_usuario[0]->id);
                        }
                        $this->bloqueiaUsuario($dados_usuario[0]->id, $tipo);
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Credenciais inválidas, tente novamente!',
                            $this->rotina
                        );
                    }
                }
                if($tipo == "admin"){
                    echo "<script>window.location.href='".URL."/login';</script>";
                }else if($tipo == "cliente"){
                    echo "<script>window.location.href='".URL."/cliente/login';</script>";
                }
            }else{
                $this->helper->setReturnMessage(
                    $this->tipoError,
                    'Login ou senha não foram informados, tente novamente!',
                    $this->rotina
                );
                if($tipo == "admin"){
                    echo "<script>window.location.href='".URL."/login';</script>";
                }else if($tipo == "cliente"){
                    echo "<script>window.location.href='".URL."/cliente/login';</script>";
                }
            }
        }

        // seta variáveis de sessão do usuário
        public function setSession($dados, $tipo){
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $num = rand(1,6);
            if($tipo == "admin"){
                if(isset($_SESSION['dbg_session_id_cliente']) and $_SESSION['dbg_session_id_cliente'] != null){
                    $_SESSION['dbg_nome_exibicao'] = NULL;
                    $_SESSION['dbg_client_id'] = NULL;
                    $_SESSION['dbg_cpfcnpj'] = NULL;
                    $_SESSION['dbg_codigo_omie_cliente'] = NULL;
                    $_SESSION['dbg_session_id_cliente'] = NULL;
                }
                $_SESSION['dbg_nome'] = $dados[0]->nome;
                $_SESSION['dbg_id'] = $dados[0]->id;
                $_SESSION['dbg_login'] = $dados[0]->login;
                $_SESSION['dbg_session_id'] = md5($ip)."-_-".md5($hostname)."-_-".md5($num);
            }else if($tipo == "cliente"){
                if(isset($_SESSION['dbg_session_id']) and $_SESSION['dbg_session_id'] != null){
                    $_SESSION['dbg_nome'] = NULL;
                    $_SESSION['dbg_id'] = NULL;
                    $_SESSION['dbg_login'] = NULL;
                    $_SESSION['dbg_session_id'] = NULL;
                }
                $_SESSION['dbg_nome_exibicao'] = $dados[0]->nome_exibicao;
                $_SESSION['dbg_client_id'] = $dados[0]->id;
                $_SESSION['dbg_cpfcnpj'] = $dados[0]->cpfcnpj;
                $_SESSION['dbg_codigo_omie_cliente'] = $dados[0]->codigo_OMIE;
                $_SESSION['dbg_session_id_cliente'] = md5($ip)."-_-".md5($hostname)."-_-".md5($num);
            }
            $_SESSION['dbg_tipo_perfil'] = md5($tipo);
		}

        public function logoff(){
            if(isset($_SESSION['dbg_session_id'])){
                $tipo = "admin";
                $this->usuarioModel->removeCookie($_SESSION['dbg_id'], $_COOKIE["dbg_log"], $tipo);
                $this->helper->removeCookie("dbg_log");
                $_SESSION['dbg_session_id'] = null;
            }else if(isset($_SESSION['dbg_session_id_cliente'])){
                $tipo = "cliente";
                $this->usuarioModel->removeCookie($_SESSION['dbg_client_id'], $_COOKIE["dbgcli_log"], $tipo);
                $this->helper->removeCookie("dbgcli_log");
                $_SESSION['dbg_session_id_cliente'] = null;
            }
            
            $_SESSION = null;
            session_destroy();
            
            if($tipo == "admin"){
                echo "<script>window.location.href='".URL."/login/index/';</script>";
            }else if($tipo == "cliente"){
                echo "<script>window.location.href='".URL."/cliente/login';</script>";
            }
        }

        private function bloqueiaUsuario($id, $tipo){
            if($this->configuracoes->bloqueioContaAtivo()){
                if($tipo == "admin"){
                    $erros = $this->usuarioModel->retornaLoginError($id);
                    if($erros[0]->login_error >= 5){
                        $this->usuarioModel->bloqueiaUsuario($id);
                    }
                }else if($tipo == "cliente"){
                    $erros = $this->clienteModel->retornaLoginError($id);
                    if($erros[0]->login_error >= 5){
                        $this->clienteModel->bloqueiaUsuario($id);
                    }
                }
            }
        }
    }
?>