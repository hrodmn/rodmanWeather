<?php
// Create connection
$sql = new mysqli('localhost', 'root', 'shasta', 'weather_database');

// Check connection
if ($sql->connect_errno > 0) {
	printf("Connect failed: %s\n", $sql->connect_err);
}

$query = "SELECT datetime, AVG(temperature) AS meanTemp, AVG(humidity) AS meanHumidity 
		FROM weatherLog WHERE datetime >= now() - INTERVAL 7 DAY
		GROUP BY DATE(datetime), HOUR(datetime)";

$result = $sql->query($query) or exit("Error code ({$sql->errno}): {$sql->error}");

$rows = array();
$table = array();
$table['cols'] = array(
		array('label' => 'datetime', 'type' => 'datetime'),
		array('label' => 'meanTemp', 'type' => 'number'),
		array('label' => 'meanHumidity', 'type' => 'number')
);

foreach($result as $r) {

	$temp = array();

	$temp[] = array('v' => 'Date('.date('Y',strtotime($r['datetime'])).',' . 
                                     (date('n',strtotime($r['datetime'])) - 1).','.
                                     date('d',strtotime($r['datetime'])).','.
                                     date('H',strtotime($r['datetime'])).','.
                                     date('i',strtotime($r['datetime'])).','.
                                     date('s',strtotime($r['datetime'])).')');
	$temp[] = array('v' => (float) $r['meanTemp']);
	$temp[] = array('v' => (float) $r['meanHumidity']);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;

$jsonTable = json_encode($table);
echo $jsonTable;

$sql->close();
?>