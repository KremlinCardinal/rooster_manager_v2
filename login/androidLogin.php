<?php
$regid = $_POST["regid"];
$naam = $_POST["username"];
$wachtwoord = md5($_POST['password']);

$db = new PDO('mysql:host=localhost;dbname=deb67958_roostermanager', 'deb67958_ruud', 'harmen');

$checkUsers = "SELECT `klas` FROM accounts WHERE gebruikersnaam = '$naam' AND wachtwoord = '$wachtwoord'";
$userStmt = $db->prepare($checkUsers);
$userStmt->execute();
$user = $userStmt->fetch();

if (isset($user["klas"])) 
{
	$checkUsers = "UPDATE accounts SET pushid='$regid' WHERE gebruikersnaam = '$naam' AND wachtwoord = '$wachtwoord'";
	$userStmt = $db->prepare($checkUsers);
	$userStmt->execute();
	echo strtoupper($user["klas"]);
}
else 
	echo "incorrect";