<?php 

    class Consultas extends Controller{
        public $helper;
        public $portaria;
        public $usuario;
        public $empresa;
        public $veiculo;
        public $motorista;
        public $operacao;
        public $log;

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
            $this->log = new Logs();         
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($form["limparFiltros"])){
                    $this->helper->redirectPage("/consultas/");
                }
                $operadoresSelecionados = null;
                $portariasSelecionados = null;
                $tiposSelecionados = null;
                $empresasSelecionadas = null;
                $veiculosSelecionados = null;
                $motoristasSelecionados = null;
                $dataDeSelecionada = $this->helper->returnDate();
                $dataAteSelecionada = $this->helper->returnDate();
                $consulta = $this->operacao->consultaOperacoes(null, null, null, null, null, null, $dataDeSelecionada, $dataAteSelecionada);
                
                if(isset($form) and $form != null){
                    $portaria = isset($form["portaria"]) ? $form["portaria"] : null;
                    $portariasSelecionados = $portaria;
                    $operador = isset($form["operador"]) ? $form["operador"] : null;
                    $operadoresSelecionados = $operador;
                    $tipo = isset($form["tipo"]) ? $form["tipo"] : null;
                    $tiposSelecionados = $tipo;
                    $empresa = isset($form["empresa"]) ? $form["empresa"] : null;
                    $empresasSelecionadas = $empresa;
                    $veiculo = isset($form["veiculo"]) ? $form["veiculo"] : null;
                    $veiculosSelecionados = $veiculo;
                    $motorista = isset($form["motorista"]) ? $form["motorista"] : null;
                    $motoristasSelecionados = $motorista;
                    $dataDe = isset($form["dataDe"]) ? $form["dataDe"] : null;
                    $dataDeSelecionada = $dataDe;
                    $dataAte = isset($form["dataAte"]) ? $form["dataAte"] : null;
                    $dataAteSelecionada = $dataAte;
                    $consulta = $this->operacao->consultaOperacoes($portaria, $operador, $tipo, $empresa, $veiculo, $motorista, $dataDe, $dataAte);
                }
                $dados = [
                    'portarias' => $this->portaria->listaPortarias(),
                    'operadores' => $this->usuario->listaUsuarios('todos'),
                    'operadoresSelecionados' => $operadoresSelecionados,
                    'portariasSelecionadas' => $portariasSelecionados,
                    'tiposSelecionados' => $tiposSelecionados,
                    'empresasSelecionadas' => $empresasSelecionadas,
                    'veiculosSelecionados' => $veiculosSelecionados,
                    'motoristasSelecionados' => $motoristasSelecionados,
                    'dataDeSelecionada' => $dataDeSelecionada,
                    'dataAteSelecionada' => $dataAteSelecionada,
                    'empresas' => $this->empresa->listaEmpresas(),
                    'veiculos' => $this->veiculo->listaVeiculos(),
                    'motoristas' => $this->motorista->listaMotoristas(),
                    'consulta' => $consulta,
                    'fezConsulta' => true
                ];
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consultas");
                $this->view('consultas/index', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function detalhada($id = null)
        {
            if($this->helper->sessionValidate()){
                if($id == null){
                    $this->view('pagenotfound');
                }else{
                    $dados = [
                        'operacao' => $this->operacao->consultaOperacaoPorId($id),
                        'imagens' => $this->operacao->buscaImagensOperacaoPorId($id),
                    ];
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consulta detalhada ID: $id");
                    $this->view('consultas/detalhada', $dados);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>