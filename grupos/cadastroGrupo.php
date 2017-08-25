<?php
$titulo = 'Cadastrar Grupo';
$ativo[1] = "active";
require '../cabecalho.php';

if (!PessoasPermissao::verificaPermissao($u->codPessoa, 8)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}
?>

<script>
$(document).ready(function() {
	// Aplica o select2 aos campos.
	$('input[name^=codPessoa]').select2({
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
		}
	});

	// Aplica o select2 aos campos.
	$('input[name^=codCentro]').select2({
		minimumInputLength: 3,
		quietMillis: 100,
		ajax: {
			url: '/tatu/centros/select2Centro.php',
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
		}
	});
});
</script>

<form class="form-horizontal" name="grupos" method="post" action="gravaGrupo.php">
	<div class="panel panel-default" style="width: 55%; float: left">
		<div class="panel-heading">
			<h3 class="panel-title">
			<span>Cadastrar Grupo</span>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Cadastrar</button>
			<button type="reset" id="limparCampo" class="btn btn-warning btn-xs pull-right" onClick="history.go(0)"><span class="glyphicon glyphicon-repeat"></span> Limpar</button>
			</h3>
		</div>

		<div class="panel-body">

			<label for="nome">Nome:</label><br />
			<input type="text" class="form-control" id="nome" name="nome" style="width: 300px;" placeholder="Digite o nome do grupo..." required /><br />

			<label for="codCentro">Centro:</label><br />
			<input type="text" id="codCentro" name="codCentro" style="width: 300px;" placeholder="Pesquise o centro..."  required /><br /><br />

			<label for="codPessoa">Gerente:</label><br />
			<input type="text" id="codPessoa" name="codPessoa" style="width: 300px;" placeholder="Pesquise o gerente..." required />

		</div>
	</div>
</form>
