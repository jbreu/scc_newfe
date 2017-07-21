<?php
  include 'header.php';
?>

<!--<div class="input-group">
    <span class="input-group-addon">Filter</span>
    <input id="filter" type="text" class="form-control" onkeyup="filter()" placeholder="Filtertext...">
</div><br />-->

Häufig verwendete Abkürzungen:<br />
<ul>
  <li>*FDR: Fliehkraft-Drehzahl-Regler</li>
  <li>*ttW: tech Geräte in Reihenfolge (Dreieck Zirkel Rad Lineal *FDR)  </li>
  <li>wp westfalen (!): Niedersachsenross</li>
  <li>wp württemberg [L]: 3 Löwen</li>
  <li>wp württemberg [G]: 3 Geweihstangen</li>
  <li>Logo Eisenbahntechnik: Rad geflügelt (Blitze)</li>
</ul>

<table id="wtable" class="table table-hover table-responsive">
	<script src="scripts/qfilter.js"></script>
	<tr class="active">
		<th>Korporation<!--<br/><input type="text" id="filterName" onkeyup="filter()" placeholder="Filter...">--></th>
		<th>Helmzier<!--<br/><input type="text" id="filterOrt" onkeyup="filter()" placeholder="Filter...">--></th>
		<th>Felder</th>
		<th>Ungeteilt</th>
		<th>Rechts</th>
    <th>Oben</th>
    <th>Oben Rechts</th>
    <th>Oben Links</th>
    <th>Mittelbalken</th>
    <th>Herzschild</th>
    <th>Vierung</th>
    <th>Unten Links</th>
    <th>Unten Rechts</th>
    <th>Unten</th>
    <th>Links</th>
    <th>Rand</th>
	</tr>

<?php
$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
      die('Connect failed: '.$mysqli->connect_error.'\n');
}

$sql = "SELECT * FROM wappen";

$statement = $mysqli->prepare($sql);
$statement->execute();

$result = $statement->get_result();

while($row = $result->fetch_object()) {
  echo "<tr id='anker".$row->id."'>";
  if (empty($row->korporation)) {
    echo "<td>".$row->korporation_name."</td>";
  } else {
    echo "<td><a href='details.php?kid=".$row->korporation."'>".$row->korporation_name."</a></td>";
  }
  echo "<td>".$row->helmzier."</td>";
  echo "<td>".$row->felder."</td>";
  echo "<td>".$row->ungeteilt."</td>";
  echo "<td>".$row->rechts."</td>";
  echo "<td>".$row->oben."</td>";
  echo "<td>".$row->oben_rechts."</td>";
  echo "<td>".$row->oben_links."</td>";
  echo "<td>".$row->mittelbalken."</td>";
  echo "<td>".$row->herzschild."</td>";
  echo "<td>".$row->vierung."</td>";
  echo "<td>".$row->unten_links."</td>";
  echo "<td>".$row->unten_rechts."</td>";
  echo "<td>".$row->unten."</td>";
  echo "<td>".$row->links."</td>";
  echo "<td>".$row->rand."</td>";
  echo "</tr>";
}
?>

</table>
