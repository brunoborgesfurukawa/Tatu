<?php
$titulo = 'Cadastrar Patronato';
$ativo[2] = "active";
require '../cabecalho.php';

if (!PessoasPermissao::verificaPermissao($u->codPessoa, 8)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}
?>
<script>
$(document).ready(function() {

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
		}
	});
});
</script>

<form name="grupos" method="post" action="gravaPatronato.php">

	<form name="centros" method="post" action="gravaCentro.php">

	<div class="panel panel-default" style="width: 55%; float: left">
		<div class="panel-heading">
			<h3 class="panel-title">
			<span>Cadastrar Patronato</span>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Cadastrar</button>
			<button type="reset" id="limparCampo" class="btn btn-warning btn-xs pull-right" onClick="history.go(0)"><span class="glyphicon glyphicon-repeat"></span> Limpar</button>
			</h3>
		</div>
	<div class="panel-body">

	<input type="hidden" id="codPatronato" name="codPatronato" value="" />

	<label for="nome">Nome:</label><br />
	<input type="text" class="form-control" id="nome" name="nome" style="width: 300px;" placeholder="Digite o nome do patronato..." required />
	<br />

	<label for="codGestor">Gestor:</label><br />
	<input type="text" id="codGestor" name="codGestor" style="width: 300px;" placeholder="Pesquise o gestor..." required />

	</div>
	</div>
</form>
