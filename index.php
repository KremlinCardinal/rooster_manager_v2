<?php
if(!isset($_REQUEST["klas"])) exit;
$klas = $_REQUEST["klas"];
require_once("functions.php");

$jsonArray = jsonFront(getRooster($klas));

$start_dt = mktime(8,30,0,date('n'),date('j'),date('Y')).'000';

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
    <header id="header">
        <nav class="top-nav">
            <div class="nav-wrapper center">
                <div id="date"></div>
            </div>
        </nav>

    </header>
    <div id="container">
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
    </div>
    <footer id="footer">

    </footer>
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/functions.js"></script>
    <script>
        var jsonArray =JSON.parse('<?= $jsonArray ?>');
        var date = <?= $date ?>;
        processJSON(jsonArray, date);
    </script>
</body>
</html>