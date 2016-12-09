<?php
$servername = "localhost";
$username = "root";
$password = "shasta";
$dbname = "weather_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM weatherLog WHERE datetime >= now() - INTERVAL 1 DAY";
$result = $conn->query($sql);

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
?>