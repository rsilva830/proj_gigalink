//FUNCAO PARA VALIDAR DADOS NO FORMULARIO
//ESCOLHER O TIPO DE VALIDACAO INCLUINDO VALIDAR="X" NO CAMPO DO FORMULARIO
function validaDados(form) {
    var resultado = true;
    for (i = 0; i < form.length; i++) {
        //NAO ACEITA VAZIO 
        //NAO ACEITA MENOR QUE 2 CARACTERES
        if (form[i].getAttribute("validar") == "1") {
            if ((form[i].value == "") || (form[i].value.length < 2)) {
                mostraMsgValidacao(form[i], " ");
                resultado = false;
                break;
            }
        }
        //NAO ACEITA VAZIO 
        //NAO ACEITA 0
        if (form[i].getAttribute("validar") == "2") {
            if ((form[i].value == "") || (form[i].value == "0")) {
                mostraMsgValidacao(form[i], " ");
                resultado = false;
                break;
            }
        }
        //NAO ACEITA VAZIO 
        //NAO ACEITA DATA INVALIDA
        if (form[i].getAttribute("validar") == "3") {
            if ((form[i].value == "") || !(dataValida(form[i].value))) {
                mostraMsgValidacao(form[i], "Formato [dd-mm-aaaa] ou [dd-mm-aaaa hh:mm:ss]");
                resultado = false;
                break;
            }
        }
        //NAO ACEITA VAZIO 
        if (form[i].getAttribute("validar") == "4") {
            if (form[i].value == "") {
                mostraMsgValidacao(form[i], " ");
                resultado = false;
                break;
            }
        }

        //NAO ACEITA VAZIO E
        //VALIDA SE E NUMERO FLOAT 9999 ou 9999.99
        if (form[i].getAttribute("validar") == "5") {
            if ((form[i].value == "") || (validaNumeroFloat(form[i].value))) {
                mostraMsgValidacao(form[i], "Formato [9999] ou [9999.99]");
                resultado = false;
                break;
            }
        }

        //NAO ACEITA VAZIO E
        //VALIDA SE E NUMERO INTEIRO POSITIVO > 0
        if (form[i].getAttribute("validar") == "6") {
            if ((form[i].value == "") || (validaNumeroInteiro(form[i].value))) {
                mostraMsgValidacao(form[i], " ");
                resultado = false;
                break;
            }
        }

        //ACEITA VAZIO OU
        //VALIDA SE E NUMERO INTEIRO POSITIVO
        if (form[i].getAttribute("validar") == "7") {
            if (!(form[i].value == "") && (validaNumeroInteiro(form[i].value))) {
                mostraMsgValidacao(form[i], "Formato [] ou [99999]");
                resultado = false;
                break;
            }
        }

        //ACEITA VAZIO OU
        //VALIDA SE E NUMERO FLOAT 9999 ou 9999.99
        if (form[i].getAttribute("validar") == "8") {
            if (!(form[i].value == "") && (validaNumeroFloat(form[i].value))) {
                mostraMsgValidacao(form[i], "Formato [9999] ou [9999.99]");
                resultado = false;
                break;
            }
        }

    }
    return resultado;
}

//VALIDA SE E NUMERO FLOAT 9999 ou 9999.99
function validaNumeroFloat(num) {
    var expressao = /^[-+]?[0-9]+\.[0-9]+$/;
    var resultado = false;
    if (num.match(expressao)) {
        resultado = false;
    } else {
        resultado = true;
    }
    return resultado;
}

//VALIDA SE E NUMERO INTEIRO POSITIVO
function validaNumeroInteiro(num) {
    var expressao = /^[0-9]+$/;
    var resultado = false;
    if (num.match(expressao)) {
        resultado = false;
    } else {
        resultado = true;
    }
    return resultado;
}

//MOSTRA A MENSAGEM DE VALIDACAO E SETA O FOCO NO CAMPO
function mostraMsgValidacao(formElem, formato) {
    var nomeCampo;
    if (formElem.id != "") {
        nomeCampo = formElem.id;
    } else if (formElem.name != "") {
        nomeCampo = formElem.name;
    }
    alert("O campo [" + nomeCampo + "] é obrigatorio e contém um valor inválido!\n" + formato);
    formElem.focus();
}

//VALIDA SE UM VALOR E NUMERO
function eNumero(pStr) {
    //EXPRESSAO REGULAR PARA VALIDAR UM OU MAIS DIGITOS
    var reDigits = /^\d+$/;
    var resultado = false;
    if (reDigits.test(pStr)) {
        resultado = true;
    } else if (pStr != null && pStr != "") {
        resultado = false;
    } else if (pStr == null || pStr == "") {
        resultado = false;
    }
    return resultado;
}

//VALIDA SE UMA DATA E VALIDA
//ANO COM 4 DIGITOS
//MES ATE 12
//DIA ATE 31
//PODE SER DATA [DD-MM-AAAA] OU [DD-MM-AAAA HH:MM:SS]
function dataValida(pData) {
    var data = pData;
    var resultado = false;
    //VALIDA DATA COM HORA
    if (pData.length < 10) {
        resultado = validaData(data);
    }
    if (pData.length > 10) {
        resultado = validaHora(data);
    }
    return resultado;
}

//VALIDA A PARTE DA DATA
function validaData(pData) {
    var resultado = false;
    //VALIDA PARTE DA DATA
    if (pData.length < 10) {
        var dia = pData.substr(0, 2);
        var mes = pData.substr(3, 2);
        var ano = pData.substr(6, 4);
        dia = Number(dia);
        mes = Number(mes);
        ano = Number(ano);
        if ((dia > 0) && (dia < 32)) {
            if ((mes > 0) && (mes < 13)) {
                if ((ano > 0) && (ano < 3001)) {
                    resultado = true;
                }
            } else {
                resultado = false;
            }
        } else {
            resultado = false;
        }
    }
    return resultado;
}

//VALIDA A PARTE DA HORA DE UMA DATA NO FORMATO
//[DD-MM-AAAA HH:MM:SS]
function validaHora(pDataHora) {
    var resultado = false;
    //VALIDA PARTE HORA DA DATA
    if (pDataHora.length == 19) {
        var hora = pDataHora.substr(11, 2);
        var min = pDataHora.substr(14, 2);
        var seg = pDataHora.substr(17, 2);

        hora = Number(hora);
        min = Number(min);
        seg = Number(seg);

        if ((hora > 0) && (hora < 24)) {
            if ((min >= 0) && (min < 60)) {
                if ((seg >= 0) && (seg < 60)) {
                    resultado = true;
                }
            } else {
                resultado = false;
            }
        } else {
            resultado = false;
        }
    }
    //alert("valores hora=" + hora + ",min=" + min + ",seg=" + seg+ ",resultado="+resultado+",length="+pDataHora.length);
    return resultado;
}