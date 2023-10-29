<?php 
    $superadmin = $_SESSION['pw_tipo_perfil'] == md5("Superadmin") ? true : false;
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
                    if($superadmin == false and in_array($configuracao->id, CONFIGURACOES_ADMIN)){
                        continue;
                    }
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
        </div>
    </div>
</div>