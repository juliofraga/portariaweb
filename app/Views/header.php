<?php $helper = new Helpers(); ?>
<nav class="navbar navbar-expand-sm navbar-dark fixed-top" aria-label="Third navbar example" style="background-color:black">
    <div class="container-fluid">
        <?php if($helper->sessionValidate()){?>
            <a class="navbar-brand" href="<?= URL ?>">Portaria Web</a>
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
                        <a class="nav-link fonteMenu <?= $helper->verificaLinkAtivo(['painel', 'painel/']) ?>" aria-current="page" href="<?= URL ?>/painel">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                </svg>
                            </center>
                            Painel
                        </a>
                    </li>
                    <?php if($_SESSION['pw_tipo_perfil'] == md5("Superadmin") or $_SESSION['pw_tipo_perfil'] == md5("Administrador")){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link fonteMenu dropdown-toggle <?= $helper->verificaLinkAtivo(['camera/novo', 'camera/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-camera-video" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5zm11.5 5.175 3.5 1.556V4.269l-3.5 1.556v4.35zM2 4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H2z"/>
                                </svg>
                            </center>
                        Câmeras
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/camera/novo">Novo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/camera/consulta">Consulta</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link fonteMenu dropdown-toggle <?= $helper->verificaLinkAtivo(['empresa/nova', 'empresa/consulta', 'veiculo/novo', 'veiculo/consulta', 'motorista/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z"/>
                                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z"/>
                                </svg>
                            </center>
                            Empresas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/empresa/nova">Nova Empresa</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/empresa/consulta">Consulta Empresa</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/veiculo/novo">Novo Veículo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/veiculo/consulta">Consulta Veículo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/motorista/consulta">Consulta Motorista</a></li>
                        </ul>
                    </li>
                    <?php if($_SESSION['pw_tipo_perfil'] == md5("Superadmin") or $_SESSION['pw_tipo_perfil'] == md5("Administrador")){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link fonteMenu dropdown-toggle <?= $helper->verificaLinkAtivo(['placa/novo', 'placa/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-motherboard" viewBox="0 0 16 16">
                                    <path d="M11.5 2a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5Zm2 0a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5Zm-10 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6Zm0 2a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6ZM5 3a1 1 0 0 0-1 1h-.5a.5.5 0 0 0 0 1H4v1h-.5a.5.5 0 0 0 0 1H4a1 1 0 0 0 1 1v.5a.5.5 0 0 0 1 0V8h1v.5a.5.5 0 0 0 1 0V8a1 1 0 0 0 1-1h.5a.5.5 0 0 0 0-1H9V5h.5a.5.5 0 0 0 0-1H9a1 1 0 0 0-1-1v-.5a.5.5 0 0 0-1 0V3H6v-.5a.5.5 0 0 0-1 0V3Zm0 1h3v3H5V4Zm6.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2Z"/>
                                    <path d="M1 2a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-2H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 9H1V8H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6H1V5H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 2H1Zm1 11a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v11Z"/>
                                </svg>
                            </center>
                            Placas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/placa/novo">Novo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/placa/consulta">Consulta</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] != md5("Operador")){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link fonteMenu dropdown-toggle <?= $helper->verificaLinkAtivo(['portaria/novo', 'portaria/consulta', 'portaria/portaria_usuario']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                                    <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                                    <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z"/>
                                </svg>
                            </center>
                            Portarias
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/portaria/novo">Novo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/portaria/consulta">Consulta</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/portaria/portaria_usuario">Ligação Portaria x Usuários</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] != md5("Operador")){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link fonteMenu dropdown-toggle <?= $helper->verificaLinkAtivo(['usuario/novo', 'usuario/consulta']) ?>" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                    <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                                </svg>
                            </center>
                            Usuários
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/usuario/novo">Novo</a></li>
                            <li><a class="dropdown-item fonteMenu" href="<?= URL ?>/usuario/consulta">Consulta</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] != md5("Operador")){ ?>
                    <li class="nav-item">
                        <a class="nav-link fonteMenu <?= $helper->verificaLinkAtivo(['configuracoes']) ?>" href="<?= URL ?>/configuracoes">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                </svg>
                            </center>
                            Configurações
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] == md5("Superadmin")){ ?>
                    <li class="nav-item">
                        <a class="nav-link fonteMenu" href="#">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-file-earmark-post" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                    <path d="M4 6.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-7zm0-3a.5.5 0 0 1 .5-.5H7a.5.5 0 0 1 0 1H4.5a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </center>
                            Logs
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] != md5("Operador") or $_SESSION["opeViewCons"] == true){ ?>
                    <li class="nav-item">
                        <a class="nav-link fonteMenu <?= $helper->verificaLinkAtivo(['consultas', 'consultas/detalhada']) ?>" href="<?= URL ?>/consultas">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </center>
                            Consultas
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['pw_tipo_perfil'] == md5("Operador")){ ?>
                    <li class="nav-item">
                        <a class="nav-link fonteMenu <?= $helper->verificaLinkAtivo(['usuario/perfil']) ?>" href="<?= URL ?>/usuario/perfil">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </center>
                            Perfil
                        </a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link fonteMenu" href="<?= URL ?>/login/logoff">
                            <center>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
                                </svg>
                            </center>
                            Logoff
                        </a>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
</nav>