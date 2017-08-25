<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="/tatu/css/bootstrap.css" />
<link rel="stylesheet" href="/tatu/css/style.css" />
<script src="/tatu/js/jquery.js"></script>
<script src="/tatu/js/bootstrap.js"></script>
<body>

<?php
$titulo = 'Login';
?>

<div class="panel" style="margin-top: 10% ; margin-left: 25%; margin-right:25%;box-shadow: 5px 5px 10px #DCDCDC;-webkit-box-shadow: 5px 5px 10px #0076a3;-moz-box-shadow: 5px 5px 10px #0076a3;">
<form class="" action="menu.php" method="post" >
      <div class="page-header">
        <h1> &nbsp; Sistema Tatu <small>Login</small></h1>
      </div>
    <div class="row" style="text-align: center; font-size:18px;">
      <label for="email" class="col-xs-3">E-mail:</label>
      <input type="text" class="form-control" id="email" name="email" style="width: 300px;">
      <br />
      <label for="senha" class="col-xs-3">Senha:</label>
      <input type="password" class="form-control" id="senha" name="senha" style="width: 300px;">
      <br />
      <button type="submit" class="btn btn-default btn-block" style="margin-left:25%; width: 30%;">Entrar</button>
    </div>
    <br />
    <div class="panel-body" style="text-align:center;">
      <a href="<?= $fb_url ?>" target="_top"><img src="/tatu/icones/facebook.gif" /></a>
      <a href="openid.php?provider=google" target="_top"><img src="/tatu/icones/google.gif" /></a>
      <a href="<?= $wl_url ?>" target="_top"><img src="/tatu/icones/windowsLive.gif" /></a>
      <a href="openid.php?provider=yahoo" target="_top"><img src="/tatu/icones/yahoo.gif" /></a>
    </div>  					
</form>
</div>
	