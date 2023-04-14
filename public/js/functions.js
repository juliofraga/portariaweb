var idOperacao;

$(document).ready(function(){
    $('#endereco_ip').mask('999.999.999.999');
});

$(document).ready(function(){
    $("#placaVeiculo").mask('AAA-9A99');
 });

function validaComplexidadeSenha(senha, complexidade){
    if(complexidade == null || complexidade == false){
        let tamanho = senha.length;
        if(tamanho < 6){
            document.getElementById('avisoComplexidadeSenha').style.display = 'block';
        }else{
            document.getElementById('avisoComplexidadeSenha').style.display = 'none';
        }
    }else if(complexidade == true){
        validaAltaComplexidade(senha, 1);
    }
}

function comparaSenhas(senhaRepetida, complexidade){
    let senha = document.getElementById('senha').value;
    if(senhaRepetida != senha){
        document.getElementById('avisoSenhasNaoConferem').style.display = 'block';
    }else{
        document.getElementById('avisoSenhasNaoConferem').style.display = 'none';
    }
    if(((senhaRepetida.length >= 6 && !complexidade) || (complexidade && validaAltaComplexidade(senhaRepetida, 0))) && senhaRepetida == senha){
        document.getElementById('cadastrar').disabled = false;
    }else{
        document.getElementById('cadastrar').disabled = true;
    }
}

function validaAltaComplexidade(senha, num){
    if(num == 0){
        senha = document.getElementById('senha').value;
    }
    document.getElementById('avisoComplexidadeSenhaMaior').style.display = 'block';
    var numeros = /([0-9])/;
    var alfabetoMinusculo = /([a-z])/;
    var alfabetoMaiusculo = /([A-Z])/;
    var ret1, ret2, ret3, ret4;
    if(senha.length >= 8){
        document.getElementById('req1').style.color = 'green';
        ret1 = true;
    }else{
        document.getElementById('req1').style.color = 'red';
        ret1 = false;
    }
    if(senha.match(numeros)){
        document.getElementById('req4').style.color = 'green';
        ret4 = true;
    }else{
        document.getElementById('req4').style.color = 'red';
        ret4 = false;
    }
    if(senha.match(alfabetoMaiusculo)){
        document.getElementById('req2').style.color = 'green';
        ret2 = true;
    }else{
        document.getElementById('req2').style.color = 'red';
        ret2 = false;
    }
    if(senha.match(alfabetoMinusculo)){
        document.getElementById('req3').style.color = 'green';
        ret3 = true;
    }else{
        document.getElementById('req3').style.color = 'red';
        ret3 = false;
    }
    if(ret1 && ret2 && ret3 && ret4){
        return true;
    }else{
        return false;
    }
}

function ativaDesativaConfig(num){
    var valor = document.getElementById("checkOpcao__"+num).innerHTML;
    if(valor == 'Desativado'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Ativado';
        valor = 'Ativado';
    }else if(valor == 'Ativado'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Desativado';
        valor = 'Desativado';
    }
    atualizaConfiguracao(num, valor);
}

function atualizaConfiguracao(id, valor){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/configuracoes/atualizaConfiguracao/'+id+'/'+valor,
        success: function(result){
            console.log('Valor atualizado com sucesso');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao atualizar valor');
        }
    });
}

function limpaSeNaoNumerico(valor){
    if($.isNumeric(valor.value) == false){
        document.getElementById(valor.id).value = "";
    }
}

function mascaraMutuario(o,f){
    v_obj=o
    v_fun=f
    setTimeout('execmascara()',1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function mtel(v){
    v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

function cepMasc(v){
    //Remove tudo o que não é dígito
    v=v.replace(/\D/g,"")
    //Coloca um hífen depois do bloco de cinco dígitos
    v=v.replace(/(\d{5})(\d)/,"$1-$2")
    return v
}

function cpfCnpj(v){

    //Remove tudo o que não é dígito
    v=v.replace(/\D/g,"")
    console.log(v.length);
    if(v.length > 11){
        //Coloca ponto entre o segundo e o terceiro dígitos
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")
        //Coloca ponto entre o quinto e o sexto dígitos
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
        //Coloca uma barra entre o oitavo e o nono dígitos
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
        //Coloca um hífen depois do bloco de quatro dígitos
        v=v.replace(/(\d{4})(\d)/,"$1-$2")
    }else if (v.length <= 11) { //CPF
        //Coloca um ponto entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um ponto entre o terceiro e o quarto dígitos
        //de novo (para o segundo bloco de números)
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um hífen entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    } 
    return v
}

function buscaCep(cep){
    cep = cep.replace('-','');
    var requestURL = "https://viacep.com.br/ws/"+cep+"/json/";
    var request = new XMLHttpRequest();
    var endereco = "";
    request.open('GET', requestURL);
    request.responseType = 'json';
    request.send();
    request.onload = function() {
        endereco = request.response;
        document.getElementById("logradouro").value = endereco.logradouro;
        document.getElementById("complemento").value = endereco.complemento;
        document.getElementById("estado").value = endereco.uf; 
        document.getElementById("cidade").value = endereco.localidade;
        document.getElementById("bairro").value = endereco.bairro;
    }
    
}

function submitSelecao(){
    document.form_seleciona_portaria.submit();
}

function exibeOperacaoEntrada(){
    escondeBtnAbrirCancela();
    escondeBtnFecharCancela();
    document.getElementById('operacaoSaida').style.display = 'none';
    $("#operacaoEntrada").fadeIn(1000);
    $("#operacaoEntrada").fadeIn();
    $('.js-example-basic-multiple').select2({
        tags: true
    });
}

function exibeOperacaoSaida(){
    escondeBtnAbrirCancela();
    escondeBtnFecharCancela();
    document.getElementById('operacaoEntrada').style.display = 'none';
    $("#operacaoSaida").fadeIn(1000);
    $("#operacaoSaida").fadeIn();
    limpaListaVeiculosSaida();
    buscaVeiculosParaSaida();
}

function buscaVeiculosParaSaida(){
    var url = document.getElementById('txtUrl').value;
    var portaria = document.getElementById('portaria_id').value;
    var listaSaida = document.getElementById("veiculoSaida");
    var opcao;
    $.ajax({
        type: "POST",
        data: "portaria="+portaria,
        url: url+'/operacao/buscaVeiculosParaSaida',
        success: function(result){
            try{
                var veiculoSaida = result.split("<registroOperacao>");
                if(veiculoSaida.length == 1){
                    opcao = document.createElement("option");
                    opcao.text = "Não existe veículo para sair nesta portaria";
                    opcao.value = "";
                    listaSaida.options.add(opcao);
                }else if(veiculoSaida.length > 1){
                    opcao = document.createElement("option");
                    opcao.text = "Selecione...";
                    opcao.value = "";
                    listaSaida.options.add(opcao);
                }
                for($i = 1; $i < veiculoSaida.length; $i++){
                    id = veiculoSaida[$i].split("<id>");
                    id = id[1].split("</id>");
                    nome_completo = veiculoSaida[$i].split("<nome_completo>");
                    nome_completo = nome_completo[1].split("</nome_completo>");
                    placaVeiculo = veiculoSaida[$i].split("<placa>");
                    placaVeiculo = placaVeiculo[1].split("</placa>");

                    // Cria elemento option no select
                    opcao = document.createElement("option");
                    opcao.text = placaVeiculo[0]+" - "+nome_completo[0];
                    opcao.value = id[0];
                    listaSaida.options.add(opcao);
                }
            }catch(error){
                opcao = document.createElement("option");
                opcao.text = "Não existe veículo para sair nesta portaria";
                opcao.value = "";
                listaSaida.options.add(opcao);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao buscar veículos');
        }
    });
}


function exibeEscondeCamera(valor, id){
    newId = id.split("camera__");
    if(valor == false){
        document.getElementById('camera_iframe_id_'+newId[1]).style.display = 'none';
    }else if(valor == true){
        document.getElementById('camera_iframe_id_'+newId[1]).style.display = 'block';
    }
}

function validaAbrirCancela(){
    if(document.getElementById('empresa').value != '' && document.getElementById('cnpj').value.length > 17 && document.getElementById('placa').value != '' && document.getElementById('descricao').value != '' && document.getElementById('tipo').value != '' && document.getElementById('motorista').value != '' && document.getElementById('cpfMotorista').value.length > 13){
        exibeBtnAbrirCancela();
    }else{
        escondeBtnAbrirCancela();
    }
}

function executaOperacaoAbrirCancela(){
    if(abreCancela()){
        if(!capturaImagens()){
            document.getElementById('alertaErrorCapturaImagens').style.display = 'block';
        }
        registraOperacao();
    }else{
        document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
    }
}

function exibeBtnAbrirCancelaSaida(){
    $("#btnAbrirCancelaSaida").fadeIn(1000);
    $("#btnAbrirCancelaSaida").fadeIn();
    document.getElementById('btnAbrirCancelaSaida').removeAttribute("disabled");
}

function executaOperacaoAbrirCancelaSaida(){
    if(abreCancela()){
        if(!capturaImagens()){
            document.getElementById('alertaErrorCapturaImagens').style.display = 'block';
        }
        registraOperacaoSaida();
    }else{
        document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
    }
}

function registraOperacaoSaida(){
    var idRegistro = document.getElementById('veiculoSaida').value;
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        type: "POST",
        data: "idRegistro="+idRegistro,
        url: url+'/operacao/registrarSaida',
        success: function(result){
            var retorno = result.split("<registroOperacao>");
            var retorno2 = retorno[1].split("</registroOperacao>");
            if(retorno2[0] == "SUCESSO"){
                $("#btnFecharCancelaSaida").fadeIn(1000);
                $("#btnFecharCancelaSaida").fadeIn();
                 document.getElementById('btnAbrirCancelaSaida').disabled = 'true';
            }else{
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar Operação');
        }
    });
}

function executaOperacaoFechamentoCancelaSaida(){
    var idRegistro = document.getElementById('veiculoSaida').value;
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        type: "POST",
        data: "operacao="+idRegistro,
        url: url+'/operacao/registrarFechamentoCancela/saida',
        success: function(result){
            var retorno = result.split("<registroOperacao>");
            var retorno2 = retorno[1].split("</registroOperacao>");
            if(retorno2[0] == "SUCESSO"){
                $("#btnFecharCancelaSaida").fadeOut(1000);
                $("#btnFecharCancelaSaida").fadeOut();
                $("#btnAbrirCancelaSaida").fadeOut(1000);
                $("#btnAbrirCancelaSaida").fadeOut();
                limpaListaVeiculosSaida();
                buscaVeiculosParaSaida();
            }else{
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar Operação');
        }
    });
    
}

function abreCancela(){
    return true;
}

function capturaImagens(){
    return true;
}

function registraOperacao(){
    var url = document.getElementById('txtUrl').value;
    var empresa = document.getElementById('empresa').value;
    var cnpj = document.getElementById('cnpj').value;
    var placa = document.getElementById('placa').value;
    var descricao = document.getElementById('descricao').value;
    var tipo = document.getElementById('tipo').value;
    var motorista = document.getElementById('motorista').value;
    var cpfMotorista = document.getElementById('cpfMotorista').value;
    var operador = document.getElementById('loginOperador').value;
    var portaria = document.getElementById('portaria_id').value;
    $.ajax({
        type: "POST",
        data: "empresa="+empresa+"&cnpj="+cnpj+"&placa="+placa+"&descricao="+descricao+"&tipo="+tipo+"&motorista="+motorista+"&cpfMotorista="+cpfMotorista+"&usuario="+operador+"&portaria="+portaria,
        url: url+'/operacao/registrarEntrada',
        success: function(result){
            var retorno = result.split("<registroOperacao>");
            var retorno2 = retorno[1].split("</registroOperacao>");
            if(retorno2[0] == "SUCESSO"){
                var retId = result.split("<idOperacao>");
                var retId2 = retId[1].split("</idOperacao>");
                idOperacao = retId2[0];
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'none';
                document.getElementById('btnAbrirCancela').disabled = 'true';
                setTimeout(() => {
                    fechaCancela();
                }, 2000);
            }else{
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar Operação');
        }
    });
}

function fechaCancela(){
    exibeBtnFecharCancela();
}

function executaOperacaoFechamentoCancela(){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        type: "POST",
        data: "operacao="+idOperacao,
        url: url+'/operacao/registrarFechamentoCancela/entrada',
        success: function(result){
            var retorno = result.split("<registroOperacao>");
            var retorno2 = retorno[1].split("</registroOperacao>");
            if(retorno2[0] == "SUCESSO"){
                escondeBtnAbrirCancela();
                escondeBtnFecharCancela();
                document.getElementById('alertaErrorCapturaImagens').style.display = 'none';
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'none';
                document.getElementById('alertaErrorAbrirCancela').style.display = 'none';
                limpaCamposEntrada();
                redefiniListaEmpresas();
            }else{
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar Operação');
        }
    });
}

function redefiniListaEmpresas(){
    var empresa = document.querySelectorAll("#empresa option");
    empresa.forEach(o => o.remove());
    carregaEmpresas();
}

function exibeBtnAbrirCancela(){
    $("#btnAbrirCancela").fadeIn(1000);
    $("#btnAbrirCancela").fadeIn();
    document.getElementById('btnAbrirCancela').removeAttribute("disabled");
}

function escondeBtnAbrirCancela(){
    $("#btnAbrirCancela").fadeOut(1000);
    $("#btnAbrirCancela").fadeOut();
}

function exibeBtnFecharCancela(){
    $("#btnFecharCancela").fadeIn(1000);
    $("#btnFecharCancela").fadeIn(); 
}

function escondeBtnFecharCancela(){
    $("#btnFecharCancela").fadeOut(1000);
    $("#btnFecharCancela").fadeOut();
}

function limpaCamposEntrada(){
    limpaListaVeiculos();
    limpaListaMotorista();
    document.getElementById('cnpj').value = "";
    document.getElementById('cpfMotorista').value = "";
    document.getElementById('tipo').selectedIndex = "";
    document.getElementById('cnpj').removeAttribute("readonly");
    document.getElementById('descricao').removeAttribute("readonly");
    document.getElementById('tipo').removeAttribute("readonly");
    document.getElementById('cpfMotorista').removeAttribute("readonly");
}

function buscaCnpjCpf(empresa){
    if(empresa == ""){
        limpaCamposEntrada();
    }else{
        document.getElementById('cpfMotorista').value = "";
        var url = document.getElementById('txtUrl').value;
        $.ajax({
            url: url+'/empresa/retornaCnpjCpf/'+empresa,
            success: function(result){
                retornaCnpjCpf(result);
                buscaVeiculos(empresa);
                buscaMotorista(empresa);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Empresa não encontrada');
            }
        });
    }
    setTimeout(() => {
        validaAbrirCancela();
    }, 500);
}

function retornaCnpjCpf(result){
    var cnpjcpf = result.split("<cnpjcpf>");
    var cnpjcpf2 = cnpjcpf[1].split("</cnpjcpf>");
    if(cnpjcpf2[0] != "Array"){
        document.getElementById('cnpj').value = cnpjcpf2[0];
        cnpjEmpresa = cnpjcpf2[0];
        document.getElementById('cnpj').setAttribute("readonly", true);
    }else{
        document.getElementById('cnpj').value = "";
        document.getElementById('cnpj').removeAttribute("readonly");
        document.getElementById('descricao').removeAttribute("readonly");
        document.getElementById('tipo').removeAttribute("readonly");
    }
}

function buscaMotorista(empresa){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/motorista/retornaMotoristaPorEmpresa/'+empresa,
        success: function(result){
            exibeMotorista(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Motorista não encontrado');
        }
    });
}

function exibeMotorista(result){
    limpaListaMotorista();
    var motoristaSelect = document.getElementById("motorista");
    var opcao;
    var motorista = result.split("<motorista>");
    var id;
    var nome_completo;
    opcao = document.createElement("option");
    opcao.text = "Selecione...";
    opcao.value = "";
    motoristaSelect.options.add(opcao);
    for($i = 1; $i < motorista.length; $i++){
        id = motorista[$i].split("<id>");
        id = id[1].split("</id>");
        nome_completo = motorista[$i].split("<nome_completo>");
        nome_completo = nome_completo[1].split("</nome_completo>");
        
        // Cria elemento option no select
        opcao = document.createElement("option");
        opcao.text = nome_completo[0];
        opcao.value = id[0];
        motoristaSelect.options.add(opcao);
    }
}

function buscaCpfMotorista(motorista){
    if(motorista == ""){
        document.getElementById('cpfMotorista').value = "";
        document.getElementById('cpfMotorista').removeAttribute("readonly");
    }else{
        var url = document.getElementById('txtUrl').value;
        $.ajax({
            url: url+'/motorista/retornaCpfMotorista/'+motorista,
            success: function(result){
                exibeCpfMotorista(result);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('CPF Motorista não encontrado');
            }
        });
    }
    setTimeout(() => {
        validaAbrirCancela();
    }, 500);
}

function exibeCpfMotorista(result){
    try{
        var cpf = result.split("<cpfMotorista>");
        var cpf2 = cpf[1].split("</cpfMotorista>");
        if(cpf2[0] != "Array"){
            document.getElementById('cpfMotorista').value = cpf2[0];
            document.getElementById('cpfMotorista').setAttribute("readonly", true);
        }else{
            document.getElementById('cpfMotorista').value = "";
            document.getElementById('cpfMotorista').removeAttribute("readonly");
        }
    } catch (error){
        document.getElementById('cpfMotorista').value = "";
        document.getElementById('cpfMotorista').removeAttribute("readonly");
    }
}

function limpaListaMotorista(){
    var motorista = document.querySelectorAll("#motorista option");
    motorista.forEach(o => o.remove());
}

function buscaVeiculos(empresa){
    limpaListaVeiculos();
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/veiculo/retornaVeiculosPorEmpresa/'+empresa,
        success: function(result){
            exibeVeiculo(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Empresa não encontrada');
        }
    });
}

function buscaDescricaoVeiculo(veiculo){
    if(veiculo != ""){
        var url = document.getElementById('txtUrl').value;
        $.ajax({
            url: url+'/veiculo/retornaDescricaoTipoVeiculo/'+veiculo,
            success: function(result){
                exibeDescricaoVeiculo(result);
                selecionaTipoVeiculo(result);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Veículo não encontrada');
            }
        });
    }else{
        document.getElementById('descricao').value = "";
        document.getElementById('tipo').selectedIndex = "";
        document.getElementById('descricao').removeAttribute("readonly");
        document.getElementById('tipo').removeAttribute("readonly");
    }
    setTimeout(() => {
        validaAbrirCancela();
    }, 500);
}

function selecionaTipoVeiculo(result){
    var tipo = document.querySelector('#tipo');
    var tipoVeiculo = result.split("<tipoVeiculo>");
    var tipoVeiculo2 = tipoVeiculo[1].split("</tipoVeiculo>");
    if(tipoVeiculo2[0] != "Array"){
        tipo.selectedIndex = tipoVeiculo2[0];
        document.getElementById('descricao').setAttribute("readonly", true);
        document.getElementById('tipo').setAttribute("readonly", true);
    }else{
        tipo.selectedIndex = "";
        document.getElementById('descricao').removeAttribute("readonly");
        document.getElementById('tipo').removeAttribute("readonly");
    }
}

function exibeDescricaoVeiculo(result){
    try{
        var veiculo = result.split("<veiculo>");
        var veiculo2 = veiculo[1].split("</veiculo>");
        if(veiculo2[0] != "Array"){
            document.getElementById('descricao').value = veiculo2[0];
            document.getElementById('descricao').setAttribute("readonly", true);
            document.getElementById('tipo').setAttribute("readonly", true);
        }else{
            document.getElementById('descricao').value = "";
            document.getElementById('descricao').removeAttribute("readonly");
            document.getElementById('tipo').removeAttribute("readonly");
        }
    }catch(error){
        document.getElementById('descricao').value = "";
        document.getElementById('tipo').selectedIndex = "";
        document.getElementById('descricao').removeAttribute("readonly");
        document.getElementById('tipo').removeAttribute("readonly");
    }
}

function limpaListaVeiculos(){
    var placa = document.querySelectorAll("#placa option");
    var tipo = document.querySelector('#tipo');
    placa.forEach(o => o.remove());
    document.getElementById('descricao').value = "";
    tipo.selectedIndex = "";
}

function limpaListaVeiculosSaida(){
    var listaVeiculosSaida = document.querySelectorAll("#veiculoSaida option");
    listaVeiculosSaida.forEach(o => o.remove());
}

function exibeVeiculo(result){
    var placa = document.getElementById("placa");
    var opcao;
    var veiculo = result.split("<veiculo>");
    var id;
    var placaRetorno;
    opcao = document.createElement("option");
    opcao.text = "Selecione...";
    opcao.value = "";
    placa.options.add(opcao);
    for($i = 1; $i < veiculo.length; $i++){
        id = veiculo[$i].split("<id>");
        id = id[1].split("</id>");
        placaRetorno = veiculo[$i].split("<placa>");
        placaRetorno = placaRetorno[1].split("</placa>");
        
        // Cria elemento option no select
        opcao = document.createElement("option");
        opcao.text = placaRetorno[0];
        opcao.value = id[0];
        placa.options.add(opcao);
    }
}

function carregaEmpresas(){
    var url = document.getElementById('txtUrl').value;
    
    $.ajax({
        url: url+'/empresa/listaEmpresas/listaPainel',
        success: function(result){
            exibeEmpresa(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao carregar empresas');
        }
    });
}

function exibeEmpresa(result){
    var empresa = document.getElementById("empresa");
    var opcao;
    var empresaRet = result.split("<empresa>");
    var id;
    var cnpj;
    var nome_fantasia;
    opcao = document.createElement("option");
    opcao.text = "Selecione...";
    opcao.value = "";
    empresa.options.add(opcao);
    for($i = 1; $i < empresaRet.length; $i++){
        id = empresaRet[$i].split("<id>");
        id = id[1].split("</id>");
        cnpj = empresaRet[$i].split("<cnpj>");
        cnpj = cnpj[1].split("</cnpj>");
        nome_fantasia = empresaRet[$i].split("<nome_fantasia>");
        nome_fantasia = nome_fantasia[1].split("</nome_fantasia>");
        
        // Cria elemento option no select
        opcao = document.createElement("option");
        opcao.text = nome_fantasia[0]+' - '+cnpj[0];
        opcao.value = id[0];
        empresa.options.add(opcao);
    }
}

/*


function ativaDesativaProd(num){
    var valor = document.getElementById("checkOpcao__"+num).innerHTML;
    if(valor == 'Inativo'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Ativo';
        valor = 'Ativo';
    }else if(valor == 'Ativo'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Inativo';
        valor = 'Inativo';
    }
    atualizaProduto(num, valor);
}

function ativaDesativaCliente(num){
    var valor = document.getElementById("checkOpcao__"+num).innerHTML;
    if(valor == 'Inativo'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Ativo';
        valor = 'Ativo';
    }else if(valor == 'Ativo'){
        document.getElementById("checkOpcao__"+num).innerHTML = 'Inativo';
        valor = 'Inativo';
    }
    atualizaCliente(num, valor);
}



function atualizaProduto(id, valor){
    var url = document.getElementById('txtUrl').value;
    console.log(valor);
    $.ajax({
        url: url+'/produto/ativaInativa/'+id+'/'+valor,
        success: function(result){
            console.log('Produto atualizado com sucesso');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao atualizar produto');
        }
    });
}

function atualizaCliente(id, valor){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/cliente/ativaInativa/'+id+'/'+valor,
        success: function(result){
            console.log('Cliente atualizado com sucesso');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao atualizar cliente');
        }
    });
}

function adicionaProdutoCiente(produto_id, cliente_id){
    document.getElementById('addProdCliente_'+produto_id).style.display = 'block';
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/cliente/atualizaClienteProduto/'+cliente_id+'/'+produto_id,
        success: function(result){
            console.log(result)
            console.log('Produto adicionado com sucesso');
            document.getElementById('addProdCliente_'+produto_id).innerHTML = 'Produto adicionado com sucesso';
            document.getElementById('addProdCliente_'+produto_id).style.color = 'green';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao adicionar produto');
            document.getElementById('addProdCliente_'+produto_id).innerHTML = 'Erro ao adicionar produto, tente novamente';
            document.getElementById('addProdCliente_'+produto_id).style.color = 'red';
        }
    });
}

function exibeLabelImportProd(){
    document.getElementById('importandoProd').style.display = 'block';
    document.getElementById('importandoSpinner').style.display = 'block';
}





function checkClubeAtual(valor){
    if(valor.checked){
        document.getElementById('data_fim').disabled = true;
    }else{
        document.getElementById('data_fim').disabled = false;
    }
    
}

function visualizaLink(valor){
    if(valor == 0){
        document.getElementById('perfilPublico').style.display = 'block';
    }else{
        document.getElementById('perfilPublico').style.display = 'none';
    }
}


function calculaTotalPedido(){
    var qtd = document.getElementById('totalItens').value;
    var item = null;
    var total = 0.0;
    for(i = 0; i < qtd; i++){
        item = document.getElementById('total_item_'+i).innerHTML;
        if(item.includes(".")){
            item = item.replace('.', '');    
        }
        item = item.replace(',', '.');
        item = parseFloat(item);
        total = total + item;
    }
    total = convertToReal(total, { moneySign: false });
    document.getElementById('totalPedido').innerHTML = total;    
}

tipoPacoteFardo = null;
idProduto = null;
function calculaFardoPacote(tipoCampo, id, qtd_fardo_pacote, preco_unitario, numItem){
    idProduto = id;
    var qtd = document.getElementById('qtd_'+id).value;
    var total = document.getElementById('total_item_'+numItem).innerHTML;
    if(qtd > 0){
        if(total.includes(".")){
            total = total.replace('.', '');    
        }
        total = total.replace(',', '.');
        if(tipoCampo == "fardo"){
            tipoPacoteFardo = "fardo";
            total = total * qtd_fardo_pacote;
        }else if(tipoCampo == "pacote"){
            tipoPacoteFardo = "pacote";
            total = qtd * preco_unitario;
        }
        total = convertToReal(total, { moneySign: false });
        document.getElementById('total_item_'+numItem).innerHTML = total;
    }else{
        if(tipoCampo == "fardo"){
            tipoPacoteFardo = "fardo";
        }else if(tipoCampo == "pacote"){
            tipoPacoteFardo = "pacote";
        }
    }
    calculaTotalPedido();
}

function incrementa(id, valor, qtd_fardo_pacote, numItem){
    var qtd = document.getElementById('qtd_'+id).value;
    var total = document.getElementById('total_item_'+numItem).innerHTML;
    var tipo;
    if(id != idProduto){
        console.log(tipo);
        if(document.getElementById('fardo_pacote_fardo_'+id).checked){
            tipo = "fardo";
            document.getElementById('fardo_pacote_pacote_'+id).required = false;
        }else if(document.getElementById('fardo_pacote_pacote_'+id).checked){
            tipo = "pacote";
            document.getElementById('fardo_pacote_fardo_'+id).required = false;
        }
        if(tipo == undefined){
            tipo = "pacote";
        }
        tipoPacoteFardo = tipo;
        idProduto = id;
    }else{
        tipo = tipoPacoteFardo;
    }
    console.log(tipo);
    qtd++;
    document.getElementById('qtd_'+id).value = qtd;
    if(total.includes(".")){
        total = total.replace('.', '');    
    }
    total = total.replace(',', '.');
    if(tipo == "fardo"){
        total = valor * qtd * qtd_fardo_pacote;
    }else{
        total = valor * qtd;
    }
    if(qtd > 0){
        document.getElementById('fardo_pacote_fardo_'+id).required = true;
        document.getElementById('fardo_pacote_pacote_'+id).required = true;
        document.getElementById('txtIdProd__'+id).value = id+'__pos';
    }
    if(tipo == "fardo"){
        valor = valor * qtd_fardo_pacote;
    }
    total = convertToReal(total, { moneySign: false });
    document.getElementById('total_item_'+numItem).innerHTML = total;
    calculaTotalPedido();
}

function decrementa(id, valor, qtd_fardo_pacote, numItem){
    var qtd = document.getElementById('qtd_'+id).value;
    var total = document.getElementById('total_item_'+numItem).innerHTML;
    qtd--;
    if(qtd <= 0){
        document.getElementById('fardo_pacote_fardo_'+id).required = false;
        document.getElementById('fardo_pacote_pacote_'+id).required = false;
        qtd = 0;
        document.getElementById('txtIdProd__'+id).value = id;
    }
    document.getElementById('qtd_'+id).value = qtd;
    if(total.includes(".")){
        total = total.replace('.', '');    
    }
    total = total.replace(',', '.');
    if(tipoPacoteFardo == "fardo"){
        total = valor * qtd * qtd_fardo_pacote;
    }else{
        total = valor * qtd;
    }
    if(tipoPacoteFardo == "fardo"){
        valor = valor * qtd_fardo_pacote;
    }
    total = convertToReal(total, { moneySign: false });
    document.getElementById('total_item_'+numItem).innerHTML = total;
    calculaTotalPedido();
}

function convertToReal(number, options = {}) {
    const { moneySign = true } = options;
    if(Number.isNaN(number) || !number) return "0,00";
    if(typeof number === "string") {
        number = Number(number);
    }
    let res;
    const config = moneySign ? {style: 'currency', currency: 'BRL'} : {minimumFractionDigits: 2};
    moneySign
    ? res = number.toLocaleString('pt-BR', config)
    : res = number.toLocaleString('pt-BR', config)
    const needComma = number => number <= 1000;
    if(needComma(number)) {
        res = res.toString().replace(".", ",");
    }
    return res;
}

function avisoDataEntrega(diaSelecionado, hoje, opcao1, opcao2, diasPermitidos){
    var dia = new Date(diaSelecionado);
    var diaSemana = dia.getDay();
    var ret1 = false;
    var ret2 = false;
    var ret3 = false;
    var ret4 = false;
    if(diaSelecionado < hoje){
        document.getElementById('avisoDataAnterior').style.display = 'block';
        ret1 = false;
    }else{
        document.getElementById('avisoDataAnterior').style.display = 'none';
        ret1 = true;
    }

    if(diaSemana == 5 || diaSemana == 6){
        document.getElementById('avisoFimSemana').style.display = 'block';
        ret2 = false;
    }else{
        document.getElementById('avisoFimSemana').style.display = 'none';
        ret2 = true;
    }
    if(opcao1 == 'false' || opcao1 == false){
        if(diaSelecionado == hoje){
            document.getElementById('avisoDataEntregaHoje').style.display = 'block';
            ret3 = false;
        }else{
            document.getElementById('avisoDataEntregaHoje').style.display = 'none';
            ret3 = true;
        }
    }else{
        ret3 = true;
    }
    if(opcao2 == 'false' || opcao2 == false){
        if(verificaDiaSemana(diaSemana, diasPermitidos)){
            document.getElementById('avisoDiasSemanaEntrega').style.display = 'none';
            ret4 = true;
        }else{
            document.getElementById('avisoDiasSemanaEntrega').style.display = 'block';
            ret4 = false;
        }
    }

    if(ret1 == true && ret2 == true && ret3 == true && ret4 == true){
        document.getElementById('finalizarPedido').disabled = false;
    }else{
        document.getElementById('finalizarPedido').disabled = true;
    }

    if(ret2 == true && ret3 == true && ret4 == true){
        document.getElementById('exibeWhatsapp').style.display = 'none';
    }else{
        if(opcao2 == false || opcao1 == false || ret3 ==  false){
            document.getElementById('exibeWhatsapp').style.display = 'block';
        }
    }
}

function verificaDiaSemana(diaSemana, diasPermitidos){
    dias = diasPermitidos.split("---");
    dias.pop();
    if(diaSemana == 6){
        diaSemana = 1;
    }else{
        diaSemana = diaSemana + 2;
    }
    diaSemana = diaSemana.toString()
    if(dias.includes(diaSemana)){
        return true;
    }else{
        return false;
    }
}

function addVisualizacaoBanner(id){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/banner/addVisualizacao/'+id,
        success: function(result){
            console.log('Visualização adicionada');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao adicionar visualização');
        }
    });
}

function addNaoVisualizarNovamente(id){
    const selecionado = document.querySelector("#naoExiberNovamente");
    var check = true;
    selecionado.addEventListener("change", (el) => {
        if (selecionado.checked) {
            check = true;
        }else{
            check = false;
        }
    });
    
    var url = document.getElementById('txtUrl').value;
    if(check == true){
        $.ajax({
            url: url+'/banner/addNaoVisualizarNovamente/'+id,
            success: function(result){
                console.log('Visualização adicionada');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Erro ao adicionar visualização');
            }
        });
    }
}

function realinhaIcones(){
    if(screen.width >= 768){
        alinhaComputador();
    }else{
        alinhaMobile();
    }
}  


function alinhaComputador(){
    document.getElementById("alinhaHome").style.marginLeft = '6px';
    document.getElementById("alinhaPedido").style.marginLeft = '10px';
    document.getElementById("alinhaClientes").style.marginLeft = '15px';
    document.getElementById("alinhaProduto").style.marginLeft = '19px';
    document.getElementById("alinhaUsuarios").style.marginLeft = '20px';
    document.getElementById("alinhaRelatorios").style.marginLeft = '20px';
    document.getElementById("alinhaLogs").style.marginLeft = '2px';
    document.getElementById("alinhaPerfil").style.marginLeft = '3px';     
    document.getElementById("alinhaSair").style.marginLeft = '3px'; 
    document.getElementById("label_complemento").innerHTML = 'Complemento';
    if(screen.width <= 820){
        document.getElementById("label_complemento").innerHTML = 'Comp.'; 
        document.getElementById("btnItensPedido").classList.add('w-100');
        document.getElementById("btnFinalizarItens").classList.add('w-100');
        document.getElementById("Cliente_multi").classList.remove('w-100');
        document.getElementById("produto_multi").classList.remove('w-100');
        document.getElementById("situacao_multi").classList.remove('w-100');
    }else{
        document.getElementById("btnItensPedido").classList.remove('w-100');
        document.getElementById("btnFinalizarItens").classList.remove('w-100');
        document.getElementById("Cliente_multi").classList.add('w-100');
        document.getElementById("produto_multi").classList.add('w-100');
        document.getElementById("situacao_multi").classList.add('w-100');
        
    }
}

function alinhaMobile(){
    document.getElementById("alinhaHome").style.marginLeft = '1px';
    document.getElementById("alinhaPedido").style.marginLeft = '1px';
    document.getElementById("alinhaClientes").style.marginLeft = '1px';
    document.getElementById("alinhaProduto").style.marginLeft = '1px';
    document.getElementById("alinhaUsuarios").style.marginLeft = '1px';
    document.getElementById("alinhaRelatorios").style.marginLeft = '1px';
    document.getElementById("alinhaLogs").style.marginLeft = '1px';
    document.getElementById("alinhaPerfil").style.marginLeft = '1px';     
    document.getElementById("alinhaSair").style.marginLeft = '1px';
    document.getElementById("label_complemento").innerHTML = 'Comp.'; 
    document.getElementById("btnItensPedido").classList.add('w-100');
    document.getElementById("btnFinalizarItens").classList.add('w-100');
    document.getElementById("Cliente_multi").classList.remove('w-100');
    document.getElementById("produto_multi").classList.remove('w-100');
    document.getElementById("situacao_multi").classList.remove('w-100');
}


function calculaTotal(n){
    var preco = document.getElementById("preco-"+n).value;
    var qtd = document.getElementById("qtd-"+n).value;
    preco = preco.replace(',', '.');
    if(preco > 0 && qtd > 0){
        var total = parseFloat(preco) * parseFloat(qtd);
        total = total.toString();
        total = total.replace('.', ',');
        if(total.includes(',') == false){
            total = total+',00';
        }else{
            var div = total.split(",");
            if(div[1].length == 1){
                total = total + '0';
            }else if(div[1].length > 3){
                var format = div[1].substr(0,2);
                total = div[0] + ',' + format;
            }
        }
        document.getElementById("total-"+n).value = total;
        calculaSubTotal();
    }
}

function calculaSubTotal(){
    v1 = document.getElementById("total-1").value;
    v2 = document.getElementById("total-2").value;
    v3 = document.getElementById("total-3").value;
    v4 = document.getElementById("total-4").value;
    v5 = document.getElementById("total-5").value;
    v6 = document.getElementById("total-6").value;
    v7 = document.getElementById("total-7").value;
    v8 = document.getElementById("total-8").value;
    v9 = document.getElementById("total-9").value;
    v10 = document.getElementById("total-10").value;
    v11 = document.getElementById("total-11").value;
    v12 = document.getElementById("total-12").value;
    v13 = document.getElementById("total-13").value;
    v14 = document.getElementById("total-14").value;
    v15 = document.getElementById("total-15").value;
    v16 = document.getElementById("total-16").value;
    v17 = document.getElementById("total-17").value;
    v18 = document.getElementById("total-18").value;
    v19 = document.getElementById("total-19").value;
    v20 = document.getElementById("total-20").value;
    v21 = document.getElementById("total-21").value;
    v22 = document.getElementById("total-22").value;
    v23 = document.getElementById("total-23").value;
    v24 = document.getElementById("total-24").value;
    v25 = document.getElementById("total-25").value;
    v26 = document.getElementById("total-26").value;
    v27 = document.getElementById("total-27").value;
    v28 = document.getElementById("total-28").value;
    v29 = document.getElementById("total-29").value;
    v30 = document.getElementById("total-30").value;


    v1 = v1.replace(',', '.');
    v2 = v2.replace(',', '.');
    v3 = v3.replace(',', '.');
    v4 = v4.replace(',', '.');
    v5 = v5.replace(',', '.');
    v6 = v6.replace(',', '.');
    v7 = v7.replace(',', '.');
    v8 = v8.replace(',', '.');
    v9 = v9.replace(',', '.');
    v10 = v10.replace(',', '.');
    v11 = v11.replace(',', '.');
    v12 = v12.replace(',', '.');
    v13 = v13.replace(',', '.');
    v14 = v14.replace(',', '.');
    v15 = v15.replace(',', '.');
    v16 = v16.replace(',', '.');
    v17 = v17.replace(',', '.');
    v18 = v18.replace(',', '.');
    v19 = v19.replace(',', '.');
    v20 = v20.replace(',', '.');
    v21 = v21.replace(',', '.');
    v22 = v22.replace(',', '.');
    v23 = v23.replace(',', '.');
    v24 = v24.replace(',', '.');
    v25 = v25.replace(',', '.');
    v26 = v26.replace(',', '.');
    v27 = v27.replace(',', '.');
    v28 = v28.replace(',', '.');
    v29 = v29.replace(',', '.');
    v30 = v30.replace(',', '.');

    valorTotal = parseFloat(v1) + parseFloat(v2) + parseFloat(v3) + parseFloat(v4) + parseFloat(v5) + parseFloat(v6) + parseFloat(v7) + parseFloat(v8) + parseFloat(v9) + parseFloat(v10) + parseFloat(v11) + parseFloat(v12) + parseFloat(v13) + parseFloat(v14) + parseFloat(v15) + parseFloat(v16) + parseFloat(v17) + parseFloat(v18) + parseFloat(v19) + parseFloat(v20) + parseFloat(v21) + parseFloat(v22) + parseFloat(v23) + parseFloat(v24) + parseFloat(v25) + parseFloat(v26) + parseFloat(v27) + parseFloat(v28) + parseFloat(v29) + parseFloat(v30);
    valorTotal = valorTotal.toString();
    valorTotal = valorTotal.replace('.', ',');
    if(valorTotal.includes(',') == false){
        valorTotal = valorTotal+',00';
    }else{
        var div = valorTotal.split(",");
        if(div[1].length == 1){
            valorTotal = valorTotal + '0';
        }else if(div[1].length > 2){
            valorTotal = valorTotal.substr(0, 5);
        }
    }
    document.getElementById("subtotal").innerHTML = valorTotal; 
    document.getElementById("txtValortotal").value = valorTotal;
}

function buscaDadosCliente(){
    var cliente = document.getElementById('nomeSel').value;
    var url = document.getElementById('txtUrl').value;
    if(cliente != ""){
        $.ajax({
            url: url+'/cliente/retornaDadosCliente/'+cliente,
            success: function(result){
                document.getElementById('btnClose').click();
                retornaNome(result);
                retornaCpfCnpj(result);
                retornaTelefone(result);   
                retornaCep(result);
                retornaRua(result);
                retornaNumero(result);
                retornaComplemento(result);
                retornaEstado(result);
                retornaCidade(result);
                retornaBairro(result);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Cliente não encontrado');
            }
        });
    }else{
        limpaCamposClientePedido();
    }
    return false;
}

function buscaDadosItem(field){
    var value = document.getElementById(field).value;
    var url = document.getElementById('txtUrl').value;
    var id = field.split('-');
    $.ajax({
        url: url+'/produto/imprimeProdutoPorId/'+value,
        success: function(result){
            retornaUM(result, id[1]);
            retornaPreco(result, id[1]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Item não encontrado');
        }
    });
}

function limpaCamposClientePedido(){
    document.getElementById('cpfcnpj').value = "";
    document.getElementById('telefone').value = "";
    document.getElementById('cep').value = "";
    document.getElementById('logradouro').value = "";
    document.getElementById('numero').value = "";
    document.getElementById('complemento').value = "";
    document.getElementById('estado').value = "";
    document.getElementById('cidade').value = "";
    document.getElementById('bairro').value = "";
}

function retornaNome(result){
    var nome = result.split("<nome>");
    var nome2 = nome[1].split("</nome>");
    document.getElementById('nome').value = nome2[0];
}

function retornaCpfCnpj(result){
    var cpfcnpj = result.split("<cpfcnpj>");
    var cpfcnpj2 = cpfcnpj[1].split("</cpfcnpj>");
    document.getElementById('cpfcnpj').value = cpfcnpj2[0];
}

function retornaTelefone(result){
    var telefone = result.split("<telefone>");
    var telefone2 = telefone[1].split("</telefone>");
    document.getElementById('telefone').value = telefone2[0];
}

function retornaCep(result){
    var cep = result.split("<cep>");
    var cep2 = cep[1].split("</cep>");
    document.getElementById('cep').value = cep2[0];
}

function retornaRua(result){
    var rua = result.split("<rua>");
    var rua2 = rua[1].split("</rua>");
    document.getElementById('logradouro').value = rua2[0];
}

function retornaNumero(result){
    var numero = result.split("<numero>");
    var numero2 = numero[1].split("</numero>");
    document.getElementById('numero').value = numero2[0];
}

function retornaComplemento(result){
    var complemento = result.split("<complemento>");
    var complemento2 = complemento[1].split("</complemento>");
    document.getElementById('complemento').value = complemento2[0];
}

function retornaEstado(result){
    var estado = result.split("<estado>");
    var estado2 = estado[1].split("</estado>");
    document.getElementById('estado').value = estado2[0];
}

function retornaCidade(result){
    var cidade = result.split("<cidade>");
    var cidade2 = cidade[1].split("</cidade>");
    document.getElementById('cidade').value = cidade2[0];
}

function retornaBairro(result){
    var bairro = result.split("<bairro>");
    var bairro2 = bairro[1].split("</bairro>");
    document.getElementById('bairro').value = bairro2[0];
}

function retornaBairro(result){
    var bairro = result.split("<bairro>");
    var bairro2 = bairro[1].split("</bairro>");
    document.getElementById('bairro').value = bairro2[0];
}

function retornaUM(result, field){
    var um = result.split("<UM>");
    var um2 = um[1].split("</UM>");
    document.getElementById('um-'+field).value = um2[0];
}

function retornaPreco(result, field){
    var preco = result.split("<preco>");
    var preco2 = preco[1].split("</preco>");
    document.getElementById('preco-'+field).value = preco2[0];
}

function exibeCampos(){
    var num = document.getElementById('num_campos_exibidos').value;
    num = parseInt(num)+1;
    document.querySelector('.coluna-item-'+num).style.display = 'block';
    document.querySelector('.coluna-um-'+num).style.display = 'block';
    document.querySelector('.coluna-preco-'+num).style.display = 'block';
    document.querySelector('.coluna-qtd-'+num).style.display = 'block';
    document.querySelector('.coluna-total-'+num).style.display = 'block';
    document.getElementById('num_campos_exibidos').value = num;
}

function validaData(dataEntrega){
    const date = new Date();
    const day = date.getDate();
    const month = date.getMonth();
    const year = date.getFullYear();
    const dataAtual = year+'-'+month+'-'+day;
    dataEntrega = dataEntrega.split("T");
    const diffInMs   = new Date(dataEntrega[0]) - new Date(dataAtual)
    const diffInDays = diffInMs / (1000 * 60 * 60 * 24) - 31;
    if(diffInDays < 0){
        document.getElementById('avisoDiferencaData').style.display = 'none';
    }else{
        document.getElementById('avisoDiferencaData').style.display = 'none';
    }
}

function mudaStatusItem(obj, pedido_id){
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        url: url+'/pedido/atualizaStatusItem/'+obj.value+'/'+obj.id,
        success: function(result){
            var id = obj.id.split('_');
            id = id[1];
            console.log('obj value: ', obj.value);
            console.log('id: ', id);
            var id_pedido = id.split('-');
            id_pedido = id_pedido[0];
            if(obj.value == 'Em produção'){
                document.getElementById(obj.id).classList.remove('btn-secondary');
                document.getElementById(obj.id).classList.add('btn-info');
                document.getElementById(obj.id).value = 'Produzido';
                document.getElementById('lbStatusItem_'+id).innerHTML = 'Em produção';
                document.getElementById('lbStatusItem_'+id).style.color = 'orange';
            }else if(obj.value == 'Produzido'){
                document.getElementById(obj.id).classList.remove('btn-info');
                document.getElementById(obj.id).classList.add('btn-success');
                document.getElementById(obj.id).value = 'Entregar';
                document.getElementById('lbStatusItem_'+id).innerHTML = 'Produzido';
                document.getElementById('lbStatusItem_'+id).style.color = 'blue';
                document.getElementById('btnCancelarItem_'+id).type = 'hidden';
            }else if(obj.value == 'Entregar'){
                document.getElementById('btnStatusItem_'+id).type = 'hidden';
                document.getElementById('btnCancelarItem_'+id).type = 'hidden';
                document.getElementById('lbStatusItem_'+id).innerHTML = 'Entregue';
                document.getElementById('lbStatusItem_'+id).style.color = 'green';
            }else if(obj.value == 'Cancelar'){
                document.getElementById('btnStatusItem_'+id).type = 'hidden';
                document.getElementById('btnCancelarItem_'+id).type = 'hidden';
                document.getElementById('btnEntregarPedidoCompleto_'+id_pedido).type = 'hidden';
                document.getElementById('lbStatusItem_'+id).innerHTML = 'Cancelado';
                document.getElementById('lbStatusItem_'+id).style.color = 'black';
            }
            setStatusPedido(result, pedido_id);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao atualizar status do item');
        }
    });
}

function setStatusPedido(obj, pedido_id){
    var status1 = obj.split("<STATUS_PEDIDO>");
    var status2 = status1[1].split("</STATUS_PEDIDO>");
    document.getElementById('lbStatusPedido_'+pedido_id).innerHTML = status2[0];
    if(status2[0] == 'Não entregue'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'white';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = 'red';
    }else if(status2[0] == 'Cancelado'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'white';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = 'black';
    }else if(status2[0] == 'Ent. Parcial'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'black';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = '#58FAF4';
    }else if(status2[0] == 'Produzido'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'white';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = 'blue';
    }else if(status2[0] == 'Em produção'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'white';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = 'orange';
    }else if(status2[0] == 'Entregue'){
        document.getElementById('lbStatusPedido_'+pedido_id).style.color = 'white';
        document.getElementById('lbStatusPedido_'+pedido_id).style.backgroundColor = 'green';
    }    
}

*/