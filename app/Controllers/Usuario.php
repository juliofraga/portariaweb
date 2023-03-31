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
            $this->helper = new Helpers();
            $this->usuarioModel = $this->model('UsuarioModel');
        }

        public function index()
        {
            
        }

        public function novo()
        {
            $dados = [
                //'complexidade' => $this->configuracoes->complexidadeSenhaAtivo(),
                'complexidade' => null,
            ];
            //if($this->helper->sessionValidate()){
                $this->view('usuario/novo', $dados);
           /* }else{
                $this->helper->loginRedirect();
            }*/
        }

        public function cadastrar()
        {
            //if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if($this->helper->validateFields($form)){
                    if(!$this->usuarioModel->verificaLogin($form["login"])){
                        if($form["senha"] == $form["repetesenha"]){
                            if(strlen($form["senha"]) >= 6){
                                $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                                $dateTime = $this->helper->returnDateTime();
                                $lastInsertId = $this->usuarioModel->cadastrarUsuario($form, $dateTime);
                                if($lastInsertId != null){
                                    $this->helper->setReturnMessage(
                                        $this->tipoSuccess,
                                        'Usuário cadastrado com sucesso!',
                                        $this->rotinaCad
                                    );
                                    //$this->log->registraLog($_SESSION['pw_id'], "Usuário", $lastInsertId, 0, $dateTime);
                                }else{
                                    $this->helper->setReturnMessage(
                                        $this->tipoError,
                                        'Não foi possível cadastrar o usuário, tente novamente!',
                                        $this->rotinaCad
                                    );
                                }
                            }else{
                                $this->helper->setReturnMessage(
                                    $this->tipoError,
                                    'A senha deve ter no minimo 6 caracteres, tente novamente!',
                                    $this->rotinaCad
                                );
                            }
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'As senhas não conferem, tente novamente!',
                                $this->rotinaCad
                            );
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Não foi possível cadastrar o usuário, já existe um usuário cadastrado no sistema com este login, tente novamente informando outro login!',
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
                
                $this->helper->redirectPage("/usuario/novo");
           // }else{
         //       $this->helper->loginRedirect();
          //  }
        }

    }

?>