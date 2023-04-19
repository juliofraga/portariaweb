<input type="hidden" value="<?= URL ?>" id="txtUrl">
<div id="conteudo" class="mb-5">
    <div class="container conteudo_consulta">
        <div class="resultados_admin mt-2">
            <h1>Consultas</h1>
            <hr class="divisor_horizontal">
            <div class="row mt-2">
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="portaria[]" id="portaria"  multiple="multiple">

                        </select>
                    </div>
                    <label for="portaria">Portaria</label>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="operador[]" id="operador"  multiple="multiple">

                        </select>
                    </div>
                    <label for="operador">Operador</label>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="tipo[]" id="tipo"  multiple="multiple">
                            <option value="N" selected>Normal</option>
                            <option value="E">Emergencial</option>
                        </select>
                    </div>
                    <label for="tipo">Tipo</label>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="empresa[]" id="empresa"  multiple="multiple">

                        </select>
                    </div>
                    <label for="empresa">Empresa</label>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="veiculo[]" id="veiculo"  multiple="multiple">

                        </select>
                    </div>
                    <label for="veiculo">Veículo</label>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mt-2">
                        <select class="js-example-basic-multiple w-100" name="motorista[]" id="motorista"  multiple="multiple">

                        </select>
                    </div>
                    <label for="motorista">Motorista</label>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-2">
                    <div class="form-floating mt-2">
                        <input type="date" class="form-control" id="dataDe" name="dataDe" placeholder="Data: Dê*" required>
                    </div>
                    <label for="dataDe">Data: Dê*</label>
                </div>
                <div class="col-sm-2">
                    <div class="form-floating mt-2">
                        <input type="date" class="form-control" id="dataAte" name="dataAte" placeholder="Data: Até*" required>
                    </div>
                    <label for="dataAte">Data: Até*</label>
                </div>
            </div>
        </div>
    </div>
</div>