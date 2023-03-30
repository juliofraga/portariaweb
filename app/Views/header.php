    <?php
        $helper = new Helpers();
    ?>
    <nav class="navbar navbar-expand-md navbar-dark" aria-label="Fifth navbar example" style="background-color:black">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"> <img src="<?= URL ?>/public/assets/img/logo.png" alt="Image" height="75" width="180"></a>
            <?php
                if(isset($_SESSION['dbg_session_id']) or isset($_SESSION['dbg_session_id_cliente'])){
            ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
            <?php } ?>
            <div class="collapse navbar-collapse" id="navbarsExample05">
                <?php
                    if(isset($_SESSION['dbg_session_id']) or isset($_SESSION['dbg_session_id_cliente'])){
                ?>
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 font-calibri">
                            <?php 
                                if($_SESSION['dbg_tipo_perfil'] == md5("admin")){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('index') ?>" aria-current="page" href="<?= URL ?>/index">Home</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo('usuario/novo', "usuario/consulta") ?>" href="#" data-bs-toggle="dropdown" aria-expanded="false">Usuários</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= URL ?>/usuario/novo">Novo</a></li>
                                        <li><a class="dropdown-item" href="<?= URL ?>/usuario/consulta">Consulta</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link <?= $helper->verificaLinkAtivo('produto') ?>" aria-current="page" href="<?= URL ?>/produto">Produtos</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo('cliente/', "cliente", "cliente/desconto") ?>" href="#" data-bs-toggle="dropdown" aria-expanded="false">Cliente</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= URL ?>/cliente/">Consulta</a></li>
                                            <li><a class="dropdown-item" href="<?= URL ?>/cliente/desconto/">Desconto Cliente x Produto</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown">
                                        
                                    </li>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo('banner/novo', "banner/consulta") ?>" href="#" data-bs-toggle="dropdown" aria-expanded="false">Banners</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= URL ?>/banner/novo">Novo</a></li>
                                        <li><a class="dropdown-item" href="<?= URL ?>/banner/consulta">Consulta</a></li>
                                    </ul>
                                </li>
                            <?php 
                                }
                            ?>
                            <?php 
                                if($_SESSION['dbg_tipo_perfil'] == md5("cliente")){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('cliente/pedido') ?>" aria-current="page" href="<?= URL ?>/cliente/pedido">Fazer Pedido</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('cliente/perfil') ?>" aria-current="page" href="<?= URL ?>/cliente/perfil">Meu Perfil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('cliente/meuspedidos') ?>" aria-current="page" href="<?= URL ?>/cliente/meuspedidos">Meus Pedidos</a>
                                </li>
                            <?php
                                }
                            ?>
                            <?php 
                                if($_SESSION['dbg_tipo_perfil'] == md5("admin")){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('configuracoes') ?>" aria-current="page" href="<?= URL ?>/configuracoes">Configurações</a>
                                </li>
                            <?php
                                }
                            ?>
                            <?php 
                                if($_SESSION['dbg_tipo_perfil'] == md5("cliente")){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $helper->verificaLinkAtivo('produtos/catalogo') ?>" aria-current="page" href="<?= URL ?>/cliente/produto/catalogo">Catálogo de Produtos</a>
                                </li>
                            <?php
                                }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="<?= URL ?>/login/logoff">Sair</a>
                            </li>
                        </ul>
                <?php
                    }
                ?>
            </div>
        </div>
    </nav>