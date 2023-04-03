<?php
    $helper = new Helpers();
    $_SESSION["pw_veiculo_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Veículos - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/veiculo/consulta" id="form_busca_veiculo" name="form_busca_veiculo">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2 mb-5">
                            <input class="form-control" type="text" id="descricao_ip" name="descricao_ip" placeholder="Descrição ou Endereço IP" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="descricao_ip">Descrição ou Endereço IP</label>
                        </div>
                    </div>
                    <div class="col-sm-2 mt-2 mb-5" style="padding-top: 6px;">
                        <button class="w-100 btn btn-secondary btn-lg" type="submit" name="buscar">Buscar</button>
                    </div>
                    <div class="col-sm-2 mt-2 mb-5" style="padding-top: 6px;">
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
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-<?= $tipo ?>" role="alert">
                            <?= $_SESSION["pw_mensagem"] ?>
                        </div>
                    </div>
                </div>
            <?php 
                }
            ?>
            <h6>Veículos Cadastradas</h6>
            <div class="row mt-4">
                <div class="col-sm-3">
                    Descrição
                </div>
                <div class="col-sm-2">
                    Endereço IP
                </div>
                <div class="col-sm-3">
                    Portaria
                </div>
                <div class="col-sm-2">
                    Situação
                </div>
            </div>
            <hr class="divisor_horizontal">
            <?php 
                foreach($dados["dados"] as $veiculo){
            ?>
                <div class="row mt-4">
                    <div class="col-sm-3">
                        <p class="pb-1 mb-0 large border-bottom mt-2 ">
                            <?= $veiculo->descricao ?>
                        </p>
                    </div>
                    <div class="col-sm-2">
                        <p class="pb-1 mb-0 large border-bottom mt-2 ">
                            <?= $veiculo->endereco_ip ?>
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <p class="pb-1 mb-0 large border-bottom mt-2 ">
                            <?= $veiculo->portaria ?>
                        </p>
                    </div>
                    <div class="col-sm-2">
                        <p class="pb-1 mb-0 large border-bottom mt-2 ">
                            <?= $this->helper->retornaSituacao($veiculo->situacao) ?>
                        </p>
                    </div>
                    <div class="col-sm-2">
                        <p class="pb-1 mb-0 large border-bottom mt-2 ">
                            <a class="btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $veiculo->id ?>">Editar</a>
                        </p>
                    </div>
                </div>
                <div class="modal fade" id="modal-<?= $veiculo->id ?>">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?= $veiculo->descricao ?></h4>
                                <button type="button" class="btn-close" data-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?= URL ?>/veiculo/alterar" id="form_altera_veiculo" name="form_altera_veiculo">
                                    <div class="row mt-3">
                                        <input type="hidden" id="id" name="id" value="<?= $veiculo->id ?>" required>
                                        <div class="col-sm-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required value="<?= $veiculo->descricao ?>">
                                                <label for="descricao">Descrição*</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-floating mt-3">
                                                <input type="text" class="form-control" id="endereco_ip" name="endereco_ip" placeholder="Endereço IP (xxx.xxx.xxx.xxx)*" required value="<?= $veiculo->endereco_ip ?>">
                                                <label for="endereco_ip">Endereço IP (xxx.xxx.xxx.xxx)*</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-floating mt-3">
                                                <input type="text" class="form-control" id="url_foto" name="url_foto" placeholder="URL Foto*" required value="<?= $veiculo->url_foto ?>">
                                                <label for="url_foto">URL Foto*</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-floating mt-3">
                                                <input type="text" class="form-control" id="url_video" name="url_video" placeholder="URL Vídeo*" required value="<?= $veiculo->url_video ?>">
                                                <label for="url_video">URL Vídeo*</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-floating mt-3">
                                                <select name="portaria" id="portaria" class="form-control">
                                                    <option value="">Selecione...</option>
                                                    <?php foreach($dados["portoes"] as $portaria){ ?>
                                                        <option value="<?= $portaria->id ?>" <?= $this->helper->setSelected($portaria->id, $veiculo->portoes_id) ?>>
                                                            <?= $portaria->descricao ?>
                                                        </option>
                                                    <?php }?>
                                                </select>
                                                <label for="portaria">Portaria</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mt-3">
                                                <b>Data de Criação:</b> <?= $helper->formataDateTime($veiculo->created_at) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mt-3">
                                                <b>Última Alteração:</b> <?= $helper->formataDateTime($veiculo->updated_at) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="inline inline-block">
                                            <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                            <?php 
                                                if($veiculo->situacao == 0){
                                            ?>
                                                    <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                            <?php 
                                                }else if($veiculo->situacao == 1){
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
        </div>
    </div>
</div>