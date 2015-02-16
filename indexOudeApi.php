<?php
print_r(getRooster("AO2A"));

function getRooster($klas, $fix=true)
{
	$url = "https://roosters.deltion.nl/json/group/groupname/" . $klas;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	$result=mb_convert_encoding($result,'ISO-8859-1','utf-8');
	curl_close($ch);
	$result = substr($result,1,strlen($result));
	$result = json_decode($result,true);
	if($fix==true)
		return fixRoosterJson($result);
	else
		return $result;
	
}
function fixRoosterJSON($json)
{
	$fixVak = array();
	$fixLokalen	= array();
	$fixKlas	= array();
	$fixDocent	= array();
	foreach($json as $key=>$vak)
	{
		if(isset($vak["Lesson"]["Name"]))
			$fixVak[$vak["Lesson"]["Id"]] = $vak["Lesson"];
		else
			$json[$key]["Lesson"] = $fixVak[$vak["Lesson"]];
		
		if(isset($vak["LessonPlace"]["RoomName"]))
			$fixLokalen[$vak["LessonPlace"]["Id"]] = $vak["LessonPlace"];
		else
			$json[$key]["LessonPlace"] = $fixLokalen[$vak["LessonPlace"]];

		if(!isset($vak["StudentGroups"]["Name"]))
			$fixKlas[$vak["StudentGroups"][0]["Id"]] = $vak["StudentGroups"][0];
		else
			$json[$key]["StudentGroups"] = $fixKlas[$vak["StudentGroups"][0]["Id"]];
		
		if(isset($vak["Teachers"][0]["Id"]))
			$fixDocent[$vak["Teachers"][0]["Id"]] = $vak["Teachers"][0];
		else
			$json[$key]["Teachers"] = $fixDocent[$vak["Teachers"][0]];
	}
	return $json;
}
?>