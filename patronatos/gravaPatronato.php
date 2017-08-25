<?php
$titulo = "Grava Patronato";

require '../autoload.php';

	$patronatos = new Patronato();
	$patronatos->codPatronato = $_POST['codPatronato'];
	$patronatos->nome = $_POST['nome'];	
	$patronatos->codGestor = $_POST['codGestor'];
	$patronatos->store();

	header('Location: patronato.php?codPatronato='. $patronatos->codPatronato);
?>
