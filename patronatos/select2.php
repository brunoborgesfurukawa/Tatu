<?php
require '../autoload.php';
require '../util/dataHora.php';

// verifica se os parâmetros necessários existem
// ou há uma busca, ou um ID e o tipo
if (!isset($_GET['query']) && !isset($_GET['id'])) {
	die();
}

header('Content-Type: application/json');

$retorno = array();

if (isset($_GET['id'])) {
	// caso seja busca por ID, retorna o objeto
	$pessoas = new Pessoa($_GET['id']);
	$retorno['id'] = $pessoas->codPessoa;
	$retorno['text'] = !empty($pessoas->nome) ? ($pessoas->nome . ' - ' . $pessoas->dataNascimento) : $pessoas->dataNascimento;
} else {
	// faz a busca e retorna objetos dos dois tipos
	$limite = isset($_GET['page_limit']) ? intval($_GET['page_limit']) : 10;
	$busca = str_replace(' ', '%', trim($_GET['query']));
	$patronatoSelect2 = $_GET['patronato'];

	// Pega os membros atuais do patronato.
	$pesquisaMembros = Pessoa::find(
		array('membroAtivo = 1'),
		array(
			'select' => 'Pessoas.codPessoa',
			'joins' => 'INNER JOIN PatronatosMembros ON PatronatosMembros.codPessoa = Pessoas.codPessoa',
			'limit' => $limite)
	);

	// Armazena o resultado em um comando IN, as pessoas que fizerem parte
	// desta lista não deverão estar no retorno da pesquisa final.
	$membrosPatronato = "IN(";
	foreach ($pesquisaMembros as $membro) {
		$membrosPatronato .= "$membro->codPessoa, ";
	}
	$membrosPatronato .= "'')";

	// Pesquisa por pessoas que não seja membro ativo deste patronato e que seja colaborador.
	$pessoas = Pessoa::find(
		array('((membroAtivo = 0 AND codPatronato = ?) OR codPatronato <> ? OR membroAtivo IS NULL) AND nome LIKE ? AND colaborador = 1 AND Pessoas.codPessoa NOT ' . $membrosPatronato, $patronatoSelect2, $patronatoSelect2, '%' . $busca . '%' ),
		array(
			'select' => 'Pessoas.codPessoa, Pessoas.nome, Pessoas.dataNascimento',
			'joins' => 'LEFT JOIN PatronatosMembros ON PatronatosMembros.codPessoa = Pessoas.codPessoa',
			'order' => 'nome',
			'limit' => $limite,
			'group' => 'codPessoa')
	);

	foreach ($pessoas as $pessoa) {
		$linha = array();
		$linha['id'] = $pessoa->codPessoa;
		$linha['text'] = $pessoa->nome . ' - ' . data_pt($pessoa->dataNascimento);
		$retorno['results'][] = $linha;
	}
}

echo json_encode($retorno);
?>
