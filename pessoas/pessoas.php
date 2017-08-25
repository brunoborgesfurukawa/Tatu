<?php
$titulo = 'Pessoas';
$ativo[0] = "active";
require '../cabecalho.php';
require '../util/paginacao.php';
require '../util/dataHora.php';

// Define o número de registros por página
const TAMANHO_PAGINA = 20;

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 2) && ($u->contatosLigados < 1)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

// Se houver a página, pega o valor, senão, coloca 1
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$inicio = ($pagina - 1) * TAMANHO_PAGINA;

// resgata as pessoas dentro da paginação e o total de registros
$pesquisa = isset($_GET['pesquisa']) ? '%' . $_GET['pesquisa'] . '%' : '%';
$pesquisa = str_replace(' ', '%', $pesquisa);

// Define a opção atual selecionada.
$tipoPessoa = isset($_GET['tipoPessoa']) ? $_GET['tipoPessoa'] : "qualquer";

// Define se são somente pessoas sem aprovação.
$aprovados = isset($_GET['aprovados']) ? $_GET['aprovados'] : NULL;

// Converte os valores do $tipoPessoa para ser usado na pesquisa.
$tipoPesquisa = ($tipoPessoa == "colaborador") ? "1" : $tipoPessoa;
$tipoPesquisa = ($tipoPessoa == "contato") ? "NULL" : $tipoPesquisa;

$codColaborador = (isset($_GET['codColaborador']) && $_GET['codColaborador'] != "") ? $_GET['codColaborador'] : "qualquer";

// Define quais serão a características do WHERE na chamada do método getPessoas.
$condicoes = array( 'pesquisa' => $pesquisa,
					'tipo' => $tipoPesquisa,
					'codColaborador' => $codColaborador,
					'aprovado' => $aprovados);

// retornar as Pessoas cadastradas no banco
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 2)) {
	// Une todos os colaboradores e contatos que a pessoa pode visualizar.
	$listaPessoas = array_merge($u->getTodosContatos(), $u->getTodosColaboradores());
	$pessoas = Pessoa::getPessoas($inicio, $condicoes, $listaPessoas);
} else {
	$pessoas = Pessoa::getPessoas($inicio, $condicoes);
}

$totalRegistros = $pessoas['quantidade'];

$pesquisa = str_replace('%', '', $pesquisa);

// calcula o total de páginas
$totalPaginas = ceil($totalRegistros / TAMANHO_PAGINA);
?>

<script>
var tipoPessoa = "<?= $tipoPessoa ?>";

function mudarSelecao(clicado) {
	if (clicado == "colaborador") {
		if (tipoPessoa == "colaborador") {
			$("#colaborador").prop("checked", false);
			tipoPessoa = "qualquer";
			$('#contatoDe').html("");
		} else {
			tipoPessoa = "colaborador";
			$('#contatoDe').html("");
		}

	}

	if (clicado == "contato") {
		if (tipoPessoa == "contato") {
			$("#contato").prop("checked", false);
			tipoPessoa = "qualquer";
			$('#contatoDe').html("");
		} else {
			tipoPessoa = "contato";

			var colaborador;
			colaborador = '\
			Colaborador:\
			<input type="text" id="codColaborador" <?= ($codColaborador != "qualquer") ? "value= $codColaborador" : "" ?> name="codColaborador" placeholder="Deixe em branco para exibir todos os contatos" />';
			$('#contatoDe').html(colaborador);
			select2Colaborador();
		}

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
		}
	});
}

$(document).ready(function() {

	if (tipoPessoa == "contato") {
		var colaborador;
			colaborador = '\
			Colaborador:\
			<input type="text" id="codColaborador" <?= ($codColaborador != "qualquer") ? "value= $codColaborador" : "" ?> name="codColaborador" placeholder="Deixe em branco para exibir todos os contatos" />';
			$('#contatoDe').html(colaborador);
			select2Colaborador();
	}

	// Remove o campo de seleção do colaborador.
	$("#colaborador").on( "click", function() {
		$('#contatoDe').html("");
	});

	$('#colaborador').click(function() {
		mudarSelecao(this.id);
	});

	$('#contato').click(function() {
		mudarSelecao(this.id);
	});
});
</script>

<center><a href="#form-filtro" class="btn btn-default btn-xs" data-toggle="collapse">
	<i class="glyphicon glyphicon-filter"></i> Filtrar resultados
</a></center>

<div id="form-filtro" class="collapse">
	<form method="get" class="form-inline well" id="formFiltro">

		<center><div class="input-group col-xs-7">
			<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 3) || PessoasPermissao::verificaPermissao($u->codPessoa, 2)) { ?>
			<span class="input-group-addon" style="width: 1%;" title="Exibir somente pessoas não aprovadas">
				<input type="checkbox" name="aprovados" value="0" <?= $aprovados === "0" ? 'checked' : '' ?> /> <span class="glyphicon glyphicon-flag"></span>
			</span>
			<?php } ?>
			<input type="text" id="pesquisa" name="pesquisa" class="form-control" value="<?= htmlspecialchars($pesquisa) ?>" placeholder="Nome" /></div>
			<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 2)) { ?>
			<span id="contatoDe"></span><br />
			<input type="radio" name="tipoPessoa" id="colaborador" value="colaborador" <?= ($tipoPessoa == "colaborador") ? "checked" : "" ?> /> Colaboradores
			<input type="radio" name="tipoPessoa" id="contato" value="contato" <?= ($tipoPessoa == "contato") ? "checked" : "" ?> /> Contatos
			<?php } ?>
				<p>
				<span class="input-group-btn">
					<button type="submit" class="btn btn-default">Pesquisar</button>
				</span>
				</p>
		</center>
	</form>
</div>

<?php
	if ($pessoas['resultado'] == NULL) {
		erroFatal('Nenhum resultado correspondente a sua pesquisa foi encontrado.');
	}
?>

<div class="panel panel-default">
	<div class="panel-heading" style="text-align:center;font-weight:bold;">Pessoas</div>
		<table class="table table-striped table-bordered" >
			<tbody style="text-align:center;">
				<tr>
					<th>Nome</th>
					<th>Data de Nascimento</th>
				</tr>
<?php
	foreach ($pessoas['resultado'] as $pessoa) {
?>
				<tr <?= ($pessoa->aprovacao == 1) ? 'class="danger" title="Esta pessoa ainda não foi aprovada por um operador do sistema."' : '' ?>>
					<td><a href="pessoa.php?codPessoa=<?= $pessoa->codPessoa ?>"><?= $pessoa->nome ?></td>
					<td><?= data_pt($pessoa->dataNascimento) ?></td>
				</tr>


<?php } ?>
			</tbody>
		</table>
</div>

<?php
	paginacao($pagina, $totalPaginas, $pesquisa);
?>
