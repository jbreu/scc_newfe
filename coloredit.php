<?php
include 'header.php';

$kid=1;
if (!isset($_GET['kid']) || !is_numeric($_GET["kid"]))
	die("Keine Korporation angegeben!");
else
	$kid=$_GET["kid"];

$bid=1;
	if (!isset($_GET['bid']) || !is_numeric($_GET["bid"]))
		die("Kein Band angegeben!");
	else
		$bid=$_GET["bid"];

if ($kid!=0) {

  $mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
    		die('Connect failed: '.$mysqli->connect_error.'\n');
	}

	$sqlcolor = "SELECT * FROM farbe";
	$statement = $mysqli->prepare($sqlcolor);
	$statement->execute();
	$result = $statement->get_result();

	while ($row = $result->fetch_object()) {
		$colors[$row->id]=$row;
	}

  $sqlband = "SELECT * FROM band WHERE id=".$bid;
	$statement = $mysqli->prepare($sqlband);
	$statement->execute();
	$result = $statement->get_result();

  $cnt = 0;

	while ($row = $result->fetch_object()) {
		$band = $row;
    $cnt++;
	}

  if ($cnt==0) {
    // TODO Behandle Korporationen ohne Farben --> Neuanlage!
  }

	echo "<form action='details.php?kid=".$kid."&bid=".$bid."' method='post' width=300px>";
	echo "<input type=hidden name='kid' value='".$kid."'>";
	echo "<input type=hidden name='bid' value='".$bid."'>";

	for ($i=1;$i<11;++$i) {

		echo '<select name=farbe'.$i.'>';

		echo '<option value="NULL">Keine</option>';
	  foreach ($colors as $color) {
      $hexbgcolor = sprintf("#%02x%02x%02x", $color->rot, $color->gruen, $color->blau);
      $hextxtcolor = sprintf("#%02x%02x%02x", 255-$color->rot, 255-$color->gruen, 255-$color->blau);
			if (!empty($band)) {
				switch ($i) {
					case '1': $fi = $band->farbe1; break;
					case '2': $fi = $band->farbe2; break;
					case '3': $fi = $band->farbe3; break;
					case '4': $fi = $band->farbe4; break;
					case '5': $fi = $band->farbe5; break;
					case '6': $fi = $band->farbe6; break;
					case '7': $fi = $band->farbe7; break;
					case '8': $fi = $band->farbe8; break;
					case '9': $fi = $band->farbe9; break;
					case '10': $fi = $band->farbe10; break;
					default: 	$fi = $band->farbe1; break;
				}
			} else {
				$fi=-1;
			}
      if ($fi == $color->id) {
        echo '<option value="'.$color->id.'" style="background:'.$hexbgcolor.';color:'.$hextxtcolor.'" selected>'.$color->name.'</option>';
      } else {
        echo '<option value="'.$color->id.'" style="background:'.$hexbgcolor.';color:'.$hextxtcolor.'">'.$color->name.'</option>';
      }
	  }

		echo '</select><br/>';
	}

	if ($bid!=0) {
		echo 'Falls die erste Farbe auf "Keine" gesetzt wird, wird der Farbeneintrag entfernt.<br/>';
		echo '<input type="submit" value="Ändern">';
	} else {
		echo '<input type="submit" value="Anlegen">';
	}

  echo '</form>';
}

  include 'footer.php';

?>
