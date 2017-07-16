<?php

include '../settings.php';

//print_r($_POST);

$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
    header('Datensatz konnte nicht hinzugefügt werden! '.$_POST['text']);
    exit();
}

$sql = "INSERT INTO ereignis (korporation, text, type) VALUES ('".$_POST['kid']."', '".$_POST['text']."', '1')";
$statement = $mysqli->prepare($sql);
$state = $statement->execute();

while (false===$state) {
  header('Datensatz konnte nicht hinzugefügt werden! '.$_POST['text']);
  exit();
}

?>
