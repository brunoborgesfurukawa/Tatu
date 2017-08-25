<?php
$titulo = "Grava Permissoes";
require '../autoload.php';

$codPessoa = $_POST["codPessoa"];
$novasPermissoes = $_POST["permissao"];
PessoasPermissao::apagaPermissoes($codPessoa);

foreach ($novasPermissoes as $novaPermissao) {
	$permissao = new PessoasPermissao();
	$permissao->codPessoa = $codPessoa;
	$permissao->codPermissao = $novaPermissao;
	$permissao->store();
}

header('Location: editaPermissoes.php?codPessoa='. $codPessoa);
?>
