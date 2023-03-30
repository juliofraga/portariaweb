<?php 

    class Configuracoes extends Controller{
        public $helper;
        public $configuracaoModel;
        public $produtoModel;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->configuracaoModel = $this->model('ConfiguracaoModel');
            $this->produtoModel = $this->model('ProdutoModel');
            $this->log = new Logs();
        }
        
        public function index(){
            if($this->helper->sessionValidate()){
                $dados = [
                    'configuracoes' => $this->listaConfiguracoes(),
                    'familia' => $this->produtoModel->listaFamiliasProdutos(),
                ];
                $this->view('configuracoes/index', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function listaConfiguracoes(){
            if($this->helper->sessionValidate()){
                return $this->configuracaoModel->listaConfiguracoes();
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function atualizaConfiguracao($id, $valor){
            if($this->helper->sessionValidate()){
                if($valor == "Ativado"){
                    $valor = 0;
                }else if($valor == "Desativado"){
                    $valor = 1;
                }
                $dateTime = $this->helper->returnDateTime();
                if($this->configuracaoModel->AtualizaConfiguracao($id, $valor, $dateTime)){
                    $this->log->registraLog($_SESSION['dbg_id'], "Configurações", $id, 1, $dateTime);
                    return true;
                }else{
                    return false;
                }              
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function entregaQualquerDiaAtivo(){
            return $this->configuracaoModel->opcaoAtiva(1);
        }

        public function entregaDiaAtualAtivo(){
            return $this->configuracaoModel->opcaoAtiva(2);
        }

        public function complexidadeSenhaAtivo(){
            return $this->configuracaoModel->opcaoAtiva(3);
        }

        public function bloqueioContaAtivo(){
            return $this->configuracaoModel->opcaoAtiva(4);
        }

        public function retiradaLocalAtiva(){
            return $this->configuracaoModel->opcaoAtiva(5);
        }
        
    }

?>