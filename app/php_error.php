<?php

	error_reporting(1);
	ini_set('error_reporting', 1);

	function phpErro($erro, $mensagem, $arquivo, $linha)
	{
		if($_SESSION['pw_grava_logs_erros_php']){
			gravaLogPHPError($erro, $mensagem, $arquivo, $linha);
		}
		switch ($erro){
			case 2;
				$css = 'alert-warning';
				break;
			case 8;
				$css = 'alert-primary';
				break;
			case 1;
			case 256;
			case 2002;
			case 1045;
			case 1049;
				$css = 'alert-danger';
				break;
			default:
				$css = '';
		}
		$texto = "file_get_contents";
		if(!preg_match("/{$texto}/", $mensagem)){
			if($erro != 2){
				echo "<p class=\"alert {$css} m-2\"><b>Erro:</b> {$mensagem} <b>no arquivo</b> {$arquivo} <b>na linha</b> <strong class=\"text-danger\">{$linha}</strong></p>";
			}
		}

		if ($erro == 1 || $erro == 256){
			die();
		}
	}

	function gravaLogPHPError($erro, $mensagem, $arquivo, $linha)
	{
		date_default_timezone_set(TIMEZONE);
		$date = date('[Y-m-d Y:i:s]');
		$msg = "$date - Erro: $erro, Mensagem: $mensagem. Arquivo $arquivo, Linha $linha \n";
		$arquivo = "logs/".date('M_Y').".txt";
		$fp = fopen($arquivo, "a+");
		fwrite($fp, $msg);
		fclose($fp);
	}

	//set_error_handler — Define uma função do usuário para manipular erros
	set_error_handler('phpErro');
?>
