<meta charset="utf-8" />

<?php
$titulo = "Grava Pessoas";
require '../autoload.php';
require '../util/dataHora.php';

if (!PessoasPermissao::verificaPermissao($u->codPessoa, 3, "Colaborador", $u->tipoPessoa)) {
	echo 'Vocë não tem permissões para gravar estes dados, voltar para a <a href="javascript:history.back()">página anterior</a>.';
	exit();
}

// Verifica se a pessoa existe.
if ($_POST['codPessoa'] != NULL || !empty($_POST['codPessoa'])) {
	$pessoas = new Pessoa($_POST['codPessoa']);
	$enderecos = new Endereco($_POST['codEndereco']);
	$numeroCampo = 1;
	$pessoas->aprovacao = 0;

	if ($_POST['pessoa'] == "contato") {
		$contato = new Contato($pessoas->codPessoa);
		if ($contato->codContatoStatus == 4) {
			$contato->codContatoStatus = 1;
		}
		$contato->store();
	}
} else {
	$pessoas = new Pessoa();
	$enderecos = new Endereco();
	$numeroCampo = 0;
	$pessoas->aprovacao = (PessoasPermissao::verificaPermissao($u->codPessoa, 4)) ? 0 : 1;
}

	$pessoas->nome = $_POST['nome'];
	$dataFormatada = data_sql($_POST['campoData']);
	$pessoas->dataNascimento = $dataFormatada;
	$pessoas->email = $_POST['email'];

	if ($_POST['pessoa'] == "colaborador") {
		$pessoas->colaborador = 1;
	}

	$pessoas->store();

if ($_POST['codPessoa'] == NULL || empty($_POST['codPessoa'])) {
	if ($_POST['pessoa'] == "contato") {
		$contato = new Contato();
		$contato->codPessoa = $pessoas->codPessoa;
		$contato->codColaborador = $_POST['codColaborador'];
		$contato->codContatoTipo = 1;
		$contato->codContatoStatus = (PessoasPermissao::verificaPermissao($u->codPessoa, 4)) ? 1 : 4;
		$contato->dados = NULL;
		$contato->store();
	}
}

	$enderecos->codPessoa = $pessoas->codPessoa;
	$enderecos->cep = $_POST['cep'];
	$enderecos->numero = $_POST['numero'];
	$enderecos->complemento = $_POST['complemento'];
	$enderecos->store();

	foreach ($_POST['telefone'] as $numeroTelefone) {
		if ($numeroTelefone != "") {

			if ($_POST['codPessoa'] != NULL || !empty($_POST['codPessoa'])) {
				$telefones = new Telefone($_POST['codTelefone'][$numeroCampo]);
			} else {
				$telefones = new Telefone();
			}

			$telefones->codPessoa = $pessoas->codPessoa;
			$telefones->telefone = $_POST['telefone'][$numeroCampo];
			$telefones->ddi = $_POST['ddi'][$numeroCampo];
			$telefones->ddd = $_POST['ddd'][$numeroCampo];
			$telefones->tipo = $_POST['tipo'][$numeroCampo];
			$telefones->store();
		}
		$numeroCampo++;
	}

	header('Location: pessoa.php?codPessoa=' . $pessoas->codPessoa);
?>
