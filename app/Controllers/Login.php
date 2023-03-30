<?php 

    class Login extends Controller{

        private $tipoError = 'error';
        private $rotina = 'login';
        public $helper;
        public $traducoes;
        public $usuarioModel;
        public $configuracoes;
        public $log;

        public function __construct()
        {
            require "Configuracoes.php";
            $this->usuarioModel = $this->model('UsuarioModel');
            $this->helper = new Helpers();
            $this->log = new Logs();
            $this->configuracoes = new Configuracoes;
        }

        public function index(){
            if(!$this->helper->sessionValidate()){
                if(isset($_COOKIE['pw_log'])){
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
                if($origem == "atualizaSenha"){
                    $login = $_SESSION["pw_user_altsen"];   
                }else{
                    if($origem == "autoLogin"){
                        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                        $login = $this->usuarioModel->buscaUsuarioCookie($_COOKIE['pw_log'], $hostname, "admin");
                        if(!isset($login) or $login == null){
                            $this->helper->removeCookie("pw_log");
                            $this->helper->redirectPage("/login");
                        }
                        $login = $login[0]->login;
                    }else{
                        $login = $form["login"];
                    }
                }
                try{
                    $dados_usuario = $this->usuarioModel->validaLogin($login);
                }catch (Throwable $th) {
                    $this->helper->redirectPage("/login");
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
                            'Usuário não encontrado no sistema, tente novamente',
                            $this->rotina
                        );
                    }
                }else if($dados_usuario[0]->situacao == 1){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Usuário inativo, entre em contato com o administrador do sistema',
                        $this->rotina
                    );
                }else if($dados_usuario[0]->alterar_senha == "S"){                    
                    $_SESSION["pw_id_altsen"] = $dados_usuario[0]->id;
                    $_SESSION["pw_user_altsen"] = $dados_usuario[0]->login;
                    $this->helper->redirectPage("/usuario/alterarsenha/");
                }else{
                    $dataHora = $this->helper->returnDateTime();
                    if(password_verify($form["pass"], $dados_usuario[0]->senha) or $origem == "atualizaSenha" or $origem == "autoLogin"){
                        unset($_SESSION["pw_id_altsen"]);
                        unset($_SESSION["pw_user_altsen"]);
                        unset($_SESSION["pw_id_client_altsen"]);
                        unset($_SESSION["pw_client_altsen"]);
                        $this->setSession($dados_usuario, $tipo);
                        $id_user = $_SESSION['pw_id'];
                        $this->log->registraLog($id_user, "Login", $id_user, 0, $dataHora);
                        if(isset($form["keepConnected"])){
                            if($form["keepConnected"] == "on" and $origem != "autoLogin"){
                                $cookieName = 'pw_log';
                                $cookieValue = $this->helper->geraHashMd5();
                                $this->helper->setCookie($cookieName, $cookieValue);
                                $this->helper->setCookie("logoff", true);
                                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                                $this->usuarioModel->registraCookie($_SESSION['pw_id'], $cookieName, $cookieValue, $hostname, $dataHora, $tipo);
                            }
                        }
                        if($dados_usuario[0]->primeiro_acesso == null){
                            $this->usuarioModel->registraPrimeiroAcesso($dados_usuario[0]->id, $dataHora);
                        }
                        $this->usuarioModel->registraAcesso($_SESSION['pw_id'], $dataHora);
                         $this->helper->homeRedirect();
                    }else{
                        $id_user = $_SESSION['pw_id'];
                        $this->log->registraLog($dados_usuario[0]->id, "Falha no login", $id_user, 0, $dataHora);
                        $this->usuarioModel->registraErroLogin($dados_usuario[0]->id);
                        $this->bloqueiaUsuario($dados_usuario[0]->id, $tipo);
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Credenciais inválidas, tente novamente',
                            $this->rotina
                        );
                    }
                }
                $this->helper->redirectPage("/login");
            }else{
                $this->helper->setReturnMessage(
                    $this->tipoError,
                    'Login ou senha não foram informados, tente novamente',
                    $this->rotina
                );
                $this->helper->redirectPage("/login");
            }
        }

        // seta variáveis de sessão do usuário
        public function setSession($dados, $tipo){
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $ip = $_SERVER['REMOTE_ADDR'];
            $num = rand(1,6);
            $_SESSION['pw_nome'] = $dados[0]->nome;
            $_SESSION['pw_id'] = $dados[0]->id;
            $_SESSION['pw_login'] = $dados[0]->login;
            $_SESSION['pw_session_id'] = md5($ip)."-_-".md5($hostname)."-_-".md5($num);
            $_SESSION['pw_tipo_perfil'] = md5($tipo);
		}

        public function logoff(){
            if(isset($_COOKIE["pw_log"])){
                $this->usuarioModel->removeCookie($_SESSION['pw_id'], $_COOKIE["pw_log"], $tipo);
            }
            $this->helper->removeCookie("pw_log");
            $_SESSION['pw_session_id'] = null;
            $_SESSION = null;
            session_destroy();
            $this->helper->loginRedirect();
        }

        public function recuperar_senha(){
            $this->view("usuario/recuperar_senha");
        }

        private function bloqueiaUsuario($id, $tipo){
            if($this->configuracoes->bloqueioContaAtivo()){
                $erros = $this->usuarioModel->retornaLoginError($id);
                if($erros[0]->login_error >= 5){
                    $this->usuarioModel->bloqueiaUsuario($id);
                }
            }
        }

    }
?>