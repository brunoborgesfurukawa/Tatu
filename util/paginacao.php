<?php
function paginacao($paginaAtual, $totalPaginas, $busca, $parametros = null) {
	// parâmetro de busca
	$link = empty($busca) ? '' : "&amp;busca=$busca";

	if (!empty($parametros)) {
		foreach ($parametros as $p => $valor) {
			$link .= '&amp;' . $p . '=' . $valor;
		}
	}

	$link = $_SERVER['PHP_SELF'] . '?' . $link . '&amp;pagina=';

	$paginaAnterior = $paginaAtual - 1;
	$proximaPagina = $paginaAtual + 1;

	echo "
		<nav>
			<div align='middle'>
				<ul class='pagination'>";
	// Exibição do botão para a página 1 e 'anterior'.
	if ($paginaAtual == 1) {
		echo "
			<li class='disabled'><a href='#'>←</a></li>
			<li class='active'><a href='#'>1</a><li>";
	} else {
		echo "
			<li><a href='$link$paginaAnterior'>←</a></li>
			<li><a href='$link" . 1 . "'>1</a><li>";
	}

	// Exibição dos botões para as demais páginas.
	for ($i = 2; $i < $totalPaginas; $i++) {
		if ($i >= ($paginaAtual - 5) && $i <= ($paginaAtual + 5)) {
			if ($paginaAtual == $i) {
				echo "<li class='active'><a href='#'>$paginaAtual</a><li>";
			} else {
				echo "
					<li><a href='$link$i'>$i</a></li>";
			}
		} else if ($i == ($paginaAtual - 6) || $i == ($paginaAtual + 6)) {
			echo "<li class='disabled'><a href='#'>...</a><li>";
		}
	}

	// Exibição do botão da última página e de 'próxima página'.
	if ($paginaAtual == $totalPaginas) {
		if ($totalPaginas > 1) {
			echo "<li class='active'><a href='#'>$totalPaginas</a><li>";
		}

		echo "<li class='disabled'><a href='#'>→</a></li>";
	} else {
		echo "
			<li><a href='$link$totalPaginas'>$totalPaginas</a><li>
			<li><a href='$link$proximaPagina'>→</a></li>";
	}
	echo "
		</ul>
	</div>
</nav>";
}
