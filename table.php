<?php include 'header.php';?>

<a href="edit.php?kid=0" class="btn btn-success" role="button">Neue Korporation anlegen</a>
<a onclick="filter()" id="filterBtn" class="btn btn-success" role="button">Filter anwenden</a><br/><br/>

<table id=findex class="table table-hover table-responsive">
	<script src="scripts/filter.js"></script>
	<tr class="active">
		<th>Name<br/><input type="text" id="filterName" placeholder="Filter..."></th>
		<th>Ort<br/><input type="text" id="filterOrt" placeholder="Filter..."></th>

<?php
	if (isset($_GET['aktiv']) && strcmp($_GET["aktiv"],"on")==0)
		echo "<th>Aktiv?<br/><input type=\"checkbox\" checked=\"checked\" onclick=\"window.location.assign('table.php?aktiv=off')\"/></th>";
	else
		echo "<th>Aktiv?<br/><input type=\"checkbox\" onclick=\"window.location.assign('table.php?aktiv=on')\"/></th>";
?>
		<th>Gründung</th>
		<th>Wahlspruch<br/><input type="text" id="filterWahlspruch" placeholder="Filter..."></th>
		<th>Aufgegangen in</th>
		<!--<th>Verband</th>-->
		<!--<th>Region</th>-->
		<th>Farben</th>
  	</tr>
<?php
	$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
    		die('Connect failed: '.$mysqli->connect_error.'\n');
	}

	$sql = "SELECT korporation.id as kid, korporation.name as name, ort.id as oid, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5, band.farbe6 as farbe6, band.farbe7 as farbe7, band.farbe8 as farbe8, band.farbe9 as farbe9, band.farbe10 as farbe10 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.aktiv=".((isset($_GET['aktiv']) && strcmp($_GET["aktiv"],"on")==0)?"1":"0");

	if (isset($_GET['farbe1']) && isset($_GET['farbe2']) && isset($_GET['farbe3']) &&
		is_numeric($_GET['farbe1']) && is_numeric($_GET['farbe2']) && is_numeric($_GET['farbe3'])) {
		$sql = $sql." AND band.farbe1=".$_GET['farbe1']." AND band.farbe2=".$_GET['farbe2']." AND band.farbe3=".$_GET['farbe3'];
	}

	if (isset($_GET['oid']) && is_numeric($_GET['oid'])) {
		$sql = $sql." AND korporation.ort=".$_GET['oid'];
	}

	$sql = $sql." ORDER BY name";

	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

	while($row = $result->fetch_object()) {
		echo "<tr class='eintrag'>";
		echo "<td><a href=details.php?kid=".$row->kid.">".$row->name."</a></td>";
		echo "<td><a href=table.php?aktiv=on&oid=".$row->oid.">".$row->ortname."</a></td>";
		//echo "<td>".$row->region."</td>";
    echo "<td>".($row->aktiv?"Ja":"Nein")."</td>";
    echo "<td>".$row->gtag."".$row->gzeitraum."</td>";
    echo "<td>".$row->wahlspruch."</td>";
    echo "<td>".$row->fusion."</td>";
		//echo "<td>".$row->verbandname."</td>";

		$band = get_band($row);
		echo "<td><table><tr>";
		foreach ($band as $farb) {
			echo "<div style='min-width:100px;''><td bgcolor=".$farb.">__</td></div>";
		}
		echo "</tr></td></table>";
		echo "</tr>";
	}
?>
</table>

<?php include 'footer.php'; ?>
