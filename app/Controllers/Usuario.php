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
            $this->log = new Logs();
            require "Configuracoes.php";
            $this->configuracoes = new Configuracoes;
        }

        public function index(){
            if($this->helper->sessionValidate()){
                $this->view('pagenotfound');
            }else{
                echo "<script>window.location.href='../login';</script>";
            }
        }

        // Exibe tela de cadastro de usuário
        public function novo(){
            $dados = [
                'complexidade' => $this->configuracoes->complexidadeSenhaAtivo(),
            ];
            if($this->helper->sessionValidate()){
                $this->view('usuario/novo', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        // Executa rotina para cadastrar um novo usuário
        public function cadastrar(){
            if($this->helper->sessionValidate()){
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
                                    $this->log->registraLog($_SESSION['dbg_id'], "Usuário", $lastInsertId, 0, $dateTime);
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
                echo "<script>window.location.href='../novo';</script>";
            }else{
                $this->helper->loginRedirect();
            }
        }

        // Exibe tela de consulta de usuários
        public function consulta($pi = 0, $pf = 10){
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["dbg_usuario_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaUsuarios($pi),
                        'nome' => null,
                        'pagina_inicio' => $pi,
                        'pagina_fim'    => $pf,
                    ];
                }else{
                    if($_SESSION["dbg_usuario_consulta"] == null or isset($form["nome_usuario"])){
                        $nome = $form["nome_usuario"]; 
                    }else{
                        $nome = $_SESSION["dbg_usuario_consulta"];
                    }
                    $_SESSION["dbg_usuario_consulta"] = $nome;
                    $dados = [
                        'dados' =>  $this->listaUsuarioPorNome($nome, $pi),
                        'nome' => $nome,
                        'pagina_inicio' => $pi,
                        'pagina_fim'    => $pf,
                    ];
                }
                $this->view('usuario/consulta', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        // Listar usuários cadastrados
        public function listaUsuarios($pi, $attr = null){
            if($this->helper->sessionValidate()){
                return $this->usuarioModel->listaUsuarios($pi, $attr);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaUsuarioPorNome($nome, $pi){
            if($this->helper->sessionValidate()){
                return $this->usuarioModel->listaUsuarioPorNome($nome, $pi);
            }else{
                $this->helper->loginRedirect();
            }
        }

        // Alterar usuário
        public function alterar(){
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["update"])){
                    if($this->updateUsuario($form))
                        $this->log->registraLog($_SESSION['dbg_id'], "Usuário", $form["id"], 1, $dateTime);
                }else if(isset($form["inativar"])){
                    if($this->ativarInativarUsuario($form, "inativar"))
                        $this->log->registraLog($_SESSION['dbg_id'], "Usuário", $form["id"], 1, $dateTime);
                }else if(isset($form["ativar"])){
                    if($this->ativarInativarUsuario($form, "ativar"))
                        $this->log->registraLog($_SESSION['dbg_id'], "Usuário", $form["id"], 1, $dateTime);
                }
                echo "<script>window.location.href='../usuario/consulta';</script>";
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterarsenha(){
            $login = $_SESSION["dbg_user_altsen"];
            $id = $_SESSION["dbg_id_altsen"];
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
                $alterarSenhaRedirect = "<script>window.location.href='".URL."/usuario/alterarsenha/';</script>";
                if($form["senha"] != $form["repetesenha"]){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, existem campos que não foram preenchidos, tente novamente!',
                        $this->rotinaAlt
                    );
                    echo $alterarSenhaRedirect;
                }else if($form["senha"] != $form["repetesenha"]){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, elas não conferem, tente novamente!',
                        $this->rotinaAlt
                    );
                    echo $alterarSenhaRedirect;
                }else if(strlen($form["senha"]) < 6){
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Não foi possível atualizar a senha, elas não possuem o mínimo de 6 caracteres!',
                        $this->rotinaAlt
                    );
                    echo $alterarSenhaRedirect;
                }else if(strlen($form["senha"]) >= 6 and $form["senha"] == $form["repetesenha"]){
                    $form["senha"] = password_hash($form["senha"], PASSWORD_DEFAULT);
                    $dateTime = $this->helper->returnDateTime();
                    if($this->usuarioModel->alteraUsuario($form, "senha_update", $dateTime)){
                        $this->log->registraLog($form["id"], "Usuário", $form["id"], 1, $dateTime);
                        echo "<script>window.location.href='".URL."/Login/validaLogin/admin/atualizaSenha';</script>";
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Erro ao atualizar a senha, tente novamente, se o problema persistir, entre em contato com o administrador do sistema!',
                            $this->rotinaAlt
                        );
                        echo $alterarSenhaRedirect;
                    }
                }
            }else{
                $this->view('pagenotfound');
            }
        }

        // Executa rotinas de alterações do usuário
        private function updateUsuario($form){
            $retorno = false;
            if(!empty($form["nome"])){
                $info = $this->usuarioModel->buscaUsuarioPorId($form["id"]);
                if($form["nome"] == $info[0]->nome and (empty($form["senha"]) and empty($form["repetesenha"]))){
                    $this->helper->setReturnMessage(
                        $this->tipoWarning,
                        'Não foi necessária nenhuma alteração no cadastro do usuário',
                        $this->rotinaAlt
                    );
                }else if(($form["nome"] != $info[0]->nome) and (empty($form["senha"]) and empty($form["repetesenha"]))){
                    if($this->usuarioModel->alteraUsuario($form, "nome", $this->helper->returnDateTime())){
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
                                if($this->usuarioModel->alteraUsuario($form, "senha", $this->helper->returnDateTime())){
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
                                if($this->usuarioModel->alteraUsuario($form, "senha", $this->helper->returnDateTime())){
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
        private function ativarInativarUsuario($form, $acao){
            if($this->helper->sessionValidate()){
                $retorno = false;
                if($this->usuarioModel->ativaInativaUsuario($form["id"], $acao, $dateTime = $this->helper->returnDateTime())){
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