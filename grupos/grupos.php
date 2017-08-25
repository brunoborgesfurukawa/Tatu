<?php
$titulo = 'Grupos';
$ativo[1] = "active";
require '../cabecalho.php';
require '../util/paginacao.php';

// Define o número de registros por página
const TAMANHO_PAGINA = 20;

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 5) && ($u->gruposLigados < 1)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

// Se houver a página, pega o valor, senão, coloca 1
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$inicio = ($pagina - 1) * TAMANHO_PAGINA;

// resgata as pessoas dentro da paginação e o total de registros
$pesquisa = isset($_GET['pesquisa']) ? '%' . $_GET['pesquisa'] . '%' : '%';
$pesquisa = str_replace(' ', '%', $pesquisa);

// Trata o valor do $_GET['codGerente'] para ser utilizado no método de pesquisa.
$codGerente = (isset($_GET['codGerente']) && $_GET['codGerente'] != "") ? $_GET['codGerente'] : "qualquer";

//Método para retornar os grupos cadastradas no banco
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 5)) {
	$grupos = Grupo::getGrupo($inicio, $pesquisa, $codGerente, $u->getTodosGrupos());
} else {
	$grupos = Grupo::getGrupo($inicio, $pesquisa, $codGerente);
}
$totalRegistros = $grupos['quantidade'];

$pesquisa = str_replace('%', '', $pesquisa);

// calcula o total de páginas
$totalPaginas = ceil($totalRegistros / TAMANHO_PAGINA);
?>

<script>
$(document).ready(function() {
	$('#codGerente').select2({
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
			Gerente: <input type="text" id="codGerente" name="codGerente" placeholder="Deixe em branco para exibir grupos de qualquer gerente" />
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
if($grupos['resultado'] == NULL) {
	erroFatal('Nenhum resultado correspondente a sua pesquisa foi encontrado.');
}
?>

<div class="panel panel-default">
	<div class="panel-heading" style="text-align:center;font-weight:bold;">Grupos</div>

	<table class="table table-striped table-bordered">
		<tbody style="text-align:center;">
		<tr>
			<th>Nome</th>
			<th>Centro</th>
			<th>Gerente</th>
		</tr>
<?php
	foreach ($grupos['resultado'] as $grupo) {
?>
		<tr>
			<td><a href="grupo.php?codGrupo=<?= $grupo->codGrupo ?>"><?= $grupo->nome ?></td>
			<td><a href="../centros/centro.php?codCentro=<?= $grupo->codCentro ?>"><?= $grupo->nomeCentro ?></a></td>
			<td><a href="../pessoas/pessoa.php?codPessoa=<?= $grupo->codGerente ?>"><?= $grupo->nomeGerente ?></a></td>
		</tr>

<?php } ?>
		</tbody>
	</table>
</div>
<?php
	paginacao($pagina, $totalPaginas, $pesquisa);
?>
