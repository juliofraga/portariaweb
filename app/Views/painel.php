<?php 

$helper = new Helpers();
?>
<div id="conteudo" class="mb-5 mt-3">
    <form method="POST" action="<?= URL ?>/painel" name="form_seleciona_portaria" id="form_seleciona_portaria">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-2">
                <select class="form-control" name="portaria" id="portaria" onchange="submitSelecao();">
                    <?php foreach($dados["portarias"] as $portaria){ ?>
                        <option value="<?= $portaria->id ?>" <?= $helper->setSelected($portaria->id, $dados["portaria_selecionada"]) ?>><?= $portaria->descricao ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
    </form>
    <div class="container conteudo_consulta">
        <div class="resultados_admin">
            <h1>Painel de Operações</h1>
            <hr class="divisor_horizontal">
            <div id="formUserAdmin">
                <div class="row">
                    <div class="col-sm-2 mt-2">
                        <button class="w-100 btn btn-success btn-lg" name="novaEntrada" id="novaEntrada" onclick="exibeOperadaEntrada();">Nova Entrada</button>
                    </div>
                    <div class="col-sm-2 mt-2">
                        <button class="w-100 btn btn-danger btn-lg" name="novaSaida" id="novaSaida" onclick="exibeOperadaSaida();">Nova Saída</button>
                    </div>
                </div>
                <div id="operacaoEntrada" style="margin-top:30px; display:none;">
                    <h4><u>Nova Entrada</u></h4>
                    <div class="row mt-4">
                        <div class="col-sm-3">
                            <select class="js-example-basic-multiple w-100" name="empresa" id="empresa">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="empresa">Empresa</label>
                        </div>
                        <div class="col-sm-3">
                            <select class="js-example-basic-multiple w-100" name="cnpj" id="cnpj">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="cnpj">CNPJ / CPF</label>
                        </div>
                        <div class="col-sm-3">
                            <select class="js-example-basic-multiple w-100" name="placa" id="placa">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="placa">Placa do Veículo</label>
                        </div>
                        <div class="col-sm-3">
                            <select class="js-example-basic-multiple w-100" name="tipo" id="tipo">
                                <option value="">Selecione...</option>    
                                <option value="1">Carro</option>
                                <option value="2">Caminhão</option>
                                <option value="3">Moto</option>
                                <option value="4">Outro</option>
                            </select>
                            <label for="tipo">Tipo do Veículo</label>
                        </div>
                    </div>
                    <hr>
                    <h6>Pessoas no Veículo</h6>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <select class="js-example-basic-multiple w-100" name="pessoa" id="pessoa1">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="cpf">Nome</label>
                        </div>
                        <div class="col-sm-6">
                            <select class="js-example-basic-multiple w-100" name="cpf" id="cpf1">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="cpf">CPF</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <select class="js-example-basic-multiple w-100" name="pessoa" id="pessoa2">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="cpf">Nome</label>
                        </div>
                        <div class="col-sm-6">
                            <select class="js-example-basic-multiple w-100" name="cpf" id="cpf2">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="cpf">CPF</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <button class="w-100 btn btn-secondary btn-md" name="cadastrar" id="cadastrar" value="cadastrar">Adicionar Mais Pessoas (+)</button>
                        </div>
                    </div>
                </div>
                <div id="operacaoSaida" style="margin-top:30px; display:none;">
                    <h4><u>Nova Saída</u></h4>
                </div>
                <hr>
                <div id="camerasPortaria">
                    <h4><u>Câmeras</u></h4>
                    <div class="row mt-3">
                        <?php foreach($dados["cameras"] as $camera){ ?>
                            <div class="col-sm-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="camera__<?= $camera->id ?>" onchange="exibeEscondeCamera(this.checked, this.id);" checked>
                                    <label class="form-check-label" for="flexSwitchCheckDefault" id="checkOpcao__<?= $camera->id ?>"><?= $camera->descricao ?></label>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                    <div class="row">
                        <?php foreach($dados["cameras"] as $camera){ ?>
                            <div class="col-sm-6 mt-3">
                                <iframe src="http://localhost/portariaweb/usuario/novo" height="100%" width="100%" allowfullscreen id="camera_iframe_id_<?= $camera->id ?>"></iframe> 
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>