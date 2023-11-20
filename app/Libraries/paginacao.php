<?php

    class Paginacao{
        private $paginaAtual;
        private $totalRegistros;
        private $paginaEsquerda;
        private $paginaDireita;
        private $paginaCentro;
        private $numRegPagina = NUM_REG_PAGINA;
        private $totalPaginas;
        private $url;

        public function __construct($totalRegistros, $paginaAtual, $url)
        {
            $this->totalRegistros = $totalRegistros;
            $this->paginaAtual = $paginaAtual;
            $this->calculaTotalPaginas();
            $this->url = $url;
            if($this->paginaAtual == 1){
                $this->paginaEsquerda = $this->paginaAtual;
                $this->paginaCentro = $this->paginaAtual + 1;
                $this->paginaDireita = $this->paginaAtual + 2;
            }else if($this->totalPaginas == 2){
                if($this->paginaAtual == 1){
                    $this->paginaEsquerda = $this->paginaAtual;
                    $this->paginaCentro = $this->paginaAtual + 1;
                }else if($this->paginaAtual == 2){
                    $this->paginaEsquerda = $this->paginaAtual - 1;
                    $this->paginaCentro = $this->paginaAtual;
                }
            }else if($this->totalPaginas >= $this->paginaAtual and $this->totalPaginas < $this->paginaAtual + 1){
                $this->paginaEsquerda = $this->paginaAtual - 2;
                $this->paginaCentro = $this->paginaAtual - 1;
                $this->paginaDireita = $this->paginaAtual;
            }else{
                $this->paginaEsquerda = $this->paginaAtual - 1;
                $this->paginaCentro = $this->paginaAtual;
                $this->paginaDireita = $this->paginaAtual + 1;
            }
        }

        public function view()
        {
            echo '
                <div class="row mt-5">
                    <div class="col-sm-12">
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                ';
            if($this->paginaAtual != 1 and $this->totalPaginas > 3){
                echo '              <a class="page-link" href="' . $this->url . '/1" aria-label="Primeira">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Primeira</span>
                                    </a>';
            }
            echo '              </li>
                                <li class="page-item ' . $this->setActive($this->paginaEsquerda) . '"><a class="page-link" href="' . $this->url . '/' . $this->paginaEsquerda . '">' . $this->paginaEsquerda . '</a></li>';
            if($this->totalPaginas > 1){
                echo '          <li class="page-item ' . $this->setActive($this->paginaCentro) . '"><a class="page-link" href="' . $this->url . '/' . $this->paginaCentro . '">' . $this->paginaCentro . '</a></li>';
            }
            if($this->totalPaginas > 2){
                echo '          <li class="page-item ' . $this->setActive($this->paginaDireita) . '"><a class="page-link" href="' . $this->url . '/' . $this->paginaDireita . '">' . $this->paginaDireita . '</a></li>';
            }
            if($this->totalPaginas > 3 and !($this->totalPaginas >= $this->paginaAtual and $this->totalPaginas < $this->paginaAtual + 1)){
                echo '          <li class="page-item">
                                    <a class="page-link" href="' . $this->url . '/' . $this->totalPaginas . '" aria-label="Última">
                                        <span class="sr-only">Última</span>    
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>';
            }
            echo '          </ul>
                        </nav>
                    </div>
                </div>
            ';
        }

        private function setActive($pagina)
        {
            if($this->paginaAtual == $pagina){
                return "active";
            }
        }

        private function calculaTotalPaginas()
        {            
            $calc = $this->totalRegistros / $this->numRegPagina;
            if(!is_int($calc)){
                $array = explode('.', $calc);
                $numPag = $array[0];
            }
            $calc = $numPag + 1;
            $this->totalPaginas = $calc;
        }

    }

?>