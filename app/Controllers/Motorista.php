<?php

    class Motorista extends Controller{
        private $tipoSuccess = 'success';
        private $tipoError = 'error';
        private $tipoWarning = 'warning';
        private $rotinaCad = 'motorista';
        private $rotinaAlt = 'motorista';
        public $helper;
        public $motoristaModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->motoristaModel = $this->model('MotoristaModel');
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

        public function cadastrar($nomeMotorista, $cpfMotorista, $veiculo_id)
        {
            if($this->helper->sessionValidate()){
                $dateTime = $this->helper->returnDateTime();
                $lastInsertId = $this->motoristaModel->cadastrarMotorista($nomeMotorista, $cpfMotorista, $dateTime);
                if($lastInsertId != null){
                    $this->motoristaModel->ligaMotoristaVeiculo($lastInsertId, $veiculo_id);
                    $this->log->registraLog($_SESSION['pw_id'], "Motorista", $lastInsertId, 0, $dateTime);
                    $this->log->gravaLog($dateTime, $lastInsertId, "Adicionou", $_SESSION['pw_id'], "Motorista");
                    return $lastInsertId;
                }
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function consulta()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta Motorista");
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if((!isset($_SESSION["pw_motorista_consulta"])) and($form == null or !isset($form)) or ($form != null and isset($form["limpar"]))){
                    $dados = [
                        'dados' =>  $this->listaMotoristas(),
                        'filtro' => null,
                    ];
                }else{
                    if($_SESSION["pw_motorista_consulta"] == null or isset($form["filtro"])){
                        $filtro = $form["filtro"]; 
                    }else{
                        $filtro = $_SESSION["pw_motorista_consulta"];
                    }
                    $_SESSION["pw_motorista_consulta"] = $filtro;
                    $dados = [
                        'dados' =>  $this->listaMotoristasPorFiltro($filtro),
                        'filtro' => $filtro,
                    ];
                }
                $this->view('motorista/consulta', $dados);
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

        public function listaMotoristas($attr = null)
        {
            if($this->helper->sessionValidate()){
                return $this->motoristaModel->listaMotoristas();
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaMotoristasPorFiltro($filtro)
        {
            if($this->helper->sessionValidate()){
                return $this->motoristaModel->listaMotoristasPorFiltro($filtro);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function verificaMotorista($motorista_id)
        {
            if($this->helper->sessionValidate()){
                return $this->motoristaModel->verificaMotorista($motorista_id);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaMotoristaPorEmpresa($empresa_id)
        {
            if($this->helper->sessionValidate()){
                $motoristas = $this->motoristaModel->retornaMotoristaPorEmpresa($empresa_id);
                if($motoristas){
                    foreach($motoristas as $motorista){
                        echo "<motorista>";
                        echo "<id>".$motorista->id;
                        echo "</id>";
                        echo "<nome_completo>".$motorista->nome_completo;
                        echo "</nome_completo>";
                        echo "</motorista>";
                    }
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function retornaCpfMotorista($motorista_id)
        {
            if($this->helper->sessionValidate()){
                $cpf = $this->motoristaModel->retornaCpfMotorista($motorista_id);
                if($cpf){
                    $cpf = $cpf[0]->cpf;
                }else{
                    $cpf = "";
                }
                echo "<cpfMotorista>" . $cpf . "</cpfMotorista>";
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function alterar()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $dateTime = $this->helper->returnDateTime();
                if($this->helper->validateFields($form, 'rg')){
                    if($this->validaMotorista($form["id"], $form["cpf"], $form["rg"])){
                        if($this->motoristaModel->alterarMotorista($form, $dateTime)){
                            $this->helper->setReturnMessage(
                                $this->tipoSuccess,
                                'Motorista atualizado com sucesso!',
                                $this->rotinaCad
                            );
                            $this->log->registraLog($_SESSION['pw_id'], "Motorista", $form["id"], 1, $dateTime);
                            $this->log->gravaLog($dateTime, $form["id"], "Alterou", $_SESSION['pw_id'], "Motorista");
                        }else{
                            $this->helper->setReturnMessage(
                                $this->tipoError,
                                'Erro ao atualizar motorista, tente novamente!',
                                $this->rotinaCad
                            );
                            $this->log->gravaLog($dateTime, null, "Tentou alterar, mas sem sucesso", $_SESSION['pw_id'], "Motorista", "Erro ao gravar no banco de dados");
                        }
                    }else{
                        $this->helper->setReturnMessage(
                            $this->tipoError,
                            'Este CPF ou RG já estão cadastrados para outro motorista, verifique novamente!',
                            $this->rotinaCad
                        );
                        $this->log->gravaLog($dateTime, null, "Tentou alterar, mas sem sucesso", $_SESSION['pw_id'], "Motorista", "CPF ou RG já estão cadastrados no sistema");
                    }
                }else{
                    $this->helper->setReturnMessage(
                        $this->tipoError,
                        'Existem campos que não foram preenchidos, verifique novamente!',
                        $this->rotinaCad
                    );
                    $this->log->gravaLog($dateTime, null, "Tentou alterar, mas sem sucesso", $_SESSION['pw_id'], "Motorista", "Existem campos que não foram preenchidos");
                }
                $this->helper->redirectPage("/motorista/consulta");
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function validaMotorista($motorista_id, $cpf, $rg)
        {
            if($this->helper->sessionValidate()){
                if($this->motoristaModel->validaCpf($motorista_id, $cpf)){
                    if(!empty($rg)){
                        if($this->motoristaModel->validaRg($motorista_id, $rg)){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        return true;
                    }
                }else{
                    return false;
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function buscaMotoristaPorCpf($cpf)
        {
            if($this->helper->sessionValidate()){
                return $this->motoristaModel->buscaMotoristaPorCpf($cpf);
            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>