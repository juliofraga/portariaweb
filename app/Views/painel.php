<?php 

$helper = new Helpers();

?>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            tags: true
        });
    });
    $(document).ready(function() {
        carregaEmpresas();
    });
</script>
<div id="conteudo" class="mb-5 mt-3">
    <form method="POST" action="<?= URL ?>/painel" name="form_seleciona_portaria" id="form_seleciona_portaria">
        <div class="row">
            <div class="col-sm-1 mt-4"></div>
            <div class="col-sm-2 mt-4">
                <input type="hidden" id="txtUrl" value="<?= URL ?>">
                <input type="hidden" id="loginOperador" value="<?= $_SESSION["pw_id"] ?>">
                <input type="hidden" id="portaria_id" value="<?= $dados["portaria_selecionada"] ?>">
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
                        <button class="w-100 btn btn-success btn-lg" name="novaEntrada" id="novaEntrada" onclick="exibeOperacaoEntrada();">Nova Entrada</button>
                    </div>
                    <div class="col-sm-2 mt-2">
                        <button class="w-100 btn btn-danger btn-lg" name="novaSaida" id="novaSaida" onclick="exibeOperacaoSaida();">Nova Saída</button>
                    </div>
                </div>
                <div id="operacaoEntrada" style="margin-top:30px; display:block;">
                    <h4>NOVA ENTRADA</h4>
                    <div class="row mt-4">
                        <div class="col-sm-3">
                            <select class="js-example-basic-multiple w-100" name="empresa" id="empresa" onchange="buscaCnpjCpf(this.value)">
                            </select>
                            <label for="empresa">Empresa</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" onblur="validaAbrirCancela();" class="form-control w-100" name="cnpj" id="cnpj" maxlength="18" onkeypress='mascaraMutuario(this,cpfCnpj)' >
                            <label for="cnpj">CNPJ / CPF</label>
                        </div>
                        <div class="col-sm-2">
                            <select class="js-example-basic-multiple w-100" name="placa" id="placa" onchange="buscaDescricaoVeiculo(this.value)" >
                            </select>
                            <label for="placa">Placa do Veículo</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control w-100" name="descricao" id="descricao" onblur="validaAbrirCancela();">
                            <label for="descricao">Descrição do Veículo</label>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control w-100" name="tipo" id="tipo" onchange="validaAbrirCancela();">
                                <option value="">Selecione...</option>    
                                <option value="1">Carro</option>
                                <option value="2">Caminhão</option>
                                <option value="3">Moto</option>
                                <option value="4">Outro</option>
                            </select>
                            <label for="tipo">Tipo do Veículo</label>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <select class="js-example-basic-multiple w-100" name="motorista" id="motorista" onchange="buscaCpfMotorista(this.value)">
                            </select>
                            <label for="motorista">Nome Motorista</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control w-100" name="cpfMotorista" id="cpfMotorista" onchange="validaAbrirCancela();" maxlength="14" onkeypress='mascaraMutuario(this,cpfCnpj)' onkeyup="validaAbrirCancela();" onblur="validaAbrirCancela();">
                            <label for="cpfMotorista">CPF</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-warning btn-lg" name="btnAbrirCancela" id="btnAbrirCancela" style="display: none;" onclick="executaOperacaoAbrirCancela();">Abrir Cancela</button>
                    </div>
                    <div class="col-sm-6  mt-2">
                        <button class="w-100 btn btn-dark btn-lg" name="btnFecharCancela" id="btnFecharCancela" style="display: none;" onclick="executaOperacaoFechamentoCancela()">Fechar Cancela</button>
                    </div>
                </div>
                <div class="row mt-4" id="alertaErrorAbrirCancela" style="display: none">
                    <div class="col-sm-12 mt-2">
                        <div class="alert alert-danger" role="alert">
                            Houve um problema na comunicação com a cancela e ela não pode ser aberta. Operação não realizada com sucesso!
                        </div>
                    </div>
                </div>
                <div class="row mt-4" id="alertaErrorCapturaImagens" style="display: none">
                    <div class="col-sm-12 mt-2">
                        <div class="alert alert-warning" role="alert">
                            Houve um problema na captura das imagens desta operação.
                        </div>
                    </div>
                </div>
                <div class="row mt-4" id="alertaErrorFecharCancela" style="display: none">
                    <div class="col-sm-12 mt-2">
                        <div class="alert alert-danger" role="alert">
                            Houve um problema na comunicação com a cancela e ela não pode ser fechada. Operação não realizada com sucesso!
                        </div>
                    </div>
                </div>
                <div class="row mt-4" id="alertaErrorRegistrarOperacao" style="display: none">
                    <div class="col-sm-12 mt-2">
                        <div class="alert alert-danger" role="alert">
                            Houve um problema no registro desta operação, comunique o administrador do sistema!
                        </div>
                    </div>
                </div>
                <div id="operacaoSaida" style="display:none;">
                    <h4>NOVA SAÍDA</h4>
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <select class="form-control w-100" name="veiculoSaida" id="veiculoSaida">
                            </select>
                            <label for="veiculo">Veículo</label>
                        </div>
                    </div>
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
                            <div class="col-sm-12 mt-5" id="camera_iframe_id_<?= $camera->id ?>">
                                <label><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></label>
                                <iframe src="http://localhost/" height="100%" width="100%" allowfullscreen></iframe> 
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>