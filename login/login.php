<?php
$login_error = '';
if(!empty($_POST['login']['submit'])) {
	if (!empty($_POST['login']['naam']) && !empty($_POST['login']['wachtwoord'])) {
		$naam = $_POST['login']['naam'];
		$wachtwoord = md5($_POST['login']['wachtwoord']);

		$db = new PDO('mysql:host=localhost;dbname=deb67958_roostermanager', 'deb67958_ruud', 'harmen');
		//$db = new PDO('mysql:host=localhost;dbname=project', 'root', '');

		$checkUsers = "SELECT * FROM accounts WHERE gebruikersnaam = '$naam' AND wachtwoord = '$wachtwoord'";
		$userStmt = $db->prepare($checkUsers);
		$userStmt->execute();
		$user = $userStmt->fetchAll();

		if (count($user) == 1) {
			$_SESSION['user'] = $naam;
			$_SESSION['logged_in'] = true;
			$login_error = 'login_error_succes';
		} else {
			$login_error = 'login_error_wrong_credentials';
		}
	} else {
		$login_error = 'login_error_empty_field';
	}
}

	