$(document).ready(function() {
    ativarValidacao();
});


// Ativa a validação de data nas classes especificadas, esta função pode ser
// chamada mais de um vez caso um novo campo seja criado após o carregamento
// da página.
function ativarValidacao() {
    $('.soNumero').keypress(function(e) {
        var argumentos = ["numeros"];
        return validaValor(e, argumentos);
    });

    $('.soLetra').keypress(function(e) {
        var argumentos = ["letras", "espaco"];
        return validaValor(e, argumentos);
    });

    $('.soCEspecial').keypress(function(e) {
        var argumentos = ["especiais", "espaco"];
        return validaValor(e, argumentos);
    });

    $('.campo-data').keypress(function(e) {
        return validaKeyPress(e, 'data', this)
    });
}

/**
 * Especifica quais caracteres são permitidos ao preencher um campo.
 *
 * @param  {object} evento
 * @param  {string} tipo   Qual tipo de caractere é permitido
 * @return {boolean}
 */
function validaValor(evento, tipo) {
    var index;
    var tecla = evento.keyCode ? evento.keyCode : evento.which ? evento.which : evento.charCode;

    for (index = 0; index < tipo.length; index++) {
        if (tipo[index] == "numeros" && (tecla > 47 && tecla < 58)) {
            return true;
        }

        if (tipo[index] == "letras" && ((tecla > 64 && tecla < 91) || (tecla > 96 && tecla < 123))) {
            return true;
        }

        if (tipo[index] == "especiais" && ( (tecla > 32 && tecla < 48) || (tecla > 57 && tecla < 41) || (tecla > 90 && tecla < 97) || tecla > 122) ) {
            return true;
        }

        if (tipo[index] == "espacos" && tecla == 32) {
            return true;
        }

        if (tecla == 8 || tecla == 9) {
            return true;
        }
    }

    return false;
}

/**
 * Formata e valida os caracteres de um campo.
 * @param  {object} evento
 * @param  {string} tipo   Tipo de campo a ser formatado
 * @param  {string} obj    Campo a ser aplicado
 * @return {boolean}
 */
function validaKeyPress(evento, tipo, obj) {
    var keyCode = evento.keyCode ? evento.keyCode : evento.which ? evento.which : evento.charCode;

    if (keyCode == 8 || keyCode == 37 || keyCode == 39 || keyCode == 36
            || keyCode == 35 || keyCode == 9) {
        return true;
    }

    evento = String.fromCharCode(keyCode);

    if (evento == "" || evento == null)    {
        return false;
    }

    if (tipo == "data")    {
        numero = "0123456789";
        if (numero.indexOf(evento) == -1) {
            return false;
        } else {
            if (obj.value.length == 2) {
                obj.value = obj.value + '/';
            } else if (obj.value.length == 5) {
                obj.value = obj.value + '/';
            }
            return true;
        }
    }


    if (tipo == "9") {
        numero = "0123456789";
        if (numero.indexOf(evento) == -1) {
            return false;
        } else {
            return true;
        }
    }

    if (tipo=="tel"){
        caracteres="0123456789";
        if (caracteres.indexOf(evento) == -1) {
            return false;
        } else {
            if (obj.value.length == 4) {
                obj.value = obj.value + '-';
            }
            return true;
        }
    }

    if (tipo == "decimal") {
        numero = "0123456789.,";
        if (numero.indexOf(evento) == -1) {
            return false;
        } else {
            if (evento == '.' || evento == ',') {
                if (obj.value.indexOf('.') != -1 || obj.value.indexOf(',') != -1) {
                    return false;
                }
            }
            return true;
        }
    }

}

/**
 * Verifica se o campo é uma data válida.
 * @param  {String} campo  Campo a se aplicar
 * @param  {integer} minimo
 * @param  {integer} maximo
 * @return {boolean}
 */
function validaData(campo, minimo, maximo) {
	if (campo.value.length == 0) {
		return false;
	}

	data = campo.value.split("/");

	if (data.length != 3) {
		alert("Data inválida.");
		campo.value = "";
		campo.focus();
		return false;
	}

	hoje = new Date();
	ano = hoje.getFullYear();

	if ((data[0] < 1 || data[0] > 31 || data[1] < 1 || data[1] > 12) || (data[1] == 2 && data[0] > 29)) {
		alert("Data inválida.");
		campo.value = "";
		campo.focus();
		return false;
	}

	 if (data[2] < (ano - maximo) || data[2] > (ano - minimo)) {
		alert("Data inválida. O ano deve estar entre " + (ano - maximo) + " e " + (ano - minimo) + ".");
		campo.value = "";
		campo.focus();
		return false;
	 }

	return true;
}

/**
 * Verifica se o e-mail preenchido já está sendo usado.
 *
 * @param  {string} valor  Valor inserido no campo.
 * @param  {string} alerta id do campo que exibirá os alertas.
 */
function verificaEmail(valor, alerta, codPessoa) {
    codPessoa = (typeof codPessoa !== 'undefined') ? codPessoa : 0;
    document.getElementById('email').value = document.getElementById('campoEmail').value;
    if (valor.length == 0) {
        document.getElementById(alerta).innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Recebe a resposta do verificaEmail.php e a divide em 2 valores.
                var retorno = xmlhttp.responseText.split("@");
                // O primeiro retornará uma string com TRUE ou FALSE, a mesma é convertida para boolean.
                document.getElementById("gravarDados").disabled = $.parseJSON(retorno[0]);

                // O segundo retornará a mensagem a ser imprimida.
                document.getElementById(alerta).innerHTML = retorno[1];
            }
        }
        xmlhttp.open("GET", "verificaEmail.php?query=" + valor + "&codPessoa=" + codPessoa, true);
        xmlhttp.send();
    }
}
