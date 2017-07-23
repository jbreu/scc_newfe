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

	$sql = "SELECT korporation.name as name, ort.name as ortname, ort.id as ortid, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, korporation.aufgegangenin_id as fusionid, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.id=".$kid;
	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

	while($row = $result->fetch_object()) {
		echo "<form action='details.php' method='post'>";
		echo "<h1>Name: <input type='text' name='name' value='".$row->name."'></h1><br/>";

    echo '
    <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css" />
    <script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="scripts/autocomplete.js"></script>';
		echo "Ort: <input class='form-control txt-auto' id='ort' value='".$row->ortname." (".$row->region.")'>";
      echo "<input type=hidden name='ort' id='ortid' value='".$row->ortid."'>";

		echo "Aktiv: <input type='checkbox' name='aktiv' ".($row->aktiv?"checked='checked'":"")."><br/>";

		echo "Gruendung <br>";
			echo "&nbsp;Gruendungstag: <input type='text' id='datepicker' name='gtag' value='".$row->gtag."'><br/>";
  		echo "&nbsp;Gruendungszeitraum: <input type='text' name='gzeitraum' value='".$row->gzeitraum."'><br/>";
		echo "Wahlspruch: <input type='text' name='wahlspruch' value='".$row->wahlspruch."'><br/>";
  	echo "Aufgegangen in: <input class='form-control txt-auto' id='nachfolger' value='".$row->fusion."'>";
      echo "<input name='nachfolgerid' id='nachfolgerid' value='".$row->fusionid."'>";

		echo "Verband: ".$row->verbandname."<br/><br/>";

    $band = get_band($row);
		echo "<table><tr>";
		foreach ($band as $farb) {
			echo "<td bgcolor=".$farb."></td>";
		}
		echo "</tr></table>";
		echo "<br/>";
		echo "</form>";
	}
?>

<script src="scripts/ajaxereignis.js"></script>

<table id=ereignisse class="table table-hover table-responsive">
<tbody>
  <tr class="active">
    <td>Text</td>
    <td>Ereignistyp</td>
    <td>Quelle</td>
    <td>Datum</td>
    <td>Jahr</td>
  </tr>

<?php
    $sql = "SELECT * FROM ereignis WHERE korporation=".$kid;
    $statement = $mysqli->prepare($sql);
    $statement->execute();

    $result = $statement->get_result();

    while($row = $result->fetch_object()) {
      echo "<tr><td>".$row->text."</td><td>".$row->type."</td><td>".$row->quelle."</td><td>".$row->datum."</td><td>".$row->jahr."</td></tr>";
    }
?>
  </tbody>
  <input type=text id="text" /><button id="add">Add</button>
  <input type=hidden id="kid" value=<?php echo $kid; ?>/>
</table>

<?php include 'footer.php'; ?>
