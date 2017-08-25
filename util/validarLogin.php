<meta charset="utf-8" />
<link rel="stylesheet" href="/tatu/css/bootstrap.css" />
<link rel="stylesheet" href="/tatu/css/style.css" />
<script src="/tatu/js/jquery.js"></script>
<script src="/tatu/js/bootstrap.js"></script>
<script src="/tatu/js/loginFacebook.js"></script>
<?php
require __DIR__ . '/../autoload.php';

$emailUsuario = isset($_POST["getEmail"]) ? $_POST["getEmail"] : "";
$nomeUsuario = isset($_POST["name"]) ? $_POST["name"] : "";
$tokenUsuario = isset($_POST["token"]) ? $_POST["token"] : "";
$provedorUsuario = isset($_POST["provider"]) ? $_POST["provider"] : "";

$return = empty($_SESSION['return']) ? '../menu.php' : urldecode($_SESSION['return']);

if (isset($tokenUsuario)) {

	$emails = Pessoa::getEmails();

	$existente = false;
	foreach ($emails as $email) {
		if ($email->email == $emailUsuario) {
			$existente = true;
			$_SESSION["codPessoa"] = $email->codPessoa;
			break;
		}
	}

	if ($existente) {
	 	$_SESSION["Logado"] = 1;
	 	$_SESSION["email_usuario"] = $emailUsuario;
	 	$_SESSION["provider"] = $provedorUsuario;
	 	$_SESSION["emailErro"] = NULL;
		header("Location: " . $return);
	} else {

		// fiz esse if pois o gmail acusa 2 sessoes iniciadas,e o facebook nao funciona sem as mesmas sessoes iniciadas.
		if ($provedorUsuario == "facebook"){
			session_start("TATU");
			$_SESSION["emailErro"] = $emailUsuario;
			$_SESSION["provider"] = $provedorUsuario;
			echo "<div class='alert alert-warning' role='alert'><center>Email <a href=''>" .$emailUsuario. "</a> não está cadastrado em nosso sistema <a href='/tatu/util/logout.php'>Clique aqui para voltar </center></a></div>";
		} else {
			session_destroy();
			session_start("TATU");
			$_SESSION["emailErro"] = $emailUsuario;
			$_SESSION["provider"] = $provedorUsuario;
			echo "<div class='alert alert-warning' role='alert'><center>Email <a href=''>" .$emailUsuario. "</a> não está cadastrado em nosso sistema <a href='/tatu/util/logout.php'>Clique aqui para voltar </center></a></div>";
		}
	}
}

?>
