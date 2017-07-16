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

	$sqlcolor = "SELECT * FROM farbe";
	$statement = $mysqli->prepare($sqlcolor);
	$statement->execute();
	$result = $statement->get_result();
	$colors;

	while ($row = $result->fetch_object()) {
		$colors[$row->id]=$row;
	}

	$sql = "SELECT korporation.name as name, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.id=".$kid;
	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

	while($row = $result->fetch_object()) {
		$band=array();
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

		echo "<form action='details.php' method='post'>";
		echo "<h1>Name: <input type='text' name='name' value='".$row->name."'></h1><br/>";

		echo "Ort: <input type='text' name='ort' list='orte' value='".$row->ortname." (".$row->region.")'>";
		echo "<label for='orte'><datalist id='orte'><select>";
			echo "<option value='1'>Stuttgart</option>";
			echo "<option value='2'>Cham</option>";
		echo "</select></datalist></label><br>";

		echo "Aktiv: <input type='checkbox' name='aktiv' ".($row->aktiv?"checked='checked'":"")."><br/>";

		echo "Gruendung <br>";
			echo "&nbsp;Gruendungstag: <input type='text' id='datepicker' name='gtag' value='".$row->gtag."'><br/>";
        		echo "&nbsp;Gruendungszeitraum: <input type='text' name='gzeitraum' value='".$row->gzeitraum."'><br/>";
		echo "Wahlspruch: <input type='text' name='wahlspruch' value='".$row->wahlspruch."'><br/>";
        	echo "Aufgegangen in: ".$row->fusion."<br/>";
		echo "Verband: ".$row->verbandname."<br/><br/>";

		echo "<table id='findex'><tr>";
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

    while($row = $result->fetch_assoc()) {
      echo "<tr><td>".$row['text']."</td><td>".$row['type']."</td><td>".$row['quelle']."</td><td>".$row['datum']."</td><td>".$row['jahr']."</td></tr>";
    }
?>
  </tbody>
  <input type=text id="text" /><button id="add">Add</button>
  <input type=hidden id="kid" value=<?php echo $kid; ?>/>
</table>

<?php include 'footer.php'; ?>
