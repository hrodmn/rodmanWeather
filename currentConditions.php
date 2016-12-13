<?php
// Create connection
$sql = new mysqli('localhost', 'root', 'shasta', 'weather_database');

// Check connection
if ($sql->connect_errno > 0) {
	printf("Connect failed: %s\n", $sql->connect_err);
}

$query = "SELECT * FROM weatherLog WHERE datetime >= now() - INTERVAL 1 DAY";

$result = $sql->query($query) or exit("Error code ({$sql->errno}): {$sql->error}");

$rows = array();
$table = array();
$table['cols'] = array(
		array('label' => 'datetime', 'type' => 'date'),
		array('label' => 'temperature', 'type' => 'number'),
		array('label' => 'humidity', 'type' => 'number')
);

foreach($result as $r) {

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

$table['rows'] = $rows;

$jsonTable = json_encode($table);
echo $jsonTable;

$sql->close();
?>