<?php
require __DIR__ . '/autoload.php';
require __DIR__ . '/util/alertas.php';
// cria o cabeçalho, define o título e usa o 'bootstrap.css' como folha de estilos
// e inclui o arquivo funcoes.js e os arquivos de scripts
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Sistema Tatu - <?= $titulo ?></title>
<link rel="shortcut icon" type="image/x-icon" href="/tatu/icones/favicon.ico" />
<link rel="stylesheet" href="/tatu/css/bootstrap.css" />
<link rel="stylesheet" href="/tatu/css/style.css" />
<link rel="stylesheet" href="/tatu/select2/select2.css" />
<script src="/tatu/js/jquery.js"></script>
<script src="/tatu/js/cep.js"></script>
<script src="/tatu/js/bootstrap.js"></script>
<script src="/tatu/js/funcoes.js"></script>
<script src="/tatu/select2/select2.js"></script>
<script src="/tatu/select2/select2_locale_pt-BR.js"></script>
<body>

<?php
require 'util/guia.php';
// Define se o perfil ativo é de teste. Se for aparecerá a mensagem:
if ($perfil === 'test') {
		echo '<div id="alertaBD" class="alert alert-warning" data-dismiss="alert" align="center">Sistema de desenvolvimento. Usando banco de dados não oficial! </div>';
	}
?>

<?php //Pequeno easter egg. Ao passar o mouse 30 vezes no alert, um "Sanic" surgirá com sua música. ?>
<div id="sanicArea">
	<img class="input-group" style="margin-left: -150px;" height="100px" width="100px" id="sanic" src="/tatu/icones/sanic.png" />
</div>

<script>
var sanicIsComing = 0;
$(document).ready(function() {
	$('#sanic').hide();
	$('#alertaBD').mouseenter(function() {
		sanicIsComing++;
		aparecerSanic();
	});
});

function aparecerSanic() {
	if (sanicIsComing == 30) {
		$('#sanic').show();

		var audio = new Audio('/tatu/icones/sanic.mp3');
		audio.play();
		$('#sanic').animate({height: '100px'}, "fast");
		$('#sanic').animate({left: '125%'}, "slow");
		$('#sanic').animate({height: '0px'}, "slow");
		$('#sanic').animate({left: '-150px'}, "fast");
		sanicIsComing = 0;
	}
}
</script>
