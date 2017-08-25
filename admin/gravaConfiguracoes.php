<?php
$titulo = "Grava Configurações";
require '../cabecalho.php';

$salarioMinimo = new Moeda(2);

$salarioMinimo->valor = $_POST["salarioMinimo"];
$salarioMinimo->store();

echo "<center>";
alertaSucesso('Modificações realizadas com sucesso, voltar para a <a href="javascript:history.back()">página anterior</a>.');
echo "</center>";
?>
