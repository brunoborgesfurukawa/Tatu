<?php
$titulo = 'Grupo';
$ativo[1] = "active";
require '../cabecalho.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 5) && !PessoasPermissao::verificaGrupo($_GET['codGrupo'], $u->getTodosGrupos()) ) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

// Verifica se algum grupo foi selecionado.
if (isset($_GET['codGrupo'])) {
	$grupos = new Grupo($_GET['codGrupo']);
}

$grupos = Grupo::getGrupoMembro($_GET['codGrupo']);
// Verifica se algum valor foi retornado, caso não, o grupo não existe.
if (empty($grupos)) {
	erroFatal('<center>Este grupo não existe. Voltar para a <a href="javascript:history.back()">página anterior</a>.</center>');
}

foreach ($grupos as $grupo) {
// Pesquisa pelos membros do grupo e guarda em uma variável a lista a ser exibida.
$membros = GrupoMembro::getMembro($_GET['codGrupo'], 1);

$htmlInicial = "";
foreach ($membros as $membro) {
	$htmlInicial .= <<<EOT
	<div class='membro$membro->codMembro row' id='membro$membro->codMembro'>\
	<div class='col-md-8'><a href='../pessoas/pessoa.php?codPessoa=$membro->codPessoa' target='_blank'>$membro->nome</a></div>\
EOT;
	if (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
		$htmlInicial .= <<<EOT
		<div class='col-md-4' style='margin-bottom:1px;'><button class='botaoRemover btn btn-danger btn-xs' onClick='removerMembro($membro->codMembro)'><span class='glyphicon glyphicon-remove'></span> Remover</button></div>\
EOT;
	}
	$htmlInicial .= "</div>";
} ?>

<script>
var numeroCampo = 0;
var htmlInicial = "<?= $htmlInicial ?>";
$(document).ready(function() {
	// Assim que a página carregar, será imprimido a lista de membros
	// e os botões de edição serão escondidos.
	document.getElementById('listaDeMembros').innerHTML = htmlInicial;
	$('.editar, .botaoRemover').hide();

	// Adiciona ação aos botões 'Editar' e 'Cancelar'.
	$('#editarGrupo, #cancelarEdicao').click(function() {
		// Caso uma edição seja cancelada, a lista de membros será imprimida novamente.
		document.getElementById('listaDeMembros').innerHTML = htmlInicial;
		$('.editar, .visualizar').toggle();
		return false;
	});

	$('#cancelarEdicao').click(function() {
		$('.botaoRemover').hide();
		numeroCampo = 0;
		$('#campo').html("<br />");
		$("input[name=pessoasRemovidas]").val("")
		return false;
	});

	function initSelectionGerente(element, callback) {
		var id = $(element).val();
		// Só faz algo se houver valor no campo.
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

	// Aplica o select2 ao campo.
	$('#codGerente').select2({
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
		initSelection: initSelectionGerente,
	});
});

// Guarda o codMembro das pessoas removidas em um hidden.
var pessoasRemovidas = "";
function removerMembro(codPessoa) {
	pessoasRemovidas = pessoasRemovidas.concat("."+codPessoa);
	$("input[name=pessoasRemovidas]").val(pessoasRemovidas);
	$('.membro'+codPessoa).hide();
}

// Aplica o select2 nos campos que são criados.
function chamaSelect(numero, codGrupo) {
	$("input[name*='membros["+numero+"]']").select2({
		minimumInputLength: 3,
		quietMillis: 100,
		ajax: {
			url: '/tatu/grupos/select2.php',
			dataType: 'json',
			data: function(term, page) {
				return {
					query: term,
					page_limit: 10,
					grupo: codGrupo
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
	chamaSelect(numeroCampo, <?=  $grupo->codGrupo ?>);
}
</script>

<?php
// Verifica se a pessoa tem permissão para editar os dados.
if ((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7))) { ?>
<form name="gravaMembros" method="post" action="gravaMembros.php">
<?php } ?>
<div class="panel panel-default" style="width: 55%; float: left">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="visualizar"><?= $grupo->nome ?></span><span class="editar">Editar</span>
			<?= !((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) ) ? '' : '<button id="editarGrupo" class="btn btn-default pull-right btn-xs visualizar"><span class="glyphicon glyphicon-pencil"></span> Editar</button>' ?>
			<button id="cancelarEdicao" class="btn btn-danger btn-xs editar pull-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs editar pull-right"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
		</h3>
	</div>

	<div class="panel-body">
		<table class="table"><tr>
			<td width="50%" style="border-top: 0px;">
				<span class="editar"><label for="nome">Nome:</label>
				<input type="text" id="nome" name="nome" class="form-control" style="width: 300px;" value="<?= $grupo->nome ?>" /></span>

				<label for="codCentro">Centro:</label><br />
				<span class="visualizar"><a href="/tatu/centros/centro.php?codCentro=<?= $grupo->codCentro ?>" style="color: #333;"><?= $grupo->nomeCentro ?></a><br /></span>
				<input type="text" style="width: 300px;" id="codCentro" class="editar form-control" name="codCentro" disabled value="<?= $grupo->nomeCentro ?>"/>

				<label for="codGerente">Gerente:</label><br />
				<span class="visualizar"><a href="/tatu/pessoas/pessoa.php?codPessoa=<?= $grupo->codGerente ?>" style="color: #333;"><?= $grupo->nomeGerente ?></span>
				<input type="text" style="width: 300px;" id="codGerente" class="editar" name="codGerente" value="<?= $grupo->codGerente ?>"/>
			</td>
			<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 7)) { ?>
			<td class="editar table-bordered" style="border-right: 0px; border-bottom: 0px; border-top: 0px;">
				<br /><input type="button" name="adicionar" style="margin-left: 25%;" class="btn btn-default btn-default" value="Adicionar Membro" onClick="novoMembro()" />
				<div id="campo"><br /></div>
			</td>
			<?php } ?>

			<input type="hidden" id="codGrupo" name="codGrupo" value="<?=  $_GET['codGrupo'] ?>" />
			<input type="hidden" id="pessoaRemovidas" name="pessoasRemovidas" value="" />
		</tr></table>
	</div>
</div>
<?php
if ((PessoasPermissao::verificaPermissao($u->codPessoa, 6)) || (PessoasPermissao::verificaPermissao($u->codPessoa, 7))) { ?>
</form>
<?php } ?>

<?php if ($htmlInicial != "") { ?>
	<div class="panel" style="width: 44%; float: right">
		<div class="panel-heading">
			<center><h3 class="panel-title">Membros</h3></center>
		</div>

		<div class="panel-body form-group">
			<div id="listaDeMembros" class="well mono"></div>
		</div>
	</div>
<?php } else { ?>
	<div id="listaDeMembros"></div>
<?php }
} ?>
