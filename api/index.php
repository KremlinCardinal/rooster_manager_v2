<?php
require_once("functions.php");
if(isset($_REQUEST["check"]))
{
	echo checkChanges();
	exit;
}
if(!isset($_REQUEST["klas"])) exit;
$klas = $_REQUEST["klas"];

echo jsonFront(getRooster($klas));
?>