<?php
$titulo = 'Grava Contribuições';
require '../cabecalho.php';
require '../util/dataHora.php';
require '../util/formularios.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 12)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}
$contribuicao = new Contribuicao($_GET['codContribuicao']);
?>
<script>
$(document).ready(function() {
	function initSelectionContribuinte(element, callback) {
		var id = $(element).val();
		if (id !== '') {
			$.ajax('/tatu/contribuicoes/select2.php',{
	 			data: {
	 				id: id
	 			}, dataType: 'json',
			}).done(function(data) {
				callback(data);
			});
		}
	}

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
		},
		initSelection: initSelectionContribuinte,
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
			<span>Editar Contribuição</span>
			<a href="contribuicoes.php"><button type="button" class="btn btn-danger btn-xs pull-right" ><span class="glyphicon glyphicon-remove"></span> Cancelar</button></a>
			<button type="submit" id="gravarDados" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
			</h3>
		</div>

	<div class="panel-body">

		<input type="hidden" name="codContribuicao" value="<?= $contribuicao->codContribuicao ?>" />

		<label for="forma">Forma:</label><br />
		<select id="forma" class="form-control" style="width: auto;" name="forma" required >
			<option value="1" <?php if ($contribuicao->codForma==1) { echo "selected='selected'"; } ?>>PONTUAL</option>
			<option value="2" <?php if ($contribuicao->codForma==1) { echo "selected='selected'"; } ?>>MENSAL</option>
		</select>

		<label for="codContribuinte">Contribuinte:</label><br />
		<input type="text" id="codContribuinte" style="width: auto;" name="codContribuinte" placeholder="Procure o contribuinte..." value="<?= $contribuicao->codPessoa ?>" required/>
		<br />

		<label for="status">Status:</label><br />
		<select id="status" class="form-control" style="width: auto;" name="status" required >
			<?php
				$status = ContribuicaoStatus::getStatus();
				foreach ($status as $Status ) { ?>
					<option id="tipo" name="tipo" value="<?=$Status->codStatus?>" <?php if ($contribuicao->codStatus==$Status->codStatus) { echo "selected='selected'"; } ?>> <?= $Status->descricao?></option>
			<?php } ?>
		</select>

		<label for="tipo">Pagamento:</label><br />
		<select id="tipo" class="form-control" style="width: auto;" name="tipo" required >
			<?php
				$status = ContribuicaoTipo::getTipo();
				foreach ($status as $Status ) { ?>
					<option id="tipo" name="tipo" value="<?=$Status->codTipo?>" <?php if ($contribuicao->codTipo==$Status->codTipo) { echo "selected='selected'"; } ?>> <?= $Status->descricao?></option>
			<?php } ?>
		</select>

		<label for="dataInicio">Data Início:</label><br />
		<input type="text" id="dataInicio" name="dataInicio" maxlength="10" size="8" class="campo-data form-control" style="width: auto;" value="<?= data_pt($contribuicao->dataInicio) ?>" required />
		
		<label for='dataFim'>Data de Vencimento:</label><br />
		<input type='text' id='dataFim'name='dataFim' maxlength='10' size='8' class='campo-data form-control' style='width: auto;' value="<?= data_pt($contribuicao->dataFim) ?>" required />

		<label for="tipoMoeda">Moeda:</label><br />
		<select id="tipoMoeda" class="form-control" style="width: auto;" name="tipoMoeda" required>
			<option value="1" <?php if ($contribuicao->codMoeda==1) { echo "selected='selected'"; } ?>>REAL</option>
			<option value="2" <?php if ($contribuicao->codMoeda==2) { echo "selected='selected'"; } ?>>SALÁRIO MINÍMO</option>
		</select>

		<label for="valor">Valor:</label><br />
		<input type='number' class='form-control' id='valor' name='valor' aria-label='Amount' OnKeyPress='formatar('###,###', this)' value="<?= $contribuicao->valor ?>" required/>
	</div>
	</div>
</form>
