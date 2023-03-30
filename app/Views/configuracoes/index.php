<?php 

$familiaProduto = "";
foreach($dados["familia"] as $familia){
    $familiaProduto .= $familia->descricao.";";
}

?>
<input type="hidden" value="<?= URL ?>" id="txtUrl">
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Configurações</h1>
            <hr class="divisor_horizontal">
            <?php
                $i = 1;
                foreach($dados["configuracoes"] as $configuracao){
            ?>
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <p class="pb-1 mb-0 large mt-2 ">
                                <b><?= $i ?>. <?= $configuracao->titulo ?></b>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="pb-1 mb-0 large mt-2 ">
                                <?= $configuracao->descricao ?>
                            </p>
                        </div>
                    </div>
                    <div class="row border-bottom">
                        <div class="col-sm-12">
                            <p class="pb-1 mb-0 large mt-2 ">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ativaDesativaCheck__<?= $configuracao->id ?>" onchange="ativaDesativaConfig(<?= $configuracao->id ?>);" <?= $configuracao->valor == 0 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="flexSwitchCheckDefault" id="checkOpcao__<?= $configuracao->id ?>"><?= $configuracao->valor == 0 ? 'Ativado' : 'Desativado' ?></label>
                                </div>
                            </p>
                        </div>
                    </div>
            <?php
                $i++;
                }
            ?>
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <p class="pb-1 mb-0 large mt-2 ">
                                <b><?= $i + 1 ?>. Famílias Permitidas p/ Importação de Produto</b>
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="<?= URL ?>/produto/salvaFamilia">
                        <div class="row border-bottom">
                            <div class="col-sm-8">
                                <p class="pb-1 mb-0 large mt-2 ">
                                    <input type="text" class="form-control" name="familiaProduto" id="familiaProduto" placeholder="Família" value="<?= $familiaProduto ?>">
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <p class="pb-1 mb-0 large mt-2 ">
                                    <button class="btn btn-warning w-50" type="submit" style="float: left"> Salvar Famílias</button>
                                </p>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
</div>