<?php 

$helper = new Helpers();

?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Portarias</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/consulta">Consulta</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/novo">Novo</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/portaria/portaria_usuario">Ligação Portaria x Usuários</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ligação Portaria x Portaria</a></li>
                
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Ligação Portaria x Portaria</h1>
                <hr class="divisor_horizontal">
                <div id="formUserAdmin">
                    <?php 
                        if(isset($_SESSION["pw_rotina"]) and $_SESSION["pw_tipo"] == 'success'){
                    ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION["pw_mensagem"] ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                        if(isset($_SESSION["pw_rotina"]) and $_SESSION["pw_tipo"] == 'error'){
                    ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION["pw_mensagem"] ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    ?>
                    <?php
                        if(count($dados["portarias"]) > 0){
                    ?>
                            <form name="form_cad_ligacao_portaria" id="form_cad_ligacao_portaria" method="POST" action="<?= URL ?>/portaria/ligar_portaria_portaria/">
                                <div class="row mt-4">
                                    <div class="col-sm-2">
                                        <select name="portaria_0" id="portaria_0" class="js-example-basic-multiple w-100" required onchange="exibeCamposLigacaoPortaria()">
                                            <option value="">Selecione...</option>
                                            <?php foreach($dados["portarias"] as $portaria){ ?>
                                                <option value="<?= $portaria->id ?>"><?= $portaria->descricao ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="portaria">Portaria*</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="portaria_1" id="portaria_1" class="js-example-basic-multiple w-100" required onchange="exibeCamposLigacaoPortaria()">
                                            <option value="">Selecione...</option>
                                            <?php foreach($dados["portarias"] as $portaria){ ?>
                                                <option value="<?= $portaria->id ?>"><?= $portaria->descricao ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="portaria">Portaria*</label>
                                    </div>
                                    <div class="col-sm-6" id="camposLigacaoPortaria" style="display:none;">
                                        <div class="col-sm-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="tipo_0" name="tipo_0" checked>
                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                    <label id="lb_portaria_1"></label> pode sair na portaria <label id="lb_portaria_2"></label>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="tipo_1" name="tipo_1" checked>
                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                    <label id="lbportaria_2"></label> pode sair na portaria <label id="lbportaria_1"></label>
                                                </label>
                                            </div>          
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="w-100 btn btn-warning btn-lg" name="cadastrar" id="cadastrar" value="cadastrar" type="button" onclick="validaLigacaoPortaria();">Cadastrar</button>
                                    </div>
                                </div>
                                <div class="row mt-2" id="alertaErrorPortariaSelecionada" style="display: none">
                                    <div class="col-sm-12 mt-2">
                                        <div class="alert alert-danger" role="alert">
                                            É necessário selecionar as duas portarias para que a ligação seja efetuada.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="alertaErrorPortariaIgual" style="display: none">
                                    <div class="col-sm-12 mt-2">
                                        <div class="alert alert-danger" role="alert">
                                            As portarias não podem ser as mesmas. Não foi possível efetuar a ligação de portarias, tente novamente selecionando uma portaria diferente.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="alertaErrorSelecionarOpcao" style="display: none">
                                    <div class="col-sm-12 mt-2">
                                        <div class="alert alert-danger" role="alert">
                                            Pelo menos uma das opções de ligação deve ser ativada. Não foi possível efetuar a ligação de portarias, tente novamente.
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr class="divisor_horizontal">
                            <?php foreach($dados["portarias_ligadas"] as $portariaLigada){?>
                                <div class="addHoverLine mt-4">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h4><?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_1, $dados["portarias"]) ?> x 
                                            <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_2, $dados["portarias"]) ?></h4>
                                        </div>
                                        <div class="col-sm-5">
                                            <label><?= $helper->retornaTipoLigacaoPortaria($portariaLigada->portaria_id_1, $portariaLigada->portaria_id_2, $portariaLigada->tipo, $dados["portarias"]) ?></label>
                                        </div>
                                        <div class="col-sm-2 mt-2">
                                            <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $portariaLigada->id ?>">Editar</a>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="modal fade" id="modal-<?= $portariaLigada->id ?>">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">
                                                    <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_1, $dados["portarias"]) ?> x 
                                                    <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_2, $dados["portarias"]) ?>
                                                </h4>
                                                <button type="button" class="btn-close" data-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="<?= URL ?>/portaria/alterarLigacaoPortaria" id="form_altera_ligacao_portaria" name="form_altera_ligacao_portaria">
                                                    <input type="hidden" name="id" value="<?= $portariaLigada->id ?>" 
                                                    required>
                                                    <input type="hidden" name="portaria_0" value="<?= $portariaLigada->portaria_id_1 ?>" 
                                                    required>
                                                    <input type="hidden" name="portaria_1" value="<?=$portariaLigada->portaria_id_2 ?>" 
                                                    required>
                                                    <div class="row mt-1">
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" placeholder="Portaria" value="<?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_1, $dados["portarias"]) ?>" readonly>
                                                        </div>
                                                        <label for="portaria">Portaria Ligada 1</label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" placeholder="Portaria" value="<?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_2, $dados["portarias"]) ?>" readonly>
                                                        </div>
                                                        <label for="portaria">Portaria Ligada 2</label>
                                                    </div>
                                                    <div class="col-sm-12 mt-3">
                                                    <hr>
                                                        <div class="col-sm-12">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="tipo_0" name="tipo_0" <?= $helper->retornaLigacaoPortariaCheck($portariaLigada->tipo, "tipo_0") ?>>
                                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                                    <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_1, $dados["portarias"]) ?> pode sair na portaria <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_2, $dados["portarias"]) ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="tipo_1" name="tipo_1" <?= $helper->retornaLigacaoPortariaCheck($portariaLigada->tipo, "tipo_1") ?>>
                                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                                    <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_2, $dados["portarias"]) ?> pode sair na portaria <?= $helper->retornaDescricaoPortaria($portariaLigada->portaria_id_1, $dados["portarias"]) ?>
                                                                </label>
                                                            </div>          
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="inline inline-block">
                                                            <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                            <input type="submit" class="btn btn-danger" style="margin-top:40px;" name="deletar" id="deletar" value="Deletar">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php 
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
            </form>
        </div>
    </div>
</div>