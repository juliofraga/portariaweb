<?php 

    class Consultas extends Controller{
        public $helper;
        public $portaria;
        public $usuario;
        public $empresa;
        public $veiculo;
        public $motorista;
        public $operacao;

        public function __construct()
        {
            require "Portaria.php";
            require "Operacao.php";
            $this->helper = new Helpers();
            $this->portaria = new Portaria();
            $this->usuario = $this->portaria->usuario;
            $this->operacao = new Operacao();
            $this->veiculo = $this->operacao->veiculo;
            $this->empresa = $this->veiculo->empresa;
            $this->motorista = $this->operacao->motorista;            
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $consulta = null;
                $fezConsulta = false;
                if(isset($form) and $form != null){
                    $fezConsulta = true;
                    $consulta = $this->operacao->consultaOperacoes();
                }
                $dados = [
                    'portarias' => $this->portaria->listaPortarias(),
                    'operadores' => $this->usuario->listaUsuarios(),
                    'empresas' => $this->empresa->listaEmpresas(),
                    'veiculos' => $this->veiculo->listaVeiculos(),
                    'motoristas' => $this->motorista->listaMotoristas(),
                    'consulta' => $consulta,
                    'fezConsulta' => $fezConsulta
                ];
                $this->view('consultas/index', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>