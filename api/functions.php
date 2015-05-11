	<?php
$dbname = "roostermanager";
$dblogin = "root";
$dbpass = "";

$dbname = "deb67958_roostermanager";
$dblogin = "deb67958_ruud";
$dbpass = "harmen";

function getRooster($klas, $start = 0, $end = 10, $skipCacheCheck = false, $skipCache = false)
{
	if($skipCacheCheck==false)
	{
		$cache = getRoosterCache($klas);
		if($cache!=false)
			return $cache;
	}
	
	$url = "https://roosters.deltion.nl/api/roster?group=" . $klas . "&start=" . $start . "&end=" . $end;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	$result=mb_convert_encoding($result,'ISO-8859-1','utf-8');
	$result = substr($result,1,strlen($result));
	
	if($skipCache==false &&  $start == 0 && $end == 10)
		cacheRooster($klas, $result);
	curl_close($ch);
	$result = json_decode($result,true);
	
	return $result;
}

function getRoosterCache($klas)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=127.0.0.1;dbname='.$dbname, $dblogin, $dbpass); 
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
	global $dbname, $dblogin, $dbpass;
	$klas = strtolower($klas);
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$STH = $db->prepare("
	SELECT `rooster` 
	FROM `klassenroosters` 
	WHERE `klas` = '".$klas."'"); 
	
	$STH->execute();
	$result = $STH->fetch(PDO::FETCH_ASSOC); 
	
	if (empty($result))
	{
		$klas = strtolower($klas);
		$sql = " 
		INSERT INTO `klassenroosters` (`klas`, `rooster`, `lastModified`) 
		VALUES ('".$klas."','".$rooster."',NOW()) 
		";
		$db->exec($sql);
	}
	else
	{
		//echo "bestaat al<br>";
		$sql = " 
		UPDATE `klassenroosters` SET `rooster` = '".$rooster."', `lastModified` = NOW()
		WHERE `klas` = '".$klas."'
		"; 
		//echo $sql."<br>";
		//if(!$db->exec($sql))
			//echo "kon ook niet updaten<br>";
		$db->exec($sql);
	}
}

function jsonFront($json)
{
	return json_encode($json, 128);
}

function checkChanges()
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=127.0.0.1;dbname='.$dbname, $dblogin, $dbpass); 
	$sql = "
	SELECT `rooster`, `klas` 
	FROM `klassenroosters`;"; 
	$results = $db->query($sql); 
	foreach($results as $row)
	{
		echo "-" . $row["klas"] . "-<br>";
		
		$check1 = array();
		$check2 = array();
		$liveRooster = getRooster($row['klas'], 0 , 10 ,true,true);
		
		foreach($liveRooster["data"] as $key=>$dag)
		{
			$check1[$dag["date_f"]] = $dag;
		}
		$cacheRooster = json_decode($row["rooster"],true);
		foreach($cacheRooster["data"] as $key=>$dag)
		{
			$check2[$dag["date_f"]] = $dag;
		}
		foreach($check1 as $key=>$dag) 
		{ 
			echo "<br><hr>liveDag:</br>";
			echo jsonFront($dag);
			echo "<br><br>cacheDag:<br>";
			echo jsonFront($check2[$key]);
			echo "<br><br><hr><br><br>";
			if(isset($check2[$key]))
				if($check2[$key]!=$dag)
				{
					echo "<h1>WIJZIGING VOOR " . $key . "!</h1><br>";
					notifyKlas($row["klas"], $key, "Roosterwijziging");//echo "<h1>oh a noa something changed for ".$row["klas"]." for ".$key."!</h1>";
					
					/*
					$sql = " 
					UPDATE `klassenroosters` SET `rooster` = '".$liveRooster."', `lastModified` = NOW()
					WHERE `klas` = '".$row["klas"]."'
					"; 
					if(!$db->exec($sql))
						echo "kon ook niet updaten<br>";
					else
						echo "geupdate<br>";*/
				}
		}
		cacheRooster($row["klas"], jsonFront($liveRooster));		
	}
}

function notifyKlas($klas, $message, $titel)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$klas = strtolower($klas);
	$sql = " 
    SELECT `pushid` 
	FROM `accounts`
	WHERE `klas` = '".$klas."'"; 
	$registrationIds[] = array();
	foreach ($db->query($sql) as $row) 
	{
		$registrationIds[] = $row["pushid"];
		echo $row["pushid"] . "<br>";
    }
	pushMessage($registrationIds, $message, $titel);
}

function pushMessage($receivers, $message, $title)
{
	
	define( 'API_ACCESS_KEY', 'AIzaSyChsqzcZFjdKEzXbd6gnkO6b-A7fDMxqq8' );
	$registrationIds = $receivers;
	//$registrationIds = array("APA91bGlme0Bo_0L_574pSlbqVKcM0KNyO4USoR22BI-urjDzCHpSm8hUPU4ThASkZDD9hJ9Q2yxZ7YSR_PfSWCT90XsUrySX_C4maBkS7PgEGFnp_LL6W8ghW5E7fKM8lasJoD995qOas-HYYfGFfXK3yQfXbs2_Q" );
	$msg = array
	(
		'message' 	=> $message,
		'title'		=> $title,
		'subtitle'	=> 'This is a subtitle. subtitle',
		'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
		'vibrate'	=> 1,
		'sound'		=> 1,
		'largeIcon'	=> 'large_icon',
		'smallIcon'	=> 'small_icon'
	);
	$fields = array
	(
		'registration_ids' 	=> $registrationIds,
		'data'			=> $msg
	);
	$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	echo $result;
}

function maakNotitie($vak,$notitie,$pushid)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$sql = "UPDATE `notities` 
			SET `notitie` = '".$notitie."'
			WHERE `vak` = '".$vak."' 
			AND `userid` IN (SELECT `id` FROM `accounts` WHERE `pushid` = '".$pushid."');";
	$STH = $db->prepare($sql); 
	$STH->execute();
	
	if(!$STH->rowCount()) 
	{
		$sql = "SELECT `id` FROM `accounts` WHERE `pushid` = '".$pushid."';";
		$STH = $db->prepare($sql); 
		$STH->execute();
		$row = $STH->fetch();
		$userid = $row["id"];
		
		$sql = "INSERT INTO `notities` (`userid`,`vak`, `notitie`) VALUES ('".$userid."','".$vak."','".$notitie."');";
		$STH = $db->prepare($sql);
		$STH->execute();
	}
	return getUserNotities($pushid);
}

function getUserNotities($pushid)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$sql = "SELECT `vak`,`notitie`, `pushid`
			FROM `notities`
			INNER JOIN `accounts`
			ON `notities`.`userid` = `accounts`.`id`
			WHERE `pushid` = '".$pushid."'";
	$notitieJSON = array();
	foreach ($db->query($sql) as $row) 
	{
		$notitieJSON[$row["vak"]] = $row["notitie"];
    }
	return jsonFront($notitieJSON);
}

function AddNotitie($vak,$notitie,$userid)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$sql = "UPDATE `notities` 
			SET `notitie` = '".$notitie."'
			WHERE `vak` = '".$vak."' 
			AND `userid` = '".$userid."';";
	$STH = $db->prepare($sql); 
	$STH->execute();
	
	if(!$STH->rowCount()) 
	{	
		$sql = "INSERT INTO `notities` (`userid`,`vak`, `notitie`) VALUES ('".$userid."','".$vak."','".$notitie."');";
		$STH = $db->prepare($sql);
		$STH->execute();
	}
	return GetNotitie($userid);
}

function GetNotitie($userid)
{
	global $dbname, $dblogin, $dbpass;
	$db = new PDO('mysql:host=localhost;dbname='.$dbname, $dblogin, $dbpass); 
	$sql = "SELECT `vak`,`notitie`
			FROM `notities`
			WHERE `userid` = '".$userid."'";
	$notitieJSON = array();
	foreach ($db->query($sql) as $row) 
	{
		$notitieJSON[$row["vak"]] = $row["notitie"];
    }
	return jsonFront($notitieJSON);
}
?>