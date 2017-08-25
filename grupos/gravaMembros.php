<?php
$titulo = "Grava Membros";
require '../autoload.php';

$codGrupo = $_POST['codGrupo'];
$grupo = new Grupo($codGrupo);
$membros = isset($_POST['membros']) ? $_POST['membros'] : NULL;
// Trata o valor recebido do hidden pessoasRemovidas e armazena em um array.
$removidos = isset($_POST['pessoasRemovidas']) ? explode(".", $_POST['pessoasRemovidas']) : NULL;
$membrosCadastrados = GrupoMembro::getMembro($codGrupo);

// Caso tenha sido adicionado um novo membro.
if ($membros != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
	foreach ($membros as $membro) {
		// Verifica se o select2 foi preenchido.
		if($membro != "") {
			$registro = new Registro();

			// Verifica se a pessoa já fez parte deste grupo e assim não será
			// criado um novo registro no GruposMembros.
			$exitente = FALSE;
			foreach ($membrosCadastrados as $membroCadastrado) {
				if ($membroCadastrado->codPessoa == $membro && $membroCadastrado->codGrupo == $codGrupo) {
					$grupoMembros = new GrupoMembro($membroCadastrado->codMembro);
					$exitente = TRUE;
					break;
				}
			}

			if (!$exitente) {
				$grupoMembros = new GrupoMembro();
			}

			$grupoMembros->codPessoa = $membro;
			$grupoMembros->codGrupo = $codGrupo;
			$grupoMembros->membroAtivo = 1;

			$registro->codPessoa = $membro;
			$registro->descricao = "Entrou no grupo $grupo->nome";
			$registro->dataHora = date ("Y-m-d H:i:s");

			$grupoMembros->store();
			$registro->store();
		}
	}
}

// Caso algum membro tenha sido removido.
if ($removidos != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 7)) {
	foreach ($removidos as $removido) {
		if (isset($removido) && $removido != "") {
			$grupoMembros = new GrupoMembro($removido);
			$registro = new Registro();

			$grupoMembros->membroAtivo = 0;

			$registro->codPessoa = $grupoMembros->codPessoa;
			$registro->descricao = "Saiu do grupo $grupo->nome";
			$registro->dataHora = date("Y-m-d H:i:s");

			$grupoMembros->store();
			$registro->store();
		}
	}
}

// Caso algum dado do grupo tenha sido editado e a pessoa tenha permissão para isso.
if ($codGrupo != NULL && PessoasPermissao::verificaPermissao($u->codPessoa, 6)) {
	$nome = $_POST['nome'];
	$codGerente = $_POST['codGerente'];
	$grupo = new Grupo($codGrupo);
	$grupo->nome = $nome;
	$grupo->codGerente = $codGerente;
	$grupo->store();
}

header('Location: grupo.php?codGrupo=' . $codGrupo);
?>
