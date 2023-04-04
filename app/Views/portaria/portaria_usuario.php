<?php ?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Ligação Portaria x Usuários</h1>
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
                    <?php foreach($dados["portarias"] as $portaria){?>
                        <form name="form_cad_portaria_usuario" id="form_cad_portaria_usuario" method="POST" action="<?= URL ?>/portaria/ligar_portaria_usuario/">
                            <div class="row mt-5">
                                <div class="col-sm-2">
                                    <div class="form-floating">
                                        <select name="portaria" id="portaria" class="form-control" required>
                                            <option value="<?= $portaria->id ?>"><?= $portaria->descricao ?></option>
                                        </select>
                                        <label for="portaria">Portaria*</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <select class="js-example-basic-multiple w-100" name="usuario[]"  multiple="multiple" placeholder="Usuários">
                                        <?php foreach($dados["usuarios"] as $usuario){ ?>
                                            <option value="<?= $usuario->id ?>"><?= $usuario->login ?> - <?= $usuario->nome ?></option>
                                        <?php }?>
                                    </select>
                                <label for="usuarios">Usuários*</label>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <button class="w-100 btn btn-warning btn-lg" name="cadastrar" id="cadastrar" value="cadastrar">Cadastrar</button>
                                </div>
                            </div>
                        </form>
                        <hr class="divisor_horizontal">
                    <?php } ?>
                    <?php 
                        $_SESSION["pw_rotina"] = null;
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>