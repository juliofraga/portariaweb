<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Novo Veículo</h1>
            <form name="form_cad_veiculo" id="form_cad_veiculo" method="POST" action="<?= URL ?>/veiculo/cadastrar/">
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
                        <div class="col-sm-3">
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control letraMaiuscula" id="placaVeiculo" name="placaVeiculo" placeholder="Placa*" required>
                                <label for="placa">Placa*</label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-floating  mt-2">
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição*" required>
                                <label for="descricao">Descrição*</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-floating mt-2">
                                <select class="form-control" id="tipo" name="tipo" placeholder="Tipo" required>
                                    <option value="" selected>Selecione...</option>
                                    <option value="1">Carro</option>
                                    <option value="2">Caminhão</option>
                                    <option value="3">Moto</option>
                                    <option value="4">Outro</option>
                                </select>
                                <label for="tipo">Tipo*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-floating mt-2">
                                <select class="form-control w-100" name="empresa" id="empresa" required placeholder="Empresa*">
                                    <option value="">Selecione...</option>
                                    <?php foreach($dados["empresas"] as $empresa){ ?>
                                        <option value="<?= $empresa->id ?>"><?= $empresa->nome_fantasia ?></option>
                                    <?php }?>
                                </select>
                                <label for="empresa">Empresa*</label>
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