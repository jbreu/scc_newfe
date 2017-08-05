<?php

function errormsg($msg) {
  header('HTTP/1.1 500 Internal Server Booboo');
  header('Content-Type: application/json; charset=UTF-8');
  $result=array();
  $result['messages'] = $msg;
  //feel free to add other information like $result['errorcode']
  die(json_encode($result));
}

function datematch($date) {
  if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
    return true;
  } else {
    return false;
  }
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
    errormsg("Datensatz konnte nicht hinzugefügt werden!");
}

$etype = $_POST['etype'];
if (!empty($etype) && !is_numeric($etype)) {
  errormsg('Ungültiger Ereignistyp!');
}

$kid = $_POST['kid'];
if (!empty($kid) && !is_numeric($kid)) {
  errormsg('Ungültige Korporation!');
}

$text = $mysqli->real_escape_string($_POST['text']);

$quelle = $_POST['quelle'];
if (!empty($quelle) && !is_numeric($quelle)) {
  errormsg('Ungültige Quelle!');
}

$evgzeitraum= $_POST['evgzeitraum'];
if (!empty($evgzeitraum) && (!is_numeric($evgzeitraum) || $evgzeitraum<1700 || $evgzeitraum>2100)) {
  errormsg('Ungültiges Ereignisjahr!');
}

$evgtag = $_POST['evgtag'];
if (!empty($evgtag) && !datematch($evgtag)) {
  errormsg('Ungültiges Ereignisdatum!');
}

$kidfremd1 = $_POST['kidfremd1'];
if (!empty($kidfremd1) && !is_numeric($kidfremd1)) {
  errormsg('Ungültige fremde Korporation!');
}

$kidfremd2 = $_POST['kidfremd2'];
if (!empty($kidfremd2) && !is_numeric($kidfremd2)) {
  errormsg('Ungültige fremde Korporation!');
}

$evverbandvid = $_POST['evverbandvid'];
if (!empty($evverbandvid) && !is_numeric($evverbandvid)) {
  errormsg('Ungültiger Verband!');
}

$ktypid = $_POST['ktypid'];
if (!empty($ktypid) && !is_numeric($ktypid)) {
  errormsg('Ungültiger Korporationstyp!');
}

$editor = $_POST['editor'];
if (empty($editor) || !is_numeric($editor)) {
  errormsg('Ungültiger Bearbeiter!');
}

$sql = "INSERT INTO ereignis (type, korporation, quelle, text, datum, jahr, fremdeKorporation1, fremdeKorporation2, verband, korporationstyp, editor) VALUES ('".$etype."', '".$kid."', '".$quelle."', '".$text."', '".$evgtag."', '".$evgzeitraum."', '".$kidfremd1."', '".$kidfremd2."', '".$evverbandvid."', '".$ktypid."', '".$editor."')";
$statement = $mysqli->prepare($sql);
$state = $statement->execute();

while (false===$state) {
  errormsg("Datensatz konnte nicht hinzugefügt werden!");
}

?>
