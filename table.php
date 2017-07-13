<?php include 'header.php';?>

<table id="findex">
	<script src="script.js"></script>
	<tr class="header">
    		<th>Name<br/><input type="text" id="filterName" onkeyup="filter()" placeholder="Filter..."></th>
    		<th>Ort<br/><input type="text" id="filterOrt" onkeyup="filter()" placeholder="Filter..."></th>
		<!--<th>Region</th>-->
<?php
	if (isset($_GET['aktiv']) && strcmp($_GET["aktiv"],"on")==0)
		echo "<th>Aktiv?<br/><input type=\"checkbox\" checked=\"checked\" onclick=\"window.location.assign('table.php?aktiv=off')\"/></th>";
	else
		echo "<th>Aktiv?<br/><input type=\"checkbox\" onclick=\"window.location.assign('table.php?aktiv=on')\"/></th>";
?>
		<th>Gründung</th>
		<th>Wahlspruch</th>
		<th>Aufgegangen in</th>
		<th>Verband</th>
		<th>Farben</th>
  	</tr>
<?php
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

	$sql = "SELECT korporation.id as kid, korporation.name as name, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.aktiv=".((isset($_GET['aktiv']) && strcmp($_GET["aktiv"],"on")==0)?"1":"0");

	if (isset($_GET['farbe1']) && isset($_GET['farbe2']) && isset($_GET['farbe3']) &&
		is_numeric($_GET['farbe1']) && is_numeric($_GET['farbe2']) && is_numeric($_GET['farbe3'])) {
		$sql = $sql." AND band.farbe1=".$_GET['farbe1']." AND band.farbe2=".$_GET['farbe2']." AND band.farbe3=".$_GET['farbe3'];
	}

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

		echo "<tr class='eintrag'>";
		echo "<td><a href=details.php?kid=".$row->kid.">".$row->name."</a></td>";
		echo "<td>".$row->ortname."</td>";
		//echo "<td>".$row->region."</td>";
        echo "<td>".($row->aktiv?"Ja":"Nein")."</td>";
        echo "<td>".$row->gtag."".$row->gzeitraum."</td>";
        echo "<td>".$row->wahlspruch."</td>";
        echo "<td>".$row->fusion."</td>";
		echo "<td>".$row->verbandname."</td>";

		echo "<td><table><tr>";
		foreach ($band as $farb) {
			echo "<td bgcolor=".$farb."></td>";
		}
		echo "</tr></td></table>";
		echo "</tr>";
	}
?>
</table>

<?php include 'footer.php'; ?>
