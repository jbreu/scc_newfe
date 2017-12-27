<?php include 'header.php';?>

<script src="scripts/jquery-3.2.1.min.js"></script>
<script src="scripts/jquery.lazyloadxt.min.js"></script>

<?php
$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
      die('Connect failed: '.$mysqli->connect_error.'\n');
}

$sql = "SELECT DISTINCT(hauptbuchstabe) FROM zirkel ORDER BY hauptbuchstabe ASC";

$statement = $mysqli->prepare($sql);
$statement->execute();

$result = $statement->get_result();

while($row = $result->fetch_object()) {
  if (strcmp($row->hauptbuchstabe, "Sonderform")!=0) {
    echo "<a href=zirkel.php?main=".$row->hauptbuchstabe.">".$row->hauptbuchstabe."</a> | ";
  }
}

echo "<a href=zirkel.php?main=Sonderform>Sonderform</a>";
?>

<table class="table table-hover table-responsive">
<?php
  $hbs = "A";

  if (isset($_GET['main'])) {
    if (strcmp($_GET['main'], "Sonderform")==0) {
      $hbs = "Sonderform";
    } else {
      $hbs = $_GET['main'][0];
    }
  }

	$sql = "SELECT zirkel.zeichen as zeichen, zirkel.farbespezifisch as farbespezifisch, zirkel.dateiname as dateiname, zirkel.sccid as sccid, zirkel.eigenschaft as eigenschaft, filteredKorporation.id as kid, filteredKorporation.name as name FROM zirkel LEFT JOIN (SELECT id, sccid, name FROM korporation WHERE aufgegangenin_id=0 GROUP by sccid) as filteredKorporation ON zirkel.sccid=filteredKorporation.sccid WHERE zirkel.hauptbuchstabe='".$hbs."' ORDER BY zirkel.hauptbuchstabe,zirkel.zeichen,zirkel.eigenschaft";

	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

  $oldzeichen = "_";
  $oldeigenschaft = "_";
  echo "<tr>";

	while($row = $result->fetch_object()) {
    if (strcmp($oldzeichen, $row->zeichen) != 0) {
        $oldzeichen=$row->zeichen;
        $oldeigenschaft = "_";
        echo "</tr>";
        echo "<tr>";
        echo "<td><h1>".$row->zeichen."</h1></td>";
        echo "</tr>";
        echo "<tr>";
    }

    if (strcmp($oldeigenschaft, $row->eigenschaft) != 0) {
        $oldeigenschaft=$row->eigenschaft;
        echo "</tr>";
        echo "<tr>";
        echo "<td><h3>".$row->eigenschaft."</h3></td>";
    }

		echo "<td>";
    echo "<table>";
      echo "<tr><td><a href='files/zirkel/".$row->dateiname."' ><img data-src='files/zirkel/".$row->dateiname."' style='width:100px;height:100px;'></a></td></tr>";
      echo "<tr><td>".$row->farbespezifisch."</td></tr>";
      if ($row->kid!==NULL) {
        echo "<tr><td><a href=details.php?kid=".$row->kid.">".$row->name."</a></td></tr>";
      }else {
        echo "<tr><td>".$row->sccid."</td></tr>";
      }
    echo "</table>";
		echo "</td>";
	}

  echo "</tr>";
?>
</table>

<?php include 'footer.php'; ?>
