<?php

	// CONFIGURAÇÃO DO BANCO DE DADOS
	const DB = [
		'HOST' => 'localhost',
		'USUARIO' => 'root',
		'SENHA' => '',
		'BANCO' => 'db_portariaweb',
		'PORTA' => '3306'
	];

	// CONSTANTES DO SISTEMA
	define('APP', dirname(__FILE__));
	define('URL','http://localhost/portariaweb');
	define('SYSTEM_ENVIRONMENT', 'WINDOWS');
	define('APP_NOME','Portaria Web');
	define('APP_VERSAO','1.0.0 - Beta');
	define('CREDENCIAIS_CAMERA', 'admin:13661366v@');
	define('TIMEZONE', 'America/Sao_Paulo');
	define('USA_BALANCA', false);
	define('CONFIGURACOES_ADMIN', [6, 7, 8, 9, 10]);
	if(SYSTEM_ENVIRONMENT == 'WINDOWS'){
		define('LOGS', 'C:/xampp/htdocs/portariaweb/app/Logs/'.date('M_Y').'.txt');
		define('WKHTMLTOIMAGE_INSTALACAO', 'C:/xampp/htdocs/portariaweb/public/vendor/wkhtmltopdf/bin/wkhtmltoimage');
		define('DIR_CAPTURA_IMAGENS', 'C:/xampp/htdocs/portariaweb/public/assets/img/');
	}else if(SYSTEM_ENVIRONMENT == 'LINUX'){
		define('LOGS', '/var/www/html/portariaweb/app/Logs/'.date('M_Y').'.txt');
		define('WKHTMLTOIMAGE_INSTALACAO', 'wkhtmltoimage');
		define('DIR_CAPTURA_IMAGENS', '/var/www/html/portariaweb/public/assets/img/');
	}
	define('INSTANCIA', 'desenvolvimento');
	
?>

<!--

COMANDOS P/ INSTALAR wkhtmltoimage no Linux:
# sudo apt install wget
# wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.jammy_amd64.de
# sudo apt install -f ./wkhtmltox_0.12.6.1-2.jammy_amd64.deb

-->