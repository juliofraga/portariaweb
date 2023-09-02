<?php

    class LoadOptions{
        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        public function setOptions(){
            try {
                // Selecionando opção para visualização de consultas de operações para operador
                $this->db->query("SELECT valor FROM configuracoes WHERE id = 5 and valor = 0");
                $this->db->execQuery();
                if($this->db->numRows() > 0)
                    $operadorVisualizaConsultas = true;
                else
                    $operadorVisualizaConsultas = false;
                $_SESSION["opeViewCons"] = $operadorVisualizaConsultas;
            } catch (Throwable $th) {
                return null;
            }
        }
    }

?>