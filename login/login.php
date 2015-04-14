<?php

	if (!empty($_POST['login']['naam'])&&!empty($_POST['login']['wachtwoord'])) {
		$naam = $_POST['login']['naam'];
		$wachtwoord = md5($_POST['login']['wachtwoord']);

		$db = new PDO('mysql:host=localhost;dbname=project', 'deb67958_ruud', 'harmen');

		$checkUsers = "SELECT * FROM accounts WHERE gebruikersnaam = '$naam' AND wachtwoord = '$wachtwoord'";
        $userStmt = $db->prepare($checkUsers);
        $userStmt->execute();
        $user = $userStmt->fetchAll();
        
        if (count($user) == 1)
        {
            $_SESSION['user'] = $naam;
            echo "<script>alert('succes');</script>";
        }
        else{
        	echo "<script>alert('no!');</script>";
        }
	}
	