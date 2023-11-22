<?php
    $helper = new Helpers();
    $_SESSION["pw_motorista_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Motorista - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/motorista/consulta" id="form_busca_motorista" name="form_busca_motorista">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2">
                            <input class="form-control" type="text" id="filtro" name="filtro" placeholder="Nome do Motorista ou CPF" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="filtro">Nome do Motorista ou CPF</label>
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
                    <br>
                    <h6>Motoristas Cadastrados</h6>
                    <div class="row mt-4">
                        <div class="col-sm-4">
                            Nome
                        </div>
                        <div class="col-sm-2">
                            CPF
                        </div>
                        <div class="col-sm-2">
                            RG
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $motorista){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $motorista->nome_completo ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $motorista->cpf ?>
                                    </p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $motorista->rg ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $motorista->id ?>">Editar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-<?= $motorista->id ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $motorista->nome_completo ?></h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= URL ?>/motorista/alterar" id="form_altera_motorista" name="form_altera_motorista">
                                            <div class="row mt-3">
                                                <input type="hidden" id="id" name="id" value="<?= $motorista->id ?>" required>
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="nome_completo" name="nome_completo" placeholder="Nome Completo*" required value="<?= $motorista->nome_completo ?>">
                                                        <label for="nome_completo">Nome Completo*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF*" required value="<?= $motorista->cpf ?>" maxlength="18" onkeypress='mascaraMutuario(this,cpfCnpj)'>
                                                        <label for="cpf">CPF*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="rg" name="rg" placeholder="RG" value="<?= $motorista->rg ?>" maxlength="10">
                                                        <label for="rg">RG</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Data de Criação:</b> <?= $helper->formataDateTime($motorista->created_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Última Alteração:</b> <?= $helper->formataDateTime($motorista->updated_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="inline inline-block">
                                                    <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
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
                <<?php if($dados["totalMotoristas"] > 0){ ?>
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
                                Não há motoristas cadastrados no sistema ainda.
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php
                $url = URL . '/motorista/consulta';
                $paginacao = new Paginacao($dados['totalMotoristas'], $dados['paginaAtual'], $url); 
                $paginacao->view(); 
            ?>
        </div>
    </div>
</div>