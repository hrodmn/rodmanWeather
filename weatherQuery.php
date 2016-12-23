<?php
// Create connection
$sql = new mysqli('localhost', 'root', 'shasta', 'weather_database');

// Check connection
if ($sql->connect_errno > 0) {
	printf("Connect failed: %s\n", $sql->connect_err);
}

// Query last 24 hours
$query24 = "SELECT * FROM weatherLog WHERE datetime >= now() - INTERVAL 1 DAY";

$result24 = $sql->query($query24) or exit("Error code ({$sql->errno}): {$sql->error}");

$rows = array();
$table = array();
$table['cols'] = array(
		array('label' => 'datetime', 'type' => 'datetime'),
		array('label' => 'temperature', 'type' => 'number'),
		array('label' => 'humidity', 'type' => 'number')
);

foreach($result24 as $r) {

	$temp = array();

	$temp[] = array('v' => 'Date('.date('Y',strtotime($r['datetime'])).',' . 
                                     (date('n',strtotime($r['datetime'])) - 1).','.
                                     date('d',strtotime($r['datetime'])).','.
                                     date('H',strtotime($r['datetime'])).','.
                                     date('i',strtotime($r['datetime'])).','.
                                     date('s',strtotime($r['datetime'])).')');
	$temp[] = array('v' => (float) $r['temperature']);
	$temp[] = array('v' => (float) $r['humidity']);
	$rows[] = array('c' => $temp);
}

$table24['rows'] = $rows;

// Query last 7 days
$query7 = "SELECT * FROM weatherLog WHERE datetime >= now() - INTERVAL 7 DAY";

$result7 = $sql->query($query7) or exit("Error code ({$sql->errno}): {$sql->error}");

$rows = array();
$table = array();
$table['cols'] = array(
		array('label' => 'datetime', 'type' => 'datetime'),
		array('label' => 'temperature', 'type' => 'number'),
		array('label' => 'humidity', 'type' => 'number')
);

foreach($result7 as $r) {

	$temp = array();

	$temp[] = array('v' => 'Date('.date('Y',strtotime($r['datetime'])).',' .
			(date('n',strtotime($r['datetime'])) - 1).','.
			date('d',strtotime($r['datetime'])).','.
			date('H',strtotime($r['datetime'])).','.
			date('i',strtotime($r['datetime'])).','.
			date('s',strtotime($r['datetime'])).')');
	$temp[] = array('v' => (float) $r['temperature']);
	$temp[] = array('v' => (float) $r['humidity']);
	$rows[] = array('c' => $temp);
}

$table7['rows'] = $rows;


$allData = array();
$allData['last24'] = $table24;
$allData['last7'] = $table7;

$jsonTable = json_encode($allData);
echo $jsonTable;

$sql->close();
?>