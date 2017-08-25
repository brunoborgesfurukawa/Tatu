<?php
require '../autoload.php';
require '../util/dataHora.php';

/*  Verifica se os parâmetros necessários existem 
	ou há uma busca, ou um ID e o tipo
*/
if (!isset($_GET['query']) && !isset($_GET['id'])) {
	die();
}

header('Content-Type: application/json');

$retorno = array();

if (isset($_GET['id'])) {
	/* Caso seja busca por ID, retorna o objeto */
	$pessoas = new Pessoa($_GET['id']);
	$retorno['id'] = $pessoas->codPessoa;
	$retorno['text'] = !empty($pessoas->nome) ? ($pessoas->nome . ' - ' . $pessoas->dataNascimento) : $pessoas->dataNascimento;
} else {
	/* Faz a busca e retorna objetos dos dois tipos */
	$retorno['results'] = array();
	$limite = isset($_GET['page_limit']) ? intval($_GET['page_limit']) : 10;
	$busca = str_replace(' ', '%', trim($_GET['query']));
	$colaborador = 1;

	$pessoas = Pessoa::find(
		array('colaborador = 1 AND nome LIKE ?', '%' . $busca . '%'),
		array(
			'select' => 'codPessoa, nome, dataNascimento',
			'order' => 'nome',
			'limit' => $limite
		)
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
