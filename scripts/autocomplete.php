<?php
require_once '../functions.php';

$supported_tables = array("ort", "nachfolger", "verband");
$table="";
$name="";

switch ($_GET['type']) {
  case 'ort':
    $table='ort';
    $name='name';
    break;

  case 'nachfolger':
    $table='korporation';
    $name='name';
    break;

  default:
    header('Fehler!');
    exit();
}


$mysqli= init_db();

$name_startsWith=strtoupper($mysqli->real_escape_string($_GET['name_startsWith']));

$sqlcolor = "SELECT * FROM ".$table." where ".$name." LIKE '".$name_startsWith."%'";
$statement = $mysqli->prepare($sqlcolor);
$statement->execute();
$result = $statement->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
  $a_json_row = array();
	$a_json_row["value"] = $row['id'];
	$a_json_row["label"] = $row['name'];
  if ($table=='ort')
    $a_json_row["label"].=" (".$row['region'].")";
	array_push($data, $a_json_row);
}
echo json_encode($data);


?>
