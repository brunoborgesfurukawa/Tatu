<?php
$titulo = "Grava Membros";
require '../autoload.php';

$codPatronato = $_POST['codPatronato'];
$patronato = new Patronato($codPatronato);
$membros = isset($_POST['membros']) ? $_POST['membros'] : NULL;
// Trata o valor recebido do hidden pessoasRemovidas e armazena em um array.
$removidos = isset($_POST['pessoasRemovidas']) ? explode(".", $_POST['pessoasRemovidas']) : NULL;
$membrosCadastrados = PatronatoMembro::getMembro($codPatronato);

// Caso tenha sido adicionado um novo membro.
if ($membros != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
	foreach ($membros as $membro) {
		// Verifica se o select2 foi preenchido.
		if($membro != "") {
			$registro = new Registro();

			// Verifica se a pessoa já fez parte deste patronato e assim não será
			// criado um novo registro no PatronatosMembros.
			$exitente = FALSE;
			foreach ($membrosCadastrados as $membroCadastrado) {
				if ($membroCadastrado->codPessoa == $membro) {
					$patronatoMembros = new PatronatoMembro($membroCadastrado->codPessoa);
					$exitente = TRUE;
					break;
				}
			}

			if (!$exitente) {
				$patronatoMembros = new PatronatoMembro();
			}

			$patronatoMembros->codPessoa = $membro;
			$patronatoMembros->codPatronato = $codPatronato;
			$patronatoMembros->membroAtivo = 1;

			$registro->codPessoa = $membro;
			$registro->descricao = "Entrou no patronato $patronato->nome";
			$registro->dataHora = date ("Y-m-d H:i:s");

			$patronatoMembros->store();
			$registro->store();
		}
	}
}

// Caso algum membro tenha sido removido.
if ($removidos != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
	foreach ($removidos as $removido) {
		if (isset($removido) && $removido != "") {

			$patronatoMembros = new PatronatoMembro($removido);
			$registro = new Registro();

			$patronatoMembros->membroAtivo = 0;

			$registro->codPessoa = $patronatoMembros->codPessoa;
			$registro->descricao = "Saiu do patronato $patronato->nome";
			$registro->dataHora = date("Y-m-d H:i:s");

			$patronatoMembros->store();
			$registro->store();
		}
	}
}

// Caso algum dado do patronato tenha sido editado.
if ($codPatronato != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 6)) {
	$nome = $_POST['nome'];
	$codGestor = $_POST['codGestor'];
	$patronato = new Patronato($codPatronato);
	$patronato->nome = $nome;
	$patronato->codGestor = $codGestor;
	$patronato->store();
}

header('Location: patronato.php?codPatronato=' . $codPatronato);
?>
