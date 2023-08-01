<?php 

$helper = new Helpers();
$tipoVeiculos = [
    '0' => 'Não Informado',
    '1' => 'Carro',
    '2' => 'Caminhão',
    '3' => 'Moto',
    '4' => 'Outro'
];
$tipoOperacao = [
    'N' => 'Normal',
    'E' => 'Emergencial'
];
$tipoCancela = [
    '0' => 'Abertura de cancela',
    '1' => 'Fechamento de cancela'
];
$tipoOperacaoCancela = [
    '0' => 'Entrada',
    '1' => 'Saída',
    '2' => 'Emergencia'
]

?>
<style>
    .modal-content {
        height: 100%;
        width: 1200px;
        margin-left: -200px;
    }
</style>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Consulta Detalhada - <?= $dados["operacao"][0]->id ?></h1>
            <hr class="divisor_horizontal">
            <?php if($dados["operacao"][0]->tipo_operacao == 'N'){ ?>
                <fieldset class="border p-4">
                    <legend class="float-none w-auto p-2">Dados do Veículo</legend>
                    <div class="row">
                        <div class="col col-6">
                            <label class="tituloConsultaDetalhada">Empresa:</label>
                            <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->razao_social ?> (<?= $dados["operacao"][0]->cnpj ?>)</label>
                        </div>
                        <div class="col col-6">
                            <label class="tituloConsultaDetalhada">Motorista:</label>
                            <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->nome_completo ?> (<?= $dados["operacao"][0]->cpf ?>)</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col col-12">
                            <label class="tituloConsultaDetalhada">Veículo:</label>
                            <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->placa ?> - <?= $dados["operacao"][0]->descricao ?> (<?= $tipoVeiculos[$dados["operacao"][0]->tipo] ?>)</label>
                        </div>
                    </div>
                </fieldset>
            <?php } ?>
            <fieldset class="border p-4">
                <legend class="float-none w-auto p-2">Dados da Operação</legend>
                <div class="row">
                    <div class="col col-6">
                        <label class="tituloConsultaDetalhada">Tipo:</label>
                        <label class="textoConsultaDetalhada"> <?= $tipoOperacao[$dados["operacao"][0]->tipo_operacao] ?></label>
                    </div>
                    <div class="col col-6">
                        <label class="tituloConsultaDetalhada">Portaria:</label>
                        <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->portaria_descricao ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-6">
                        <label class="tituloConsultaDetalhada">Abertura da cancela (Entrada):</label>
                        <label class="textoConsultaDetalhada"> <?= $helper->formataDateTime($dados["operacao"][0]->hora_abre_cancela_entrada) ?></label>
                    </div>
                    <div class="col col-6">
                        <label class="tituloConsultaDetalhada">Fechamento da cancela (Entrada):</label>
                        <label class="textoConsultaDetalhada"> <?= $helper->formataDateTime($dados["operacao"][0]->hora_fecha_cancela_entrada) ?></label>
                    </div>
                </div>
                <?php if($dados["operacao"][0]->tipo_operacao == 'N'){ ?>
                    <div class="row">
                        <div class="col col-6">
                            <label class="tituloConsultaDetalhada">Abertura da cancela (Saída):</label>
                            <label class="textoConsultaDetalhada"> <?= $helper->formataDateTime($dados["operacao"][0]->hora_abre_cancela_saida) ?></label>
                        </div>
                        <div class="col col-6">
                            <label class="tituloConsultaDetalhada">Fechamento da cancela (Saída):</label>
                            <label class="textoConsultaDetalhada"> <?= $helper->formataDateTime($dados["operacao"][0]->hora_fecha_cancela_saida) ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-12">
                            <label class="tituloConsultaDetalhada">Tempo total do veículo dentro da empresa:</label>
                            <label class="textoConsultaDetalhada"><?= $helper->calculaTempoTotal($dados["operacao"][0]->hora_abre_cancela_entrada, $dados["operacao"][0]->hora_abre_cancela_saida) ?></label>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col col-12">
                        <label class="tituloConsultaDetalhada">Observação (Emergência)</label>
                        <textarea style="background-color: white;"class="form-control" rows="5" readonly><?= $dados["operacao"][0]->obs_emergencia ?>
                        </textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-12">
                        <label class="tituloConsultaDetalhada">Operador:</label>
                        <label class="textoConsultaDetalhada"><?= $dados["operacao"][0]->nome ?> (<?= $dados["operacao"][0]->login ?>)</label>
                    </div>
                </div>
            </fieldset>
            <fieldset class="border p-4">
                <legend class="float-none w-auto p-2">Imagens da operação</legend>
                <div class="row">
                    <?php foreach($dados["imagens"] as $imagem){ ?>
                        <div class="col col-6">
                            <figure>
                                <a href="#" data-toggle="modal" data-target="#modal-<?= $imagem->id ?>">
                                    <img src="<?= $helper->formataUrlImagem($imagem->url_imagem) ?>" class="img-fluid" alt="Imagem responsiva">
                                </a>
                                <figcaption>
                                    <?= $tipoOperacaoCancela[$imagem->tipo_operacao] ?> (<?= $tipoCancela[$imagem->tipo] ?>)
                                </figcaption>
                            </figure>
                        </div>
                        <div class="modal fade" id="modal-<?= $imagem->id ?>" tabindex="-1" role="dialog" style="z-index: 1100;" data-backdrop="static">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $tipoOperacaoCancela[$imagem->tipo_operacao] ?> (<?= $tipoCancela[$imagem->tipo] ?>)</h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="<?= $helper->formataUrlImagem($imagem->url_imagem) ?>" class="img-fluid" alt="Imagem responsiva">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>