<html>
<head>
<title>Login Tatu</title>
</head>
<meta name="google-signin-client_id" content="122419430720-t1b7c444pfg1iu4dvabju6j3p7v7dca9.apps.googleusercontent.com">
<meta charset="utf-8" />
<link rel="stylesheet" href="/tatu/css/bootstrap.css" />
<link rel="stylesheet" href="/tatu/css/style.css" />
<script src="/tatu/js/jquery.js"></script>
<script src="/tatu/js/bootstrap.js"></script>
<script src="/tatu/js/loginFacebook.js"></script>
<script src="/tatu/js/loginGmail.js"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<body>
<div class="panel" style="margin-top: 10% ; margin-left: 25%; margin-right:25%;" >
    <form class="" action="menu.php" method="post" >
        <div class="page-header">
              <h1><center> &nbsp; Sistema Tatu <small>Login</small></center></h1>
          </div>
          <div class="row" style="text-align: center; font-size:18px;">
            <div>
               <fb:login-button scope="public_profile,email" data-max-rows="5" data-size="large" onlogin="checkLoginState();"></fb:login-button>
              <label class="g-signin2" style="width: 80px; height:27px;" data-onsuccess="getLoginGmail"></label>
            </div>
          </div>

          <br />
      <div class="panel-body" style="text-align:center;">
          <div>
            <div class="panel panel-default">
            <div class="accordion-group">
            <div class="accordion-heading">
              <a class="accordion-toggle" data-toggle="collapse" href="#perguntas">Perguntas frequentes</a>
            </div>
            <div id="perguntas" class="accordion-body collapse out">
                <div class="panel-body" style="text-align:left;" >
                  <ul>
                      <strong>Sou novo, como faço para acessar o Sistema Tatu?</strong>
                      <ol>
                        <li>Entre em contato com os adiministradores para ter acesso ao sistema.</a></l>
                      </ol>
                    </li>
                    <br />
                    <strong>Ao tentar logar,aparece uma mensagem :<br>"O email "<a href="#">fulano@provedor.org</a>" nao está em nosso bando de dados" ?</strong>
                      <ol>
                        <li>Para arrumar isso,verifique seu email juntamente a um administrador.</a></l>
                      </ol>
                    </li>

                  </ul>
                </div>
            </div>
          </div>
        </div>
          </div>
        <?php
        session_start("TATU");
        if (isset($_GET['return'])) {
         $_SESSION['return'] = $_GET['return'];
        }
            if(isset($_SESSION["emailErro"])) {
              if($_SESSION["provider"] == "facebook"){
                echo "<script>
                  var emailInvalido = true;
                </script>";
              } else {
                echo "<script>
                    var emailInvalido = false;
                    </script>";
              }
                echo "<div class='alert alert-danger' role='alert'><small>O Email ". '"<b>' . $_SESSION["emailErro"] . '</b>"' . ", não está cadastrado em nosso sistema.</small></div>";
                $_SESSION["emailErro"] = null;
            } else {

              echo "<script>
                    var emailInvalido = false;
                    </script>";
                echo "Por Favor efetue o Login";
            }
        ?>
      </div>
  </form>
</div>
<!-- Pega os dados enviados pelo javascript e reenvia para o php -->
<form name="login" method="POST" action="util/validarLogin.php">
    <input type="hidden" id="getEmail" name="getEmail">
    <input type="hidden" id="name" name="name">
    <input type="hidden" id="token" name="token">
    <input type="hidden" id="provider" name="provider">
</form>
</body>
</html>