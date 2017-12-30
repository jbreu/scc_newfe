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

  for ($i=1;$i<11;++$i) {
    switch ($i) {
      case '1': $fi = $row->farbe1; break;
      case '2': $fi = $row->farbe2; break;
      case '3': $fi = $row->farbe3; break;
      case '4': $fi = $row->farbe4; break;
      case '5': $fi = $row->farbe5; break;
      case '6': $fi = $row->farbe6; break;
      case '7': $fi = $row->farbe7; break;
      case '8': $fi = $row->farbe8; break;
      case '9': $fi = $row->farbe9; break;
      case '10': $fi = $row->farbe10; break;
    }

    if ($fi!=0) {
      $band[] = sprintf("#%02x%02x%02x", ($colors[(int)$fi])->rot, ($colors[(int)$fi])->gruen, ($colors[(int)$fi])->blau);
    }
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

function check_string($str) {
  // https://stackoverflow.com/questions/110575/do-htmlspecialchars-and-mysql-real-escape-string-keep-my-php-code-safe-from-inje/110576#110576
  $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
  $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
  return $str;
}

function check_numeric($in) {
  if ($in != 'NULL' && !empty($in) && !is_numeric($in)) {
    die("Falsches Datenformat!");
  } else {
    return intval($in);
  }
}

/*
  Workaround: Lösche Eintrag in der Datenbank und passe dann in der History-Tabelle den Editor des Löscheintrags an.
*/
function delete_from_table($table, $id, $editor) {
  $mysqli = init_db();

  $sql = "DELETE FROM ".$table." WHERE id=".$id;

  $result = $mysqli->query($sql);

  if ( $result === FALSE) {
    //die($mysqli->error);
    return $result;
  }

  // Passe History-Eintrag an
	$getdelev = "SELECT * FROM ".$table."_history WHERE id=".$id." ORDER BY revision DESC";
	$statement = $mysqli->prepare($getdelev);
	$statement->execute();
	$result = $statement->get_result();

  $row = $result->fetch_object();
  $keyrev = $row->revision;

  $sqlhistupdate = "UPDATE ".$table."_history SET editor=".$editor." WHERE id=".$id." AND revision=".$keyrev;
  $mysqli->query($sqlhistupdate);
}

?>
