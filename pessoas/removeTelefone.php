<?php
require '../autoload.php';

if (!PessoasPermissao::verificaPermissao($u->codPessoa, 3)) {
	echo 'Vocë não tem permissões para gravar estes dados, voltar para a <a href="javascript:history.back()">página anterior</a>.';
	exit();
}

var_dump($_POST['codRemover']);

$telefone = new Telefone($_POST['codRemover']);
$codPessoa = $telefone->codPessoa;

$telefone->delete();

header('Location: pessoa.php?codPessoa=' . $codPessoa);

?>