<?php

echo "Iniciando script\n";

$cliente = $argv[1];
if (!$cliente) {
    echo "Cliente não pode ser vazio, encerrando o script\n";
    exit;
}
try {
    $dirBase = "/var/www/html/portariaweb/";
    $dirCliente = "/var/www/html/" . $cliente . "-portariaweb/";
    // inserir verificação se o cliente já existe
    echo "Criando diretório do cliente\n";
    exec("mkdir " . $dirCliente, $ret, $return);
    if($return === 1){
        echo "Não foi possível criar novo cliente, já existe um cliente com este nome.\n";
        echo "Script encerrado\n";
        exit;
    }
    
    echo "Criando diretórios e arquivos públicos\n";
    exec("mkdir " . $dirCliente . "/public");
    exec("chmod 777 " . $dirCliente . "/public");
    exec("cp " . $dirBase . "/.htaccess " . $dirCliente . "/.htaccess");
    exec("cp -r " . $dirBase . "/public/assets " . $dirCliente . "/public/assets");
    exec("chmod 777 " . $dirCliente . "/public/assets/img");
    exec("ln -s " . $dirBase . "/public/css " . $dirCliente . "/public/css");
    exec("ln -s " . $dirBase . "/public/js " . $dirCliente . "/public/js");
    exec("ln -s " . $dirBase . "/public/vendor " . $dirCliente . "/public/vendor");
    exec("cp " . $dirBase . "/public/.htaccess " . $dirCliente . "/public/.htaccess");
    exec("cp " . $dirBase . "/public/index.php " . $dirCliente . "/public/index.php");

    echo "Criando diretórios e arquivos app\n";
    exec("mkdir " . $dirCliente . "/app");
    exec("ln -s " . $dirBase . "/app/Controllers " . $dirCliente . "/app/Controllers");
    exec("ln -s " . $dirBase . "/app/Libraries " . $dirCliente . "/app/Libraries");
    exec("ln -s " . $dirBase . "/app/Models " . $dirCliente . "/app/Models");
    exec("ln -s " . $dirBase . "/app/Views " . $dirCliente . "/app/Views");
    exec("ln -s " . $dirBase . "/app/autoload.php " . $dirCliente . "/app/autoload.php");
    exec("ln -s " . $dirBase . "/app/php_error.php " . $dirCliente . "/app/php_error.php");

    echo "Criando arquivos e diretórios de logs e configurações\n";
    exec("mkdir " . $dirCliente . "/app/Logs");
    exec("chmod 777 " . $dirCliente . "/app/Logs");
    exec("cp " . $dirBase . "/app/config.php " . $dirCliente  . "/app/config.php");
    exec("cp " . $dirBase . "/app/.htaccess " . $dirCliente  . "/app/.htaccess");

    echo "Criando Banco de Dados\n";
    criaArquivoSQLTemp($cliente);
    exec("mysql -u root -p'vsfoleoplan' < cria_base_" . $cliente . ".sql" );
    exec("mysql -u root -p'vsfoleoplan' db_portariaweb_" . $cliente . " < db_portariaweb.sql" );
    exec("rm cria_base_" . $cliente . ".sql");
    echo "Banco de dados criado com sucesso!\n";
    
    echo "Atualizando arquivos de configuração\n";
    atualizaArquivos($cliente);
    echo "Arquivos atualizados com sucesso!\n";

    echo "Script finalizado com sucesso!\n";
} catch (\Throwable $th) {
    echo "Não foi possível finalizar o script\n";
    echo $th;
}

function atualizaArquivos($cliente) {
    // Atualizando config
    $config = fopen("/var/www/html/" . $cliente . "-portariaweb/app/config.php", 'r+');
    $configFile = file_get_contents("/var/www/html/" . $cliente . "-portariaweb/app/config.php");
    $configFile = str_replace("db_portariaweb", "db_portariaweb_" . $cliente, $configFile);
    $configFile = str_replace("//define('CLIENTE', '');", "define('CLIENTE', '" . $cliente . "');", $configFile);
    rewind($config);
    ftruncate($config, 0);
    fwrite($config, $configFile);
    fclose($config);

    // Atualizando .htaccess
    $htaccess = fopen("/var/www/html/" . $cliente . "-portariaweb/public/.htaccess", 'r+');
    $htaccessFile = file_get_contents("/var/www/html/" . $cliente . "-portariaweb/public/.htaccess");
    $htaccessFile = str_replace("RewriteBase /portariaweb/public", "RewriteBase /" . $cliente . "-portariaweb/public", $htaccessFile);
    rewind($htaccess);
    ftruncate($htaccess, 0);
    fwrite($htaccess, $htaccessFile);
    fclose($htaccess);
}

function criaArquivoSQLTemp($cliente) {
    $arquivo = fopen('cria_base_' . $cliente . '.sql','w');
    if ($arquivo == false) die('Não foi possível criar o arquivo.');
    $sql = "CREATE DATABASE db_portariaweb_" . $cliente . " CHARACTER SET utf8 COLLATE utf8_general_ci;";
    fwrite($arquivo, $sql);
    fclose($arquivo); 
}

?>