<?php
    $complexidadeAtiva = $dados["complexidade"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Novo Usuário</h1>
            <form name="form_cad_usuario" id="form_cad_usuario" method="POST" action="<?= URL ?>/usuario/cadastrar/">
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
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo*" required>
                                <label for="nome">Nome completo*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="login" name="login" placeholder="Login*" required>
                                <label for="login">Login*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <select class="form-control" name="perfil" id="perfil" required>
                                    <option value="">selecione...</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Operador">Operador</option>
                                </select>
                                <label for="perfil">Perfil*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha*" required onkeyup="validaComplexidadeSenha(this.value, <?= $complexidadeAtiva ?>);">
                                <label for="senha">Senha*</label>
                                <small id="avisoComplexidadeSenha" class="form-text" style="color:red; display:none;">
                                    A senha deve ter no mínimo 6 caracteres.
                                </small>
                                <small id="avisoComplexidadeSenhaMaior" class="form-text" style="display:none;">
                                    A senha deve ter atender os seguintes requisitos:<br><br>
                                    <label id="req1" style="color:red;"><li>Ter no mínimo 8 caracteres</li></label><br>
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
                                <input type="password" class="form-control" id="repeteSenha" name="repetesenha" placeholder="Repetir Senha*" required onkeyup="comparaSenhas(this.value, <?= $complexidadeAtiva ?>);">
                                <label for="repeteSenha">Repetir Senha*</label>
                                <small id="avisoSenhasNaoConferem" class="form-text" style="color:red;display:none;">
                                    As senhas devem ser iguais.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 mb-5">
                            <button class="w-100 btn btn-warning btn-lg" name="cadastrar" id="cadastrar" value="cadastrar" disabled>Cadastrar</button>
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