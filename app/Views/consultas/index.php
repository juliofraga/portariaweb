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
                                    <option value="<?= $portaria->id ?>"><?= $portaria->descricao ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="portaria">Portaria</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="operador[]" id="operador"  multiple="multiple">
                                <?php foreach($dados["operadores"] as $operador){?>
                                    <option value="<?= $operador->id ?>"><?= $operador->nome ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="operador">Operador</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="tipo[]" id="tipo"  multiple="multiple">
                                <option value="N" selected>Normal</option>
                                <option value="E">Emergencial</option>
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
                                    <option value="<?= $empresa->id ?>"><?= $empresa->nome_fantasia ?> (<?= $empresa->cnpj ?>)</option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="empresa">Empresa</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="veiculo[]" id="veiculo"  multiple="multiple">
                                <?php foreach($dados["veiculos"] as $veiculo){?>
                                    <option value="<?= $veiculo->id ?>"><?= $veiculo->placa ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="veiculo">Veículo</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <select class="js-example-basic-multiple w-100" name="motorista[]" id="motorista"  multiple="multiple">
                                <?php foreach($dados["motoristas"] as $motorista){?>
                                    <option value="<?= $motorista->id ?>"><?= $motorista->nome_completo ?> (<?= $motorista->cpf ?>)</option>
                                <?php }?>
                            </select>
                        </div>
                        <label for="motorista">Motorista</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-2">
                        <div class="form-floating mt-2">
                            <input type="date" class="form-control" id="dataDe" name="dataDe" placeholder="Data: Dê">
                        </div>
                        <label for="dataDe">Data: Dê</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-floating mt-2">
                            <input type="date" class="form-control" id="dataAte" name="dataAte" placeholder="Data: Até">
                        </div>
                        <label for="dataAte">Data: Até</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <button class="w-100 btn btn-success btn-lg" name="consultar" id="consultar" value="consultar" type="submit">Consultar</button>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-floating mt-2">
                            <button class="w-100 btn btn-danger btn-lg" name="limpar" id="limpar" value="limpar" type="clear">Limpar Filtros</button>
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
                            <b>Hora Entrada</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Hora Saída</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Veículo</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Operador</b>
                        </div>
                        <div class="col-sm-2">
                            <b>Tipo</b>
                        </div>
                    </div>
                    <?php foreach($dados["consulta"] as $consulta){?>
                        <div class="row mt-4 border-bottom">
                            <div class="col-sm-2">
                                <?= $helper->formataDateTime($consulta->hora_abre_cancela_entrada) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $helper->formataDateTime($consulta->hora_fecha_cancela_saida) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $consulta->placa ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $consulta->nome ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $helper->retornaTipoOperacao($consulta->tipo) ?>
                            </div>
                            <div class="col-sm-2">
                                <form action="<?= URL ?>/consultas/detalhada" method="POST" target="_blank">
                                    <input type="hidden" name="operacao_id" value="<?= $consulta->id ?>">
                                    <button class="w-100 btn btn-secondary btn-sm" name="consultar" id="consultar" value="consultar" type="submit">Consultar</button>
                                </form>
                            </div>
                    </div>
                    <?php }?>
            <?php }}?>
        </div>
    </div>
</div>