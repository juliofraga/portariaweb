<?php $helper = new Helpers(); ?>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" aria-label="Third navbar example" style="background-color:black">
    <div class="container-fluid">
        <?php if($helper->sessionValidate()){?>
            <a class="navbar-brand" href="#">Portaria Web</a>
        <?php }else{ ?>
            <a class="navbar-brand" href="#"><br></a>
        <?php } ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <?php if($helper->sessionValidate()){?>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Painel de Operações</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo(['camera/novo', 'camera/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Câmeras</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL ?>/camera/novo">Novo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/camera/consulta">Consulta</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo(['empresa/nova', 'empresa/consulta', 'veiculo/novo', 'veiculo/consulta', 'pessoa/nova', 'pessoa/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Empresas</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL ?>/empresa/nova">Nova Empresa</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/empresa/consulta">Consulta Empresa</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/veiculo/novo">Novo Veículo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/veiculo/consulta">Consulta Veículo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/pessoa/consulta">Consulta Pessoa</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo(['placa/novo', 'placa/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Placas</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL ?>/placa/novo">Novo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/placa/consulta">Consulta</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo(['portaria/novo', 'portaria/consulta', 'portaria/portaria_usuario']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Portarias</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL ?>/portaria/novo">Novo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/portaria/consulta">Consulta</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/portaria/portaria_usuario">Ligação Portaria x Usuários</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $helper->verificaLinkAtivo(['usuario/novo', 'usuario/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Usuários</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL ?>/usuario/novo">Novo</a></li>
                            <li><a class="dropdown-item" href="<?= URL ?>/usuario/consulta">Consulta</a></li>
                        </ul>
                    </li>              
                    <li class="nav-item">
                        <a class="nav-link <?= $helper->verificaLinkAtivo(['configuracoes']) ?>" href="<?= URL ?>/configuracoes">Configurações do Sistema</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Relatórios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL ?>/login/logoff">Logoff</a>
                    </li>
                </ul>
                <form>
                    <input class="form-control" type="text" placeholder="Buscar Placa" aria-label="Search">
                </form>
            </div>
        <?php } ?>
    </div>
</nav>