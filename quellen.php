<?php
  include 'header.php';
?>

<div class="input-group">
    <span class="input-group-addon">Filter</span>
    <input id="filter" type="text" class="form-control" onkeyup="filter()" placeholder="Filtertext...">
</div><br />

<table id="qtable" class="table table-hover table-responsive">
	<script src="scripts/qfilter.js"></script>
	<tr class="active">
		<th>Kürzel<!--<br/><input type="text" id="filterName" onkeyup="filter()" placeholder="Filter...">--></th>
		<th>Titel<!--<br/><input type="text" id="filterOrt" onkeyup="filter()" placeholder="Filter...">--></th>
		<th>Autor</th>
		<th>Jahrgang</th>
		<th>Bereich</th>
	</tr>

<?php
$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
      die('Connect failed: '.$mysqli->connect_error.'\n');
}

$sql = "SELECT * FROM quelle";

$statement = $mysqli->prepare($sql);
$statement->execute();

$result = $statement->get_result();

while($row = $result->fetch_object()) {
  echo "<tr id='anker".$row->id."'>";
  echo "<td>".$row->kuerzel."</td>";
  echo "<td>".$row->titel."</td>";
  echo "<td>".$row->autor."</td>";
  echo "<td>".$row->jahrgang."</td>";
  echo "<td>".$row->bereich."</td>";
  echo "</tr>";
}
?>

</table>
