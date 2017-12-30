<?php

function errormsg($msg) {
  header('HTTP/1.1 500 Internal Server Booboo');
  header('Content-Type: application/json; charset=UTF-8');
  $result=array();
  $result['messages'] = $msg;
  //feel free to add other information like $result['errorcode']
  die(json_encode($result));
}

session_start();
if(!isset($_SESSION['userid'])) {
  errormsg("Unangemeldeter Benutzer!");
}

include '../settings.php';

$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
    errormsg("Datensatz konnte nicht bearbeitet werden!");
}

$eid = $_POST['eid'];
if (empty($eid) || !is_numeric($eid)) {
  errormsg('Ungültiges Ereignis!');
}

$editor = $_POST['editor'];
if (empty($editor) || !is_numeric($editor)) {
  errormsg('Ungültiger Bearbeiter!');
}

$sql = "DELETE FROM ereignis WHERE id=".$eid;
$statement = $mysqli->prepare($sql);
$state = $statement->execute();

while (false===$state) {
  errormsg("Datensatz konnte nicht bearbeitet werden!");
}

?>
