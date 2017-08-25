<?php
$titulo = 'Grava Contribuições';
require '../cabecalho.php';
require '../util/dataHora.php';
require '../util/formularios.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 12)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

?>
<script>
$(document).ready(function() {
	$("#forma").on("change", function() {
	var escolha = $(this).val();
	var campo;
	if(escolha == 2){
		campo = "\
			<label for='dataFim'>Data de Vencimento:</label><br />\
			<input type='text' id='dataFim'name='dataFim' maxlength='10' size='8' class='campo-data form-control' style='width: auto;' value='<?= date('t/m/Y') ?>'' required />";
		$('#campoDataFim').html(campo);
	}
	else {
		$('#campoDataFim').html("");
	}
	});
	
	$("#codContribuinte").select2({
		minimumInputLength: 3,
		quietMillis: 100,
		ajax: {
			url: '/tatu/contribuicoes/select2.php',
			dataType: 'json',
			data: function(term, page) {
				return {
					query: term,
					page_limit: 10
				};
			},
			results: function(data, page) {
				return data;
			}
		}
	});
	// Aplica a validação de data.
	$("#dataInicio").blur(function() {
		return validaData(this, 0, 120);
	});
});
</script>

<body>
<form name="pessoas" method="post" action="gravaContribuicao.php">
	<div class="panel panel-default" style="width: 55%; float: left">

		<div class="panel-heading">
			<h3 class="panel-title">
			<span>Cadastrar Contribuição</span>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Cadastrar</button>
			<button type="reset" id="limparCampo" class="btn btn-warning btn-xs pull-right" onClick="history.go(0)"><span class="glyphicon glyphicon-repeat"></span> Limpar</button>
			</h3>
		</div>

	<div class="panel-body">

		<label for="forma">Forma:</label><br />
		<select id="forma" class="form-control" style="width: auto;"name="forma" required >
			<option value="1" >PONTUAL</option>
			<option value="2" >MENSAL</option>
		</select>

		<label for="codContribuinte">Contribuinte:</label><br />
		<input type="text" id="codContribuinte" style="width: auto;" name="codContribuinte" placeholder="Procure o contribuinte..." required/>
		<br />

		<label for="tipo">Pagamento:</label><br />
		<select id="tipo" class="form-control" style="width: auto;" name="tipo" required >
			<?php
				$status = ContribuicaoTipo::getTipo();
				foreach ($status as $Status ) { ?>
					<option id="tipo" name="tipo" value="<?=$Status->codTipo?>" > <?= $Status->descricao?></option>
			<?php } ?>
		</select>

		<label for="dataInicio">Data Início:</label><br />
		<input type="text" id="dataInicio" name="dataInicio" maxlength="10" size="8" class="campo-data form-control" style="width: auto;" value="<?= date('d/m/Y') ?>" required />
		<div id="campoDataFim"></div>

		<label for="tipoMoeda">Moeda:</label><br />
		<select id="tipoMoeda" class="form-control" style="width: auto;" name="tipoMoeda" required>
			<option value="1" >REAL</option>
			<option value="2" >SALÁRIO MINÍMO</option>
		</select>

		<label for="valor">Valor:</label><br />
		<input type='number' class='form-control' id='valor' name='valor' aria-label='Amount' OnKeyPress='formatar('###,###', this)' required/>
	</div>
	</div>
</form>
