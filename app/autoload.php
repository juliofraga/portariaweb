<?php

	// Responsável pelo carregamento automático das classes
 
	spl_autoload_register(function ($classe){
		$dir = [
			'Libraries',
		];
		
		foreach($dir as $diretorio){
			$arquivo = (__DIR__.DIRECTORY_SEPARATOR.$diretorio.DIRECTORY_SEPARATOR.$classe.'.php');
			if(file_exists($arquivo))
				require_once $arquivo;
		}

	});
?>