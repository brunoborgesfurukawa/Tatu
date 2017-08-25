<?php
$titulo = "Grava Grupos";

require '../autoload.php';

	$grupos = new Grupo();
	$grupos->codGrupo = $_POST['codGrupo'];
	$grupos->nome = $_POST['nome'];
	$grupos->codCentro = $_POST['codCentro'];
	$grupos->codGerente = $_POST['codPessoa'];
	$grupos->store();

	header('Location: grupo.php?codGrupo='. $grupos->codGrupo);
?>
