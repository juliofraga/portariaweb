<?php 

$helper = new Helpers();

?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Consulta Detalhada - <?= $dados["operacao"][0]->id ?></h1>
            <hr class="divisor_horizontal">
            <div class="row border-bottom">
                <div class="col col-6">
                    <label class="tituloConsultaDetalhada">Empresa:</label>
                    <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->razao_social ?> (<?= $dados["operacao"][0]->cnpj ?>)</label>
                </div>
                <div class="col col-6">
                    <label class="tituloConsultaDetalhada">Motorista:</label>
                    <label class="textoConsultaDetalhada"> <?= $dados["operacao"][0]->nome_completo ?> (<?= $dados["operacao"][0]->cpf ?>)</label>
                </div>
            </div>
        </div>
    </div>
</div>