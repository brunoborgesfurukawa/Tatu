function statusChangeCallback(response) {

  if (response.status === 'connected') {
    if (!emailInvalido) {
      getLoginFacebook();
    } else {
      logoutFacebook();
    }

    token = response.authResponse.accessToken;
  } else if (response.status === 'not_authorized') {
    // document.getElementById('status').innerHTML = 'Por favor efetue o login no nosso sistema';
  } else {
    // document.getElementById('status').innerHTML = "<small><div>Por favor efetue o Login</></small>";
  }
}

  function checkLoginState() {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1426447620990013',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.2'
    });

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&appId=1426447620990013&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function getLoginFacebook() {
      FB.api('/me', function(response) {
        document.getElementById('getEmail').value = response.email;
        document.getElementById('name').value = response.name;
        document.getElementById('token').value = token;
        document.getElementById('provider').value = "facebook";
        document.login.submit();
      });
    }

  function logoutFacebook() {
    FB.logout(function(response) {
      statusChangeCallback(response);
    });
  }