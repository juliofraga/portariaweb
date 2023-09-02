<?php
    $complexidadeAtiva = $dados["complexidade"];
?>
<div id="conteudo-cliente" class="mb-5 mt-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-5">
            <h1>Perfil</h1>
            <form name="form_alt_usuario" id="form_alt_usuario" method="POST" action="<?= URL ?>/usuario/alterar/perfil">
                <hr class="divisor_horizontal">
                <div id="formUserAdmin">
                    <?php 
                        if(isset($_SESSION["pw_tipo"]) and $_SESSION["pw_tipo"] == 'success'){
                    ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION["pw_mensagem"] ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                        if(isset($_SESSION["pw_tipo"]) and $_SESSION["pw_tipo"] == 'error'){
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
                                <input type="text" class="form-control" id="perfil" name="perfil" required value="<?= $dados['usuario'][0]->perfil ?>" readonly>
                                <label for="perfil">Perfil*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="hidden" class="form-control" id="id" name="id" required value="<?= $dados['usuario'][0]->id ?>">
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome*" required value="<?= $dados['usuario'][0]->nome ?>">
                                <label for="nome">Nome*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha*" onkeyup="validaComplexidadeSenha(this.value, <?= $complexidadeAtiva ?>);">
                                <label for="senha">Senha*</label>
                                <small id="avisoComplexidadeSenha" class="form-text" style="color:red; display:none;">
                                    A senha deve ter no mínimo 6 caracteres.    
                                </small>
                                <small id="avisoComplexidadeSenhaMaior" class="form-text" style="display:none;">
                                    A senha deve ter atender os seguintes requisitos:<br><br>
                                    <label id="req1" style="color:red;"><li>Ter no mínimo 8 caracteres"</li></label><br>
                                    <label id="req2" style="color:red;"><li>Ter letras maiúsculas</li></label><br>
                                    <label id="req3" style="color:red;"><li>Ter letras minúsculas</li></label><br>
                                    <label id="req4" style="color:red;"><li>Ter números</li></label><br>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="repeteSenha" name="repetesenha" placeholder="Repetir Senha*" onkeyup="comparaSenhas(this.value, <?= $complexidadeAtiva ?>);">
                                <label for="repeteSenha">Repetir Senha*</label>
                                <small id="avisoSenhasNaoConferem" class="form-text" style="color:red;display:none;">
                                    As senhas devem ser iguais ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 mb-5">
                            <button class="w-100 btn btn-warning btn-lg" name="update" id="update" value="update">Alterar</button>
                        </div>
                    </div>
                    <?php 
                        $_SESSION["pw_tipo"] = null;
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>