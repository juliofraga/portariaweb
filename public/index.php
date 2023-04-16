<?php
    session_start(["session_portaria_web"]);
    include './../app/php_error.php';
    include './../app/config.php';
    include './../app/autoload.php';
    include './../app/Libraries/Helpers.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="Portaria Web">
        <meta name="Júlio Fraga" content="Portaria Web" />
        <link href="<?= URL ?>/public/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= URL ?>/public/css/navbar.css" rel="stylesheet">
        <link href="<?= URL ?>/public/css/bootstrap.min.css.map" rel="stylesheet">
        <link href="<?= URL ?>/public/css/styles.css" rel="stylesheet">
        <link href="<?= URL ?>/public/css/modals.css" rel="stylesheet">
        
        <script src="<?= URL ?>/public/js/jquery.min.js"></script>
        <script src="<?= URL ?>/public/js/jquery-3.5.1.min.js"></script>
        <script src="<?= URL ?>/public/js/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="<?= URL ?>/public/js/jquery.mask.min.js"></script>
        <title><?= APP_NOME ?></title>
        <!-- Favicon-->
        <!-- <link rel="icon" type="image/x-icon" href="/public/assets/favicon.ico" /> -->
        <!-- Bootstrap Icons-->
        <link href="<?= URL ?>/public/css/bootstrap-icons.css" rel="stylesheet" />

        <!-- MULTI SELECT -->
        <script src="<?= URL ?>/public/js/select2.min.js"></script>
        <link href="<?= URL ?>/public/css/select2.min.css" rel="stylesheet">
        <script>
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2();
            });
        </script>

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
    
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
    </head>
    <body>
        <?php 
            include '../app/Views/header.php';
            echo "<br><br><br>";
            $rotas = new Routes();
            include '../app/Views/footer.php';
        ?>
    </body>
    <!-- INCLUIR LINKS PARA OS ARQUIVOS JAVASCRIPtS, JQUERY-->
    <script src="<?= URL ?>/public/js/scripts.js"></script>
    <script src="<?= URL ?>/public/js/bootstrap.bundle.min.js"></script>
    <script src="<?= URL ?>/public/js/functions.js"></script>
    <script src="<?= URL ?>/public/vendor/ckeditor/ckeditor.js"></script>
         <!-- Bootstrap core JS-->
    <script src="<?= URL ?>/public/js/bootstrap.min.js"></script>
 </html>