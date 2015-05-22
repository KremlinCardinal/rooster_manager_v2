<?php
include_once "../api/functions.php";

if(isset($_POST['addnote'])) {
	$pUserId = $_POST['addnote']['userid'];
	$pClass = $_POST['addnote']['class'];
	$pNote = htmlspecialchars($_POST['addnote']['note']);

	echo AddNotitie($pClass,$pNote,$pUserId);
}

if(isset($_POST['getNotes'])) {
	$pUserId = $_POST['getNotes']['userid'];
	echo GetNotitie($pUserId);
}

if(isset($_POST['editnote'])) {
	$pUserId = $_POST['editnote']['userid'];
	$pClass = $_POST['editnote']['class'];
	$pNote = htmlspecialchars($_POST['editnote']['note']);

	echo AddNotitie($pClass,$pNote,$pUserId);
}