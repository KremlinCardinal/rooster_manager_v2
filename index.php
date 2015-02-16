<?php
if(!isset($_REQUEST["klas"])) exit;
$klas = $_REQUEST["klas"];
require_once("functions.php");

echo jsonFront(getRooster($klas));
?>