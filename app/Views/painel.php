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
    <div class="container conteudo_consulta">
        <div class="row">
            <div class="col-sm-3 mt-2">
                <form method="POST" action="<?= URL ?>/painel" name="form_seleciona_portaria" id="form_seleciona_portaria">
                    <input type="hidden" id="txtUrl" value="<?= URL ?>">
                    <input type="hidden" id="loginOperador" value="<?= $_SESSION["pw_id"] ?>">
                    <input type="hidden" id="portaria_id" value="<?= $dados["portaria_selecionada"] ?>">
                    <input type="hidden" id="rele_abre_cancela" value="<?= $dados["reles"][0]->rele_abre_cancela ?>">
                    <input type="hidden" id="rele_fecha_cancela" value="<?= $dados["reles"][0]->rele_fecha_cancela ?>">
                    <input type="hidden" id="porta" value="<?= $dados["reles"][0]->porta ?>">
                    <input type="hidden" id="endereco_ip_placa" value="<?= $dados["reles"][0]->endereco_ip ?>">
                    <input type="hidden" id="usa_balanca" value="<?= USA_BALANCA ?>">
                    <select class="form-control" name="portaria" id="portaria" onchange="selecionaPortaria();">
                        <?php foreach($dados["portarias"] as $portaria){ ?>
                            <option value="<?= $portaria->id ?>" <?= $helper->setSelected($portaria->id, $dados["portaria_selecionada"]) ?>><?= $portaria->descricao ?></option>
                        <?php }?>
                    </select>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Status da Cancela: <label id="statusPlaca"></label>
            </div>
        </div>
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
                    <?php if($dados["emergencia"] or $_SESSION['pw_tipo_perfil'] != md5("Operador")){ ?>
                    <div class="col-sm-2 mt-2">
                        <button class="w-100 btn btn-warning btn-lg" name="saidaEmergencia" id="saidaEmergencia" onclick="exibeOperacaoEmergencia();">Emergência</button>
                    </div>
                    <?php } ?>
                </div>
                <div id="operacaoEmergencia" style="margin-top:30px; display:none;">
                    <h4>EMERGÊNCIA</h4>
                    <div class="row mt-4">
                        <textarea name="obsEmergencia" id="obsEmergencia" class="form-control" placeholder="Observação" rows="6"></textarea>
                        <small id="avisoObservacao" class="form-text" style="color:red; display:none;">
                            O campo observação é de preenchimento obrigatório.
                        </small>
                    </div>
                </div>
                <div id="operacaoEntrada" style="margin-top:30px; display:none;">
                    <h4>NOVA ENTRADA</h4>
                    <div class="row mt-2">
                        <div class="col-sm-3">
                            <label for="empresa" class="mt-3">Empresa</label>    
                            <select class="js-example-basic-multiple w-100" name="empresa" id="empresa" onchange="buscaCnpjCpf(this.value)">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="cnpj" class="mt-3">CNPJ / CPF</label>
                            <input type="text" onblur="validaAbrirCancela();" class="form-control w-100" name="cnpj" id="cnpj" maxlength="18" onkeypress='mascaraMutuario(this,cpfCnpj)' >
                        </div>
                        <div class="col-sm-2">
                            <label for="placa" class="mt-3">Placa do Veículo</label>
                            <select class="js-example-basic-multiple w-100" name="placa" id="placa" onchange="buscaDescricaoVeiculo(this.value)" >
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="descricao" class="mt-3">Descrição do Veículo</label>
                            <input type="text" class="form-control w-100" name="descricao" id="descricao" onblur="validaAbrirCancela();">
                        </div>
                        <div class="col-sm-2">
                            <label for="tipo" class="mt-3">Tipo do Veículo</label>
                            <select class="form-control w-100" name="tipo" id="tipo" onchange="validaAbrirCancela();">
                                <option value="">Selecione...</option>    
                                <option value="1">Carro</option>
                                <option value="2">Caminhão</option>
                                <option value="3">Moto</option>
                                <option value="4">Outro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="motorista" class="mt-3">Nome Motorista</label>
                            <select class="js-example-basic-multiple w-100" name="motorista" id="motorista" onchange="buscaCpfMotorista(this.value)">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="cpfMotorista" class="mt-3">CPF</label>
                            <input type="text" class="form-control w-100" name="cpfMotorista" id="cpfMotorista" onchange="validaAbrirCancela();" maxlength="14" onkeypress='mascaraMutuario(this,cpfCnpj)' onkeyup="validaAbrirCancela();" onblur="validaAbrirCancela();">
                        </div>
                    </div>
                </div>
                <div id="operacaoSaida" style="margin-top:30px; display:none;">
                    <h4>NOVA SAÍDA</h4>
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <label for="veiculo">Veículo</label>
                            <select class="form-select w-100" name="veiculoSaida" id="veiculoSaida" onchange="exibeBtnAbrirCancelaSaida();">
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-warning btn-lg" name="btnAbrirCancela" id="btnAbrirCancela" style="display: none;" onclick="executaOperacaoAbrirCancela();">Abrir Cancela</button>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-dark btn-lg" name="btnFecharCancela" id="btnFecharCancela" style="display: none;" onclick="executaOperacaoFechamentoCancela()">Fechar Cancela</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-warning btn-lg" name="btnAbrirCancelaSaida" id="btnAbrirCancelaSaida" style="display: none;" onclick="executaOperacaoAbrirCancelaSaida();">Abrir Cancela</button>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-dark btn-lg" name="btnFecharCancelaSaida" id="btnFecharCancelaSaida" style="display: none;" onclick="executaOperacaoFechamentoCancelaSaida()">Fechar Cancela</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-warning btn-lg" name="btnAbrirCancelaEmergencia" id="btnAbrirCancelaEmergencia" style="display: none;" onclick="executaOperacaoAbrirCancelaEmergencia();">Abrir Cancela</button>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <button class="w-100 btn btn-dark btn-lg" name="btnFecharCancelaEmegrencia" id="btnFecharCancelaEmegrencia" style="display: none;" onclick="executaOperacaoFechamentoCancelaEmergencia()">Fechar Cancela</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12 mt-1" style="display: none" id="alertaAbrindoCancela">
                        <div class="alert alert-warning" role="alert">
                            Abrindo a cancela...
                        </div>
                    </div>
                    <div class="col-sm-12 mt-1" style="display: none" id="alertaFechandoCancela">
                        <div class="alert alert-warning" role="alert">
                            Fechando a cancela...
                        </div>
                    </div>
                    <div class="col-sm-12 mt-1" style="display: none" id="alertaCapturandoImagens">
                        <div class="alert alert-warning" role="alert">
                            Capturando imagens...
                        </div>
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
                <div class="row mt-4" id="alertaErrorVeiculoNaoPodeEntrar" style="display: none">
                    <div class="col-sm-12 mt-2">
                        <div class="alert alert-danger" role="alert">
                            Veículo não pode entrar!! Foi registrada uma entrada deste veículo, porém não roi registrada a saída, verifique!
                        </div>
                    </div>
                </div>
                <?php if(USA_BALANCA){ ?>
                    <div class="row" id="telaCapturaPeso">
                        <div class="col-sm-6">
                            <input type="text" class="form-control-lg w-100" id="peso" name="peso" style="height:100px;font-size:50px;text-align:right;">
                        </div>
                        <div class="col-sm-1 mt-3">
                            <h1>Kg</h1>
                        </div>
                        <div class="col-sm-3 mt-3">
                            <button class="w-100 btn btn-secondary btn-lg" name="btnCapturaPeso" id="btnCapturaPeso" onclick="capturaPeso();">Capturar Peso</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>