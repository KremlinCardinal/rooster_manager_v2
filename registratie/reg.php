<?php
$register_error = '';

if(!empty($_POST['reg']['submit'])) {
	if (empty($_POST['reg']['klas']) || empty($_POST['reg']['naam']) || empty($_POST['reg']['wachtwoord']) || empty($_POST['reg']['wachtwoord2'])) {
		$register_error = 'register_error_empty_field';
	} else {
		if(strcmp($_POST['reg']['wachtwoord'], $_POST['reg']['wachtwoord2']) !== 0) {
			$register_error = 'register_error_passwords_not_equal';
		} else {
			$db = new PDO('mysql:host=localhost;dbname=project', 'deb67958_ruud', 'harmen');

			$klas = strtolower($_POST['reg']['klas']);
			$naam = $_POST['reg']['naam'];
			$wachtwoord = md5($_POST['reg']['wachtwoord']);

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
