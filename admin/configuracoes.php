<?php
$titulo = 'Configurações';
require '../cabecalho.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 1)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

$salarioMinimo = new Moeda(2);
$salarioMinimo = $salarioMinimo->valor
?>

<center>
<form name="gravaConfigurações" method="post" action="gravaConfiguracoes.php">
	<legend style="width: 55%;">Configurações</legend>

		<b>Valor do salário mínimo</b>
		<div class='input-group' style="width: 30%;">
			<span class='input-group-addon'>R$</span>
				<input type='number' class='form-control' id='salarioMinimo' name='salarioMinimo' value='<?= $salarioMinimo ?>' aria-label='Amount' OnKeyPress='formatar('###,###', this)' required/>
		</div><br />
	<button class="btn btn-default" type="submit">Salvar</button>
</form>
</center>
