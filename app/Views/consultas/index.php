<?php 

$helper = new Helpers();

?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Consultas</h1>
            <form name="form_consultas" id="form_consultas" method="POST" action="<?= URL ?>/consultas">
                <hr class="divisor_horizontal">
                <div class="row mt-2">
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="portaria[]" id="portaria"  multiple="multiple">
                                <?php foreach($dados["portarias"] as $portaria){?>
                                    <option value="<?= $portaria->id ?>" <?= $helper->setMultiSelect($portaria->id, $dados['portariasSelecionadas']) ?>><?= $portaria->descricao ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="portaria">Portaria</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="operador[]" id="operador"  multiple="multiple">
                                <?php foreach($dados["operadores"] as $operador){?>
                                    <option value="<?= $operador->id ?>" <?= $helper->setMultiSelect($operador->id, $dados['operadoresSelecionados']) ?>><?= $operador->nome ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="operador">Operador</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="tipo[]" id="tipo"  multiple="multiple">
                                <option value="N" <?= $helper->setMultiSelect('N', $dados['tiposSelecionados']) ?>>Normal</option>
                                <option value="E" <?= $helper->setMultiSelect('E', $dados['tiposSelecionados']) ?>>Emergencial</option>
                            </select>
                        </div>
                        <label for="tipo">Tipo</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="empresa[]" id="empresa"  multiple="multiple">
                                <?php foreach($dados["empresas"] as $empresa){?>
                                    <option value="<?= $empresa->id ?>" <?= $helper->setMultiSelect($empresa->id, $dados['empresasSelecionadas']) ?>><?= $empresa->nome_fantasia ?> (<?= $empresa->cnpj ?>)</option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="empresa">Empresa</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="veiculo[]" id="veiculo"  multiple="multiple">
                                <?php foreach($dados["veiculos"] as $veiculo){?>
                                    <option value="<?= $veiculo->id ?>" <?= $helper->setMultiSelect($veiculo->id, $dados['veiculosSelecionados']) ?>><?= $veiculo->placa ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="veiculo">Veículo</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="motorista[]" id="motorista"  multiple="multiple">
                                <?php foreach($dados["motoristas"] as $motorista){?>
                                    <option value="<?= $motorista->id ?>" <?= $helper->setMultiSelect($motorista->id, $dados['motoristasSelecionados']) ?>><?= $motorista->nome_completo ?> (<?= $motorista->cpf ?>)</option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="motorista">Motorista</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-2">
                        <div class="form-floating mt-2">
                            <input type="date" class="form-control" id="dataDe" name="dataDe" placeholder="Data: Dê" value="<?= $dados["dataDeSelecionada"] ?>">
                        </div>
                        <label for="dataDe">Data: Dê</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-floating mt-2">
                            <input type="date" class="form-control" id="dataAte" name="dataAte" placeholder="Data: Até" value="<?= $dados["dataAteSelecionada"] ?>">
                        </div>
                        <label for="dataAte">Data: Até</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-floating mt-2">
                            <input type="text" class="form-control" id="id" name="id" placeholder="ID" value="<?= $dados["idFiltro"] ?>">
                        </div>
                        <label for="id">ID</label>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-floating mt-2">
                            <button class="w-100 btn btn-success btn-lg" name="consultar" id="consultar" value="consultar" type="submit">Consultar</button>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-floating mt-2">
                            <button class="w-100 btn btn-danger btn-lg" name="limparFiltros" id="limparFiltros" value="limparFiltros" type="submit">Limpar Filtros</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="divisor_horizontal">
            <?php if($dados["fezConsulta"]){ ?>
                <?php if($dados["consulta"] == null or !isset($dados["consulta"])){ ?>
                    <center>Não foram encontrados resultados com os filtros informados, tente novamente informando outros dados!</center>
                <?php }else{?>
                    <div class="row mt-2">
                        <div class="col-sm-2">
                            <b>ID</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Hora Entrada</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Hora Saída</b>
                        </div>
                        <div class="col-sm-1">
                            <b>Veículo</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Motorista</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Portaria</b>
                        </div>
                        <div class="col-sm-1">
                            <b>Tipo</b>
                        </div>
                    </div>
                    <?php foreach($dados["consulta"] as $consulta){?>
                        <div class="addHoverLine">
                            <div class="row mt-4 border-bottom">
                                <div class="col-sm-1">
                                    <?= $consulta->id ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $helper->formataDateTime($consulta->hora_abre_cancela_entrada) ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $helper->formataDateTime($consulta->hora_fecha_cancela_saida) ?>
                                </div>
                                <div class="col-sm-1">
                                    <?= $consulta->placa ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $consulta->nome_completo ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $consulta->descricao ?>
                                </div>
                                <div class="col-sm-1">
                                    <?= $helper->retornaTipoOperacao($consulta->tipo) ?>
                                </div>
                                <div class="col-sm-1">
                                    <form action="<?= URL ?>/consultas/detalhada/<?= $consulta->id ?>" method="POST" target="_blank">
                                        <input type="hidden" name="operacao_id" value="<?= $consulta->id ?>">
                                        <button class="w-100 btn btn-secondary btn-sm" name="visualizar" id="visualizar" value="visualizar" type="submit">Visualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }?>
            <?php }}?>
        </div>
    </div>
</div>