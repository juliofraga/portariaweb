<?php

	// CONFIGURAÇÃO DO BANCO DE DADOS
	const DB = [
		'HOST' => 'localhost',
		'USUARIO' => 'root',
		'SENHA' => '',
		'BANCO' => 'portaria_web',
		'PORTA' => '3306'
	];

	// CONSTANTES DO SISTEMA
	define('APP', dirname(__FILE__));
	define('URL','http://localhost/portariaweb');
	define('APP_NOME','Portaria Web');
	define('APP_VERSAO','1.0.0');
	define('CREDENCIAIS_CAMERA', 'admin:13661366v@');
	define('WKHTMLTOIMAGE_INSTALACAO', 'C:/xampp/htdocs/portariaweb/public/vendor/wkhtmltopdf/bin/wkhtmltoimage');
	define('DIR_CAPTURA_IMAGENS', 'C:/xampp/htdocs/portariaweb/public/assets/img/');
	/**
	 * CONSTANTES A DEFINIR
	 * - diretório de instalação do wkhtmltoimage
	 * - diretório onde as imagens serão salvas
	 * 
	 */
?>
