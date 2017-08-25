<?php
$titulo = 'Centros';
$ativo[3] = "active";
require '../cabecalho.php';
require '../util/paginacao.php';

/* Define o número de registros por página */
const TAMANHO_PAGINA = 20;

/* Verifica se a pessoa tem permissão para visualizar a página. */
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 9) && ($u->centrosLigados < 1)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$inicio = ($pagina - 1) * TAMANHO_PAGINA;

/* Resgata as pessoas dentro da paginação e o total de registros*/
$pesquisa = isset($_GET['pesquisa']) ? '%' . $_GET['pesquisa'] . '%' : '%';
$pesquisa = str_replace(' ', '%', $pesquisa);

/* Trata o valor do $_GET['codGestor'] para ser utilizado no método de pesquisa. */
$codGestor = (isset($_GET['codGestor']) && $_GET['codGestor'] != "") ? $_GET['codGestor'] : "qualquer";

/* Método para retornar os Centros cadastradas no banco */
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 9)) {
	$centros = Centro::getCentro($inicio, $pesquisa, $codGestor, $u->getTodosCentros());
} else {
	$centros = Centro::getCentro($inicio, $pesquisa, $codGestor);
}

$totalRegistros = $centros['quantidade'];

$pesquisa = str_replace('%', '', $pesquisa);

/* calcula o total de páginas */
$totalPaginas = ceil($totalRegistros / TAMANHO_PAGINA);
?>

<script>
$(document).ready(function() {
	$('#codGestor').select2({
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
		}
	});
});
</script>

<center><a href="#form-filtro" class="btn btn-default btn-xs" data-toggle="collapse">
	<i class="glyphicon glyphicon-filter"></i> Filtrar resultados
</a></center>

<div id="form-filtro" class="collapse">
	<form method="get" class="form-inline well" id="formFiltro">

		<center><div class="input-group col-xs-7 ?>">
			<input type="text" id="pesquisa" name="pesquisa" class="form-control" value="<?= htmlspecialchars($pesquisa) ?>" placeholder="Nome" />
			<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 5)) { ?>
			<br />
			Gestor: <input type="text" id="codGestor" name="codGestor" placeholder="Deixe em branco para exibir centros de qualquer gestor" />
			<?php } ?>
				<p>
				<span class="input-group-btn">
					<button type="submit" class="btn btn-default">Pesquisar</button>
				</span>
				</p>
		</div></center>
	</form>
</div>

<?php
if($centros['resultado'] == NULL) {
	erroFatal('Nenhum resultado correspondente a sua pesquisa foi encontrado.');
}
?>

<div class="panel panel-default">
	<div class="panel-heading" style="text-align:center;font-weight:bold;">Centros</div>

		<table class="table table-striped table-bordered">
			<tbody style="text-align:center;">
			<tr>
				<th>Nome</th>
				<th>Local</th>
				<th>Gestor</th>
			</tr>
<?php
	foreach ($centros['resultado'] as $centro) {
?>
			<tr>
				<td><a href="centro.php?codCentro=<?= $centro->codCentro ?>"><?= $centro->nome ?></td>
				<td><?= $centro->local ?></td>
				<td><a href="../pessoas/pessoa.php?codPessoa=<?= $centro->codGestor ?>"><?= $centro->gestor ?></a></td>
			</tr>
<?php } ?>
			</tbody>
		</table>
</div>

<?php
	paginacao($pagina, $totalPaginas, $pesquisa);
?>
