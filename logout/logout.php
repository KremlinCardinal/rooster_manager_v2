<?php
session_start();

if(isset($_POST['logout']) && $_POST['logout'] == 'true') {
	session_unset();
	header('Location: ../index.php');
}