<?php
$titulo = 'Exibir informações';
$ativo[0] = "active";
require '../cabecalho.php';
require '../util/dataHora.php';
require '../util/formularios.php';
$numeroCampo = 1;

// Une todos os colaboradores e contatos que a pessoa pode visualizar.
	$listaPessoas = array_merge($u->getTodosContatos(), $u->getTodosColaboradores());

// Verifica se a pessoa tem permissão para visualizar a página.
if ( !PessoasPermissao::verificaPermissao($u->codPessoa, 2) && $u->codPessoa != $_GET['codPessoa'] && !PessoasPermissao::verificaContato($_GET['codPessoa'], $listaPessoas) ) {
	erroFatal('Vocë não tem permissões para visualizar esta página, voltar para a <a href="javascript:history.back()">página anterior</a>.');
}

$codPessoa = $_GET['codPessoa'];

$registros = Registro::getRegistro($_GET['codPessoa']);
// Verifica se foi selecionado uma pessoa.
if (isset($_GET['codPessoa'])) {
	// Instancia um objeto para a pessoa selecionada e resgata seus dados.
	$pessoa = new Pessoa($_GET['codPessoa']);
	$telefones = $pessoa->telefone;
	$enderecos = $pessoa->endereco;
	$contatos = $pessoa->contato;

	$nome = $pessoa->nome;
	$dataNascimento = $pessoa->dataNascimento;
	$email = $pessoa->email;
	$colaborador = $pessoa->colaborador;

	foreach ($enderecos as $endereco) {
		$codEndereco = $endereco->codEndereco;
		$cep = $endereco->cep;
		$numero = $endereco->numero;
		$complemento = $endereco->complemento;
	}

	$indice = 1;

	// Imprime todos os telefones cadastrados daquela pessoa.
	foreach ($telefones as $telefone) {
		$telefonesCadastrados[$indice]['ddi'] = ($telefone->ddi == 0) ? "" : "+" . $telefone->ddi . " - ";
		$telefonesCadastrados[$indice]['ddd'] = ($telefone->ddd == 0) ? "" : "(" . $telefone->ddd . ") - ";
		$telefonesCadastrados[$indice]['numero'] = ($telefone->telefone == 0) ? "" : $telefone->telefone . " - ";
		$telefonesCadastrados[$indice]['tipo'] = $telefone->tipo;
		$codTelefone[$indice] = $telefone->codTelefone;
		$indice++;
	}

}
// Caso não haja nenhum codPessoa selecionado, ou o mesmo seja de alguém que não exista
// no banco de dados, será exibido um erro.
if (!isset($_GET['codPessoa']) || $pessoa->nome == NULL) { ?>
	<center>
		<?php erroFatal('Esta pessoa não existe. Voltar para a <a href="javascript:history.back()">página anterior</a>.'); ?>
	</center>
<?php } ?>

<script>
var numeroCampo = <?= $numeroCampo ?>;
// Mantém o panel da direita com a mesma altura do panel a esquerda.
function mudarAltura() {
	var altura = $('#corpoDados').height() + 30;
	$('#corpoHistorico').css('height', altura);
}

function removerTele(codTelefone) {
	$("input[name=codRemover]").val(codTelefone);
	document.getElementById("removerTelefone").submit();
}

// Resgata o endereço da pessoa para exibir fora dos campos de edição.
function mostraEndereco() {
	document.getElementById('valorLogradouro').innerHTML = document.getElementById('logradouro').value;
	document.getElementById('valorBairro').innerHTML = document.getElementById('bairro').value;
	document.getElementById('valorCidade').innerHTML = document.getElementById('cidade').value;
	document.getElementById('valorUF').innerHTML = document.getElementById('uf').value;
	$('.carregado, .carregando').toggle();
	mudarAltura();
}

function novoTelefone() {
	var html = " \
		+<input type='text' class='soNumero' id='ddi["+ numeroCampo +"' name='ddi["+ numeroCampo +"]' maxlength='3' size='3' placeholder='DDI' /> \
		(<input type='text' class='soNumero' id='ddd["+ numeroCampo +"' name='ddd["+ numeroCampo +"]' maxlength='2' size='3' placeholder='DDD' />) \
		<input type='text' class='soNumero' id='telefone["+ numeroCampo +"]' name='telefone["+ numeroCampo +"]' maxlength='9' size='9' placeholder='Telefone' /> \
		<select id='tipo["+ numeroCampo +"]' name='tipo["+ numeroCampo +"]' > \
			<option value='Residencial'>Residencial</option> \
			<option value='Celular'>Celular</option> \
			<option value='Comercial'>Comercial</option> \
			<option value='Ramal'>Ramal</option>\
		</select> \
		<br />";
	$('#telefones').append(html);
	ativarValidacao();
	numeroCampo++;
}

function initSelectionColaborador(element, callback) {
	var id = $(element).val();
	// Só faz algo se houver valor no campo.
	if (id !== '') {
		$.ajax('/tatu/centros/select2.php',{
			data: {
				id: id
			}, dataType: 'json',
		}).done(function(data) {
			callback(data);
		});
	}
}

$(document).ready(function() {
	$('.editar, .botaoRemover, .carregado, #infoContatos').hide();
	mudarAltura();

	$('#editarPessoa, #cancelarEdicao').click(function() {
		$('.editar, .visualizar').toggle();
		$('#telefones').html("");
		document.getElementById('cep').value = '<?= $cep ?>';
		consultacep('<?= $cep ?>');
		mudarAltura();
		return false;
	});

	$("#infoColaborador").mouseout(function() {
		$("#infoContatos").hide();
	});

	$("#infoColaborador").mouseover(function() {
		$("#infoContatos").show();
	});

	$("#campoData").blur(function() {
		return validaData(this, 10, 120);
	});

	$('#codColaborador').select2({
	minimumInputLength: 3,
	quietMillis: 100,
	ajax: {
		url: '/tatu/pessoas/select2.php',
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
		initSelection: initSelectionColaborador,
	});
});
</script>

<form name="removerTelefone" id="removerTelefone" method="POST" action="removeTelefone.php">
	<input type="hidden" name="codRemover" id="codRemover" value="<?= $codTelefone[$numeroCampo] ?>">
</form>

<?php
// Verifica se a pessoa tem permissão para editar os dados.
if (PessoasPermissao::verificaPermissao($u->codPessoa, 3)) { ?>
<form name="pessoas" method="post" action="gravaPessoa.php">
<?php } ?>
	<div class="panel  <?= ($pessoa->aprovacao == 1) ? 'panel-danger' : 'panel-default' ?>" style="width: 54%; float: left;">
		<div class="panel-heading">
			<h3 class="panel-title" <?= ($pessoa->aprovacao == 1) ? 'title="Esta pessoa ainda não foi aprovada por um operador do sistema."' : '' ?>><spam class="visualizar"><?= $pessoa->nome ?></spam><spam class="editar">Editar</spam>
				<button type="button" id="cancelarEdicao" class="editar btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
				<button type="submit" id="gravarDados" class="editar btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span> Salvar</button>
				<?= !(PessoasPermissao::verificaPermissao($u->codPessoa, 3)) ? '' : '<button type="button" id="editarPessoa" class="visualizar btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-pencil"></span> Editar</button>' ?>
				<?= !(PessoasPermissao::verificaPermissao($u->codPessoa, 14)) ? '' : '<a href="/tatu/admin/editaPermissoes.php?codPessoa=' . $codPessoa . '"><button type="button" id="irPermissoes" class="visualizar btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-eye-open"></span> Permissões</button></a>' ?>
			</h3>
		</div>

		<div class="panel-body" class="corpoDados" id="corpoDados">
			<input type="hidden" name="codPessoa" value="<?= $_GET['codPessoa'] ? $_GET['codPessoa'] : NULL ?>" />
			<p>
				<span class="editar">
					<label class="editar" for="nome">Nome:</label>
					<input type="text" id="nome" size="30" name="nome" value="<?= $nome ?>" /><br />
				</span>

				<label for="dataNascimento">Data de nascimento:</label>
				<span class="visualizar"><?= data_pt($dataNascimento) ?></span>
				<input type="text" id="campoData" name="campoData" value="<?= data_pt($dataNascimento) ?>" class="campo-data input-small editar" maxlength="10" size="10" required />
				<br />

				<label for="email">E-mail:</label>
				<span class="visualizar"><?= $email ?></span>
				<input type="hidden" id="email" name="email" value="<?= $email ?>" />
				<input type="email" id="campoEmail" size="30" name="campoEmail" class="editar" onkeyup="verificaEmail(this.value, 'alerta', <?= $_GET['codPessoa'] ?>)" value="<?= $email ?>" <?= (PessoasPermissao::verificaPermissao($u->codPessoa, 3)) ? '' : 'disabled' ?>/>
				<span id="alerta"></span>

				<br />
			</p>

			<p>
				<label for="telefone">Telefones:</label><br />
					<span class="visualizar">
						<?php
							foreach ($telefonesCadastrados as $telefoneCadastrado) {
								foreach ($telefoneCadastrado as $valor) {
									echo "$valor";
								}
								echo "<br />";
							}
						?>
					</span>
					<span class="editar">
						<?php
							foreach ($telefonesCadastrados as $telefoneCadastrado) { ?>
								<input type="hidden" id="codTelefone[<?= $numeroCampo ?>]" name="codTelefone[<?= $numeroCampo ?>]" value="<?= $codTelefone[$numeroCampo] ?>" />
								+<input type="text" class='soNumero' id="ddi[<?= $numeroCampo ?>]" name="ddi[<?= $numeroCampo ?>]" maxlength="3" size="3" placeholder="DDI" value="<?= preg_replace("/[^0-9]/", "", $telefoneCadastrado['ddi']) ?>" />
								(<input type="text" class='soNumero' id="ddd[<?= $numeroCampo ?>]" name="ddd[<?= $numeroCampo ?>]" maxlength="2" size="3" placeholder="DDD" value="<?= preg_replace("/[^0-9]/", "", $telefoneCadastrado['ddd']) ?>" />)
								<input type="text" class='soNumero' id="telefone[<?= $numeroCampo ?>]" name="telefone[<?= $numeroCampo ?>]" maxlength="9" size="9" placeholder="Telefone" value="<?= preg_replace("/[^0-9]/", "", $telefoneCadastrado['numero']) ?>" />
								<select id="tipo[<?= $numeroCampo ?>]" name="tipo[<?= $numeroCampo ?>]">
									<option value="Residencial" <?= $telefoneCadastrado['tipo'] == "Residencial" ? "selected" : "" ?> >Residencial</option>
									<option value="Celular" <?= $telefoneCadastrado['tipo'] == "Celular" ? "selected" : "" ?> >Celular</option>
									<option value="Comercial" <?= $telefoneCadastrado['tipo'] == "Comercial" ? "selected" : "" ?> >Comercial</option>
									<option value="Ramal" <?= $telefoneCadastrado['tipo'] == "Ramal" ? "selected" : "" ?> >Ramal</option>
								</select>
								<input type="button" class="btn btn-danger btn-xs" onClick="removerTele(<?= $codTelefone[$numeroCampo] ?>)" value="Remover telefone" />
								<br />
						<?php
								$numeroCampo++;
								echo "<script>numeroCampo++;</script>";
							}
						?>
					</span>
					<div id="telefones" class="editar"></div>
					<br class='editar'/>
					<input type="button" name="adicionar" class="btn btn-xs btn-default editar" value="Adicionar telefone" onClick="novoTelefone(), mudarAltura()" />
			</p>

			<p>
				<label for="endereco">Endereço:</label><br />
				<span class="visualizar">
					<span class="carregando" style="color: #FF0066;">
						<span class="glyphicon glyphicon-time"></span> - Procurando cep...
					</span>

					<span class="carregado"><span id="valorLogradouro"></span> - <?= $numero ?><br />
					<span id="valorBairro"></span> - <span id="valorCidade"></span> - <span id="valorUF"></span><br />
					<?= $complemento ?></span>
				</span>
				<span class="editar"><?php $dadosEndereco = campoCep("cep", "cep", $cep);?>
					<br />
				    <input id="logradouro" name="logradouro" type="text" size="40" placeholder="Logradouro" value="" onChange="mostraEndereco()" disabled/>
					<input id="numero" class='soNumero' name="numero" type="text" size="6" placeholder="Número" value="<?= $numero ?>" maxlength="5" required/>
					<br />
				    <input id="bairro" name="bairro" type="text" placeholder="Bairro" value="" onChange="mostraEndereco()" disabled/>
				    <input id="cidade" name="cidade" type="text" placeholder="Cidade" value="" onChange="mostraEndereco()" disabled/>
				    <input id="uf" name="uf" type="text" size ="2" placeholder="UF" value="" onChange="mostraEndereco()" disabled/>
					<br />
					<textarea id="complemento" name="complemento" class="width-30" placeholder="Complemento"><?= $complemento ?></textarea>
					<input id="codEndereco" name="codEndereco" type="hidden" value="<?= $codEndereco ?>" />
				</span>
				<br />

				<?php
				if($colaborador == 1) { ?>
					<u class="visualizar" id="infoColaborador"><a style="color: black;" href="pessoas.php?pesquisa=&codColaborador=<?= $_GET['codPessoa'] ?>&tipoPessoa=contato" target='_blank'>Colaborador</a></u><span id="infoContatos" style="color: grey;"> - Clique para ver seus contatos.</span>
					<span class="editar">
						<input type="hidden" name="pessoa" id="colaborador" value="colaborador" />
					</span>
				<?php
				} else {
					$contato = new Contato($pessoa->codPessoa);
					foreach ($contato->colaborador as $colaborador) {
						$contatoColaborador = $colaborador;
					}
				?>
					<u class="visualizar">Contato de <a style="color: #333;" href="pessoa.php?codPessoa=<?= $contatoColaborador->codPessoa ?>"><?= $contatoColaborador->nome ?></a></u>
					<span class="editar">
						<input type="hidden" name="pessoa" id="contato" value="contato" />
						<br/>
						<label for='codColaborador' >Colaborador:</label>
						<input type='text' style='width: 350px' id='codColaborador' class='editar' name='codColaborador' value = "<?= $contatoColaborador->codPessoa ?>" placeholder='Digite o nome do colaborador...' required />
					</span>
				<?php } ?>
			</p>
		</div>
	</div>
<?php
if (PessoasPermissao::verificaPermissao($u->codPessoa, 3)) { ?>
</form>
<?php } ?>

<?php
if (!empty($registros)) { ?>
	<div class="panel" style="width: 44%; float: right;">
		<div class="panel-heading">
			<center><h3 class="panel-title">Histórico</h3></center>
		</div>
		<div id="corpoHistorico" name="corpoHistorico" class="panel-body" style="overflow: auto">
<?php
	foreach (array_reverse($registros) as $registro) {
		echo "<div class='well  well-sm mono'><em>" .
				data_pt($registro->dataHora) . "</em><br />
				$registro->descricao <br />
			  </div>";
	}
} ?>
	</div>
</div>
