<?php 
    $superadmin = $_SESSION['pw_tipo_perfil'] == md5("Superadmin") ? true : false;
?>
<input type="hidden" value="<?= URL ?>" id="txtUrl">
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Importador</h1>
            <hr class="divisor_horizontal">
            <form name="form_import_arquivo" id="form_import_arquivo" method="POST" action="<?= URL ?>/importador/importaArquivo/" enctype="multipart/form-data">
                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="formFile" class="form-label">Selecione o arquivo...</label>
                        <input class="form-control" type="file" id="arquivo" name="arquivo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-4">
                        <button class="w-50 btn btn-warning btn-md" name="importar" id="importar" value="importar">Importar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>