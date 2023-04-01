<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Nova Placa</h1>
            <form name="form_cad_camera" id="form_cad_camera" method="POST" action="<?= URL ?>/placa/cadastrar/">
                <hr class="divisor_horizontal">
                <div id="formUserAdmin">
                    <?php 
                        if(isset($_SESSION["pw_rotina"]) and $_SESSION["pw_tipo"] == 'success'){
                    ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION["pw_mensagem"] ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                        if(isset($_SESSION["pw_rotina"]) and $_SESSION["pw_tipo"] == 'error'){
                    ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION["pw_mensagem"] ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    ?>
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required>
                                <label for="descricao">Descrição*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="endereco_ip" name="endereco_ip" placeholder="Endereço IP (xxx.xxx.xxx.xxx)*" required>
                                <label for="endereco_ip">Endereço IP (xxx.xxx.xxx.xxx)*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="rele1" name="rele1" placeholder="Relé 1*" required maxlength="2" onkeyup="limpaSeNaoNumerico(this);">
                                <label for="rele1">Relé 1*</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mt-3">
                            <input type="text" class="form-control" id="rele2" name="rele2" placeholder="Relé 2*" required maxlength="2" onkeyup="limpaSeNaoNumerico(this);">
                                <label for="rele1">Relé 2*</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="rele3" name="rele3" placeholder="Relé 3*" required maxlength="2" onkeyup="limpaSeNaoNumerico(this);">
                                <label for="rele1">Relé 3*</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-floating mt-3">
                            <input type="text" class="form-control" id="rele4" name="rele4" placeholder="Relé 4*" required maxlength="2" onkeyup="limpaSeNaoNumerico(this);">
                                <label for="rele1">Relé 4*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 mb-5">
                            <button class="w-100 btn btn-warning btn-lg" name="cadastrar" id="cadastrar" value="cadastrar">Cadastrar</button>
                        </div>
                    </div>
                    <?php 
                        $_SESSION["pw_rotina"] = null;
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>