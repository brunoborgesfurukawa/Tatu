<?php
$titulo = 'Relatorios';
require '../cabecalho.php';
require '../util/dataHora.php';

$dados = Centro::getDados();
$numeroCentros = 0;
$numeroGrupos = 0;
$numeroColaboradores = 0;
$numeroContatos = 0;
$numeroContribuintes = 0;
$contribuicaoTotal = 0;
$totalContribuintes = 0;
$dataInicio = date("Y-m-d");
if (isset($_GET['dataFinal'])) {
	$dataFim = date("Y")."-".$_GET['dataFinal']."-".date("d");
	echo $dataFim;
}
else{
	$dataFim = date("Y-m-d");
	echo $dataFim;
}

if(empty($_GET['codCentro']) && empty($_GET['codGrupo']) && empty($_GET['codColaborador'])) {
	$todasContribuicoes = Contribuicao::getContribuicoes();
	$campo = "Centros";
	$contribuintes = Centro::getCentros();
	$informacoes = Informacoes::getInformacoes("total");
}

if(!empty($_GET['codCentro']) && empty($_GET['codGrupo']) && empty($_GET['codColaborador'])) {
	$todasContribuicoes = Contribuicao::getContribuicoes($_GET['codCentro'],"centro");
	$campo = "Grupos";
	$contribuintes = Centro::getGrupos($_GET['codCentro']);
	$informacoes = Informacoes::getInformacoes("centro",$_GET['codCentro']);
}

if(!empty($_GET['codGrupo']) && !empty($_GET['codCentro']) && empty($_GET['codColaborador'])) {
	$todasContribuicoes = Contribuicao::getContribuicoes($_GET['codGrupo'],"grupo");
	$campo = "Colaboradores";
	$contribuintes = GrupoMembro::getMembro($_GET['codGrupo'],1);
	$informacoes = Informacoes::getInformacoes("grupo",$_GET['codGrupo']);
}

if(!empty($_GET['codColaborador']) && !empty($_GET['codGrupo']) && !empty($_GET['codCentro'])) {
	$todasContribuicoes = Contribuicao::getContribuicoes($_GET['codColaborador'],"colaborador");
	$campo = "Contribuintes";
	$contribuintes = Contato::getContatos($_GET['codColaborador'],2);
	$informacoes = Informacoes::getInformacoes("colaborador",$_GET['codColaborador']);
}

foreach ($todasContribuicoes as $contribuinte) {
	$contribuicoes[$contribuinte->nome] = 0;
}
foreach ($todasContribuicoes as $contribuicao) {
	if ($contribuicao->codMoeda == 2) {
		$valor = ($contribuicao->valor * $contribuicao->base) / 100;
	}
	else {
		$valor = $contribuicao->valor;
	}
	if ($contribuicao->dataInicio != $contribuicao->dataFim){
		if ($contribuicao->dataFim > $dataFim) {
			$tempoContribuicao = diferencaMeses($contribuicao->dataInicio,$dataFim);
			$valor = $valor*$tempoContribuicao;
		}
		else{
			$tempoContribuicao = diferencaMeses($contribuicao->dataInicio,$contribuicao->dataFim);
			$valor = $valor*$tempoContribuicao;
		}
	}
	$contribuicoes[$contribuicao->nome] += $valor;
	$contribuicaoTotal += $valor;
}
?>

<script>
	$(document).ready(function() {
		function organizarSelects() {
			/* Atualiza a quantidade de options da data inicial e verifica se a mesma
			tem um valor selecionado maior do que o da dataFinal, caso tenha, sua
			opção será modificado para uma que seja válida. */

			dFinal = $("#dataFinal").val() != "" ? parseInt($("#dataFinal").val()) : 13;
			dInicio = parseInt($("#dataInicial").val());

			for (i = 1; i <= 12; i++) {
					$("#dataInicial").find("option[value=" + i + "]").show();
			}

			for (i = 12; i > dFinal; i--) {
					$("#dataInicial").find("option[value=" + i + "]").hide();
			}

			if (dInicio > dFinal) {
				$('#dataInicial').val(dFinal);
			}
		}
		// Atualiza o select assim que a página é carregada.
		organizarSelects();

		$("#dataFinal").change(function() {
			// Atualiza o select sempre que a data final for modificada.
			organizarSelects();
		});
	});
</script>

<div  style="width: 20%; float: right">
	<form>
		<label for="dataInicial">De </label>
		<select name="dataInicial" id="dataInicial">
			<option></option>
			<?php for ($i = 1; $i <= 12; $i++) { ?>
						<option value="<?= adicionarZero($i) ?>"><?= mesNome($i) ?></option>
			<?php } ?>
		</select>
		<label for="dataFinal"> a </label>
		<select name="dataFinal" id="dataFinal" onchange="form.submit()">
			<option></option>
			<?php for ($i = 1; $i <= 12; $i++) { ?>
						<option value="<?= adicionarZero($i) ?>"><?= mesNome($i) ?></option>
			<?php } ?>
		</select>
	</form>
	<form method="GET" action="relatorios.php" >
		<input type="hidden" name="guia" value="<?= isset($_GET['guia']) ? $_GET['guia'] : '' ?>" />
		<select name="codCentro" id="codCentro" class="form-control" onchange="form.submit()" style="width: 99%;">
			<option></option>
			<?php $centros = Centro::getCentros();
				foreach ($centros as $centro) { ?>
					<option value="<?= $centro->codCentro ?>" <?php if ($centro->codCentro==@$_GET['codCentro']) { ?>selected="selected"<?php } ?>><?= $centro->nome ?></option>
			<?php } ?>
		</select>
	</form>
	<form method="GET" action="relatorios.php" >
		<input type="hidden" name="guia" value="<?= isset($_GET['guia']) ? $_GET['guia'] : '' ?>" />
		<input type="hidden" name="codCentro" value="<?= isset($_GET['codCentro']) ? $_GET['codCentro'] : '' ?>" />
		<select name="codGrupo" id="codGrupo" class="form-control" onchange="form.submit()" style="width: 99%;">
			<option></option>
			<?php
			isset($_GET['codCentro']) ? $gruposCentro = Centro::getGrupos($_GET['codCentro']) : $gruposCentro = array();
			 	foreach ($gruposCentro as $grupoCentro) { ?>
					<option value="<?= $grupoCentro->codGrupo ?>" <?php if ($grupoCentro->codGrupo==@$_GET['codGrupo']) { ?>selected="selected"<?php } ?>><?= $grupoCentro->nome ?></option>
			<?php } ?>
		</select>
	</form>
	<form method="GET" action="relatorios.php" >
		<input type="hidden" name="guia" value="<?= isset($_GET['guia']) ? $_GET['guia'] : '' ?>" />
		<input type="hidden" name="codCentro" value="<?= isset($_GET['codCentro']) ? $_GET['codCentro'] : '' ?>" />
		<input type="hidden" name="codGrupo" value="<?= isset($_GET['codGrupo']) ? $_GET['codGrupo'] : '' ?>" />
		<select name="codColaborador" id="codColaborador" class="form-control" onchange="form.submit()" style="width: 99%;">
			<option></option>
			<?php isset($_GET['codGrupo']) ? $colaboradores = GrupoMembro::getMembro($_GET['codGrupo'],1) : $colaboradores = array();
				foreach ($colaboradores as $colaborador) { ?>
					<option value="<?= $colaborador->codPessoa ?>" <?php if ($colaborador->codPessoa==@$_GET['codColaborador']) { ?>selected="selected"<?php } ?>><?= $colaborador->nome ?></option>
			<?php } ?>
		</select>
	</form>
	<p></p>
	<div class="panel panel-default">
		<div class="panel-heading" style="text-align:center;font-weight:bold;">Informações</div>
		<ul class="list-group">
			<?php 
			if(!empty($informacoes->Centros)) { ?>
				<li class="list-group-item">
					Centros
					<span class="badge"><?= $informacoes->Centros ?></span>
				</li>
			<?php } 
			if(!empty($informacoes->Grupos)) { ?>
				<li class="list-group-item">
					Grupos
					<span class="badge"><?= $informacoes->Grupos ?></span>
				</li>
			<?php } 
			if(!empty($informacoes->Colaboradores)) { ?>
				<li class="list-group-item">
					Colaboradores
					<span class="badge"><?= $informacoes->Colaboradores ?></span>
				</li>
			<?php } 
			if(!empty($informacoes->Contribuintes)) { ?>
				<li class="list-group-item">
					Contribuintes
					<span class="badge"><?= $informacoes->Contribuintes ?></span>
				</li>
			<?php } 
			if(!empty($informacoes->Contatos)) { ?>
				<li class="list-group-item">
					Contatos
					<span class="badge"><?= $informacoes->Contatos ?></span>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>

<div style="width: 70%; float: left; border: 1px solid; border-color: #eee;">
	<?php
	$variaveis = null;
	if (!empty($_GET["codCentro"])) {
		$variaveis = $variaveis."&codCentro=".$_GET["codCentro"];
		if (!empty($_GET["codGrupo"])) {
		$variaveis = $variaveis."&codGrupo=".$_GET["codGrupo"];
			if (!empty($_GET["codColaborador"])) {
			$variaveis = $variaveis."&codColaborador=".$_GET["codColaborador"];
			}
		}
	}
	?>
			<ul class="nav nav-tabs">
  				<li role="presentation" <?php if (@$_GET['guia']=="graficoContribuicao") { ?>class="active"<?php } ?> ><a href="?guia=graficoContribuicao<?= $variaveis ?>">Grafico De Contribuição</a></li>
 				<li role="presentation" <?php if (@$_GET['guia']=="tabelaContribuicao") { ?>class="active"<?php } ?>><a href="?guia=tabelaContribuicao<?= $variaveis ?>" >Tabela De Contribuição</a></li>
  				<li role="presentation" <?php if (@$_GET['guia']=="historico") { ?>class="active"<?php } ?>><a href="?guia=historico<?= $variaveis ?>">Historico</a></li>
			</ul>
	
	<?php
		if(@$_GET["guia"] == "tabelaContribuicao") {
			include 'tabelaContribuicao.php';
		}
		if(@$_GET["guia"] == "graficoContribuicao") {
			include 'graficoContribuicao.php';
		}
	?>
</div>
