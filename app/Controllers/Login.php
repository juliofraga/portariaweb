<?php 

    class Login extends Controller{

        private $tipoError = 'error';
        private $rotina = 'login';
        public $helper;
        public $usuarioModel;
        public $configuracoes;
        public $log;

        public function __construct()
        {
            $this->usuarioModel = $this->model('UsuarioModel');
            $this->helper = new Helpers();
        }

        public function index(){
            /*if(!$this->helper->sessionValidate()){
                if(isset($_COOKIE['pw_log'])){
                    //$this->validaLogin("admin", "autoLogin");
                }else{
                    $this->view('login');
                }
            }else{
                $this->helper->homeRedirect();
            }*/
            $this->view('login');
        }

    }

?>