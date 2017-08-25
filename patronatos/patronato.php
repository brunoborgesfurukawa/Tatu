<?php
$titulo = 'Patronato';
$ativo[2] = "active";
require '../cabecalho.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 5) && !PessoasPermissao::verificaPatronato($_GET['codPatronato'], $u->getTodosPatronatos()) ) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

// Verifica se algum patronato foi selecionado.
if (isset($_GET['codPatronato'])) {
	$patronatos = new Patronato($_GET['codPatronato']);
}

$patronatos = Patronato::getPatronatoMembro($_GET['codPatronato']);

// Verifica se algum valor foi retornado, caso não, o patronato não existe.
if (empty($patronatos)) {
	erroFatal('<center>Este patronato não existe. Voltar para a <a href="javascript:history.back()">página anterior</a>.</center>');
}

foreach ($patronatos as $patronato) {

	// Pesquisa pelos membros do patronato e guarda em uma variável a lista a ser exibida.
	$membros = PatronatoMembro::getMembro($_GET['codPatronato'], 1);
	$htmlInicial = "";
	foreach ($membros as $membro) {
		$htmlInicial .= <<<EOT
		<div class='membro$membro->codPessoa row' id='membro$membro->codPessoa'>\
		<div class='col-md-8'><a href='../pessoas/pessoa.php?codPessoa=$membro->codPessoa' target='_blank'>$membro->nome</a></div>\
EOT;
	if (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
		$htmlInicial .= <<<EOT
		<div class='col-md-4'><button class='botaoRemover btn btn-danger btn-xs' onClick='removerMembro($membro->codPessoa, $membro->codMembro)'><span class='glyphicon glyphicon-remove'></span> Remover</button></div>\
EOT;
	}
$htmlInicial .= "</div>";
}
?>

<script>
var numeroCampo = 0;
var htmlInicial = "<?= $htmlInicial ?>";
$(document).ready(function() {
	// Assim que a página carregar, será imprimido a lista de membros
	// e os botões de edição serão escondidos.
	document.getElementById('listaDeMembros').innerHTML = htmlInicial;
	$('.editar, .botaoRemover').hide();

	// Adiciona ação aos botões 'Editar' e 'Cancelar'.
	$('#editarPatronato, #cancelarEdicao').click(function() {
		// Caso uma edição seja cancelada, a lista de membros será reimprimida.
		document.getElementById('listaDeMembros').innerHTML = htmlInicial;
		$('.editar, .visualizar').toggle();
		return false;
	});

	$('#cancelarEdicao').click(function() {
		$('.botaoRemover').hide();
		numeroCampo = 0;
		$('#campo').html("");
		$("input[name=pessoasRemovidas]").val("")
		return false;
	});

	function initSelectionGestor(element, callback) {
		var id = $(element).val();
		// só faz algo se houver valor no campo
		if (id !== '') {
			$.ajax('/tatu/centros/select2.php',{
	 			data: {
	 				id: id
	 			}, dataType: 'json',
			}).done(function(data) {
				callback(data);
			});
		}
	}

	$('#codGestor').select2({
		minimumInputLength: 3,
		quietMillis: 100,
		ajax: {
			url: '/tatu/centros/select2.php',
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
		initSelection: initSelectionGestor,
	});
});

// Guarda o codPessoa das pessoas removidas em um hidden.
var pessoasRemovidas = "";
function removerMembro(codPessoa, codMembro) {
	pessoasRemovidas = pessoasRemovidas.concat("."+codMembro);
	$("input[name=pessoasRemovidas]").val(pessoasRemovidas);
	$('.membro'+codPessoa).hide();
}

// Aplica o select2 nos campos que são criados.
function chamaSelect(numero, codPatronato) {
	$("input[name*='membros["+numero+"]']").select2({
		minimumInputLength: 3,
		quietMillis: 100,
		ajax: {
			url: '/tatu/patronatos/select2.php',
			dataType: 'json',
			data: function(term, page) {
				return {
					query: term,
					page_limit: 10,
					patronato: codPatronato
				};
			},
			results: function(data, page) {
				return data;
			}
		}
	});
}

// Cria campos para adicionar mais membros.
function novoMembro(event) {
	numeroCampo++;
	var html = "\
	Membro:<br />\
	<input type='text' id='membros["+numeroCampo+"]' name='membros["+numeroCampo+"]' style='width: 270px;' /><br />"
	$('#campo').append(html);
	$('input[name^=membro]:last').focus();
	chamaSelect(numeroCampo, <?= $patronato->codPatronato ?>);
}
</script>

<?php if ((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7))) { ?>
<form name="gravaMembros" method="post" action="gravaMembros.php">
<?php } ?>
<div class="panel panel-default" style="width: 55%; float: left">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="visualizar"><?= $patronato->nome ?></span><span class="editar">Editar</span>
			<?= !((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) ) ? '' : '<button id="editarPatronato" class="btn btn-default pull-right btn-xs visualizar"><span class="glyphicon glyphicon-pencil"></span> Editar</button>' ?>
			<button id="cancelarEdicao" class="btn btn-danger btn-xs editar pull-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs editar pull-right"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
		</h3>
	</div>
	<div class="panel-body">
		<table class="table"><tr>
			<td width="50%" style="border-top: 0px;">
				<span class="editar"><label for="nome">Nome:</label>
				<input type="text" class="form-control" id="nome" name="nome" style="width: 300px;" value="<?= $patronato->nome ?>" /><br /></span>

				<label for="codGestor">Gestor:</label><br />
				<em class="visualizar"><a href="/tatu/pessoas/pessoa.php?codPessoa=<?= $patronato->codGestor ?>" style="color: #333;"><?= $patronato->nomeGestor ?></a></em>
				<input type="text" style="width: 300px;" id="codGestor" class="editar" name="codGestor" value="<?= $patronato->codGestor ?>"/>
			</td>

			<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) { ?>
			<td class="editar table-bordered" style="border-right: 0px; border-bottom: 0px; border-top: 0px;">
				<br /><input type="button" name="adicionar" style="margin-left: 25%;" class="btn btn-default btn-default" value="Adicionar Membro" onClick="novoMembro()" />
				<div id="campo"></div></span>
			</td>
			<?php } ?>

			<input type="hidden" id="codPatronato" name="codPatronato" value="<?=  $patronato->codPatronato ?>" />
			<input type="hidden" id="pessoaRemovidas" name="pessoasRemovidas" value="" />
		</tr></table>
	</div>
</div>
<?php if ((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7))) { ?>
</form>
<?php } ?>

<?php if ($htmlInicial != "") { ?>
<div class="panel panel" style="width: 44%; float: right">
	<div class="panel-heading">
		<center><h3 class="panel-title">Membros</h3></center>
	</div>
		<div class="panel-body">
			<div id="listaDeMembros" class="well mono"></div>
		</div>
	</div>
<?php } else { ?>
	<div id="listaDeMembros"></div>
<?php }
}
?>
