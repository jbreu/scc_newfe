<?php
  include 'header.php';

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
	$colors;

	while ($row = $result->fetch_object()) {
		$colors[$row->id]=$row;
	}

	echo "<form action='table.php' method='get' width=300px>";
	echo '<select name=farbe1>';

	foreach ($colors as $color) {
		echo '<option value="'.$color->id.'">'.$color->name.'</option>';
	}

	echo '</select><br/>';

        echo '<select name=farbe2>';

        foreach ($colors as $color) {
                echo '<option value="'.$color->id.'">'.$color->name.'</option>';
        }

        echo '</select><br/>';

        echo '<select name=farbe3>';

        foreach ($colors as $color) {
                echo '<option value="'.$color->id.'">'.$color->name.'</option>';
        }

        echo '</select><br/>';

	echo '<div class="checkbox"><label><input type="checkbox" name="aktiv" checked="checked">Aktive Korporationen</label></div>';

	echo '<input type="submit" value="Suchen">';

  echo '</form>';

  include 'footer.php';
?>
