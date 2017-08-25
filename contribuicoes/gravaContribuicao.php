<?php
$titulo = "Grava Contribuicao";

require '../autoload.php';
require '../util/dataHora.php';

	$contribuinte = new Contato($_POST['codContribuinte']);
	$codColaborador = $contribuinte->codColaborador;
	$grupo = GrupoMembro::getGrupo($codColaborador);
	$codCentro = Grupo::getGrupoMembro($grupo[0]->codGrupo);
	$valorMoeda = new Moeda($_POST['tipoMoeda']);

	if ($_POST['codContribuicao'] != NULL || !empty($_POST['codContribuicao'])) {
		$contribuicoes  = new Contribuicao($_POST['codContribuicao']);
		$contribuicoes->codStatus = $_POST['status'];
	}
	else{
		$contribuicoes  = new Contribuicao();
		$contribuicoes->codStatus = 1;
	}
	$contribuicoes->codPessoa = $_POST['codContribuinte'];
	$contribuicoes->codColaborador = $codColaborador;
	$contribuicoes->codCentro = $codCentro[0]->codCentro;
	$contribuicoes->codGrupo = $grupo[0]->codGrupo;
	$contribuicoes->dataInicio = data_sql($_POST['dataInicio']);
	$contribuicoes->dataFim = isset($_POST['dataFim']) ? data_sql($_POST['dataFim']) : ultimoDiaMes_sql(data_sql($_POST['dataInicio']));
	$contribuicoes->codForma = $_POST['forma'];
	$contribuicoes->codTipo = $_POST['tipo'];
	$contribuicoes->codMoeda = $_POST['tipoMoeda'];
	$contribuicoes->base = $valorMoeda->valor;
	$contribuicoes->valor = $_POST['valor'];

	$contribuicoes->store();

	$contato = new Contato($_POST['codContribuinte']);

	$contato->codContatoTipo = 2;
	$contato->codContatoStatus = 2;

	$contato->store();

	header('Location: /tatu/contribuicoes/contribuicoes.php');

?>
