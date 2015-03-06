<?php
if(!isset($_REQUEST["klas"])) {
	$_REQUEST["klas"] = 'AO2A';
}
$klas = $_REQUEST["klas"];
require_once("functions.php");

$jsonArray = jsonFront(getRooster($klas));

$start_dt = strtotime('08:30').'000';

if(!isset($_REQUEST['date'])) {
    $_REQUEST['date'] = $start_dt;
}
$date = $_REQUEST['date'];
?>

<!DOCTYPE html>
<html>
<head lang="nl">
    <meta charset="UTF-8">

    <title></title>

    <link rel="stylesheet" href="css/materialize.min.css"/>
    <link rel="stylesheet" href="css/custom.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300,500,100' rel='stylesheet' type='text/css'>
</head>
<body>
    <header id="header" class="collection-header">
        <nav class="top-nav">
            <span id="date" class="center"></span>
        </nav>
    </header>
    <main id="main">
        <div id="rooster">
            <table class="striped">
                <thead>
                    <th data-field="time" class="time col1">Tijd</th>
                    <th data-field="class" class="class col2">Vak</th>
                    <th data-field="room" class="room col3">Lokaal</th>
                    <th data-field="teacher" class="teacher col4">Docent</th>
                </thead>
                <tbody id="rooster_body"></tbody>
            </table>
        </div>
    </main>
    <footer id="footer" class="page-footer">
        <div class="container">
            <span class="previous left-align left" onclick="goToPreviousDay()">
                <a class="waves-effect waves-light btn red lighten-3">
                    <i class="mdi-hardware-keyboard-arrow-left left"></i>
                    <span class="day-previous">Vorige dag</span>
                </a>
            </span>
            <span class="next right-align right" onclick="goToNextDay()">
                <a class="waves-effect waves-light btn red lighten-3">
                    <i class="mdi-hardware-keyboard-arrow-right right"></i>
                    <span class="day-next">Volgende dag</span>
                </a>
            </span>
        </div>
    </footer>
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/functions.js"></script>
    <script>
        var jsonArray = JSON.parse('<?= $jsonArray ?>');
        var preTs = <?= $date ?>;
        var fullDate = new Date(<?= $date ?>);

        var date = addZero(fullDate.getDate());
        var month = addZero(fullDate.getMonth()+1);
        var year = fullDate.getFullYear();

        var dateTs = year + '-' + month + '-' + date;
        processJSON(jsonArray.data, dateTs, preTs);
    </script>
</body>
</html>