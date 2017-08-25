<?php
$titulo = "Grava Centro";
require '../autoload.php';

	$centros = new Centro();
	$centros->codCentro = $_POST['codCentro'];
	$centros->codGestor = $_POST['codGestor'];
	$centros->nome = $_POST['nome'];
	$centros->local = $_POST['local'];
	$centros->store();

	header('Location: centro.php?codCentro='. $centros->codCentro);
?>
