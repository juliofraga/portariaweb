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
        public $configuracoes;

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
            $this->configuracoes = new Configuracoes();  
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil']) and $this->configuracoes->operadorVisualizaConsultas() == 0){
                    $this->view('pagenotfound');
                }else{
                    $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if(isset($form["limparFiltros"])){
                        $this->helper->redirectPage("/consultas/");
                    }
                    $ehOperador = $_SESSION['pw_tipo_perfil'] == md5('Operador') ? true : false;
                    $operadoresSelecionados = null;
                    $portariasSelecionados = null;
                    $tiposSelecionados = null;
                    $empresasSelecionadas = null;
                    $veiculosSelecionados = null;
                    $motoristasSelecionados = null;
                    $dataDeSelecionada = $this->helper->returnDate();
                    $dataAteSelecionada = $this->helper->returnDate();
                    $portariaOperador = null;
                    if($ehOperador) {
                        $portariaOperador = [];
                        $returnPortariaOperador = $this->portaria->listaPortariasPorUsuario($_SESSION['pw_id'], 'Operador');
                        foreach($returnPortariaOperador as $rpo){
                            $portariaOperador[] = $rpo->id;
                        }
                    }
                    $consulta = $this->operacao->consultaOperacoes($portariaOperador, null, null, null, null, null, $dataDeSelecionada, $dataAteSelecionada);
                    $idFiltro = null;
                    if(isset($form) and $form != null){
                        if($ehOperador) {
                            $portaria = isset($form["portaria"]) ? $form["portaria"] : $portariaOperador;
                            $portariasSelecionados = isset($form["portaria"]) ? $form["portaria"] : null;
                        }else{
                            $portaria = isset($form["portaria"]) ? $form["portaria"] : null;
                            $portariasSelecionados = $portaria;
                        }

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
                        $idFiltro = isset($form["id"]) ? $form["id"] : null;
                        $consulta = $this->operacao->consultaOperacoes($portaria, $operador, $tipo, $empresa, $veiculo, $motorista, $dataDe, $dataAte, $idFiltro);
                        
                    }
                    $dados = [
                        'portarias' => $ehOperador ? $this->portaria->listaPortariasPorUsuario($_SESSION['pw_id'], 'Operador') : $this->portaria->listaPortarias(),
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
                        'fezConsulta' => true,
                        'idFiltro' => $idFiltro,
                    ];
                    $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Consultas");
                    $this->view('consultas/index', $dados);
                }
            }else{
                $this->helper->loginRedirect();
            }
        }

        public function detalhada($id = null)
        {
            if($this->helper->sessionValidate()){
                if($this->helper->isOperador($_SESSION['pw_tipo_perfil']) and $this->configuracoes->operadorVisualizaConsultas() == 0){
                    $this->view('pagenotfound');
                }else{
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
                }
            }else{
                $this->helper->loginRedirect();
            }
        }
    }
?>