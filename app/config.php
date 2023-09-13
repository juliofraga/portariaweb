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
	define('APP_NOME','Portaria Web');
	define('APP_VERSAO','1.0.0 - Beta');
	define('CREDENCIAIS_CAMERA', 'admin:13661366v@');
	define('TIMEZONE', 'America/Sao_Paulo');
	define('WKHTMLTOIMAGE_INSTALACAO', 'C:/xampp/htdocs/portariaweb/public/vendor/wkhtmltopdf/bin/wkhtmltoimage');
	define('DIR_CAPTURA_IMAGENS', 'C:/xampp/htdocs/portariaweb/public/assets/img/');
	define('USA_BALANCA', false);
	define('CONFIGURACOES_ADMIN', [6, 7, 8, 9, 10]);
	define('LOGS', 'C:/xampp/htdocs/portariaweb/app/Logs/'.date('M_Y').'.txt');
	define('INSTANCIA', 'desenvolvimento');
	
?>
