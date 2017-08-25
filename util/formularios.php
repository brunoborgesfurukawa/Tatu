<script>
	/**
	 * proximoCampo - Troca automaticamente o campo selecionado
	 * quando o máximo de caracteres de um for atingido.
	 *
	 * @param  {string} atual   Campo selecionado e que está
	 *                          fazendo a chamada da função.
	 *
	 * @param  {string} proximo Campo a ser selecionado assim
	 *                          que o limite de caracteres do
	 *                          atual for atingido.
	 */
	function proximoCampo (atual, proximo) {
		if (atual.value.length >= atual.maxLength) {
		document.getElementById(proximo).focus();
		}
	}
</script>

<?php

/**
 * Cria um campo para inserção de CEP que consulta o logradouro, bairro,
 * cidade e UF do mesmo para preencher os campos de endereço.
 *
 * @param  string $id         id do campo CEP.
 * @param  string $name       name do campo CEP.
 * @param  string $value      value padráo do campo.
 * @param  string $logradouro id do campo que receberá o logradouro.
 * @param  string $bairro     id do campo que receberá o bairro.
 * @param  string $cidade     id do campo que receberá a cidade.
 * @param  string $uf         id do campo que receberá o UF.
 * @param  string $class      class do campo CEP.
 */

// FIXME trocar todos estes paramêtros por um único que receberá um array.
function campoCep($id = "cep", $name = "cep", $value="", $logradouro = "logradouro", $bairro = "bairro", $cidade = "cidade", $uf = "uf", $class = "cep") { ?>
	<script>
		// Assim que a página carrega é feito uma consulta
		// caso a página já tenha um campo CEP preenchido.
		$(document).ready(function() {
			consultacep("<?= $value ?>");
			$('.error').hide();
		});

		// Abre um script no site do correios que faz a chamada
		// da função correiocontrolcep() com os dados do CEP em
		// um array.
		function consultacep(cep) {
			if (cep == ""){
				$('.error').hide();
				return;
			}

			cep = cep.replace(/\D/g,"")
			url="http://cep.correiocontrol.com.br/"+cep+".js"
			s=document.createElement('script')
			s.setAttribute('charset','utf-8')
			s.src=url
			document.querySelector('head').appendChild(s)
		}

		// Preenche os campos de endereço com os valores do array recebido.
		function correiocontrolcep(valor) {
			if (valor.erro) {
				$('.error').show();
				document.getElementById("gravarDados").disabled = true;
				return;
			} else {
				document.getElementById("gravarDados").disabled = false;
				$('.error').hide();

				document.getElementById('<?= $logradouro ?>').value=valor.logradouro
				document.getElementById('<?= $bairro ?>').value=valor.bairro
				document.getElementById('<?= $cidade ?>').value=valor.localidade
				document.getElementById('<?= $uf ?>').value=valor.uf

				mostraEndereco();
			}
		}
	</script>

	<input type="text" id="<?= $id ?>" name="<?= $name ?>" class="<?= $class ?>, soNumero" value="<?= $value ?>" maxlength="8" size="9" required onblur="consultacep(this.value)" onkeyup="proximoCampo(this, 'numero')" /><span class="error"><font color="red"> CEP não encontrado</font></span>
	<br /><small style="color: #8B8989" >CEP somente números</small>
<?php } ?>
