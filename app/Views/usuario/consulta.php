<?php
    $helper = new Helpers();
    $_SESSION["pw_usuario_consulta"] = $dados["nome"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/usuario/novo">Novo</a></li>
                <li class="breadcrumb-item active" aria-current="page">Consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Usuário - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/usuario/consulta" id="form_busca_usuario" name="form_busca_usuario">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2">
                            <input class="form-control" type="text" id="nome_usuario" name="nome_usuario" placeholder="Nome do Usuário" value="<?= $dados["nome"] != null ? $dados["nome"] : '' ?>"/>
                            <label for="usuario">Nome do Usuário</label>
                        </div>
                    </div>
                    <div class="col-sm-2 mt-2" style="padding-top: 6px;">
                        <button class="w-100 btn btn-secondary btn-lg" type="submit" name="buscar">Buscar</button>
                    </div>
                    <div class="col-sm-2 mt-2" style="padding-top: 6px;">
                        <button class="w-100 btn btn-warning btn-lg" type="submit" name="limpar">Limpar</button>
                    </div>
                </div>
            </form>
            <?php 
                if(isset($_SESSION["pw_rotina"])){
                    if($_SESSION["pw_tipo"] == "error")
                        $tipo = "danger";
                    else if($_SESSION["pw_tipo"] == "success")
                        $tipo = "success";
                    else if($_SESSION["pw_tipo"] == "warning")
                        $tipo = "warning";
            ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-<?= $tipo ?>" role="alert">
                            <?= $_SESSION["pw_mensagem"] ?>
                        </div>
                    </div>
                </div>
            <?php 
                }
            ?>
            <?php
                if(count($dados["dados"]) > 0){
            ?>
                    <h6 class="mt-3">Usuários Cadastrados</h6>
                    <div class="row mt-4">
                        <div class="col-sm-3">
                            Nome Completo
                        </div>
                        <div class="col-sm-3">
                            Login
                        </div>
                        <div class="col-sm-2">
                            Perfil
                        </div>
                        <div class="col-sm-2">
                            Situação
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $usuarios){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4">
                                <div class="col-sm-3">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $usuarios->nome ?>
                                    </p>
                                </div>
                                <div class="col-sm-3">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $usuarios->login ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $usuarios->perfil ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $this->helper->retornaSituacao($usuarios->situacao) ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $usuarios->id ?>">Editar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-<?= $usuarios->id ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $usuarios->nome ?></h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= URL ?>/usuario/alterar" id="form_altera_usuario" name="form_altera_usuario">
                                            <div class="row mt-3">
                                                <input type="hidden" id="id" name="id" value="<?= $usuarios->id ?>" required>
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo*" required value="<?= $usuarios->nome ?>">
                                                        <label for="nome">Nome Completo*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="email" class="form-control" id="login" name="login" placeholder="Login*" value="<?= $usuarios->login ?>" disabled required>
                                                        <label for="login">Login*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <select class="form-control" id="perfil" name="perfil" required>
                                                            <option value="Administrador" <?= $helper->setSelected($usuarios->perfil, "Administrador") ?>>Administrador</option>
                                                            <option value="Operador" <?= $helper->setSelected($usuarios->perfil, "Operador") ?>>Operador</option>
                                                        </select>
                                                        <label for="perfil">Perfil*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" value="">
                                                        <label for="senha">Senha</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="password" class="form-control" id="repeteSenha" name="repetesenha" placeholder="Repetir Senha" value="">
                                                        <label for="repeteSenha">Repetir Senha</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b> Último Acesso: </b> <?= $helper->formataDateTime($usuarios->ultimo_acesso) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Data de Criação:</b> <?= $helper->formataDateTime($usuarios->created_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Última Alteração:</b> <?= $helper->formataDateTime($usuarios->updated_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="inline inline-block">
                                                    <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                    <?php 
                                                        if($usuarios->situacao == 0){
                                                    ?>
                                                            <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                                    <?php 
                                                        }else if($usuarios->situacao == 1){
                                                    ?>
                                                            <input type="submit" class="btn btn-success" style="margin-top:40px;" name="ativar" id="ativar" value="Ativar">
                                                    <?php 
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                        $_SESSION["pw_rotina"] = null;
                    ?>
            <?php }else{ ?>
                <?php if($dados["totalUsuarios"] > 0){ ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                Nenhum registro encontrado.
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                Não há usuários cadastrados no sistema ainda.
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php
                $url = URL . '/usuario/consulta';
                $paginacao = new Paginacao($dados['totalUsuarios'], $dados['paginaAtual'], $url); 
                $paginacao->view(); 
            ?>
        </div>
    </div>
</div>