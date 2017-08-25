<?php
require '../autoload.php';

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
	$centros = new Centro($_GET['id']);
	$retorno['id'] = $centros->codCentro;
	$retorno['text'] = !empty($centros->nome) ? ($centros->nome . ' - ' . $centros->local) : $centros->local;
} else {
	/* Faz a busca e retorna objetos dos dois tipos */
	$retorno['results'] = array();
	$limite = isset($_GET['page_limit']) ? intval($_GET['page_limit']) : 10;
	$busca = str_replace(' ', '%', trim($_GET['query']));
	
	$centros = Centro::find(
		array('nome LIKE ?', '%' . $busca . '%'),
		array(
			'order' => 'nome',
			'limit' => $limite
		)
	);

	foreach ($centros as $centro) {
		$linha = array();
		$linha['id'] = $centro->codCentro;
		$linha['text'] = $centro->nome . ' - ' . $centro->local;
		$retorno['results'][] = $linha;
	}
}

echo json_encode($retorno);
?>
