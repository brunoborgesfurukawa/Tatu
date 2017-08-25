<?php
require __DIR__ . '/../autoload.php';

$query = $_REQUEST["query"];
$pessoa = $_REQUEST["codPessoa"];
$alerta = "";
$emails = Pessoa::getEmails();

if ($query !== "") {

	$existente = false;
	foreach ($emails as $email) {
		if ($email->email == $query && !($email->codPessoa == $pessoa)) {
			$existente = true;
			break;
		}
	}

	if ($existente) {
		$alerta = "<font class='editar' color='red'>E-mail já cadastrado.</font>";
		echo "true";
	} else {
		$alerta = "<font class='editar' color='green'>E-mail disponível.</font>";
		echo "false";
	}
}

echo "@" . $alerta;
?>
