<?php 
    $helper = new Helpers();
    $perfil = $_SESSION['pw_tipo_perfil'];
?>
<body>
    <?php
        if($helper->isAdministrador($perfil) or $helper->isSuperadmin($perfil)){
    ?>
            <div id="page-not-found">
                <h3>PORTARIA NÃO CADASTRADA</h3>
                <H6>NÃO EXISTEM PORTARIAS CADASTRADAS NO SISTEMA</H6>
            </div>
    <?php
        }else{
    ?>
            <div id="page-not-found">
                <h3>FALTA DE PERMISSÃO DE ACESSO</h3>
                <H6>CONTATE O ADMINISTRADOR DO SISTEMA PARA QUE ELE LHE DÊ O ACESSOS NECESSÁRIOS PARA A UTILIZAÇÃO DO SISTEMA</H6>
            </div>
    <?php } ?>
</body>