<?php 

    class Helpers{

        private $url = URL;


        public function __construct()
        {
            date_default_timezone_set('America/Sao_Paulo');   
        }

        public function sessionValidate(){
            if(isset($_SESSION['pw_session_id'])){
                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                $ip = $_SERVER['REMOTE_ADDR'];
                $array = explode("-_-", $_SESSION['pw_ession_id']);
                if(count($array) > 1){
                    if($array[2] == md5(1) or $array[2] == md5(3) or $array[2] == md5(5)){
                        if($array[1] == md5($hostname)){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        if($array[0] == md5($ip)){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }else{
                    return false;
                }
            }else{
                return false;   
            }   
        }

        public function validateFields($array, $attr = null){
            if($attr != null){
                $atributos = explode(";", $attr);
                for($i = 0; $i < count($atributos); $i++){
                    unset($array[$atributos[$i]]);
                }
            }
            $retorno = true;
            if($array){
                foreach($array as $ar){
                    if($ar == "" or empty($ar) or $ar == null)
                        $retorno = false;
                }
            }else{
                $retorno = false;
            }
            return $retorno;  
        }

        public function setReturnMessage($tipo, $mensagem, $rotina){
            $_SESSION["pw_tipo"] = $tipo;
            $_SESSION["pw_mensagem"] = $mensagem;
            $_SESSION["pw_rotina"] = $rotina;
        }

        //return São Paulo date time
        public function returnDateTime(){
            $dateTime = date("Y-m-d H:i:s");
            return $dateTime;
        }

        //return current date
        public function returnDate(){
            $dateTime = date("Y-m-d");
            return $dateTime;
        }

        public function setSelected($valor1, $valor2){
            if($valor1 == $valor2)
                return "selected";
        }

        public function redirectPage($url){
            echo "<script>window.location.href='".URL."$url';</script>";
        }

        public function loginRedirect(){
            $this->redirectPage("/login");
        }

        public function homeRedirect(){
            $this->redirectPage("/home");
        }

        /*


        public function setMultiSelect($array = null, $valor){
            if($array != null){
                for($i = 0; $i < count($array); $i++){
                    if($array[$i] == $valor){
                        return "selected";
                    }
                }
            }
        }

        public function setSelectProdutos($produtos_ligados, $produto_id){
            foreach($produtos_ligados as $produto){
                if($produto->produtos_id == $produto_id){
                    echo "selected";
                }
            }
        }

        public function formataDateTime($dateTime){
            if($dateTime != null and !empty($dateTime)){
                $divisor = explode(" ", $dateTime);
                $data = explode("-", $divisor[0]);
                return $dateTime = $data[2]."/".$data[1]."/".$data[0]." - ".$divisor[1];
            }else{
                return "00/00/00 00:00:00";
            }
        }

        public function formataDate($date, $attr = null){
            if($date != null and !empty($date)){
                $data = explode("-", $date);
                return $date = $data[2]."/".$data[1]."/".$data[0];
            }else{
                if($attr == null){
                    return "00/00/0000";
                }else{
                    return "<br>";
                }
            }
        }

        public function multiplicaFormata($v1, $v2){
            $v2 = str_replace(",", ".", $v2);
            $total = $v1 * $v2;
            $array = explode(".", $total);
            if(count($array) < 2){
                $total .= ",00";
            }else{
                if(strlen($array[1]) > 2){
                    $total = $array[0].",".substr($array[1], 0, 2);
                }else if(strlen($array[1]) == 2){
                    $total = str_replace(".", ",", $total);
                }else if(strlen($array[1]) < 2){
                    $total = str_replace(".", ",", $total);
                    $total = $total."0";
                }
            }
            $total = str_replace(",", ".", $total);
            $total = $this->formataValor($total);
            $total = 'R$ '.$total;
            return $total;
        }

        public function formataValor($valor){
            return number_format($valor, 2, ',', '.');
        }

        public function retornaHorario($dateTime){
            $array = explode(" ", $dateTime);
            return $array[1];
        }

        public function setCookie($nome, $valor){
            setcookie($nome, $valor, time()+2592000, "/");
        }

        public function removeCookie($nome){
            try{
                setcookie($nome, NULL, -1, "/");
            }catch (Throwable $th) {
                return null;
            } 
            
        }

        public function geraHashMd5(){
            return md5(rand(1,100000));   
        }

        public function setBusca($texto){
            if(empty($texto)){
                return null;
            }else{
                $substituiveis = array(" a ", " e ", "o ", " da "," de ", " do ", " para ");
                $novoTexto = str_replace($substituiveis, " ", $texto);
                $array = explode(" ", $novoTexto);
                return $array;	
            }
        }

        public function calculaParcela($valor, $qtd){
            if(strpos($valor, ".")){
                $valor = str_replace(".", "", $valor);
            }
            $valor = str_replace(",", ".", $valor);
            $res = $valor / $qtd;
            $array = explode(".", $res);
            if(count($array) == 2){
                if(strlen($array[1]) > 2){
                    $decimal = substr($array[1], 0, 2);
                }else if(strlen($array[1]) == 1){
                    $decimal = $array[1]."0";
                }else if(strlen($array[1]) == 0){
                    $decimal = "00";
                }
            }else if(count($array) == 1){
                $decimal = "00";
            }
            if(strlen($array[0]) == 4){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
            }else if(strlen($array[0]) == 5){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
            }else if(strlen($array[0]) == 6){
                $array[0] = $this->insertInPosition($array[0], 3, ".");
            }else if(strlen($array[0]) == 7){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
                $array[0] = $this->insertInPosition($array[0], 5, ".");
            }else if(strlen($array[0]) == 8){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
                $array[0] = $this->insertInPosition($array[0], 6, ".");
            }
            return $array[0].",".$decimal;
        }

        public function subtraiValores($valor1, $valor2){
            $valor1 = str_replace(".", "", $valor1);
            $valor2 = str_replace(".", "", $valor2);
            $valor1 = str_replace(",", ".", $valor1);
            $valor2 = str_replace(",", ".", $valor2);
            $res = $valor1 - $valor2;
            $array = explode(".", $res);
            if(isset($array[1])){
                if(strlen($array[1]) == 2){
                    $res = str_replace(".", ",", $res);
                    return $res;
                }
            }
            if(count($array) == 2){
                if(strlen($array[1]) > 2){
                    $decimal = substr($array[1], 0, 2);
                }else if(strlen($array[1]) == 1){
                    $decimal = $array[1]."0";
                }else if(strlen($array[1]) == 0){
                    $decimal = "00";
                }
            }else if(count($array) == 1){
                $decimal = "00";
            }
            if(strlen($array[0]) == 4){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
            }else if(strlen($array[0]) == 5){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
            }else if(strlen($array[0]) == 6){
                $array[0] = $this->insertInPosition($array[0], 3, ".");
            }else if(strlen($array[0]) == 7){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
                $array[0] = $this->insertInPosition($array[0], 5, ".");
            }else if(strlen($array[0]) == 8){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
                $array[0] = $this->insertInPosition($array[0], 6, ".");
            }
            return $array[0].",".$decimal;
        }

        public function somaValores($valor1, $valor2){
            if($valor1 == null){
                return $valor2;
            }
            $valor1 = str_replace(".", "", $valor1);
            $valor2 = str_replace(".", "", $valor2);
            $valor1 = str_replace(",", ".", $valor1);
            $valor2 = str_replace(",", ".", $valor2);
            $res = $valor1 + $valor2;
            $array = explode(".", $res);
            if(isset($array[1])){
                if(strlen($array[1]) == 2){
                    $res = str_replace(".", ",", $res);
                    return $res;
                }
            }
            if(count($array) == 2){
                if(strlen($array[1]) > 2){
                    $decimal = substr($array[1], 0, 2);
                }else if(strlen($array[1]) == 1){
                    $decimal = $array[1]."0";
                }else if(strlen($array[1]) == 0){
                    $decimal = "00";
                }
            }else if(count($array) == 1){
                $decimal = "00";
            }
            if(strlen($array[0]) == 4){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
            }else if(strlen($array[0]) == 5){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
            }else if(strlen($array[0]) == 6){
                $array[0] = $this->insertInPosition($array[0], 3, ".");
            }else if(strlen($array[0]) == 7){
                $array[0] = $this->insertInPosition($array[0], 1, ".");
                $array[0] = $this->insertInPosition($array[0], 5, ".");
            }else if(strlen($array[0]) == 8){
                $array[0] = $this->insertInPosition($array[0], 2, ".");
                $array[0] = $this->insertInPosition($array[0], 6, ".");
            }
            return $array[0].",".$decimal;
        }

        public function insertInPosition($str, $pos, $c){
            return substr($str, 0, $pos) . $c . substr($str, $pos);
        }

        
        public function formataNumero($valor){
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);
            return $valor;
        }

        public function verificaLinkAtivo($url1, $url2 = null){
            $urlRequested = explode('pedido/', $_SERVER["REQUEST_URI"]);
            if($urlRequested[1] == $url1 or $urlRequested[1] == $url2){
                return "active";
            }else{
                return "";
            }
        }

        public function verificaCheck($array, $valor){
            foreach($array as $valores){
                if($valores->dia_compra_id == $valor->id){
                    echo "checked";
                }
            }
        }

        public function verificaValor($produto_id, $array){
            if($array == null){
                return 0;
            }else{
                foreach($array as $produto){
                    if($produto->produtos_id == $produto_id){
                        if($produto->unidade_medida == "fardo"){
                            return $produto->quantidade / $produto->qtd_pacote_fardo;
                        }else{
                            return $produto->quantidade;
                        }   
                    }
                }
                return 0;
            }
        }

        public function verificaCheckFardoPacote($produto_id, $array, $tipo){
            if($array == null){
                return "";
            }else{
                foreach($array as $produto){
                    if($produto->produtos_id == $produto_id){
                        if($produto->unidade_medida == $tipo){
                            return "checked";
                        }
                    }
                }
                return "";
            }
        }

        public function calculaTotalItem($produto_id, $valor, $array){
            if($array == null){
                return "0,00";
            }else{
                foreach($array as $produto){
                    if($produto->produtos_id == $produto_id){
                        $_SESSION["valor_total"] = $_SESSION["valor_total"] + $produto->quantidade * $valor;
                        return $this->formataValor($produto->quantidade * $valor);
                    }
                }
                return "0,00";
            }
        }

        public function retornaValorTotal(){
            return $this->formataValor($_SESSION["valor_total"]);
        }
        
        public function verificaPositivo($produto_id, $array){
            if($array == null){
                return $produto_id;
            }else{
                foreach($array as $produto){
                    if($produto->produtos_id == $produto_id){
                        return $produto_id."__pos";
                    }
                }
                return $produto_id;
            }
        }

        public function formataDateDBMode($data){
            $array = explode("-", $data);
            return $array[2]."/".$array[1]."/".$array[0];
        }

        public function calculaDesconto($tags, $descontos, $preco, $produto, $qtd_pacote_fardo = null){
            $precoFinal = $preco;
            if($descontos != null){
                foreach($descontos as $desconto){
                    if($desconto->produto_id == $produto){
                        $precoFinal = $preco - (($desconto->desconto / 100) * $preco);
                    }
                }
                
            }else if($tags != null){
                $vlrDesconto = str_replace("%", "", $tags[0]->tag);
                $vlrDesconto = str_replace(",", ".", $vlrDesconto);
                $precoFinal = $preco - (($vlrDesconto / 100) * $preco);
            }
            if($qtd_pacote_fardo == null){
                return $this->formataValor($precoFinal);
            }else{
                return $this->multiplicaFormata($precoFinal, $qtd_pacote_fardo);
            }
        }*/
    } 
?>