<?php
    $helper = new Helpers();
    $_SESSION["pw_portaria_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Portarias</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/novo">Novo</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/portaria_usuario">Ligação Portaria x Usuários</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/ligacao_portaria">Ligação Portaria x Portaria</a></li>
                <li class="breadcrumb-item active" aria-current="page">Consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Portarias - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/portaria/consulta" id="form_busca_portaria" name="form_busca_portaria">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2 mb-5">
                            <input class="form-control" type="text" id="descricao_ip" name="descricao" placeholder="Descrição" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="descricao">Descrição</label>
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
            <?php
                if(count($dados["dados"]) > 0){
            ?>
                    <h6>Portarias Cadastradas</h6>
                    <div class="row mt-4">
                        <div class="col-sm-4">
                            Descrição
                        </div>
                        <div class="col-sm-4">
                            Placa
                        </div>
                        <div class="col-sm-2">
                            Situação
                        </div>
                    </div>
                    <?php
                        $portariaTemp = "";
                        foreach($dados["dados"] as $portaria){
                            if($portariaTemp != $portaria->id){
                                $portariaTemp = $portaria->id;
                    ?>  
                            <hr class="divisor_horizontal">
                            <div class="addHoverLine">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="pb-1 mb-0 large">
                                            <?= $portaria->descricao ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="pb-1 mb-0 large">
                                            <?= $portaria->placa ?> - <?= $portaria->ip_placa ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="pb-1 mb-0 large">
                                            <?= $this->helper->retornaSituacao($portaria->situacao) ?>
                                        </p>
                                    </div>
                                     <div class="col-sm-12">
                                        <?php
                                            $cameras = "";
                                            foreach($dados["dados"] as $camera){
                                                if($portaria->id == $camera->id){
                                                    $cameras .= "$camera->camera ($camera->ip_camera) - ";
                                                }
                                            }
                                            $cameras = substr($cameras, 0, -2);
                                        ?>
                                        <i><b>Câmeras: </b><?= $cameras == " () " ? "" : $cameras ?></i>
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <p class="pb-1 mb-0 large">
                                            <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $portaria->id ?>">Editar</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="modal fade" id="modal-<?= $portaria->id ?>">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?= $portaria->descricao ?></h4>
                                            <button type="button" class="btn-close" data-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="<?= URL ?>/portaria/alterar" id="form_altera_portaria" name="form_altera_portaria">
                                                <div class="row mt-3">
                                                    <input type="hidden" id="id" name="id" value="<?= $portaria->id ?>" required>
                                                    <div class="col-sm-12">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required value="<?= $portaria->descricao ?>">
                                                            <label for="descricao">Descrição*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-floating mt-3">
                                                            <select name="placa" id="placa" class="form-control">
                                                                <option value="">Selecione...</option>
                                                                <?php if(isset($portaria->placas_id)){ ?>
                                                                    <option value="<?= $portaria->placas_id ?>" selected><?= $portaria->placa ?> - <?= $portaria->ip_placa ?></option>
                                                                <?php }?>
                                                                <?php foreach($dados["placas"] as $placa){ ?>
                                                                    <option value="<?= $placa->id ?>"><?= $placa->descricao ?> - <?= $placa->endereco_ip ?></option>
                                                                <?php }?>
                                                            </select>
                                                            <label for="placa">Placa</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-sm-12">
                                                        <label for="placa">Câmeras Entrada</label>
                                                        <select class="form-control" name="cameraEntrada[]" id="cameraEntrada"  multiple="multiple">
                                                            <option value = "">Selecione...</option>
                                                            <?php 
                                                                foreach($dados["dados"] as $cameraSelect){
                                                                    if($portaria->id == $cameraSelect->id and isset($cameraSelect->camera_id) and $cameraSelect->entrada_saida == "E"){
                                                                ?>
                                                                <option value="<?= $cameraSelect->camera_id ?>" selected><?= $cameraSelect->camera ?> - <?= $cameraSelect->ip_camera ?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                            <?php foreach($dados["cameras"] as $camera){ ?>
                                                                <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-sm-12">
                                                        <label for="placa">Câmeras Saída</label>
                                                        <select class="form-control" name="cameraSaida[]" id="cameraSaida"  multiple="multiple">
                                                            <option value = "">Selecione...</option>
                                                            <?php 
                                                                foreach($dados["dados"] as $cameraSelect){
                                                                    if($portaria->id == $cameraSelect->id and isset($cameraSelect->camera_id) and $cameraSelect->entrada_saida == "S"){
                                                                ?>
                                                                <option value="<?= $cameraSelect->camera_id ?>" selected><?= $cameraSelect->camera ?> - <?= $cameraSelect->ip_camera ?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                            <?php foreach($dados["cameras"] as $camera){ ?>
                                                                <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="mt-3">
                                                            <b>Data de Criação:</b> <?= $helper->formataDateTime($portaria->created_at) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="mt-3">
                                                            <b>Última Alteração:</b> <?= $helper->formataDateTime($portaria->updated_at) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="inline inline-block">
                                                        <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                        <?php 
                                                            if($portaria->situacao == 0){
                                                        ?>
                                                                <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                                        <?php 
                                                            }else if($portaria->situacao == 1){
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
                            Não há portarias cadastradas no sistema ainda.
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>