<?php
$titulo = 'Contribuições';

require '../cabecalho.php';
require '../util/paginacao.php';
require '../util/dataHora.php';
// Define o número de registros por página
const TAMANHO_PAGINA = 20;

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 13) && ($u->contribuicoesLigadas < 1)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

// Se houver a página, pega o valor, senão, coloca 1
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

$inicio = ($pagina - 1) * TAMANHO_PAGINA;

//Método para retornar os grupos cadastradas no banco
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 13)) {
	$contribuicoes = Contribuicao::getContribuicao($inicio, $u->getTodasContribuicoes());
} else {
	$contribuicoes = Contribuicao::getContribuicao($inicio);
}
$totalRegistros = $contribuicoes['quantidade'];

// calcula o total de páginas
$totalPaginas = ceil($totalRegistros / TAMANHO_PAGINA);

if ($contribuicoes['resultado'] == NULL) {
	erroFatal('Nenhum resultado correspondente a sua pesquisa foi encontrado.');
}
?>
<div class="panel panel-default">
	<div class="panel-heading" style="text-align:center;font-weight:bold;">Contribuicões</div>

		<table class="table table-striped table-bordered">
			<tbody>
			<tr>
				<th>Contribuinte</th>
				<th>Data Inicio</th>
				<th>Data Fim</th>
				<th>Status</th>
				<th>Tipo</th>
				<th>Moeda</th>
				<th>Valor</th>
				<?= (PessoasPermissao::verificaPermissao($u->codPessoa, 12)) ? '<th>Editar</th>' : '' ?>
			</tr>
<?php
	foreach ($contribuicoes['resultado'] as $contribuicao) {
?>
			<tr>
				<td><a href="../pessoas/pessoa.php?codPessoa=<?= $contribuicao->codPessoa ?>"><?= $contribuicao->nome ?></a></td>
				<td><?= data_pt($contribuicao->dataInicio) ?></td>
				<td><?= data_pt($contribuicao->dataFim) ?></td>
				<td><?= $contribuicao->descricaoStatus ?></td>
				<td><?= $contribuicao->descricao ?></td>
				<td><?= $contribuicao->moedaTipo ?></td>
				<?php
				if($contribuicao->moedaTipo == "SALÁRIO MINÍMO") {
					echo "<td>".(int)$contribuicao->valor."%</td>";
				}
				else{
					echo "<td>R$ ".$contribuicao->valor."</td>";
				}
				?>
				<?= (PessoasPermissao::verificaPermissao($u->codPessoa, 12)) ? '<td class="text-center"><a href="editarContribuicao.php?codContribuicao='.$contribuicao->codContribuicao.'"><button type="button" class="btn btn-link btn-xs btn-block"><span class="glyphicon glyphicon-pencil"></span> Editar</button></a></td>' : '' ?>
			</tr>

<?php } ?>
			</tbody>
		</table>
	</div>
</div≳
<?php
	paginacao($pagina, $totalPaginas);
?>
