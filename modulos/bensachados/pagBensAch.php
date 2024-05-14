<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="class/dataTable/datatables.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/relacmod.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="comp/css/jquery-confirm.min.css" />
        <script src="comp/js/jquery-confirm.min.js"></script> <!-- https://craftpip.github.io/jquery-confirm/#quickfeatures -->
        <script src="class/dataTable/datatables.min.js"></script>
        <script src="comp/js/jquery.mask.js"></script>
        <style>
            .quadro{
                position: relative; float: left; text-align: center; margin: 5px; width: 95%; padding: 2px; padding-top: 5px;
            }
        </style>
        <script>
             function ajaxIni(){
                try{
                ajax = new ActiveXObject("Microsoft.XMLHTTP");}
                catch(e){
                try{
                   ajax = new ActiveXObject("Msxml2.XMLHTTP");}
                   catch(ex) {
                   try{
                       ajax = new XMLHttpRequest();}
                       catch(exc) {
                          alert("Esse browser não tem recursos para uso do Ajax");
                          ajax = null;
                       }
                   }
                }
            }
            $(document).ready(function(){
                $("#carregaBens").load("modulos/bensachados/relBens.php");
                $("#dataregistro").mask("99/99/9999");
                $("#dataachado").mask("99/99/9999");
                $("#cpfproprietario").mask("999.999.999-99");
                document.getElementById("botimprReg").style.visibility = "hidden"; 
                document.getElementById("botInsReg").style.visibility = "hidden"; 
                if(parseInt(document.getElementById("guardaescEdit").value) === 1){ // tem que estar autorizado no cadastro de usuários
                    if(parseInt(document.getElementById("admIns").value) >= parseInt(document.getElementById("UsuAdm").value)){
                        document.getElementById("botInsReg").style.visibility = "visible"; 
                    }
                    if(parseInt(document.getElementById("UsuAdm").value) > 6){
                        document.getElementById("botInsReg").style.visibility = "visible"; 
                    }
                }
            });

            function abreRegistro(){
                document.getElementById("guardacod").value = 0;
                document.getElementById("botsalvareg").style.visibility = "visible"; 
                document.getElementById("dataregistro").value = document.getElementById("guardahoje").value;
                document.getElementById("dataachado").value = document.getElementById("guardahoje").value;
                document.getElementById("numprocesso").innerHTML = "";
                document.getElementById("descdobem").value = "";
                document.getElementById("localachado").value = "";
                document.getElementById("nomeachou").value = "";
                document.getElementById("telefachou").value = "";
                document.getElementById("relacmodalRegistro").style.display = "block";
            }

            function salvaModalRegistro(){
                if(parseInt(document.getElementById("mudou").value) === 0){
                    document.getElementById("relacmodalRegistro").style.display = "none";
                    return false;
                }
                if(document.getElementById("dataregistro").value === ""){
                    let element = document.getElementById('dataregistro');
                    element.classList.add('destacaBorda');
                    document.getElementById("dataregistro").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Insira a data do registro";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("dataachado").value === ""){
                    let element = document.getElementById('dataachado');
                    element.classList.add('destacaBorda');
                    document.getElementById("dataachado").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Insira a data em que foi encontradp";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("descdobem").value === ""){
                    let element = document.getElementById('descdobem');
                    element.classList.add('destacaBorda');
                    document.getElementById("descdobem").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Escreva uma breve descrição do bem encontrado";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("localachado").value === ""){
                    let element = document.getElementById('localachado');
                    element.classList.add('localachado');
                    document.getElementById("localachado").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Escreva uma breve descrição do local onde foi encontrado";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("nomeachou").value === ""){
                    let element = document.getElementById('nomeachou');
                    element.classList.add('nomeachou');
                    document.getElementById("nomeachou").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Anote o nome do colaborador que encontrou";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("telefachou").value === ""){
                    let element = document.getElementById('telefachou');
                    element.classList.add('telefachou');
                    document.getElementById("telefachou").focus();
                    $('#mensagem').fadeIn("slow");
                    document.getElementById("mensagem").innerHTML = "Anote o telefone do colaborador que encontrou";
                    $('#mensagem').fadeOut(2000);
                    return false;
                }
                 if(!validaData(document.getElementById("dataachado").value)){
                    let element = document.getElementById('dataachado');
                    element.classList.add('destacaBorda');
                    $.confirm({
                        title: 'Atenção!',
                        content: 'A data está incorreta.',
                        draggable: true,
                        buttons: {
                            OK: function(){}
                        }
                    });
                    return false;
                }
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=salvaRegBem&codigo="+document.getElementById("guardacod").value+
                    "&dataregistro="+encodeURIComponent(document.getElementById("dataregistro").value)+
                    "&dataachado="+encodeURIComponent(document.getElementById("dataachado").value)+
                    "&descdobem="+encodeURIComponent(document.getElementById("descdobem").value)+
                    "&localachado="+encodeURIComponent(document.getElementById("localachado").value)+
                    "&nomeachou="+encodeURIComponent(document.getElementById("nomeachou").value)+
                    "&numrelato="+encodeURIComponent(document.getElementById("guardaNumRelat").value)+
                    "&telefachou="+encodeURIComponent(document.getElementById("telefachou").value)
                    , true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("guardacod").value = Resp.codigonovo;
                                    document.getElementById("guardaNumRelat").value = Resp.numrelat;
                                    document.getElementById("mudou").value = "0";
                                    $("#carregaBens").load("modulos/bensachados/relBens.php");
                                    document.getElementById("relacmodalRegistro").style.display = "none";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            function verRegistroRcb(Cod){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=buscaBem&codigo="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("guardacod").value = Cod;
                                    document.getElementById("mudou").value = "0";
                                    document.getElementById("dataregistro").value = Resp.datareg;
                                    document.getElementById("dataachado").value = Resp.dataachou;
                                    document.getElementById("descdobem").value = Resp.descdobem;
                                    document.getElementById("localachado").value = Resp.localachou;
                                    document.getElementById("nomeachou").value = Resp.nomeachou;
                                    document.getElementById("telefachou").value = Resp.telefachou;
                                    document.getElementById("guardaNumRelat").value = Resp.numprocesso;
                                    document.getElementById("numprocesso").innerHTML = "Registrado sob nº "+Resp.numprocesso;
                                    document.getElementById("botsalvareg").innerHTML = "Salvar";
                                    document.getElementById("relacmodalRegistro").style.display = "block";
                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            function mostraBem(Cod, modal, Restit){
                ajaxIni();
                if(ajax){
                    ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=buscaBem&codigo="+Cod, true);
                    ajax.onreadystatechange = function(){
                        if(ajax.readyState === 4 ){
                            if(ajax.responseText){
//alert(ajax.responseText);
                                Resp = eval("(" + ajax.responseText + ")");
                                if(parseInt(Resp.coderro) === 1){
                                    alert("Houve um erro no servidor.")
                                }else{
                                    document.getElementById("guardacod").value = Cod;
                                    document.getElementById("mudou").value = "0";
                                    document.getElementById("codusuins").value = Resp.codusuins;
                                    if(parseInt(modal) === 1){
                                        document.getElementById("numprocessotransf").innerHTML = Resp.numprocesso;
                                        document.getElementById("etiqprocessoReg").innerHTML = "registrado por "+Resp.nomeusuins+" em "+Resp.datareg+".";
                                        document.getElementById("descdobemtransf").innerHTML = Resp.descdobem;
                                        document.getElementById("relacmodalTransfGuarda").style.display = "block";
                                    }
                                    if(parseInt(modal) === 2){
                                        if(parseInt(Restit) > 0){
                                            document.getElementById('nomeproprietario').disabled = true;
                                            document.getElementById('cpfproprietario').disabled = true;
                                            document.getElementById('telefproprietario').disabled = true;
                                            document.getElementById('botsalvaRestit').disabled = true;
                                        }else{
                                            document.getElementById('nomeproprietario').disabled = false;
                                            document.getElementById('cpfproprietario').disabled = false;
                                            document.getElementById('telefproprietario').disabled = false;
                                            document.getElementById('botsalvaRestit').disabled = false;
                                        }
                                     
                                        document.getElementById("numprocessoRest").innerHTML = Resp.numprocesso;
                                        document.getElementById("etiqprocessoRest").innerHTML = "registrado por "+Resp.nomeusuins+" em "+Resp.datareg+".";
                                        document.getElementById("descdobemRest").innerHTML = Resp.descdobem;
                                        document.getElementById('nomeproprietario').value = Resp.nomepropriet;
                                        document.getElementById('cpfproprietario').value = Resp.cpfpropriet;
                                        document.getElementById('telefproprietario').value = Resp.telefpropriet;
                                        document.getElementById("relacmodalRestit").style.display = "block";
                                    }
                                    if(parseInt(modal) === 3){
                                        if(parseInt(Resp.intervalo) < 0){ // aguardar 3 meses para encaminhar o bem
                                            $.confirm({
                                                title: 'Atenção!',
                                                content: 'Ainda não cumpriu o prazo de 90 dias.',
                                                draggable: true,
                                                buttons: {
                                                    OK: function(){}
                                                }
                                            });
                                            return false;
                                        }else{
                                            document.getElementById("numprocessoEncam").innerHTML = Resp.numprocesso;
                                            document.getElementById("etiqprocessoEncam").innerHTML = "registrado por "+Resp.nomeusuins+" em "+Resp.datareg+".";
                                            document.getElementById("descdobemEncam").innerHTML = Resp.descdobem;
//                                            document.getElementById('nomeproprietario').value = "";
//                                            document.getElementById('cpfproprietario').value = "";
//                                            document.getElementById('telefproprietario').value = "";
                                            document.getElementById("relacmodalEncam").style.display = "block";
                                        }
                                    }
                                    if(parseInt(modal) === 4){
                                        document.getElementById("numprocessoDest").innerHTML = Resp.numprocesso;
                                        document.getElementById("etiqprocessoDest").innerHTML = "registrado por "+Resp.nomeusuins+" em "+Resp.datareg+".";
                                        document.getElementById("descdobemDest").innerHTML = Resp.descdobem;
                                        document.getElementById("selecdestino").value = Resp.destino;
                                        document.getElementById("setordestino").value = Resp.setordestino;
                                        document.getElementById("nomefuncionario").value = Resp.nomerecebeu;
                                        document.getElementById('nomeproprietario').value = Resp.nomepropriet;
                                        document.getElementById('cpfproprietario').value = Resp.cpfpropriet;
                                        document.getElementById('telefproprietario').value = Resp.telefpropriet;
                                        document.getElementById("relacmodalDest").style.display = "block";
                                    }

                                }
                            }
                        }
                    };
                    ajax.send(null);
                }
            }

            //Aceita a transferência do objeto para guarda
            function salvaModalTransf(){
                $Mensag = "Aceitar a guarda.";
                if(parseInt(document.getElementById("codusuins").value) === parseInt(document.getElementById("usuarioID").value)){
                    $Mensag = "Aceitar a guarda. <br>Mesmo usuário que registrou?";
                }
                $.confirm({
                    title: $Mensag,
                    content: 'Confirma aceitar a guarda deste objeto?',
                    autoClose: 'Não|10000',
                    draggable: true,
                    buttons: {
                        Sim: function () {
                            ajaxIni();
                            if(ajax){
                                ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=RcbGuardaBem&codigo="+document.getElementById("guardacod").value, true);
                                ajax.onreadystatechange = function(){
                                    if(ajax.readyState === 4 ){
                                        if(ajax.responseText){
//alert(ajax.responseText);
                                            Resp = eval("(" + ajax.responseText + ")");
                                            if(parseInt(Resp.coderro) === 1){
                                                alert("Houve um erro no servidor.")
                                            }else{
                                                $.confirm({
                                                    title: 'Sucesso!',
                                                    content: 'Objeto colocado sob guarda de '+document.getElementById("usuarioNome").value,
                                                    draggable: true,
                                                    buttons: {
                                                        OK: function(){}
                                                    }
                                                });
                                                document.getElementById("relacmodalTransfGuarda").style.display = "none";
                                                $("#carregaBens").load("modulos/bensachados/relBens.php");
                                            }
                                        }
                                    }
                                };
                                ajax.send(null);
                            }
                        },
                        Não: function () {
                        }
                    }
                });
            }

            //Restituição do objeto
            function modalRestit(){
                if(document.getElementById("nomeproprietario").value === ""){
                    let element = document.getElementById('nomeproprietario');
                    element.classList.add('destacaBorda');
                    document.getElementById("nomeproprietario").focus();
                    $('#mensagemrest').fadeIn("slow");
                    document.getElementById("mensagemrest").innerHTML = "Insira o nome do proprietário";
                    $('#mensagemrest').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("cpfproprietario").value === ""){
                    let element = document.getElementById('cpfproprietario');
                    element.classList.add('destacaBorda');
                    document.getElementById("cpfproprietario").focus();
                    $('#mensagemrest').fadeIn("slow");
                    document.getElementById("mensagemrest").innerHTML = "Insira o CPF do proprietário";
                    $('#mensagemrest').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("telefproprietario").value === ""){
                    let element = document.getElementById('telefproprietario');
                    element.classList.add('destacaBorda');
                    document.getElementById("telefproprietario").focus();
                    $('#mensagemrest').fadeIn("slow");
                    document.getElementById("mensagemrest").innerHTML = "Insira o número do telefone do proprietário";
                    $('#mensagemrest').fadeOut(2000);
                    return false;
                }
                $.confirm({
                    title: 'Restituição',
                    content: 'Confirma a restituição deste objeto?',
                    autoClose: 'Não|10000',
                    draggable: true,
                    buttons: {
                        Sim: function () {
                            ajaxIni();
                            if(ajax){
                                ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=restituiBem&codigo="+document.getElementById("guardacod").value
                                +"&nomeproprietario="+encodeURIComponent(document.getElementById("nomeproprietario").value)
                                +"&cpfproprietario="+encodeURIComponent(document.getElementById("cpfproprietario").value)
                                +"&telefproprietario="+encodeURIComponent(document.getElementById("telefproprietario").value), true);
                                ajax.onreadystatechange = function(){
                                    if(ajax.readyState === 4 ){
                                        if(ajax.responseText){
//alert(ajax.responseText);
                                            Resp = eval("(" + ajax.responseText + ")");
                                            if(parseInt(Resp.coderro) === 1){
                                                alert("Houve um erro no servidor.")
                                            }else{
                                                $.confirm({
                                                    title: 'Sucesso!',
                                                    content: 'Objeto restituido ao proprietário.',
                                                    draggable: true,
                                                    buttons: {
                                                        OK: function(){}
                                                    }
                                                });
                                                document.getElementById("relacmodalRestit").style.display = "none";
                                                $("#carregaBens").load("modulos/bensachados/relBens.php");
                                            }
                                        }
                                    }
                                };
                                ajax.send(null);
                            }
                        },
                        Não: function () {
                        }
                    }
                });
            }

            function modalRcbCSG(){
                $Mensag = "Guarda para destinação.";
                if(parseInt(document.getElementById("codusuins").value) === parseInt(document.getElementById("usuarioID").value)){
                    $Mensag = "Aceitar a guarda. <br>Mesmo usuário que registrou?";
                }
                $.confirm({
                    title: $Mensag,
                    content: 'Confirma aceitar a guarda deste objeto?',
                    autoClose: 'Não|10000',
                    draggable: true,
                    buttons: {
                        Sim: function () {
                            ajaxIni();
                            if(ajax){
                                ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=encamBemCsg&codigo="+document.getElementById("guardacod").value, true);
                                ajax.onreadystatechange = function(){
                                    if(ajax.readyState === 4 ){
                                        if(ajax.responseText){
//alert(ajax.responseText);
                                            Resp = eval("(" + ajax.responseText + ")");
                                            if(parseInt(Resp.coderro) === 1){
                                                alert("Houve um erro no servidor.")
                                            }else{
                                                $.confirm({
                                                    title: 'Sucesso!',
                                                    content: 'Objeto recebido na CSG por '+document.getElementById("usuarioNome").value,
                                                    draggable: true,
                                                    buttons: {
                                                        OK: function(){}
                                                    }
                                                });
                                                document.getElementById("relacmodalEncam").style.display = "none";
                                                $("#carregaBens").load("modulos/bensachados/relBens.php");
                                            }
                                        }
                                    }
                                };
                                ajax.send(null);
                            }
                        },
                        Não: function () {
                        }
                    }
                });
            }

            //Aceita a transferência do objeto para guarda
            function modalDestino(){
                if(document.getElementById("setordestino").value === ""){
                    let element = document.getElementById('setordestino');
                    element.classList.add('destacaBorda');
                    document.getElementById("setordestino").focus();
                    $('#mensagemdest').fadeIn("slow");
                    document.getElementById("mensagemrest").innerHTML = "Insira o nome do setor de destino do objeto";
                    $('#mensagemdest').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("nomefuncionario").value === ""){
                    let element = document.getElementById('nomefuncionario');
                    element.classList.add('destacaBorda');
                    document.getElementById("nomefuncionario").focus();
                    $('#mensagemdest').fadeIn("slow");
                    document.getElementById("mensagemdest").innerHTML = "Insira o nome de quem recebeu";
                    $('#mensagemdest').fadeOut(2000);
                    return false;
                }
                if(document.getElementById("selecdestino").value === "0"){
                    let element = document.getElementById('selecdestino');
                    element.classList.add('destacaBorda');
                    document.getElementById("selecdestino").focus();
                    $('#mensagemdest').fadeIn("slow");
                    document.getElementById("mensagemdest").innerHTML = "Selecione o destino dado ao objeto";
                    $('#mensagemdest').fadeOut(2000);
                    return false;
                }
                $.confirm({
                    title: 'Destinação',
                    content: 'Confirma a destinação data ao objeto e arquivamento do processo?',
                    autoClose: 'Não|10000',
                    draggable: true,
                    buttons: {
                        Sim: function () {
                            ajaxIni();
                            if(ajax){
                                ajax.open("POST", "modulos/bensachados/salvaBens.php?acao=destinaBem&codigo="+document.getElementById("guardacod").value
                                +"&setordestino="+encodeURIComponent(document.getElementById("setordestino").value)
                                +"&nomefuncionario="+encodeURIComponent(document.getElementById("nomefuncionario").value)
                                +"&selecdestino="+document.getElementById("selecdestino").value, true);
                                ajax.onreadystatechange = function(){
                                    if(ajax.readyState === 4 ){
                                        if(ajax.responseText){
//alert(ajax.responseText);
                                            Resp = eval("(" + ajax.responseText + ")");
                                            if(parseInt(Resp.coderro) === 1){
                                                alert("Houve um erro no servidor.")
                                            }else{
                                                $.confirm({
                                                    title: 'Sucesso!',
                                                    content: 'Processo Arquivado.',
                                                    draggable: true,
                                                    buttons: {
                                                        OK: function(){}
                                                    }
                                                });
                                                document.getElementById("relacmodalDest").style.display = "none";
                                                $("#carregaBens").load("modulos/bensachados/relBens.php");
                                            }
                                        }
                                    }
                                };
                                ajax.send(null);
                            }
                        },
                        Não: function () {
                        }
                    }
                });
            }

            function fechaModalReg(){
                document.getElementById("relacmodalRegistro").style.display = "none";
            }
            function modif(){ // assinala se houve qualquer modificação
                document.getElementById("mudou").value = "1";
            }
            function tiraBorda(id){
                let element = document.getElementById(id);
                element.classList.remove('destacaBorda');
            }
            function imprProcesso(Cod){
                window.open("modulos/bensachados/imprReg.php?acao=imprProcesso&codigo="+Cod, Cod);
            }
            function imprRestit(){
                if(document.getElementById("nomeproprietario").value === ""){
                    alert("Insira o nome do proprietário.");
                    return false;
                }
                window.open("modulos/bensachados/imprReg.php?acao=imprReciboRest&codigo="+document.getElementById("guardacod").value+"&nomeproprietario="+document.getElementById("nomeproprietario").value+"&cpfproprietario="+document.getElementById("cpfproprietario").value+"&telefproprietario="+document.getElementById("telefproprietario").value, document.getElementById("guardacod").value);
            }

            function fechaModalTransf(){
                document.getElementById("relacmodalTransfGuarda").style.display = "none";
            }
            function fechaModalRestit(){
                document.getElementById("relacmodalRestit").style.display = "none";
            }
            function fechaModalDest(){
                document.getElementById("relacmodalDest").style.display = "none";
            }

            function foco(id){
                document.getElementById(id).focus();
            }
            function fechaModalEncam(){
                document.getElementById("relacmodalEncam").style.display = "none";
            }

            function validaData (valor) { // tks ao Arthur Ronconi  - https://devarthur.com/blog/funcao-para-validar-data-em-javascript
                // Verifica se a entrada é uma string
                if (typeof valor !== 'string') {
                    return false;
                }
                // Verifica formado da data
                if (!/^\d{2}\/\d{2}\/\d{4}$/.test(valor)) {
                    return false;
                }
                // Divide a data para o objeto "data"
                const partesData = valor.split('/')
                const data = { 
                    dia: partesData[0], 
                    mes: partesData[1], 
                    ano: partesData[2] 
                }
                // Converte strings em número
                const dia = parseInt(data.dia);
                const mes = parseInt(data.mes);
                const ano = parseInt(data.ano);
                // Dias de cada mês, incluindo ajuste para ano bissexto
                const diasNoMes = [ 0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
                // Atualiza os dias do mês de fevereiro para ano bisexto
                if (ano % 400 === 0 || ano % 4 === 0 && ano % 100 !== 0) {
                    diasNoMes[2] = 29
                }
                // Regras de validação:
                // Mês deve estar entre 1 e 12, e o dia deve ser maior que zero
                if (mes < 1 || mes > 12 || dia < 1) {
                    return false;
                }else if (dia > diasNoMes[mes]) { // Valida número de dias do mês
                    return false;
                }
                return true // Passou nas validações
            }
            function validaCPF(cpf) {
                var Soma = 0
                var Resto
                var strCPF = String(cpf).replace(/[^\d]/g, '')
                if (strCPF.length !== 11)
                    return false
                if ([
                    '00000000000',
                    '11111111111',
                    '22222222222',
                    '33333333333',
                    '44444444444',
                    '55555555555',
                    '66666666666',
                    '77777777777',
                    '88888888888',
                    '99999999999',
                ].indexOf(strCPF) !== -1)
                return false
                for (i=1; i<=9; i++)
                    Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
                    Resto = (Soma * 10) % 11
                    if ((Resto == 10) || (Resto == 11)) 
                        Resto = 0
                    if (Resto != parseInt(strCPF.substring(9, 10)) )
                    return false
                    Soma = 0
                    for (i = 1; i <= 10; i++)
                        Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i)
                        Resto = (Soma * 10) % 11
                        if ((Resto == 10) || (Resto == 11)) 
                            Resto = 0
                        if (Resto != parseInt(strCPF.substring(10, 11) ) )
                            return false
                return true
            }

        </script>
    </head>
    <body>
        <?php
        date_default_timezone_set('America/Sao_Paulo');
        require_once(dirname(dirname(__FILE__))."/config/abrealas.php");
        $Hoje = date('d/m/Y');
        if(!$Conec){
            echo "Sem contato com o PostGresql";
            return false;
        }

        $rs = pg_query($Conec, "SELECT column_name, data_type, character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'livroreg'");
        $row = pg_num_rows($rs);
        if($row == 0){
            $Erro = 1;
            echo "Faltam tabelas. Informe à ATI.";
            return false;
        }
        $admIns = parAdm("insbens", $Conec, $xProj);   // nível para inserir 
        $admEdit = parAdm("editbens", $Conec, $xProj); // nível para editar -> foi para relBens.php
        $escEdit = parEsc("bens", $Conec, $xProj, $_SESSION["usuarioID"]); // está na escala
        $OpDestBens = pg_query($Conec, "SELECT numdest, descdest FROM ".$xProj.".bensdestinos ORDER BY descdest");

        ?>
        <!-- div três colunas -->
        <div class="container" style="margin: 0 auto;">
            <div class="row">
                <div class="col quadro"><button class="botpadrGr fundoAmarelo" id="botInsReg" onclick="abreRegistro();" >Registro de Recebimento</button></div>
                <div class="col quadro"><h5>Registro de Bens Encontrados</h5></div> <!-- Central - espaçamento entre colunas  -->
                <div class="col quadro"><img src="imagens/iinfo.png" height="20px;" style="cursor: pointer;" onclick="carregaHelpOcor();" title="Guia rápido"></div> 
            </div>
        </div>
        <br>
<!--<div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 " style="border: 1px solid;">Teste de coluna</div> -->

        <input type="hidden" id="guardacod" value="0" /> <!-- id ocorrência -->
        <input type="hidden" id="mudou" value="0" />
        <input type="hidden" id="guardahoje" value="<?php echo $Hoje; ?>" />
        <input type="hidden" id="guardaNumRelat" value="0" />
        <input type="hidden" id="usuarioID" value="<?php echo $_SESSION["usuarioID"]; ?>" />
        <input type="hidden" id="usuarioNome" value="<?php echo $_SESSION["NomeCompl"]; ?>" />
        <input type="hidden" id="codusuins" value="0" />
        <input type="hidden" id="guardaescEdit" value="<?php echo $escEdit; ?>" />
        <input type="hidden" id="UsuAdm" value="<?php echo $_SESSION["AdmUsu"] ?>" />
        <input type="hidden" id="admIns" value="<?php echo $admIns; ?>" /> <!-- nível mínimo para inserir  -->
        <input type="hidden" id="admEdit" value="<?php echo $admEdit; ?>" /> <!-- nível mínimo para editar -->

        
        <div style="margin: 10px; border: 2px solid blue; border-radius: 15px; padding: 10px;">
            <div id="carregaBens"></div>
        </div>

        <!-- div modal para registrar ocorrência do bem encontrado  -->
        <div id="relacmodalRegistro" class="relacmodal">
            <div class="modal-content-Bens">
                <span class="close" onclick="fechaModalReg();">&times;</span>
                <!-- div três colunas -->
                <div class="container" style="margin: 0 auto;">
                    <div class="row">
                        <div class="col quadro" style="margin: 0 auto;"><button class="botpadrred" id="botimprReg" style="font-size: 80%;" onclick="imprReg();">Gerar PDF</button></div>
                        <div class="col quadro"><h5 id="titulomodal" style="color: #666;">Registro de Recebimento de Bens Encontrados</h5></div> <!-- Central - espaçamento entre colunas  -->
                        <div class="col quadro" style="margin: 0 auto; text-align: center;"><!-- <button class="botpadrred" onclick="enviaModalReg(1);">Enviar</button> --> </div> 
                    </div>
                </div>
                <div style="border: 2px solid blue; border-radius: 10px; padding: 10px;">
                    <table style="margin: 0 auto; width:85%">
                        <tr>
                            <td class="etiqAzul">Data do recebimento: </td>
                            <td>
                                <input type="text" id="dataregistro" onkeydown="tiraBorda(id);" value="<?php echo $Hoje; ?>" onchange="modif();" placeholder="Data" style="font-size: .9em; width: 100px; text-align: center;">
                                <label id="numprocesso" class="etiqAzul" style="padding-left: 30px; color: red;"></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Descrição do bem encontrado: </td>
                            <td>
                                <textarea style="border: 1px solid blue; border-radius: 10px; padding: 3px;" rows="3" cols="65" id="descdobem" onchange="modif();"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Data em que foi encontrado: </td>
                            <td><input type="text" id="dataachado" onkeydown="tiraBorda(id);" value="<?php echo $Hoje; ?>" onchange="modif();" placeholder="Data" style="font-size: .9em; width: 100px; text-align: center;"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Local em que foi encontrado: </td>
                            <td><textarea style="border: 1px solid blue; border-radius: 10px; padding: 3px;" rows="2" cols="65" id="localachado" onchange="modif();"></textarea></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Nome do Colaborador que encontrou o bem: </td>
                            <td><input type="text" id="nomeachou" onconkeydownlick="tiraBorda(id);" value="" onchange="modif();" placeholder="Nome do colaborador que encontrou" style="font-size: .9em; width: 90%;"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Telefone: </td>
                            <td><input type="text" id="telefachou" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="Telefone do colaborador que encontrou" style="font-size: .9em; width: 90%;"></td>
                        </tr>
                    </table>

                    <div id="mensagem" style="color: red; font-weight: bold; margin: 5px; text-align: center; padding-top: 10px;"></div>
                    <br>
                    <div style="text-align: center; padding-bottom: 20px;">
                        <button class="botpadrblue" id="botsalvareg" onclick="salvaModalRegistro();">Registrar</button>
                    </div>
                </div>
           </div>
        </div> <!-- Fim Modal-->


        <!-- div modal para transferir a guarda do objeto  -->
        <div id="relacmodalTransfGuarda" class="relacmodal">
            <div class="modal-content-Bens">
                <span class="close" onclick="fechaModalTransf();">&times;</span>
                <!-- div três colunas -->
                <div class="container" style="margin: 0 auto;">
                    <div class="row">
                        <div class="col quadro" style="margin: 0 auto;"></div>
                        <div class="col quadro"><h5 id="titulomodal" style="color: #666;">Registro de Transferência para Guarda</h5></div> <!-- Central - espaçamento entre colunas  -->
                        <div class="col quadro" style="margin: 0 auto; text-align: center;"><!-- <button class="botpadrred" onclick="enviaModalReg(1);">Enviar</button> --> </div> 
                    </div>
                </div>
                <div style="border: 2px solid blue; border-radius: 10px; padding: 10px;">
                    <table style="margin: 0 auto; width:85%">
                        <tr>
                            <td class="etiqAzul">Processo: </td>
                            <td>
                                <label id="numprocessotransf" class="etiqAzul" style="padding-left: 5px; font-size: 1.1rem;"></label>
                                <label id="etiqprocessoReg"class="etiqAzul"></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Descrição: </td>
                            <td>
                                <div id="descdobemtransf" style="border: 1px solid blue; border-radius: 10px; padding: 3px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Termo: </td>
                            <td><div style="border: 1px solid blue; border-radius: 10px; padding: 3px;">Declaro que recebi o Bem acima descrito, ao qual efetuarei a guarda pelo período de 90 (noventa) dias. Após esse prazo, a destinação do bem seguirá o caminho estabelecido na NI-4.05-B (DAF).</div></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-bottom: 20px;"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Depositário: </td>
                            <td style="padding-left: 15px; padding-right: 15px;"><div style="border: 1px solid blue; border-radius: 10px; text-align: center;">(a) <label style="font-weight: bold;"> <?php echo $_SESSION["NomeCompl"]; ?> </label></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><button class="botpadrblue" id="botsalvareg" onclick="salvaModalTransf();">Objeto Recebido</button></td>
                        </tr>
                    </table>

                    <div id="mensagem" style="color: red; font-weight: bold; margin: 5px; text-align: center; padding-top: 10px;"></div>
                    <br>
                </div>
           </div>
        </div> <!-- Fim Modal-->


        <!-- div modal para restituir o objeto  -->
        <div id="relacmodalRestit" class="relacmodal">
            <div class="modal-content-Bens">
                <span class="close" onclick="fechaModalRestit();">&times;</span>
                <!-- div três colunas -->
                <div class="container" style="margin: 0 auto;">
                    <div class="row">
                        <div class="col quadro" style="margin: 0 auto;"><button class="botpadrred" onclick="imprRestit();">Recibo PDF</button>  </div>
                        <div class="col quadro"><h5 id="titulomodal" style="color: #666;">Registro de Restituição</h5></div> <!-- Central - espaçamento entre colunas  -->
                        <div class="col quadro" style="margin: 0 auto; text-align: center;"><!-- <button class="botpadrred" onclick="enviaModalReg(1);">Enviar</button> --> </div> 
                    </div>
                </div>
                <div style="border: 2px solid blue; border-radius: 10px; padding: 10px;">
                    <table style="margin: 0 auto; width:85%">
                        <tr>
                            <td class="etiqAzul">Processo: </td>
                            <td>
                                <label id="numprocessoRest" class="etiqAzul" style="padding-left: 5px; font-size: 1.1rem;"></label>
                                <label id="etiqprocessoRest"class="etiqAzul"></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Descrição: </td>
                            <td>
                                <div id="descdobemRest" style="border: 1px solid blue; border-radius: 10px; padding: 3px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Proprietário: </td>
                            <td><input type="text" id="nomeproprietario" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="Nome do proprietário" style="font-size: .9em; width: 90%;" onkeypress="if(event.keyCode===13){javascript:foco('cpfproprietario');return false;}"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">CPF: </td>
                            <td><input type="text" id="cpfproprietario" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="CPF do proprietário" style="font-size: .9em; width: 90%;" onkeypress="if(event.keyCode===13){javascript:foco('telefproprietario');return false;}"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Telefone: </td>
                            <td><input type="text" id="telefproprietario" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="Telefone do proprietário" style="font-size: .9em; width: 90%;" onkeypress="if(event.keyCode===13){javascript:foco('nomeproprietario');return false;}"></td>
                        </tr>

                        <tr>
                            <td colspan="2" style="padding-bottom: 20px;"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Funcionário: </td>
                            <td style="padding-left: 15px; padding-right: 15px;"><div style="border: 1px solid blue; border-radius: 10px; text-align: center;">(a) <label style="font-weight: bold;"> <?php echo $_SESSION["NomeCompl"]; ?> </label></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><button class="botpadrblue" id="botsalvaRestit" onclick="modalRestit();">Objeto Restituido</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><div id="mensagemrest" style="color: red; font-weight: bold; margin: 5px; text-align: center; padding-top: 10px;"></div></td>
                        </tr>
                    </table>
                    <br>
                </div>
           </div>
        </div> <!-- Fim Modal-->


        <!-- div modal para encaminhar o objeto para CSG  -->
        <div id="relacmodalEncam" class="relacmodal">
            <div class="modal-content-Bens">
                <span class="close" onclick="fechaModalEncam();">&times;</span>
                <!-- div três colunas -->
                <div class="container" style="margin: 0 auto;">
                    <div class="row">
                        <div class="col quadro" style="margin: 0 auto;"></div>
                        <div class="col quadro"><h5 id="titulomodal" style="color: #666;">Registro de Encaminhamento para CSG</h5></div> <!-- Central - espaçamento entre colunas  -->
                        <div class="col quadro" style="margin: 0 auto; text-align: center;"><!-- <button class="botpadrred" onclick="enviaModalReg(1);">Enviar</button> --> </div> 
                    </div>
                </div>
                <div style="border: 2px solid blue; border-radius: 10px; padding: 10px;">
                    <table style="margin: 0 auto; width:85%">
                        <tr>
                            <td class="etiqAzul">Processo: </td>
                            <td>
                                <label id="numprocessoEncam" class="etiqAzul" style="padding-left: 5px; font-size: 1.1rem;"></label>
                                <label id="etiqprocessoEncam"class="etiqAzul"></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Descrição: </td>
                            <td>
                                <div id="descdobemEncam" style="border: 1px solid blue; border-radius: 10px; padding: 3px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Termo: </td>
                            <td><div style="border: 1px solid blue; border-radius: 10px; padding: 3px;">Declaro que recebi nesta CSG, o processo acima identificado para armazenamento, destinação e arquivamento do processo.</div></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-bottom: 20px;"></td>
                        </tr>

                        <tr>
                            <td colspan="2" style="padding-bottom: 20px;"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Funcionário da CSG: </td>
                            <td style="padding-left: 15px; padding-right: 15px;"><div style="border: 1px solid blue; border-radius: 10px; text-align: center;">(a) <label style="font-weight: bold;"> <?php echo $_SESSION["NomeCompl"]; ?> </label></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><button class="botpadrblue" id="botsalvareg" onclick="modalRcbCSG();">Objeto Recebido</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><div id="mensagemrest" style="color: red; font-weight: bold; margin: 5px; text-align: center; padding-top: 10px;"></div></td>
                        </tr>
                    </table>
                    <br>
                </div>
           </div>
        </div> <!-- Fim Modal-->


        <!-- div modal para destinação do objeto  -->
        <div id="relacmodalDest" class="relacmodal">
            <div class="modal-content-Bens">
                <span class="close" onclick="fechaModalDest();">&times;</span>
                <!-- div três colunas -->
                <div class="container" style="margin: 0 auto;">
                    <div class="row">
                        <div class="col quadro" style="margin: 0 auto;"></div>
                        <div class="col quadro"><h5 id="titulomodal" style="color: #666;">Registro de Destinação</h5></div> <!-- Central - espaçamento entre colunas  -->
                        <div class="col quadro" style="margin: 0 auto; text-align: center;"><!-- <button class="botpadrred" onclick="enviaModalReg(1);">Enviar</button> --> </div> 
                    </div>
                </div>
                <div style="border: 2px solid blue; border-radius: 10px; padding: 10px;">
                    <table style="margin: 0 auto; width:85%">
                        <tr>
                            <td class="etiqAzul">Processo: </td>
                            <td>
                                <label id="numprocessoDest" class="etiqAzul" style="padding-left: 5px; font-size: 1.1rem;"></label>
                                <label id="etiqprocessoDest"class="etiqAzul"></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Descrição: </td>
                            <td>
                                <div id="descdobemDest" style="border: 1px solid blue; border-radius: 10px; padding: 3px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Setor de Destino: </td>
                            <td><input type="text" id="setordestino" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="Nome do setor de destino" style="font-size: .9em; width: 90%;" onkeypress="if(event.keyCode===13){javascript:foco('nomefuncionario');return false;}"></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Nome do Funcionário do Setor: </td>
                            <td><input type="text" id="nomefuncionario" onkeydown="tiraBorda(id);" value="" onchange="modif();" placeholder="Nome de quem recebe" style="font-size: .9em; width: 90%;" onkeypress="if(event.keyCode===13){javascript:foco('setordestino');return false;}"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-bottom: 20px;"></td>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center;"><label>O bem descrito neste processo foi destinado a: </label>
<!--
                            <select id="selecdestino" onclick="tiraBorda(id);" onchange="modif();" style="font-size: 0.9rem; min-width: 100px;" title="Selecione o destino dado ao bem encontrado.">
                                <option value="0"></option>
                                <option value="1">Descarte</option>
                                <option value="2">Destruição</option>
                                <option value="3">Doação</option>
                                <option value="4">Venda</option>
                            </select>
-->
                            <select id="selecdestino" onclick="tiraBorda(id);" onchange="modif();" style="font-size: 0.9rem; min-width: 100px;" title="Selecione o destino dado ao bem encontrado.">
                            <?php 
                            if($OpDestBens){
                                while ($Opcoes = pg_fetch_row($OpDestBens)){ ?>
                                    <option value="<?php echo $Opcoes[0]; ?>"><?php echo $Opcoes[1]; ?></option>
                                <?php 
                                }
                            }
                            ?>
                            </select>

                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center; padding-bottom: 20px; padding-top: 20px;">Este processo será arquivado nesta data: <?php echo $Hoje; ?></td>
                        </tr>
                        <tr>
                            <td class="etiqAzul">Funcionário: </td>
                            <td style="padding-left: 15px; padding-right: 15px;"><div style="border: 1px solid blue; border-radius: 10px; text-align: center;">(a) <label style="font-weight: bold;"> <?php echo $_SESSION["NomeCompl"]; ?> </label></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><button class="botpadrblue" id="botsalvareg" onclick="modalDestino();">Arquivar Processo</button></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center; padding-top: 25px;"><div id="mensagemdest" style="color: red; font-weight: bold; margin: 5px; text-align: center; padding-top: 10px;"></div></td>
                        </tr>
                    </table>
                    <br>
                </div>
           </div>
        </div> <!-- Fim Modal-->

    </body>
</html>