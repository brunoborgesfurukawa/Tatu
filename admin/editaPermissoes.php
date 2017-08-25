<?php
$titulo = 'Permissões';
require '../cabecalho.php';

// Verifica se a pessoa tem permissão para visualizar a página.
if (!PessoasPermissao::verificaPermissao($u->codPessoa, 14)) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

$codPessoa = $_GET["codPessoa"];
$pessoa = new Pessoa($codPessoa);
$permissoes = Permissao::getTotal();
$pessoaPermissoes = PessoasPermissao::getPermissoesPessoa($codPessoa);
$pessoaUsuario = new Usuario($_GET["codPessoa"]);

?>

<center>
<form name="gravaPermissoes" method="post" action="gravaPermissoes.php">
	<input type="hidden" value="<?= $codPessoa ?>" name="codPessoa">

	<legend style="width: 55%;"><?= $pessoa->nome ?></legend>

	<table class="table" style="width: 55%;">
		<tr>
		<td width="50%" style="border-top: 0px;">
			<center>Permissões</center>

			<?php
			foreach ($permissoes as $permissao) {
				$contemPermissao = "";
				foreach ($pessoaPermissoes as $pessoaPermissao) {
					if ($pessoaPermissao == $permissao->codPermissao) {
						$contemPermissao = "checked";
						break;
					}
				}
			?>
			<input type="checkbox" value="<?= $permissao->codPermissao ?>" name="permissao[<?= $permissao->codPermissao ?>]" id="permissao[<?= $permissao->codPermissao ?>]" <?= $contemPermissao ?> /></input>
			<span><?= $permissao->codPermissao ?> - <?= $permissao->descricao ?></span><br />
			<?php } ?>

		</td>

		<td class="editar table-bordered" style="border-right: 0px; border-bottom: 0px; border-top: 0px;">
			<center>
				Status<br /><br />
				<table class="">
					<tr>
						<td>Gestor de centro</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Gestor de centro") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>

					<tr>
						<td>Gerente de grupo</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Gerente de grupo") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>

					<tr>
						<td>Gestor de patronato</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Gestor de patronato") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>

					<tr>
						<td>Colaborador</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Colaborador") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>

					<tr>
						<td>Contato</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Contato") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>

					<tr>
						<td>Contribuinte</td>
						<td>
							<?php
								$glyphicon = "glyphicon glyphicon-remove";
								foreach ($pessoaUsuario->tipoPessoa as $tipo) {
									if ($tipo == "Contribuinte") {
										$glyphicon = "glyphicon glyphicon-ok";
									}
								}
							?>
							<span class="<?= $glyphicon ?>"></span>
						</td>
					</tr>
				</table>

				<?php
					echo "<br />";
					echo "Contatos: $pessoaUsuario->contatos <br /> Centros: $pessoaUsuario->centros <br /> Grupos: $pessoaUsuario->grupos <br /> Patronatos: $pessoaUsuario->patronatos";
				?>
			</center>
		</td>
		</tr>
	</table>
	<button class="btn btn-default" type="submit">Salvar</button>
</form>
</center>
