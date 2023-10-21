<?php 

    class Helpers{

        private $url = URL;


        public function __construct()
        {
            date_default_timezone_set(TIMEZONE);   
        }

        public function sessionValidate(){
            if(isset($_SESSION['pw_session_id'])){
                $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                $ip = $_SERVER['REMOTE_ADDR'];
                $array = explode("-_-", $_SESSION['pw_session_id']);
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
            $this->redirectPage("/painel");
        }

        public function removeCookie($nome){
            try{
                setcookie($nome, NULL, -1, "/");
            }catch (Throwable $th) {
                return null;
            } 
            
        }

        public function setCookie($nome, $valor){
            setcookie($nome, $valor, time()+2592000, "/");
        }

        public function geraHashMd5(){
            return md5(rand(1,100000));   
        }

        public function setMultiSelect($valor, $array){
            if(is_array($array)){
                if(in_array($valor, $array)){
                    echo "selected";
                }
            }
        }

        public function setMultiSelectUsuariosPortaria($array = null, $usuario, $portaria){
            if($array != null){
                foreach($array as $array){
                    if($array->usuarios_id == $usuario and $array->portoes_id == $portaria){
                        echo "selected";
                    }
                }
            }
        }

        public function formataDateTime($dateTime){
            if($dateTime != null and !empty($dateTime)){
                $divisor = explode(" ", $dateTime);
                $data = explode("-", $divisor[0]);
                return $dateTime = $data[2]."/".$data[1]."/".$data[0]." - ".$divisor[1];
            }else{
                return "-";
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

        public function verificaLinkAtivo($url){
            $urlRequested = explode('portariaweb/', $_SERVER["REQUEST_URI"]);
            foreach($url as $url){
                if($urlRequested[1] == $url or strpos($urlRequested[1], $url) === 0){
                    return "active";
                }
            }
            return "";
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
        }

        public function retornaSituacao($situacao){
            $situacoes = [
                0 => 'Ativo',
                1 => 'Inativo',
                2 => 'Bloqueado'
            ];
            return $situacoes[$situacao];
        }

        public function handleEnderecoIp($ip){
            $array = explode(".", $ip);
            $enderecoFormatado = "";
            for($i = 0; $i < count($array); $i++){
                if($array[$i][0] == 0){
                    if(strlen($array[$i]) > 2){
                        $array[$i] = $array[$i][1].$array[$i][2];
                    }
                }
                if($array[$i][0] == 0){
                    if(strlen($array[$i]) > 1){
                        $array[$i] = $array[$i][1];
                    }
                }
                $enderecoFormatado .= $array[$i].".";
            }
            $enderecoFormatado = substr($enderecoFormatado, 0, -1);
            return $enderecoFormatado;
        }

        public function isSuperadmin($perfil)
        {
            if($perfil == md5("Superadmin")){
                return true;
            }else{
                return false;
            }
        }

        public function isAdministrador($perfil)
        {
            if($perfil == md5("Administrador")){
                return true;
            }else{
                return false;
            }
        }

        public function isOperador($perfil)
        {
            if($perfil == md5("Operador")){
                return true;
            }else{
                return false;
            }
        }

        public function retornaTipoOperacao($valor)
        {
            if($valor == "N"){
                return "Normal";
            }else if($valor == "E"){
                return "Emergência";
            }
        }

        public function formataUrlImagem($url)
        {
            $array = explode('/public/', $url);
            return URL."/public/".$array[1];
        }

        public function calculaTempoTotal($entrada, $saida = null)
        {
            if($saida != null) {
                $saida = strtotime($saida);
            }else{
                $saida = strtotime($this->returnDateTime());
            }
            $entrada = strtotime($entrada);
            $total = $saida - $entrada;
            $total = gmdate('d-H-i-s', $total);
            $data = explode('-', $total);
            $dia = $data[0] - 1;
            $horas = $data[1];
            $minutos = $data[2];
            $segundos = $data[3];
            $total = $dia . 'd ' . $horas . 'h ' . $minutos . 'm ' . $segundos . 's';
            echo $total;
        }

        public function formataLogRetorno($log)
        {
            if ($log->classe == 'Câmera'){
                if(empty($log->camera)){
                    return $log->id_classe;
                }else{
                    return $log->camera;
                }
            } else if ($log->classe == "Configurações") {
                return $log->titulo;
                
            } else if ($log->classe == "Motorista") {
                return $log->motorista;
                
            }else if ($log->classe == "Empresa") {
                return $log->razao_social;
                
            }else if ($log->classe == "Veículo") {
                return $log->placa;
                
            }else if ($log->classe == "Placa") {
                return $log->placa_desc . " (". $log->placa_ip . ")";
                
            }else if ($log->classe == "Portaria") {
                return $log->portaria;
                
            }else if ($log->classe == "Usuário") {
                return $log->usu_login;
                
            } else {
                return $log->id_classe;
            }
        }

        public function retornaIPPorta($endereco, $retorno = null){
                $split = explode('http://', $endereco);
                $split2 = explode(":", $split[1]);
                $ip = $split2[0];
                $split3 = explode("/", $split2[1]);
                $porta = $split3[0];
                if($retorno == null){
                    return $ip . ':' . $porta;
                }else if($retorno == "ip"){
                    return $ip;
                }else if($retorno == "porta"){
                    return $porta;
                }
        }
    } 
?>