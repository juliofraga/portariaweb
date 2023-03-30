<?php

    class Database {

        private $host = DB['HOST'];
        private $usuario = DB['USUARIO'];
        private $senha = DB['SENHA'];
        private $banco = DB['BANCO'];
        private $porta = DB['PORTA'];
        private $dbh;
        private $stmt;

        public function __construct()
        {
            $dsn = 'mysql:host='.$this->host.';port='.$this->porta.';dbname='.$this->banco;
            $opcoes = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            try {
                $this->dbh = new PDO($dsn, $this->usuario, $this->senha, $opcoes);
            } catch (PDOException $e) {
                phpErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
                die();
            }
        }

        //recebe e prepara a query
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        public function bind($parametro, $valor, $tipo = null){
            if(is_null($tipo)):
                switch (true):
                    case is_int($valor):
                        $tipo = PDO::PARAM_INT;
                    break;
                    case is_bool($valor):
                        $tipo = PDO::PARAM_BOOL;
                    break;
                    case is_null($valor):
                        $tipo = PDO::PARAM_NULL;
                    break;
                    default:
                    $tipo = PDO::PARAM_STR;
                endswitch;
            endif;
            $this->stmt->bindValue($parametro, $valor, $tipo);
        }

        //executa o statement
        public function execQuery(){
            return $this->stmt->execute();
        }

        //Return only 1 register
        public function result(){
            $this->execQuery();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        //Return 1 or more registers
        public function results(){
            $this->execQuery();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        //Return num rows
        public function numRows(){
            return $this->stmt->rowCount();
        }

        //Return last inser ID
        public function lastInsertId(){
            return $this->dbh->lastInsertId();
        }

    }

?>

