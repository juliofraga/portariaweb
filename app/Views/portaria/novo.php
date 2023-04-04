<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Nova Portaria</h1>
            <form name="form_cad_portaria" id="form_cad_portaria" method="POST" action="<?= URL ?>/portaria/cadastrar/">
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
                                <select name="placa" id="placa" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($dados["placas"] as $placa){ ?>
                                        <option value="<?= $placa->id ?>"><?= $placa->descricao ?> - <?= $placa->endereco_ip ?></option>
                                    <?php }?>
                                </select>
                                <label for="placa">Placa</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <select class="js-example-basic-multiple w-100" name="camera[]" id="camera"  multiple="multiple">
                                <?php foreach($dados["cameras"] as $camera){ ?>
                                    <option value="<?= $camera->id ?>"><?= $camera->descricao ?> - <?= $camera->endereco_ip ?></option>
                                <?php }?>
                            </select>
                            <label for="placa">Câmeras</label>
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