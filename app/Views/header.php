<nav class="navbar navbar-expand-sm navbar-dark" aria-label="Third navbar example" style="background-color:black">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Portaria Web</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

        <div class="collapse navbar-collapse" id="navbarsExample03">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Painel de Operações</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Câmeras</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown03">
                        <li><a class="dropdown-item" href="#">Novo</a></li>
                        <li><a class="dropdown-item" href="#">Consulta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Empresas</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown03">
                        <li><a class="dropdown-item" href="#">Novo</a></li>
                        <li><a class="dropdown-item" href="#">Consulta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Placas</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown03">
                        <li><a class="dropdown-item" href="#">Novo</a></li>
                        <li><a class="dropdown-item" href="#">Consulta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Usuários</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown03">
                        <li><a class="dropdown-item" href="<?= URL ?>/usuario/novo">Novo</a></li>
                        <li><a class="dropdown-item" href="#">Consulta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Veículos</a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown03">
                        <li><a class="dropdown-item" href="#">Novo</a></li>
                        <li><a class="dropdown-item" href="#">Consulta</a></li>
                    </ul>
                </li>                
                <li class="nav-item">
                    <a class="nav-link" href="#">Configurações do Sistema</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Relatórios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logoff</a>
                </li>
            </ul>
             <form>
                <input class="form-control" type="text" placeholder="Buscar Placa" aria-label="Search">
            </form>
        </div>
    </div>
</nav>