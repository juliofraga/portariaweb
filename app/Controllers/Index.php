<?php 

    class Index extends Controller{
        public $helper;
        public $pedido;
        public $cliente;
        public $log;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->pedido = $this->model('PedidoModel');
            $this->cliente = $this->model('ClienteModel');
            $this->log = $this->model('LogModel');
        }
        
        public function index(){
            if($this->helper->sessionValidate()){
                $hoje = $this->helper->returnDate();
                $ontem = date('Y-m-d', strtotime('-1 day', strtotime($hoje)));
                $qtdHoje = $this->pedido->retornaQuantidadePedidosDia($hoje);   
                $qtdOntem = $this->pedido->retornaQuantidadePedidosDia($ontem);
                $acessosHoje = $this->log->retornaQuantidadeAcessosDia($hoje);
                $acessoOntem = $this->log->retornaQuantidadeAcessosDia($ontem);
                $dados = [
                    'numeroPedidosHoje' => $qtdHoje,
                    'DiferencaPedidosHoje' => $this->calculaDiferencas($qtdHoje[0]->qtd, $qtdOntem[0]->qtd),
                    'numeroAcessosHoje' => $acessosHoje,
                    'DiferencaAcessosHoje' => $this->calculaDiferencas($acessosHoje[0]->qtd, $acessoOntem[0]->qtd),
                    'numeroClientesCadastrados' => $this->cliente->retornaQtdClientesCadastrados(),
                    'pedidosPorDia' => $this->retornaPedidosPorDia($hoje),
                    'pedidosPorMes' => $this->retornaPedidosPorMes(),
                    'acessosPorMes' => $this->retornaAcessosPorMes()
                ];
                $this->view('index', $dados);
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function retornaPedidosPorMes(){
            if($this->helper->sessionValidate()){
                $ano = date('Y');
                $mes = [
                    'janeiro' => $this->pedido->retornaQuantidadePedidosMes("$ano-01-01", "$ano-01-31"),
                    'fevereiro' => $this->pedido->retornaQuantidadePedidosMes("$ano-02-01", "$ano-02-28"),
                    'marco' => $this->pedido->retornaQuantidadePedidosMes("$ano-03-01", "$ano-03-31"),
                    'abril' => $this->pedido->retornaQuantidadePedidosMes("$ano-04-01", "$ano-04-30"),
                    'maio' => $this->pedido->retornaQuantidadePedidosMes("$ano-05-01", "$ano-05-31"),
                    'junho' => $this->pedido->retornaQuantidadePedidosMes("$ano-06-01", "$ano-06-30"),
                    'julho' => $this->pedido->retornaQuantidadePedidosMes("$ano-07-01", "$ano-07-31"),
                    'agosto' => $this->pedido->retornaQuantidadePedidosMes("$ano-08-01", "$ano-08-31"),
                    'setembro' => $this->pedido->retornaQuantidadePedidosMes("$ano-09-01", "$ano-09-30"),
                    'outubro' => $this->pedido->retornaQuantidadePedidosMes("$ano-10-01", "$ano-10-31"),
                    'novembro' => $this->pedido->retornaQuantidadePedidosMes("$ano-11-01", "$ano-11-30"),
                    'dezembro' => $this->pedido->retornaQuantidadePedidosMes("$ano-12-01", "$ano-12-31"),
                ];
                return $mes;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function retornaAcessosPorMes(){
            if($this->helper->sessionValidate()){
                $ano = date('Y');
                $mes = [
                    'janeiro' => $this->log->retornaQuantidadeAcessoMes("$ano-01-01", "$ano-01-31"),
                    'fevereiro' => $this->log->retornaQuantidadeAcessoMes("$ano-02-01", "$ano-02-28"),
                    'marco' => $this->log->retornaQuantidadeAcessoMes("$ano-03-01", "$ano-03-31"),
                    'abril' => $this->log->retornaQuantidadeAcessoMes("$ano-04-01", "$ano-04-30"),
                    'maio' => $this->log->retornaQuantidadeAcessoMes("$ano-05-01", "$ano-05-31"),
                    'junho' => $this->log->retornaQuantidadeAcessoMes("$ano-06-01", "$ano-06-30"),
                    'julho' => $this->log->retornaQuantidadeAcessoMes("$ano-07-01", "$ano-07-31"),
                    'agosto' => $this->log->retornaQuantidadeAcessoMes("$ano-08-01", "$ano-08-31"),
                    'setembro' => $this->log->retornaQuantidadeAcessoMes("$ano-09-01", "$ano-09-30"),
                    'outubro' => $this->log->retornaQuantidadeAcessoMes("$ano-10-01", "$ano-10-31"),
                    'novembro' => $this->log->retornaQuantidadeAcessoMes("$ano-11-01", "$ano-11-30"),
                    'dezembro' => $this->log->retornaQuantidadeAcessoMes("$ano-12-01", "$ano-12-31"),
                ];
                return $mes;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function retornaPedidosPorDia($hoje){
            if($this->helper->sessionValidate()){
                $diaSemana = date('w', strtotime($hoje));
                $semana = [];
                if($diaSemana == '0'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '1'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '2'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '3'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '4'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '5'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                    ];
                }else if($diaSemana == '6'){
                    $semana = [
                        'domingo' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-6 days', strtotime($hoje)))),
                        'segunda' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-5 days', strtotime($hoje)))),
                        'terca' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-4 days', strtotime($hoje)))),
                        'quarta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-3 days', strtotime($hoje)))),
                        'quinta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-2 days', strtotime($hoje)))),
                        'sexta' => $this->pedido->retornaQuantidadePedidosDia(date('Y-m-d', strtotime('-1 day', strtotime($hoje)))),
                        'sabado' => $this->pedido->retornaQuantidadePedidosDia($hoje),
                    ];
                }
                return $semana;
            }else{
                $this->helper->loginRedirect();
            }
        }

        private function calculaDiferencas($hoje, $ontem){
            if($this->helper->sessionValidate()){
                $hoje = (int)$hoje;
                $ontem = (int)$ontem;
                if($ontem > $hoje){
                    $diff = ($hoje * 100) / $ontem;
                    $texto = "menos do que ontem";
                    $posneg = "negativo";
                }else if($hoje > $ontem){
                    $diff = ($ontem * 100) / $hoje;
                    $texto = "mais do que ontem";
                    $posneg = "positivo";
                }else{
                    $diff = "100";
                    $texto = "igual a ontem";
                    $posneg = "neutro";
                }
                $diff = number_format($diff, 2, ',', '.');
                $retorno = [$diff, $texto, $posneg];
                return $retorno;
            }else{
                $this->helper->loginRedirect();
            }
        }

    }

?>