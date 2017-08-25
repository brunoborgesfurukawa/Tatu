<?php
	session_start("TATU");
	$emailErro = $_SESSION["emailErro"];
	$provider = isset($_SESSION["emailErro"]) ? $_SESSION["provider"] : "";
	session_destroy();
	$_SESSION["emailErro"] = $emailErro;
	$_SESSION["provider"] = $provider;
	header("Location: /tatu/index.php");
?>