<?php

    use Shuchkin\SimpleXLSX;

    class Importador extends Controller{

        public $helper;
        public $log;
        public $SimpleXLSX;

        public function __construct()
        {
            $this->helper = new Helpers();
            $this->log = new Logs();
            require "../public/vendor/simplexlsx/src/SimpleXLSX.php";
        }

        public function index()
        {
            if($this->helper->sessionValidate()){
                $this->log->gravaLog($this->helper->returnDateTime(), null, "Abriu tela", $_SESSION['pw_id'], null, null, "Importador");
                $this->view('ferramentas/importador');
            }else{
                $this->helper->redirectPage("/login/");
            }  
        }

        public function importaArquivo(){
            if($this->helper->sessionValidate()){
                $temp_file = $_FILES["arquivo"]["tmp_name"];
                $arquivo = SimpleXLSX::parse($temp_file);
                //var_dump($arquivo->rows());
                for($i = 0; $i < count($arquivo->rows()); $i++){
                    var_dump($arquivo->rows()[$i]);
                    echo "<br><br><br>";
                }
            }else{
                $this->helper->redirectPage("/login/");
            } 
        }

    }

?>