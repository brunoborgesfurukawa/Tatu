function logout(provider) {
	if (provider == "facebook") {
		logoutFacebook();
	}
	else if (provider == "gmail") {
		logoutGmail();
	}
}