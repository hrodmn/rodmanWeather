<?php
// Create connection
$sql = new mysqli('localhost', 'root', 'shasta', 'weather_database');

// Check connection
if ($sql->connect_errno > 0) {
	printf("Connect failed: %s\n", $sql->connect_err);
}

$query = "SELECT * FROM weatherLog WHERE datetime >= now() - INTERVAL 1 DAY"

$result = $sql->query($query) or exit("Error code ({$sql->errno}): {$sql->error}");

$rows = array();
$table = array();
$table['cols'] = array(
		array('label' => 'datetime', 'type' => 'string'),
		array('label' => 'temperature', 'type' => 'number'),
		array('label' => 'humidity', 'type' => 'number')
);

foreach($result as $r) {

	$temp = array();

	$temp[] = array('v' => (string) $r['datetime']);
	$temp[] = array('v' => (int) $r['temperature']);
	$temp[] = array('v' => (int) $r['humidity']);
	$rows[] = array('c' => $temp);
}

$table['rows'] = $rows;

$jsonTable = json_encode($table);
echo $jsonTable

$sql->close();
?>