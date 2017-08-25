<?php
session_start("TATU");

	if (isset($_SESSION["codPessoa"])) {
		$u = new Usuario($_SESSION["codPessoa"], $_SESSION["provider"]);
	} else {
		header("Location: /tatu/index.php?return=" . urlencode($_SERVER['REQUEST_URI']));
	}
?>
