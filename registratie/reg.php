<?php

	if (empty($_POST['klas'])&&empty($_POST['naam'])&&empty($_POST['wachtwoord'])) 
	{
		echo "U moet alles invullen!";
	}
	else
	{
		$db = new PDO('mysql:host=localhost;dbname=project', 'deb67958_ruud', 'harmen');

		$klas = strtolower($_POST['klas']);
		$naam = $_POST['naam'];
		$wachtwoord = md5($_POST['wachtwoord']);

		$check = $db->prepare("SELECT gebruikersnaam FROM accounts WHERE gebruikersnaam = '$naam'");
		$check->execute();

		if($check->rowCount() > 0){
		    echo "U bestaad al!";
		}
		else
		{
			$sql = $db->prepare("INSERT INTO accounts (klas,gebruikersnaam,wachtwoord,pushid,id)
					VALUES ('$klas','$naam','$wachtwoord','','');");
			if ($sql->execute()) {
				echo "U bent geregistreerd klik hier om in te loggen!";
			}
			else
			{
				echo "Er is iets fout gegaan!";
			}
		}
	}