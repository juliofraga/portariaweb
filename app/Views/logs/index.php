<?php
    $helper = new Helpers();
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Logs</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/motorista/consulta" id="form_busca_motorista" name="form_busca_motorista">
                <div class="row mb-5">
                    <div class="col-sm-3">
                        <div class="form-floating mt-2">
                            <input class="form-control" type="datetime-local" id="dataDe" name="dataDe" placeholder="Dê" value=""/>
                            <label for="dataDe">Dê:</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-floating mt-2">
                            <input class="form-control" type="datetime-local" id="dataAte" name="dataAte" placeholder="Até" value=""/>
                            <label for="dataAte">Até:</label>
                        </div>
                    </div>
                    <div class="col-sm-2 mt-2" style="padding-top: 6px;">
                        <button class="w-100 btn btn-secondary btn-lg" type="submit" name="buscar">Buscar</button>
                    </div>
                    <div class="col-sm-2 mt-2" style="padding-top: 6px;">
                        <button class="w-100 btn btn-warning btn-lg" type="submit" name="limpar">Limpar</button>
                    </div>
                </div>
            </form>
            <?php
                if(count($dados["dados"]) > 0){
            ?>
                    <h6>Logs</h6>
                    <div class="row mt-4">
                        <div class="col-sm-2">
                            Usuário
                        </div>
                        <div class="col-sm-2">
                            Classe
                        </div>
                        <div class="col-sm-4">
                            Descrição
                        </div>
                        <div class="col-sm-2">
                            Ação
                        </div>
                        <div class="col-sm-2">
                            Data/Hora
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $log){
                    ?>
                        <div class="row mt-4">
                            <div class="col-sm-2">
                                <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                    <?= $log->usuario_id ?>
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                    <?= $log->classe ?>
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                    <?= $log->id_classe ?>
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                    <?= $log->acao ?>
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                    <?= $log->created_at ?>
                                </p>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
            <?php }else{ ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                Não há logs registrados no sistema ainda.
                            </div>
                        </div>
                    </div>
            <?php } ?>
        </div>
    </div>
</div>