<?php
    $helper = new Helpers();
    $_SESSION["pw_veiculo_consulta"] = $dados["filtro"];
    $tipos = [
        "0" => "Não informado",
        "1" => "Carro",
        "2" => "Caminhão",
        "3" => "Moto",
        "4" => "Outro",
    ];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Veículos</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/veiculo/novo">Novo</a></li>
                <li class="breadcrumb-item active" aria-current="page">Consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Veículos - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/veiculo/consulta" id="form_busca_veiculo" name="form_busca_veiculo">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-3 mt-2 mb-5">
                            <input class="form-control" type="text" id="descricao_placa" name="descricao_placa" placeholder="Descrição ou Placa" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="descricao_placa">Descrição ou Placa</label>
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
                    <h6>Veículos Cadastradas</h6>
                    <div class="row mt-4">
                        <div class="col-sm-2">
                            Descrição
                        </div>
                        <div class="col-sm-2">
                            Placa
                        </div>
                        <div class="col-sm-2">
                            Tipo
                        </div>
                        <div class="col-sm-2">
                            Empresa
                        </div>
                        <div class="col-sm-2">
                            Situação
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $veiculo){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4">
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $veiculo->descricao ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $veiculo->placa ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $tipos[$veiculo->tipo] ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $veiculo->nome_fantasia ?>
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
                                                        <input type="text" class="form-control letraMaiuscula" id="placaVeiculo" name="placaVeiculo" placeholder="Placa*" required value="<?= $veiculo->placa ?>">
                                                        <label for="placa">Placa*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required value="<?= $veiculo->descricao ?>">
                                                        <label for="descricao">Descrição*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <select class="form-control" id="tipo" name="tipo" placeholder="Tipo" required>
                                                            <option value="1" <?= $this->helper->setSelected("1", $veiculo->tipo) ?>>Carro</option>
                                                            <option value="2" <?= $this->helper->setSelected("2", $veiculo->tipo) ?>>Caminhão</option>
                                                            <option value="3" <?= $this->helper->setSelected("3", $veiculo->tipo) ?>>Moto</option>
                                                            <option value="4" <?= $this->helper->setSelected("4", $veiculo->tipo) ?>>Outro</option>
                                                        </select>
                                                        <label for="tipo">Tipo*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <div class="form-floating">
                                                        <select class="form-control w-100" name="empresa" id="empresa" required placeholder="Empresa*">
                                                            <option value="">Selecione...</option>
                                                            <?php foreach($dados["empresas"] as $empresa){ ?>
                                                                <option value="<?= $empresa->id ?>" <?= $this->helper->setSelected($empresa->id, $veiculo->empresas_id) ?>><?= $empresa->nome_fantasia ?></option>
                                                            <?php }?>
                                                        </select>
                                                        <label for="empresa">Empresa*</label>
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
                            Não há veículos cadastrados no sistema ainda.
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>