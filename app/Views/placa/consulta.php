<?php
    $helper = new Helpers();
    $_SESSION["pw_placa_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Placas</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/placa/novo">Novo</a></li>
                <li class="breadcrumb-item active" aria-current="page">Consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Placas - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/placa/consulta" id="form_busca_placa" name="form_busca_placa">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2">
                            <input class="form-control" type="text" id="descricao_ip" name="descricao_ip" placeholder="Descrição ou Endereço IP" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="descricao_ip">Descrição ou Endereço IP</label>
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
                    <h6>Placas Cadastradas</h6>
                    <div class="row mt-4">
                        <div class="col-sm-3">
                            Descrição
                        </div>
                        <div class="col-sm-2">
                            Endereço IP
                        </div>
                        <div class="col-sm-1">
                            Porta
                        </div>
                        <div class="col-sm-2">
                            Portaria
                        </div>
                        <div class="col-sm-2">
                            Situação
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $placa){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4 border-bottom mt-2">
                                <div class="col-sm-3">
                                    <p class="pb-1 mb-0 large">
                                        <?= $placa->descricao ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large">
                                        <?= $placa->endereco_ip ?>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="pb-1 mb-0 large">
                                        <?= $placa->porta ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large">
                                        <?= $placa->portaria ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large">
                                        <?= $this->helper->retornaSituacao($placa->situacao) ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large">
                                        <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $placa->id ?>">Editar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-<?= $placa->id ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $placa->descricao ?></h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= URL ?>/placa/alterar" id="form_altera_camera" name="form_altera_camera">
                                            <div class="row mt-3">
                                                <input type="hidden" id="id" name="id" value="<?= $placa->id ?>" required>
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required value="<?= $placa->descricao ?>">
                                                        <label for="descricao">Descrição*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-9">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="endereco_ip" name="endereco_ip" placeholder="Endereço IP (xxx.xxx.xxx.xxx)*" required value="<?= $placa->endereco_ip ?>">
                                                        <label for="endereco_ip">Endereço IP (xxx.xxx.xxx.xxx)*</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="porta" name="porta" placeholder="Porta*" required value="<?= $placa->porta ?>">
                                                        <label for="porta">Porta*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-floating mt-3">
                                                        <select class="form-control" id="rele_abre_cancela" name="rele_abre_cancela" placeholder="Relé Abre Cancela*" required>
                                                            <option value="r1" <?= $helper->setSelected('r1', $placa->rele_abre_cancela) ?>>Relé 1</option>
                                                            <option value="r2" <?= $helper->setSelected('r2', $placa->rele_abre_cancela) ?>>Relé 2</option>
                                                            <option value="r3" <?= $helper->setSelected('r3', $placa->rele_abre_cancela) ?>>Relé 3</option>
                                                            <option value="r4" <?= $helper->setSelected('r4', $placa->rele_abre_cancela) ?>>Relé 4</option>
                                                        </select>
                                                        <label for="rele_abre_cancela">Relé Abre Cancela*</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-floating mt-3">
                                                        <select class="form-control" id="rele_fecha_cancela" name="rele_fecha_cancela" placeholder="Relé Fecha Cancela*" required>
                                                            <option value="r1" <?= $helper->setSelected('r1', $placa->rele_fecha_cancela) ?>>Relé 1</option>
                                                            <option value="r2" <?= $helper->setSelected('r2', $placa->rele_fecha_cancela) ?>>Relé 2</option>
                                                            <option value="r3" <?= $helper->setSelected('r3', $placa->rele_fecha_cancela) ?>>Relé 3</option>
                                                            <option value="r4" <?= $helper->setSelected('r4', $placa->rele_fecha_cancela) ?>>Relé 4</option>
                                                        </select>
                                                        <label for="rele_fecha_cancela">Relé Fecha Cancela*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Data de Criação:</b> <?= $helper->formataDateTime($placa->created_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Última Alteração:</b> <?= $helper->formataDateTime($placa->updated_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="inline inline-block">
                                                    <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                    <?php 
                                                        if($placa->situacao == 0){
                                                    ?>
                                                            <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                                    <?php 
                                                        }else if($placa->situacao == 1){
                                                    ?>
                                                            <input type="submit" class="btn btn-success" style="margin-top:40px;" name="ativar" id="ativar" value="Ativar">
                                                    <?php 
                                                        }
                                                    ?>
                                                    <input type="submit" class="btn btn-danger" style="margin-top:40px;" name="deletar" id="deletar" value="Deletar">
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
                <?php if($dados["totalPlacas"] > 0){ ?>
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
                                Não há placas cadastradas no sistema ainda.
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php
                $url = URL . '/placa/consulta';
                $paginacao = new Paginacao($dados['totalPlacas'], $dados['paginaAtual'], $url); 
                $paginacao->view(); 
            ?>
        </div>
    </div>
</div>