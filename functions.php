<?php

function init_db() {
  include 'settings.php';

  $mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
    		die('Connect failed: '.$mysqli->connect_error.'\n');
	}

  return $mysqli;
}

function get_mensurstandpunkte() {
  $mysqli = init_db();
	$sqlms = "SELECT * FROM mensurstandpunkt";
	$statement = $mysqli->prepare($sqlms);
	$statement->execute();
	$result = $statement->get_result();

  $mensurstandpunkte = array();

	while ($row = $result->fetch_object()) {
    $mensurstandpunkte[] = $row;
	}

  return $mensurstandpunkte;
}

function get_colors() {
  $mysqli = init_db();
	$sqlcolor = "SELECT * FROM farbe";
	$statement = $mysqli->prepare($sqlcolor);
	$statement->execute();
	$result = $statement->get_result();

	while ($row = $result->fetch_object()) {
		$colors[$row->id]=$row;
	}

  return $colors;
}

function get_band($row) {
  $band=array();
  $colors=get_colors();
  if ($row->farbe1!=0) {
    $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$row->farbe1])->rot, ($colors[(int)$row->farbe1])->gruen, ($colors[(int)$row->farbe1])->blau);
  }
  if ($row->farbe2!=0) {
    $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$row->farbe2])->rot, ($colors[(int)$row->farbe2])->gruen, ($colors[(int)$row->farbe2])->blau);
  }
  if ($row->farbe3!=0) {
    $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$row->farbe3])->rot, ($colors[(int)$row->farbe3])->gruen, ($colors[(int)$row->farbe3])->blau);
  }
  if ($row->farbe4!=0) {
    $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$row->farbe4])->rot, ($colors[(int)$row->farbe4])->gruen, ($colors[(int)$row->farbe4])->blau);
  }
  if ($row->farbe5!=0) {
    $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$row->farbe5])->rot, ($colors[(int)$row->farbe5])->gruen, ($colors[(int)$row->farbe5])->blau);
  }
  return $band;
}

function get_korporation($kid) {
  if (!isset($kid) || !is_numeric($kid))
		die("Keine Korporation angegeben!");

  include 'settings.php';

  $mysqli = init_db();

	$sql = "SELECT * FROM korporation WHERE id=".$kid;
  $statement = $mysqli->prepare($sql);
  $statement->execute();

  $result = $statement->get_result();
  return $result->fetch_object();
}

function get_ereignistypen() {
  $mysqli = init_db();

	$sql = "SELECT * FROM ereignistyp";
  $statement = $mysqli->prepare($sql);
  $statement->execute();

  $result = $statement->get_result();

  $ereignistypen = array();

	while ($row = $result->fetch_object()) {
    $ereignistypen[] = $row;
	}

  return $ereignistypen;
}

?>
