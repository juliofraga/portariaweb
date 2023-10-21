<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Câmeras</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/camera/consulta">Consulta</a></li>
                <li class="breadcrumb-item active" aria-current="page">Novo</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Nova Câmera</h1>
            <form name="form_cad_camera" id="form_cad_camera" method="POST" action="<?= URL ?>/camera/cadastrar/">
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
                        <div class="col-sm-10">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="endereco_ip" name="endereco_ip" placeholder="Endereço IP (xxx.xxx.xxx.xxx)*" required>
                                <label for="endereco_ip">Endereço IP (xxx.xxx.xxx.xxx)*</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="porta" name="porta" placeholder="Porta*" required  onkeyup="limpaSeNaoNumerico(this);">
                                <label for="porta">Porta*</label>
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