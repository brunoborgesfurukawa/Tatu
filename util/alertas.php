<?php

/**
*	erroFatal - Termina a execução do código e exibe uma mensagem de erro.
*
*	@param string $mensagem - Texto a ser exibido no alerta;
*	@param string $class - Define a classe html do alerta, seu valor padrão utiliza
*	o alert-danger do bootstrap.
*/
function erroFatal($mensagem, $classe = 'alert-danger') {
	echo '<div class="alert ' . $classe . ' fade in">' . $mensagem . '</div>';
	exit();
}

function alertaSucesso($mensagem, $classe = 'alert-success') {
	echo '<div class="alert ' . $classe . ' fade in">' . $mensagem . '</div>';
}
?>
