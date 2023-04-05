<?php 

$helper = new Helpers();

?>
<div id="conteudo" class="mb-5">
    <form method="POST" action="<?= URL ?>/painel" name="form_seleciona_portaria" id="form_seleciona_portaria">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-1">
                <select class="form-control" name="portaria" id="portaria" onchange="submitSelecao();">
                    <?php foreach($dados["portarias"] as $portaria){ ?>
                        <option value="<?= $portaria->id ?>" <?= $helper->setSelected($portaria->id, $dados["portaria_selecionada"]) ?>><?= $portaria->descricao ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
    </form>
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Painel de Operações</h1>
            <hr class="divisor_horizontal">
            <div id="formUserAdmin">
            </div>
        </div>
    </div>
</div>