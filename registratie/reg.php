<?php
$register_error = '';
$register_errormessage = '';

if(!empty($_POST)) {
	if (empty($_POST['klas']) || empty($_POST['naam']) || empty($_POST['wachtwoord']) || empty($_POST['wachtwoord2'])) {
		$register_error = 'register_error_empty_field';
	} else {
		if(strcmp($_POST['wachtwoord'], $_POST['wachtwoord2']) !== 0) {
			$register_error = 'register_error_passwords_not_equal';
		} else {
			$db = new PDO('mysql:host=localhost;dbname=project', 'deb67958_ruud', 'harmen');

			$klas = strtolower($_POST['klas']);
			$naam = $_POST['naam'];
			$wachtwoord = md5($_POST['wachtwoord']);

			$check = $db->prepare("SELECT gebruikersnaam FROM accounts WHERE gebruikersnaam = '$naam'");
			$check->execute();

			if($check->rowCount() > 0) {
				$register_error = 'register_error_username_occupied';
			} else {
				$sql = $db->prepare("INSERT INTO accounts (klas,gebruikersnaam,wachtwoord,pushid,id)
					VALUES ('$klas','$naam','$wachtwoord','','');");
				if ($sql->execute()) {
					$register_error = 'register_error_succes';
				} else {
					$register_error = 'register_error_mysql';
				}
			}
		}
	}
}
