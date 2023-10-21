var idOperacao;
var idOperacaoEmergencia;
var usa_balanca;

$(document).ready(function(){
    $('#endereco_ip').mask('999.999.999.999');
});

$(document).ready(function(){
    $("#placaVeiculo").mask('AAA-9A99');
});

$(document).ready(function() {
    usa_balanca = document.getElementById('usa_balanca');
    if(usa_balanca){
        usa_balanca = usa_balanca.value;
        if(usa_balanca == 1){
            usa_balanca = true;
        }else{
            usa_balanca = false;
        }
    }else{
        usa_balanca = false;
    }
});

$(document).ready(function(){
    verificaStatusPlaca();
});

function verificaStatusPlaca(){
    console.log('carregando status');
    var statusPlaca = document.getElementById("statusPlaca");
    var porta = document.getElementById('porta').value;
    var placa_endereco = "http://"+document.getElementById("endereco_ip_placa").value+":"+porta;
    statusPlaca.innerHTML = "<b>Carregando status</b>";
    var seg = 1;
    var carregandoStatus = setInterval(() => {
        if(seg==1){
            statusPlaca.innerHTML = "<div><b>Carregando status.</b></div>";
        }else if(seg==2){
            statusPlaca.innerHTML = "<div><b>Carregando status..</b></div>";
        }else if(seg==3){
            statusPlaca.innerHTML = "<div><b>Carregando status...</b></div>";
        }
        if(seg == 3){
            seg = 0;
        }else{
            seg++;
        }
    }, 500);
    setInterval(() => {
        clearInterval(carregandoStatus);
        var request = new XMLHttpRequest();
        request.open('GET', placa_endereco);
        request.responseType = 'json';
        request.send();
        request.onload = function() {
            statusPlaca.innerHTML = "<div style='color:green'><b>Online</b></div>";
        }
        request.onerror = function(){
            statusPlaca.innerHTML = "<div style='color:red'><b>Offline</b></div>";
        }
    }, 10000);
}

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

function selecionaPortaria(){
    gravaLog('selecionaPortaria');
    document.form_seleciona_portaria.submit();
}

function exibeOperacaoEntrada(){
    gravaLog('exibeOperacaoEntrada');
    escondeBtnAbrirCancela();
    escondeBtnFecharCancela();
    document.getElementById('operacaoSaida').style.display = 'none';
    document.getElementById('operacaoEmergencia').style.display = 'none';
    document.getElementById('btnAbrirCancelaEmergencia').style.display = 'none';
    document.getElementById('btnFecharCancelaEmegrencia').style.display = 'none';
    document.getElementById('alertaErrorVeiculoNaoPodeEntrar').style.display = 'none';
    $("#operacaoEntrada").fadeIn(1000);
    $("#operacaoEntrada").fadeIn();
    $('.js-example-basic-multiple').select2({
        tags: true
    });
}

function exibeOperacaoSaida(){
    gravaLog('exibeOperacaoSaida');
    escondeBtnAbrirCancela();
    escondeBtnFecharCancela();
    document.getElementById('operacaoEntrada').style.display = 'none';
    document.getElementById('operacaoEmergencia').style.display = 'none';
    document.getElementById('btnAbrirCancelaEmergencia').style.display = 'none';
    document.getElementById('btnFecharCancelaEmegrencia').style.display = 'none';
    document.getElementById('alertaErrorVeiculoNaoPodeEntrar').style.display = 'none';
    $("#operacaoSaida").fadeIn(1000);
    $("#operacaoSaida").fadeIn();
    limpaListaVeiculosSaida();
    buscaVeiculosParaSaida();
}

function exibeOperacaoEmergencia(){
    gravaLog('exibeOperacaoEmergencia');
    escondeBtnAbrirCancela();
    escondeBtnFecharCancela();
    document.getElementById('operacaoEntrada').style.display = 'none';
    document.getElementById('operacaoSaida').style.display = 'none';
    $("#operacaoEmergencia").fadeIn(1000);
    $("#operacaoEmergencia").fadeIn();
    document.getElementById('btnAbrirCancelaEmergencia').style.display = 'block';
    document.getElementById('alertaErrorVeiculoNaoPodeEntrar').style.display = 'none';
}

function executaOperacaoAbrirCancelaEmergencia(){
    gravaLog('executaOperacaoAbrirCancelaEmergencia');
    if(document.getElementById('obsEmergencia').value == ""){
        document.getElementById('avisoObservacao').style.display = 'block';
        document.getElementById('obsEmergencia').focus();
    }else{
        document.getElementById('avisoObservacao').style.display = 'none';
        abreCancela('emergencia');
    }
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
                }
                if(veiculoSaida.length <= 10){
                    listaSaida.setAttribute("size", veiculoSaida.length);
                }else{
                    listaSaida.setAttribute("size", 10);
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
    if(document.getElementById('empresa').value != '' && document.getElementById('cnpj').value.length > 13 && document.getElementById('placa').value != '' && document.getElementById('descricao').value != '' && document.getElementById('tipo').value != '' && document.getElementById('motorista').value != '' && document.getElementById('cpfMotorista').value.length > 13){
        exibeBtnAbrirCancela();
    }else{
        escondeBtnAbrirCancela();
    }
}

function executaOperacaoAbrirCancela(){
    gravaLog('executaOperacaoAbrirCancela');
    abreCancela('entrada');
}

function exibeBtnAbrirCancelaSaida(){
    gravaLog('exibeBtnAbrirCancelaSaida');
    $("#btnAbrirCancelaSaida").fadeIn(1000);
    $("#btnAbrirCancelaSaida").fadeIn();
    document.getElementById('btnAbrirCancelaSaida').removeAttribute("disabled");
}

function executaOperacaoAbrirCancelaSaida(){
    gravaLog('executaOperacaoAbrirCancelaSaida');
    abreCancela("saida")
}

function executaOperacaoFechamentoCancelaEmergencia(){
    gravaLog('executaOperacaoFechamentoCancelaEmergencia');
    var url = document.getElementById('txtUrl').value;
    var releFechaCancela = document.getElementById('rele_fecha_cancela').value;
    var rfa = defineRele(releFechaCancela);
    var endereco_ip = document.getElementById('endereco_ip_placa').value;
    var porta = document.getElementById('porta').value;
    var requestURLAbreRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"1";
    var requestURLFechaRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"0";
    var request = new XMLHttpRequest();
    var response = '';
    request.open('GET', requestURLAbreRele);
    request.responseType = 'json';
    request.send();
    request.onload = function() {
        response = request.response;
        var response_status;
        if(releFechaCancela == 'r1'){
            response_status = response.status[0].r1;
        }else if(releFechaCancela == 'r2'){
            response_status = response.status[0].r2;
        }else if(releFechaCancela == 'r3'){
            response_status = response.status[0].r3;
        }else if(releFechaCancela == 'r4'){
            response_status = response.status[0].r4;
        }
        if(response_status === 1){
            capturaImagens('emergencia', 1);
            $.ajax({
                type: "POST",
                data: "idOperacao="+idOperacaoEmergencia,
                url: url+'/operacao/fechaCancelaEmergencia',
                success: function(result){
                    try{
                        var retorno = result.split("<registroOperacao>");
                        var retorno2 = retorno[1].split("</registroOperacao>");
                        if(retorno2[0] == "SUCESSO"){
                            $("#btnAbrirCancelaEmergencia").fadeOut(1000);
                            $("#btnAbrirCancelaEmergencia").fadeOut();
                            $("#btnFecharCancelaEmegrencia").fadeOut(1000);
                            $("#btnFecharCancelaEmegrencia").fadeOut();
                            document.getElementById('operacaoEmergencia').style.display = 'none';
                            document.getElementById('alertaErrorRegistrarOperacao').style.display = 'none';
                        }else{
                            document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
                        }
                    }catch (error){
                        document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Falha ao registrar Operação');
                }
            });
            request.open('GET', requestURLFechaRele);
        }else{
            document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
        }
    }    
}

function registraOperacaoEmergencia(){
    var url = document.getElementById('txtUrl').value;
    var portaria = document.getElementById('portaria_id').value;
    var usuario = document.getElementById('loginOperador').value;
    var observacao = document.getElementById('obsEmergencia').value;
    $.ajax({
        type: "POST",
        data: "portaria_id="+portaria+"&usuario_id="+usuario+"&observacao="+observacao,
        url: url+'/operacao/registrarOperacaoEmergencia',
        success: function(result){
            try{
                var retorno = result.split("<registroOperacao>");
                var retorno2 = retorno[1].split("</registroOperacao>");
                if(retorno2[0] == "SUCESSO"){
                    $("#btnFecharCancelaEmegrencia").fadeIn(1000);
                    $("#btnFecharCancelaEmegrencia").fadeIn();
                    document.getElementById('btnAbrirCancelaEmergencia').disabled = 'true';
                    retorno = result.split("<idOperacaoEmergencia>");
                    retorno2 = retorno[1].split("</idOperacaoEmergencia>");
                    idOperacaoEmergencia = retorno2[0];
                }else{
                    document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
                }
            }catch (error){
                document.getElementById('alertaErrorRegistrarOperacao').style.display = 'block';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar Operação');
        }
    });
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
    gravaLog('executaOperacaoFechamentoCancelaSaida');
    var idRegistro = document.getElementById('veiculoSaida').value;
    var url = document.getElementById('txtUrl').value;
    var releFechaCancela = document.getElementById('rele_fecha_cancela').value;
    var rfa = defineRele(releFechaCancela);
    var endereco_ip = document.getElementById('endereco_ip_placa').value;
    var porta = document.getElementById('porta').value;
    var requestURLAbreRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"1";
    var requestURLFechaRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"0";
    var request = new XMLHttpRequest();
    var response = '';
    request.open('GET', requestURLAbreRele);
    request.responseType = 'json';
    request.send();
    request.onload = function() {
        response = request.response;
        var response_status;
        if(releFechaCancela == 'r1'){
            response_status = response.status[0].r1;
        }else if(releFechaCancela == 'r2'){
            response_status = response.status[0].r2;
        }else if(releFechaCancela == 'r3'){
            response_status = response.status[0].r3;
        }else if(releFechaCancela == 'r4'){
            response_status = response.status[0].r4;
        }
        if(response_status === 1){
            capturaImagens('saida', 1);
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
            request.open('GET', requestURLFechaRele);
        }else{
            document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
        }
    } 

    
    
    
}

function abreCancela(tipo){
    gravaLog('abreCancela');
    try {
        $("#alertaAbrindoCancela").fadeIn(2000);
        $("#alertaAbrindoCancela").fadeIn();
        var releAbreCancela = document.getElementById('rele_abre_cancela').value;
        rac = defineRele(releAbreCancela);
        var endereco_ip = document.getElementById('endereco_ip_placa').value;
        var porta = document.getElementById('porta').value;
        var requestURLAbreRele = "http://"+endereco_ip+":"+porta+"/"+rac+"1";
        var requestURLFechaRele = "http://"+endereco_ip+":"+porta+"/"+rac+"0";
        var request = new XMLHttpRequest();
        var response = '';
        request.open('GET', requestURLAbreRele);
        request.responseType = 'json';
        request.send();
        request.onload = function() {
            response = request.response;
            var response_status;
            if(releAbreCancela == 'r1'){
                response_status = response.status[0].r1;
            }else if(releAbreCancela == 'r2'){
                response_status = response.status[0].r2;
            }else if(releAbreCancela == 'r3'){
                response_status = response.status[0].r3;
            }else if(releAbreCancela == 'r4'){
                response_status = response.status[0].r4;
            }
            if(response_status === 1){
                $("#alertaAbrindoCancela").fadeOut(1500);
                $("#alertaAbrindoCancela").fadeOut();
                capturaImagens(tipo, 0);
                setTimeout(() => {
                    $("#alertaCapturandoImagens").fadeIn(1500);
                    $("#alertaCapturandoImagens").fadeIn();
                    $("#alertaCapturandoImagens").fadeOut(1500);
                    $("#alertaCapturandoImagens").fadeOut();
                }, 500);
                if(tipo == "entrada"){
                    registraOperacao();
                }else if(tipo == "emergencia"){
                    registraOperacaoEmergencia();
                }else if(tipo == "saida"){
                    registraOperacaoSaida();
                }
                request.open('GET', requestURLFechaRele);
            }else{
                document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
            }
        }
        request.onerror = function(){
            $("#alertaAbrindoCancela").fadeOut(1000);
            $("#alertaAbrindoCancela").fadeOut();
            document.getElementById('alertaErrorAbrirCancela').style.display = 'block'
        }
    } catch (error) {
        $("#alertaAbrindoCancela").fadeOut(1000);
        $("#alertaAbrindoCancela").fadeOut();
        document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
    }
    
}

function defineRele(rele){
    if(rele == "r1"){
        return '1';
    }else if(rele == "r2"){
        return '2';
    }else if(rele == "r3"){
        return '3';
    }else if(rele == "r4"){
        return '4';
    }
}

function capturaImagens(tipo, operacao){
    gravaLog('capturaImagens');
    var portaria = document.getElementById('portaria_id').value;
    var url = document.getElementById('txtUrl').value;
    $.ajax({
        type: "POST",
        data: "portaria="+portaria+"&tipo="+tipo+"&operacao="+operacao,
        url: url+'/operacao/capturaImagem',
        success: function(result){
            console.log('Imagens capturadas com sucesso');
            return true;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            document.getElementById('alertaErrorCapturaImagens').style.display = 'block';
            console.log('Erro ao capturar imagens');
            return false
        }
    });
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
    gravaLog('executaOperacaoFechamentoCancela');
    var url = document.getElementById('txtUrl').value;
    var releFechaCancela = document.getElementById('rele_fecha_cancela').value;
    var rfa = defineRele(releFechaCancela);
    var endereco_ip = document.getElementById('endereco_ip_placa').value;
    var porta = document.getElementById('porta').value;
    var requestURLAbreRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"1";
    var requestURLFechaRele = "http://"+endereco_ip+":"+porta+"/"+rfa+"0";
    var request = new XMLHttpRequest();
    var response = '';
    request.open('GET', requestURLAbreRele);
    request.responseType = 'json';
    request.send();
    request.onload = function() {
        response = request.response;
        var response_status;
        if(releFechaCancela == 'r1'){
            response_status = response.status[0].r1;
        }else if(releFechaCancela == 'r2'){
            response_status = response.status[0].r2;
        }else if(releFechaCancela == 'r3'){
            response_status = response.status[0].r3;
        }else if(releFechaCancela == 'r4'){
            response_status = response.status[0].r4;
        }
        if(response_status === 1){
            capturaImagens('entrada', 1);
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
            request.open('GET', requestURLFechaRele);
        }else{
            document.getElementById('alertaErrorAbrirCancela').style.display = 'block';
        }
    }
    request.onerror = function(){
        document.getElementById('alertaErrorAbrirCancela').style.display = 'block'
    }
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
    gravaLog('exibeBtnFecharCancela');
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
    if(cnpjcpf2[0] != ""){
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
        if(cpf2[0] != ""){
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
                var liberarEntrada = result.split("<veiculoPodeEntrar>");
                liberarEntrada = liberarEntrada[1].split("</veiculoPodeEntrar>");
                if(liberarEntrada[0] == "Sim"){
                    desbloqueiaCampos();
                    document.getElementById('alertaErrorVeiculoNaoPodeEntrar').style.display = 'none';
                    exibeDescricaoVeiculo(result);
                    selecionaTipoVeiculo(result);
                    
                }else{
                    document.getElementById('alertaErrorVeiculoNaoPodeEntrar').style.display = 'block';
                    bloqueiaCampos();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Veículo não encontrado');
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

function bloqueiaCampos(){
    document.getElementById('descricao').disabled = true;
    document.getElementById('tipo').disabled = true;
    document.getElementById('motorista').disabled = true;
    document.getElementById('cpfMotorista').disabled = true;
}

function desbloqueiaCampos(){
    document.getElementById('descricao').disabled = false;
    document.getElementById('tipo').disabled = false;
    document.getElementById('motorista').disabled = false;
    document.getElementById('cpfMotorista').disabled = false;
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

function capturaPeso(){
    $.ajax({
        url: 'http://localhost/geraPeso/',
        success: function(result){
            document.getElementById("peso").value = result;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao capturar peso');
        }
    });
}

function gravaLog(funcao){
    var url = document.getElementById('txtUrl').value;
    var mensagem = funcao;
    $.ajax({
        type: "POST",
        data: "mensagem="+mensagem,
        url: url+'/logs/gravaLogFrontEnd',
        success: function(result){},
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Falha ao registrar log');
        }
    });
}