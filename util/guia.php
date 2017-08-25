<script src="/tatu/js/loginFacebook.js"></script>
<script src="/tatu/js/loginGmail.js"></script>
<script src="/tatu/js/controleLogout.js"></script>
<script>
$(document).ready(function() {

 	$("#dropdown").dropdown();

});
</script>
<?php
for ($numero = 0; $numero <= 6; $numero++) {
  if (!isset($ativo[$numero])) $ativo[$numero] = ""; 
}
?>

<nav class="navbar navbar-default">
  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Tatu Navegacao</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/tatu/menu.php"><b>TATU</b></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

      	<?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 2, "Colaborador", $u->tipoPessoa) || PessoasPermissao::verificaPermissao($u->codPessoa, 4, "Colaborador", $u->tipoPessoa)) { ?>
          <li class="dropdown <?= $ativo[0] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Pessoas <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 2, "Colaborador", $u->tipoPessoa)) { ?>
            <li><a href="/tatu/pessoas/pessoas.php">Página de Pessoas</a></li>
            <?php }
            if (PessoasPermissao::verificaPermissao($u->codPessoa, 4, "Colaborador", $u->tipoPessoa)) { ?>
            <li class="divider"></li>
            <li><a href="/tatu/pessoas/cadastroPessoa.php">Cadastrar Pessoa</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <?php
        if (PessoasPermissao::verificaPermissao($u->codPessoa, 5) || PessoasPermissao::verificaPermissao($u->codPessoa, 8) || ($u->gruposLigados > 0)) { ?>
        <li class="dropdown <?= $ativo[1] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Grupos <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 5) || ($u->gruposLigados > 0)) { ?>
            	<li><a href="/tatu/grupos/grupos.php">Página de Grupos</a></li>
              <?php }
              if (PessoasPermissao::verificaPermissao($u->codPessoa, 8)) { ?>
				      <li class="divider"></li>
				      <li><a href="/tatu/grupos/cadastroGrupo.php">Cadastrar Grupo</a></li>
              <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <?php
        if (PessoasPermissao::verificaPermissao($u->codPessoa, 5) || PessoasPermissao::verificaPermissao($u->codPessoa, 8) || ($u->patronatosLigados > 0)) { ?>
        <li class="dropdown <?= $ativo[2] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Patronatos <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 5) || ($u->patronatosLigados > 0)) { ?>
        		<li><a href="/tatu/patronatos/patronatos.php">Página de Patronatos</a></li>
            <?php }
            if (PessoasPermissao::verificaPermissao($u->codPessoa, 8)) { ?>
				    <li class="divider"></li>
				    <li><a href="/tatu/patronatos/cadastroPatronato.php">Cadastrar Patronato</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <?php
        if (PessoasPermissao::verificaPermissao($u->codPessoa, 9) || PessoasPermissao::verificaPermissao($u->codPessoa, 1) || ($u->centrosLigados > 0)) { ?>
        <li class="dropdown <?= $ativo[3] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Centros <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 9) || ($u->centrosLigados > 0)) { ?>
            <li><a href="/tatu/centros/centros.php">Página de Centros</a></li>
            <?php }
            if (PessoasPermissao::verificaPermissao($u->codPessoa, 8)) { ?>
			     <li class="divider"></li>
			     <li><a href="/tatu/centros/cadastroCentro.php">Cadastrar Centro</a></li>
           <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 12) || PessoasPermissao::verificaPermissao($u->codPessoa, 13) || ($u->contribuicoesLigadas > 0)) { ?>
        <li class="dropdown <?= $ativo[4] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Contribuintes <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 13) || ($u->contribuicoesLigadas > 0)) { ?>
            <li><a href="/tatu/contribuicoes/contribuicoes.php">Página de Contribuições</a></li>
            <?php } ?>
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 12)) { ?>
            <li class="divider"></li>
            <li><a href="/tatu/contribuicoes/cadastroContribuicao.php">Cadastrar Contribuição</a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>

      <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 13)) { ?>
        <li class="dropdown <?= $ativo[5] ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Relatórios <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/tatu/relatorios/relatorios.php">Página de Relatórios</a></li>
          </ul>
        </li>
      <?php } ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-th"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="/tatu/pessoas/pessoa.php?codPessoa=<?= $_SESSION['codPessoa'] ?>"><span class="glyphicon glyphicon-user"></span> <?= $u->nome ?></a></li>
            <?php if (PessoasPermissao::verificaPermissao($u->codPessoa, 1)) { ?>
            <li><a href="/tatu/admin/configuracoes.php"><span class="glyphicon glyphicon-cog"></span> Configurações</a>
            <?php } ?>
            <li class="divider"></li>

            <li><a onclick="logout('<?= $_SESSION['provider'] ?>')" href="/tatu/util/logout.php"><span class="glyphicon glyphicon-off"></span>  Sair</a></li>
          </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>
