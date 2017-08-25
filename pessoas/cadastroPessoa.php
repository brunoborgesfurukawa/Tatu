<?php
$titulo = 'Cadastrar Pessoa';
$ativo[0] = "active";
require '../cabecalho.php';
require '../util/dataHora.php';
require '../util/formularios.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 4, "Colaborador", $u->tipoPessoa)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}
?>
<script>
var numeroCampo = 1;
// Imprime novos campos de telefone.
function novoTelefone() {
	var html = " \
		+<input type='text' class='telefone soNumero' id='ddi["+ numeroCampo +"]' name='ddi["+ numeroCampo +"]' maxlength='3' size='3' onkeyup=\"proximoCampo(this, 'ddd["+ numeroCampo +"]')\" placeholder='DDI' /> \
		(<input type='text' class='soNumero' id='ddd["+ numeroCampo +"]' name='ddd["+ numeroCampo +"]' maxlength='2' size='3' onkeyup=\"proximoCampo(this, 'telefone["+ numeroCampo +"]')\" placeholder='DDD' />) \
		<input type='text' class='soNumero' id='telefone["+ numeroCampo +"]' name='telefone["+ numeroCampo +"]' maxlength='9' size='9' onkeyup=\"proximoCampo(this, 'ddi["+ (numeroCampo + 1) +"]')\" placeholder='Telefone' /> \
		<select id='tipo["+ numeroCampo +"]' name='tipo["+ numeroCampo +"]' > \
			<option value='Residencial'>Residencial</option> \
			<option value='Celular'>Celular</option> \
			<option value='Comercial'>Comercial</option> \
			<option value='Ramal'>Ramal</option> \
		</select> \
		<br />";
	$('#telefones').append(html);
	numeroCampo++;
}

function initSelectionColaborador(element, callback) {
	var id = $(element).val();
	// só faz algo se houver valor no campo
	if (id !== '') {
		$.ajax('/tatu/pessoas/select2.php',{
 			data: {
 				id: id
			}, dataType: 'json',
		}).done(function(data) {
			callback(data);
		});
	}
}
// Aplica o select2.
function select2Colaborador() {
	$('#codColaborador').select2({
	minimumInputLength: 3,
	quietMillis: 100,
	ajax: {
		url: '/tatu/pessoas/select2.php',
		dataType: 'json',
		data: function(term, page) {
			return {
				query: term,
				page_limit: 10
			};
		},
		results: function(data, page) {
			return data;
			}
		},
		initSelection: initSelectionColaborador,
	});
}

$(document).ready(function() {
	function chamaColaborador() {
		var colaborador;
		colaborador = "\
			<label for='codColaborador'>Colaborador:</label>\
			<input type='text' style='width: 300px' id='codColaborador' class='editar' name='codColaborador' value ='<?= $u->codPessoa ?>' placeholder='Digite o nome do colaborador...' required />";
			$('#campo').html(colaborador);
			select2Colaborador();
	}

	// Chama o campo de seleção para um colaborador.
	$("#contato").on("click", function() {
		chamaColaborador();
	});

	// Remove o campo de seleção do colaborador.
	$("#colaborador").on( "click", function() {
		$('#campo').html("");
	});

	$('#limparCampo').click(function() {
			$('#telefones').html("");
			numeroCampo = 0;
	});


	// Ativa validação de data.
	$("#campoData").blur(function() {
			return validaData(this, 10, 120);
	});

});

</script>

<body>
<form name="pessoas" method="post" action="gravaPessoa.php">
	<div class="panel panel-default" style="width: 55%; float: left">
		<div class="panel-heading">
			<h3 class="panel-title">
				<span>Cadastrar Pessoa</span>
					<button type="submit" id="gravarDados" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Cadastrar</button>
					<button type="reset" id="limparCampo" onClick="history.go(0)" class="btn btn-warning btn-xs pull-right"><span class="glyphicon glyphicon-repeat"></span> Limpar</button>
			</h3>
		</div>
		<div class="panel-body">

	<input type="hidden" id="codPessoa" name="codPessoa" />

	<label for="nome">Nome:</label><br />
	<input type="text" id="nome" name="nome" size="50" required />
<br />
	<label for="dataNascimento">Data de Nascimento:</label><br />
	<input type="text" id="campoData" name="campoData" class="campo-data input-small" onkeyup="proximoCampo(this, 'campoEmail')" maxlength="10" size="10" />
	<span style="color: #8B8989" >dd/mm/aaaa</span>
<br />
	<label for="email">E-mail:</label><br />
	<input type="hidden" id="email" name="email" />
	<input type="email" id="campoEmail" size="30" name="campoEmail" class="editar" onkeyup="verificaEmail(this.value, 'alerta', 0)" required />
	<span id="alerta"></span>
<br />

	<label for="telefone">Telefone:</label><br />
	+<input type="text" id="ddi[0]" name="ddi[0]" class="soNumero" maxlength="3" size="3" placeholder="DDI" onkeyup="proximoCampo(this, 'ddd[0]')" />
	(<input type="text" id="ddd[0]" name="ddd[0]" class="soNumero" maxlength="2" size="3" placeholder="DDD" onkeyup="proximoCampo(this, 'telefone[0]')"/>)
	<input type="tel" id="telefone[0]" name="telefone[0]" class="soNumero" maxlength="9" size="9" placeholder="Telefone" onkeyup="proximoCampo(this, 'ddi[1]')" />
		<select id="tipo[0]" name="tipo[0]"  >
			<option value="Residencial">Residencial</option>
			<option value="Celular">Celular</option>
			<option value="Comercial">Comercial</option>
			<option value="Ramal">Ramal</option>
		</select>
	<input type="button" name="adicionar" class="btn btn-xs btn-default" value="Adicionar telefone" onClick="novoTelefone()" />
	<div id="telefones"></div>
<br />
	<label>Endereço:</label>
	<input type="hidden" id="codEndereco" name="codEndereco" />
<br />
	<?php campoCep(); ?>
<br />
    <input id="logradouro" name="logradouro" type="text" size="40" disabled />
	<input id="numero" name="numero" class="soNumero" type="text" size="6" onkeyup="proximoCampo(this, 'complemento')" placeholder="Número" maxlength="5" />
<br />
    <input id="bairro" name="bairro" type="text" disabled/>
    <input id="cidade" name="cidade" type="text" disabled/>
    <input id="uf" name="uf" type="text" size ="2" disabled/>
<br />
	<label>Complemento:</label>
<br />
	<textarea id="complemento" name="complemento" class="width-30" ></textarea>
<br /><br />

<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 4)) { ?>
		<input type="radio" name="pessoa" id="colaborador" value="colaborador" />
		<label for="colaborador">Colaborador</label><br />
		<input type="radio" name="pessoa" id="contato" value="contato" />
		<label for="contato">Contato</label>
		<span id="campo" class="pull-right"></span>
<?php } else {?>
		<input type="hidden" name="pessoa" value="contato" />
		<input type="hidden" name="codColaborador" value="<?= $u->codPessoa ?>" />
		<label> Colaborador: </label> <?= $u->nome ?>
<?php } ?>
	</div>
	</div>
</form>
