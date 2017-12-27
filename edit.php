<?php
  include 'header.php';

	$kid=1;
	if (!isset($_GET['kid']) || !is_numeric($_GET["kid"]))
		die("Keine Korporation angegeben!");
	else
		$kid=$_GET["kid"];

	$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
    		die('Connect failed: '.$mysqli->connect_error.'\n');
	}

  echo '
  <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css" />
  <script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="scripts/autocomplete.js"></script>';

	$sql = "SELECT korporation.name as name, korporation.sccid as sccid, ort.name as ortname, korporation.postleitzahl as postleitzahl, korporation.strasse as strasse, korporation.telefonnummer as telefonnummer, korporation.emailadresse as emailadresse, korporation.internetseite as internetseite, korporation.waffenspruch as waffenspruch, korporation.mensurstandpunkt as mensurstandpunkt, ort.id as ortid, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, korporation.aufgegangenin_id as fusionid, korporation.verband as verbandid, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.id=".$kid;
	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

	while($row = $result->fetch_object()) {
		echo "<form action='details.php?kid=".$kid."' method='post'>";
    echo "<input type=hidden name='kid' value='".$kid."'>";
		echo "<h1>Name: <input type='text' name='name' value='".$row->name."'></h1><br/>";

    echo "Straße: <input type='text' class='form-control' name='strasse' value='".$row->strasse."'>";
    echo "Postleitzahl: <input type='text' class='form-control' name='postleitzahl' value='".$row->postleitzahl."'>";
		echo "Ort: <input class='form-control txt-auto' id='ort' value='".$row->ortname." (".$row->region.")'>";
      echo "<input type=hidden name='ort' id='ortid' value='".$row->ortid."'>";

    echo "Telefonnummer: <input type='text' class='form-control' name='telefonnummer' value='".$row->telefonnummer."'>";
    echo "Emailadresse: <input type='text' class='form-control' name='emailadresse' value='".$row->emailadresse."'>";
    echo "Internetseite: <input type='text' class='form-control' name='internetseite' value='".$row->internetseite."'>";

		echo "Aktiv: <input type='checkbox' name='aktiv' ".($row->aktiv?"checked='checked'":"")."><br/>";

    echo '
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <script src="scripts/moment-with-locales.min.js"></script>
    <script src="scripts/bootstrap-datetimepicker.min.js"></script>';

    echo 'Gründungstag: <div class="input-group date" id="gtagpicker">
             <input type="text" class="form-control" name="gtag" value="'.$row->gtag.'">
             <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
             </span>
         </div>';

         echo "<script type='text/javascript'>
            $(function () {
                $('#gtagpicker').datetimepicker({
                  format: 'YYYY-MM-DD'
                });
            });
        </script>";


		//echo "Gruendungstag: <input class='form-control' id='gtagpicker' name='gtag' value='".$row->gtag."'><br/>";
		echo "Gründungszeitraum: <input class='form-control' type='text' name='gzeitraum' value='".$row->gzeitraum."'><br/>";
		echo "Wahlspruch: <input class='form-control' name='wahlspruch' value='".$row->wahlspruch."'>";

    echo 'Mensurstandpunkt: <select class="form-control" id="mensurstandpunkt">';
      $ms = get_mensurstandpunkte();
      foreach ($ms as $m) {
        if ((!empty($row->mensurstandpunkt) && $row->mensurstandpunkt==$m->id) || (empty($row->mensurstandpunkt) && 5==$m->id)) {
            echo '<option value="'.$m->id.'" selected>'.$m->name.'</option>';
        } else {
          echo '<option value="'.$m->id.'">'.$m->name.'</option>';
        }
    	}
    echo '</select>';

    echo "Waffenspruch: <input class='form-control' name='waffenspruch' value='".$row->waffenspruch."'>";
  	echo "Aufgegangen in: <input class='form-control txt-auto' name='nachfolger' id='nachfolger' value='".$row->fusion."'>";
    echo "<input type=hidden name='nachfolgerid' id='nachfolgerid' value='".$row->fusionid."'>";
    echo "Verband: <input class='form-control txt-auto' id='verband' value='".$row->verbandname."'>";
    echo "<input type=hidden name='verbandid' id='verbandid' value='".$row->verbandid."'>";

    $band = get_band($row);
		echo "<table><tr>";
		foreach ($band as $farb) {
			echo "<td bgcolor=".$farb."></td>";
		}
		echo "</tr></table>";
		echo "<br/>";
    echo "<input class='btn btn-primary' type='submit' value='Speichern'>";
		echo "</form>";
	}
?>

<?php include 'footer.php'; ?>
