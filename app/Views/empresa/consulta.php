<?php
    $helper = new Helpers();
    $_SESSION["pw_empresa_consulta"] = $dados["filtro"];
?>
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Empresas</a></li>
                <li class="breadcrumb-item"><a href="<?= URL ?>/empresa/nova">Nova</a></li>
                <li class="breadcrumb-item active" aria-current="page">Consulta</li>
            </ol>
        </nav>
        <div class="resultados_admin">
            <h1>Empresas - Consulta</h1>
            <hr class="divisor_horizontal">
            <form method="POST" action="<?= URL ?>/empresa/consulta" id="form_busca_empresa" name="form_busca_empresa">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-floating mb-2 mt-2">
                            <input class="form-control" type="text" id="cnpj_nome" name="cnpj_nome" placeholder="CNPJ, Razão Social ou Nome Fantasia" value="<?= $dados["filtro"] != null ? $dados["filtro"] : '' ?>"/>
                            <label for="cnpj_nome">CNPJ, Razão Social ou Nome Fantasia</label>
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
                if(isset($_SESSION["pw_rotina"])){
                    if($_SESSION["pw_tipo"] == "error")
                        $tipo = "danger";
                    else if($_SESSION["pw_tipo"] == "success")
                        $tipo = "success";
                    else if($_SESSION["pw_tipo"] == "warning")
                        $tipo = "warning";
            ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-<?= $tipo ?>" role="alert">
                            <?= $_SESSION["pw_mensagem"] ?>
                        </div>
                    </div>
                </div>
            <?php 
                }
            ?>
            <?php
                if(count($dados["dados"]) > 0){
            ?>
                    <h6 class="mt-5">Empresas Cadastradas</h6>
                    <div class="row mt-4">
                        <div class="col-sm-2">
                            CNPJ/CPF
                        </div>
                        <div class="col-sm-3">
                            Razão Social
                        </div>
                        <div class="col-sm-3">
                            Nome Fantasia
                        </div>
                        <div class="col-sm-2">
                            Situação
                        </div>
                    </div>
                    <hr class="divisor_horizontal">
                    <?php 
                        foreach($dados["dados"] as $empresa){
                    ?>
                        <div class="addHoverLine">
                            <div class="row mt-4">
                                <div class="col-sm-3">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $empresa->cnpj ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $empresa->razao_social ?>
                                    </p>
                                </div>
                                <div class="col-sm-3">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $empresa->nome_fantasia ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <?= $this->helper->retornaSituacao($empresa->situacao) ?>
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <p class="pb-1 mb-0 large border-bottom mt-2 ">
                                        <a class="addHoverButton btn btn-secondary btn-sm" style="width: 100%;" data-toggle="modal" data-target="#modal-<?= $empresa->id ?>">Editar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-<?= $empresa->id ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?= $empresa->nome_fantasia ?></h4>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= URL ?>/empresa/alterar" id="form_altera_empresa" name="form_altera_empresa">
                                            <div class="row">
                                                <input type="hidden" id="id" name="id" value="<?= $empresa->id ?>" required>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ/CPF*" required maxlength="18" onkeypress='mascaraMutuario(this,cpfCnpj)' value="<?= $empresa->cnpj ?>" readonly>
                                                        <label for="cnpj">CNPJ/CPF*</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="razao_social" name="razao_social" placeholder="Razão Social" maxlength="18" value="<?= $empresa->razao_social ?>">
                                                        <label for="razao_social">Razão Social</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" placeholder="Nome Fantasia*" required maxlength="18" value="<?= $empresa->nome_fantasia ?>">
                                                        <label for="nome_fantasia">Nome Fantasia*</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onBlur="buscaCep(this.value);" onkeypress='mascaraMutuario(this,cepMasc)' maxlength="9" value="<?= $empresa->cep ?>">
                                                        <label for="cep">CEP</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Logradouro" value="<?= $empresa->logradouro ?>">
                                                        <label for="logradouro">Logradouro</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" value="<?= $empresa->numero ?>">
                                                        <label for="numero">Número</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Compl." value="<?= $empresa->complemento ?>">
                                                        <label id="label_complemento">Compl.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <select class="form-control" id="estado" name="estado">
                                                            <option value="">Selecione...</option>
                                                            <option value="AC" <?= $helper->setSelected('AC', isset($empresa->estado) ? $empresa->estado : '') ?>>AC</option>
                                                            <option value="AL" <?= $helper->setSelected('AL', isset($empresa->estado) ? $empresa->estado : '') ?>>AL</option>
                                                            <option value="AP" <?= $helper->setSelected('AP', isset($empresa->estado) ? $empresa->estado : '') ?>>AP</option>
                                                            <option value="AM" <?= $helper->setSelected('AM', isset($empresa->estado) ? $empresa->estado : '') ?>>AM</option>
                                                            <option value="BA" <?= $helper->setSelected('BA', isset($empresa->estado) ? $empresa->estado : '') ?>>BA</option>
                                                            <option value="CE" <?= $helper->setSelected('CE', isset($empresa->estado) ? $empresa->estado : '') ?>>CE</option>
                                                            <option value="DF" <?= $helper->setSelected('DF', isset($empresa->estado) ? $empresa->estado : '') ?>>DF</option>
                                                            <option value="ES" <?= $helper->setSelected('ES', isset($empresa->estado) ? $empresa->estado : '') ?>>ES</option>
                                                            <option value="GO" <?= $helper->setSelected('GO', isset($empresa->estado) ? $empresa->estado : '') ?>>GO</option>
                                                            <option value="MA" <?= $helper->setSelected('MA', isset($empresa->estado) ? $empresa->estado : '') ?>>MA</option>
                                                            <option value="MT" <?= $helper->setSelected('MT', isset($empresa->estado) ? $empresa->estado : '') ?>>MT</option>
                                                            <option value="MS" <?= $helper->setSelected('MS', isset($empresa->estado) ? $empresa->estado : '') ?>>MS</option>
                                                            <option value="MG" <?= $helper->setSelected('MG', isset($empresa->estado) ? $empresa->estado : '') ?>>MG</option>
                                                            <option value="PA" <?= $helper->setSelected('PA', isset($empresa->estado) ? $empresa->estado : '') ?>>PA</option>
                                                            <option value="PB" <?= $helper->setSelected('PB', isset($empresa->estado) ? $empresa->estado : '') ?>>PB</option>
                                                            <option value="PR" <?= $helper->setSelected('PR', isset($empresa->estado) ? $empresa->estado : '') ?>>PR</option>
                                                            <option value="PE" <?= $helper->setSelected('PE', isset($empresa->estado) ? $empresa->estado : '') ?>>PE</option>
                                                            <option value="PI" <?= $helper->setSelected('PI', isset($empresa->estado) ? $empresa->estado : '') ?>>PI</option>
                                                            <option value="RJ" <?= $helper->setSelected('RJ', isset($empresa->estado) ? $empresa->estado : '') ?>>RJ</option>
                                                            <option value="RN" <?= $helper->setSelected('RN', isset($empresa->estado) ? $empresa->estado : '') ?>>RN</option>
                                                            <option value="RS" <?= $helper->setSelected('RS', isset($empresa->estado) ? $empresa->estado : '') ?>>RS</option>
                                                            <option value="RO" <?= $helper->setSelected('RO', isset($empresa->estado) ? $empresa->estado : '') ?>>RO</option>
                                                            <option value="RR" <?= $helper->setSelected('RR', isset($empresa->estado) ? $empresa->estado : '') ?>>RR</option>
                                                            <option value="SC" <?= $helper->setSelected('SC', isset($empresa->estado) ? $empresa->estado : '') ?>>SC</option>
                                                            <option value="SP" <?= $helper->setSelected('SP', isset($empresa->estado) ? $empresa->estado : '') ?>>SP</option>
                                                            <option value="SE" <?= $helper->setSelected('SE', isset($empresa->estado) ? $empresa->estado : '') ?>>SE</option>
                                                            <option value="TO" <?= $helper->setSelected('TO', isset($empresa->estado) ? $empresa->estado : '') ?>>TO</option>
                                                        </select>
                                                        <label for="estado">Estado</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="<?= $empresa->cidade ?>">
                                                        <label for="cidade">Cidade</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-floating mt-3">
                                                        <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" value="<?= $empresa->bairro ?>">
                                                        <label for="bairro">Bairro</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Data de Criação:</b> <?= $helper->formataDateTime($empresa->created_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="mt-3">
                                                        <b>Última Alteração:</b> <?= $helper->formataDateTime($empresa->updated_at) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="inline inline-block">
                                                    <input type="submit" class="btn btn-secondary" style="margin-top:40px;" name="update" id="update" value="Alterar">
                                                    <?php 
                                                        if($empresa->situacao == 0){
                                                    ?>
                                                            <input type="submit" class="btn btn-warning" style="margin-top:40px;" name="inativar" id="inativar" value="Inativar">
                                                    <?php 
                                                        }else if($empresa->situacao == 1){
                                                    ?>
                                                            <input type="submit" class="btn btn-success" style="margin-top:40px;" name="ativar" id="ativar" value="Ativar">
                                                    <?php 
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                        $_SESSION["pw_rotina"] = null;
                    ?>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Não há empresas cadastradas no sistema ainda.
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>