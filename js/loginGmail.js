function getLoginGmail(googleUser) {
  var profile = googleUser.getBasicProfile();
  document.getElementById('getEmail').value = profile.getEmail();
  document.getElementById('name').value = profile.getName();
  document.getElementById('token').value = profile.getId();
  document.getElementById('provider').value = "gmail";
  document.login.submit();
}

function logoutGmail() {
    GoogleAuth.disconnect();  
  }
