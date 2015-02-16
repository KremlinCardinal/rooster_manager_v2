<?php

function getRooster($klas, $start = 0, $end = 10)
{
	$cache = getRoosterCache($klas);
	if($cache!=false)
		return $cache;
	else
	{
		$start = 0;
		$end = 10;
	}
	
	$url = "https://roosters.deltion.nl/api/roster?group=" . $klas . "&start=" . $start . "&end=" . $end;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	cacheRooster($klas, $result);
	$result=mb_convert_encoding($result,'ISO-8859-1','utf-8');
	$result = substr($result,1,strlen($result));
	curl_close($ch);
	$result = json_decode($result,true);
	
	return $result;
}

function getRoosterCache($klas)
{
	$db = new PDO('mysql:host=127.0.0.1;dbname=roostermanager', 'root', ''); 
	$klas = strtolower($klas);
	$STH = $db->prepare("
	SELECT `rooster` 
	FROM `klassenroosters` 
	WHERE `klas` = '".$klas."' 
	LIMIT 1;"); 
	
	$STH->execute();
	$result = $STH->fetch(); 
	
	if (empty($result))
		return false;
	
	return json_decode($result["rooster"]);
}

function cacheRooster($klas, $rooster)
{
	$db = new PDO('mysql:host=127.0.0.1;dbname=roostermanager', 'root', ''); 
	$klas = strtolower($klas);
	$sql = " 
    INSERT INTO `roosterManager`.`klassenroosters` (`klas`, `rooster`) 
    VALUES ('".$klas."','".$rooster."') 
    "; 
	$results = $db->query($sql); 
}

function jsonFront($json)
{
	return json_encode($json, 128);
}

function checkChanges()
{
	$db = new PDO('mysql:host=127.0.0.1;dbname=roostermanager', 'root', '');
	$sql = "
	SELECT `rooster` 
	FROM `klassenroosters`;"; 
	$results = $db->query($sql); 
	
}
?>