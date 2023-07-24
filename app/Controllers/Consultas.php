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
                $operadoresSelecionados = null;
                $portariasSelecionados = null;
                $tipo = null;
                if(isset($form) and $form != null){
                    $portaria = isset($form["portaria"]) ? $form["portaria"] : null;
                    $portariasSelecionados = $portaria;
                    $operador = isset($form["operador"]) ? $form["operador"] : null;
                    $operadoresSelecionados = $operador;
                    $tipo = isset($form["tipo"]) ? $form["tipo"] : null;
                    $fezConsulta = true;
                    $consulta = $this->operacao->consultaOperacoes($portaria, $operador, $tipo);
                }
                $dados = [
                    'portarias' => $this->portaria->listaPortarias(),
                    'operadores' => $this->usuario->listaUsuarios('todos'),
                    'operadoresSelecionados' => $operadoresSelecionados,
                    'portariasSelecionadas' => $portariasSelecionados,
                    'tiposSelecionados' => $tipo,
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

        public function detalhada()
        {
            if($this->helper->sessionValidate()){
                $this->view('consultas/detalhada');
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>