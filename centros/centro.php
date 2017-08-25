<?php
$titulo = 'Centro';
$ativo[3] = "active";
require '../cabecalho.php';

/*
Verifica se a pessoa tem permissão para visualizar a página.
*/
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 9) && !PessoasPermissao::verificaCentro($_GET['codCentro'], $u->getTodosCentros()) ) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

if (isset($_GET['codCentro'])) {
	$centros = new Centro($_GET['codCentro']);
}
	$centros = Centro::getCentroMembro($_GET['codCentro']);

if (empty($centros)) {
	erroFatal('<center>Este centro não existe. Voltar para a <a href="javascript:history.back()">página anterior</a>.</center>');
}
	foreach ($centros as $centro) {
?>
<script>
/* 
Script responsável pela mudança de visibilidade de cada item da página
*/
$(document).ready(function() {
	$('.editar').hide();
	$('visualizar').show();

	$('#editarCentro, #cancelarEdicao').click(function() {
		$('.editar, .visualizar').toggle();
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
</script>


<?php
 /*
 Verifica se a pessoa tem permissão para editar os dados.
*/
 if (PessoasPermissao::verificaPermissao($u->codPessoa, 10)) { ?>
<form method="POST" action="gravaCentro.php">
<?php } ?>
<div class="panel panel-default" style="width: 55%; float: left">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="visualizar"><?= $centro->nome ?></span><span class="editar">Editar</span>
			<?= !(PessoasPermissao::verificaPermissao($u->codPessoa, 10)) ? '' : '<button id="editarCentro" class="btn btn-default pull-right btn-xs visualizar"><span class="glyphicon glyphicon-pencil"></span> Editar</button>' ?>
			<button id="cancelarEdicao" class="btn btn-danger btn-xs editar pull-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs editar pull-right"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
		</h3>
	</div>
	<div class="panel-body">

		<input type="hidden" name="codCentro" value="<?= $_GET['codCentro'] ?>" />

		<span class="editar" >
		<label for="nome">Nome:</label>
		<input type="text" class="form-control" id="nome" name="nome" style="width: 300px;" value="<?= $centro->nome ?>" required /></span>

		<label for="local">Local:<br /></label><br />
		<em class="visualizar" ><?= $centro->local ?><br /></em>
		<input type="text" id="local" class="editar form-control" name="local" style="width: 300px;" value="<?= $centro->local ?>" required />

		<label for="gestor">Gestor:</label><br />
		<em class="visualizar" ><a href="/tatu/pessoas/pessoa.php?codPessoa=<?= $centro->codGestor ?>" style="color: #333;"><?= $centro->gestor ?><br /></em>
		<input type="text" id="codGestor" class="editar" name="codGestor" style="width: 300px;" value="<?= $centro->codGestor ?>" required />

	</div>
</div>
<?php
if (PessoasPermissao::verificaPermissao($u->codPessoa, 10)) { ?>
</form>
<?php } ?>

<?php
	if(!empty($_GET['codCentro'])){
		$grupos = Centro::getGrupos($_GET['codCentro']);
		if(!empty($grupos)){
?>
	<div class="panel" style="width: 44%; float: right">
		<div class="panel-heading">
			<center><h3 class="panel-title">Grupos</h3></center>
		</div>
		<div class="panel-body">
			<div class="well mono">
		<?php
			foreach ($grupos as $grupo) {
				echo "<a href='../grupos/grupo.php?codGrupo=". $grupo->codGrupo ."' target='_blank'>". $grupo->nome ."</a><br />";}
		} ?>
			</div>
		</div>
	<?php } ?>
	</div>
<?php } ?>
