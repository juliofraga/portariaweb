<?php
    $helper = new Helpers();
    $_SESSION["pw_camera_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Câmeras</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/camera/novo">Novo</a></li>
                <li class="breadcrumb-item active" aria-current="page">consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Câmeras - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/camera/consulta" id="form_busca_camera" name="form_busca_camera">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-2 mt-2">
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
                    <h6 class="mt-5">Câmeras Cadastrada</h6>
                        <div class="row mt-4">
                            <div class="col-sm-2">
                                Descrição
                            </div>
                            <div class="col-sm-5">
                                Endereço IP + Porta
                            </div>
                            <div class="col-sm-2">
                                Portaria
                            </div>
                            <div class="col-sm-1">
                                Situação
                            </div>
                        </div>
                        <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $camera){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4">
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $camera->descricao ?>
                                    </p>
                                </div>
                                <div class="col-sm-5">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $this->helper->retornaIPPorta($camera->endereco_ip) ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2">
                                        <?= $camera->portaria ?>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $this->helper->retornaSituacao($camera->situacao) ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $camera->id ?>">Editar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-<?= $camera->id ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $camera->descricao ?></h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= URL ?>/camera/alterar" id="form_altera_camera" name="form_altera_camera">
                                            <div class="row mt-3">
                                                <input type="hidden" id="id" name="id" value="<?= $camera->id ?>" required>
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required value="<?= $camera->descricao ?>">
                                                        <label for="descricao">Descrição*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="endereco_ip_<?= $camera->id ?>" name="endereco_ip" placeholder="Endereço IP (xxx.xxx.xxx.xxx)*" required value="<?= $this->helper->retornaIPPorta($camera->endereco_ip, "ip") ?>" onkeyup="formataIpAddress(<?= $camera->id ?>)">
                                                        <label for="endereco_ip">Endereço IP (xxx.xxx.xxx.xxx)*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="porta" name="porta" placeholder="Porta*" required  onkeyup="limpaSeNaoNumerico(this);" value="<?= $this->helper->retornaIPPorta($camera->endereco_ip, "porta") ?>">
                                                        <label for="porta">Porta*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Data de Criação:</b> <?= $helper->formataDateTime($camera->created_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Última Alteração:</b> <?= $helper->formataDateTime($camera->updated_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="inline inline-block">
                                                    <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                    <?php 
                                                        if($camera->situacao == 0){
                                                    ?>
                                                            <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                                    <?php 
                                                        }else if($camera->situacao == 1){
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
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Não há câmeras cadastradas no sistema ainda.
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php
                $url = URL . '/camera/consulta';
                $paginacao = new Paginacao($dados['totalCameras'], $dados['paginaAtual'], $url); 
                $paginacao->view(); 
            ?>
        </div>
    </div>
</div>