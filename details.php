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

	$sql = "SELECT korporation.name as name, korporation.sccid as sccid, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion_text, korporation.aufgegangenin_id as fusion_id, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.id=".$kid;
	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

	while($row = $result->fetch_object()) {
    // Zeige Folio-PDF (falls vorhanden)
    $filestr = 'files/*'.strtolower(trim($row->sccid)).'.pdf';
    $list = glob($filestr);
    if (sizeof($list)==1) {
    echo '<div style="float:right"><object data="'.$list[0].'" type="application/pdf" width=600px height=400px>
            <embed src="'.$list[0].'" type="application/pdf" />
        </object></div>';
    }

		echo "<h1>".$row->name."</h1><br/>";
		echo "Ort: ".$row->ortname." (".$row->region.")<br/>";
        	echo "Aktiv: ".($row->aktiv?"Ja":"Nein")."<br/>";
        	echo "Gründung: ".$row->gtag."".$row->gzeitraum."<br/>";
        	echo "Wahlspruch: ".$row->wahlspruch."<br/>";
        	echo "Aufgegangen in: ".$row->fusion."<br/>";
		echo "Verband: ".$row->verbandname."<br/><br/>";

    $band = get_band($row);
		echo "<table><tr>";
		foreach ($band as $farb) {
			echo "<td bgcolor=".$farb.">__</td>";
		}
		echo "</tr></table>";
		echo "<br/>";
	}

	$sql = "SELECT * FROM wappen WHERE wappen.korporation=".$kid;
        $statement = $mysqli->prepare($sql);
        $statement->execute();

        $result = $statement->get_result();

        while($row = $result->fetch_object()) {
		        echo "Wappen:<br><div style='padding-left:5em'>";
        			if (!empty($row->helmzier)) echo "Helmzier: ".$row->helmzier."<br>";
        			if (!empty($row->felder)) echo "Felder: ".$row->felder."<br>";
             	if (!empty($row->ungeteilt)) echo "Ungeteilt: ".$row->ungeteilt."<br>";
              if (!empty($row->rechts)) echo "Rechts: ".$row->rechts."<br>";
             	if (!empty($row->oben)) echo "Oben: ".$row->oben."<br>";
             	if (!empty($row->oben_rechts)) echo "Oben rechts: ".$row->oben_rechts."<br>";
             	if (!empty($row->oben_links)) echo "Oben links: ".$row->oben_links."<br>";
             	if (!empty($row->mittelbalken)) echo "Mittelbalken: ".$row->mittelbalken."<br>";
             	if (!empty($row->herzschild)) echo "Herzschild: ".$row->herzschild."<br>";
             	if (!empty($row->vierung)) echo "Vierung: ".$row->vierung."<br>";
              if (!empty($row->unten_links)) echo "Unten links: ".$row->unten_links."<br>";
              if (!empty($row->unten_rechts)) echo "Unten rechts: ".$row->unten_rechts."<br>";
              if (!empty($row->unten)) echo "Unten: ".$row->unten."<br>";
              if (!empty($row->links)) echo "Links: ".$row->links."<br>";
              if (!empty($row->rand)) echo "Rand: ".$row->rand."<br>";
            echo "</div>";
	     }

       $sql = "SELECT name FROM korporation WHERE korporation.aufgegangenin_id=".$kid;

       $statement = $mysqli->prepare($sql);
       $statement->execute();

       $statement->store_result();
       $count = $statement->num_rows;

       if($count>0) {

         echo '<br />
            In dieser Korporation aufgegangen:<br />

            <table  class="table table-hover table-responsive">
            	<tr class="active">
            		<th>Name</th>
            		<th>Ort</th>
                <th>Aktiv</th>
            		<!--<th>Region</th>-->
            		<th>Gründung</th>
            		<th>Wahlspruch</th>
            		<!--<th>Aufgegangen in</th>-->
            		<!--<th>Verband</th>-->
            		<th>Farben</th>
              	</tr>
          ';


  	$sql = "SELECT korporation.id as kid, korporation.name as name, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5 FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.aufgegangenin_id=".$kid;

  	$statement = $mysqli->prepare($sql);
  	$statement->execute();

  	$result = $statement->get_result();

  	while($row = $result->fetch_object()) {
  		echo "<tr class='eintrag'>";
  		echo "<td><a href=details.php?kid=".$row->kid.">".$row->name."</a></td>";
  		echo "<td>".$row->ortname."</td>";
  		//echo "<td>".$row->region."</td>";
      echo "<td>".($row->aktiv?"Ja":"Nein")."</td>";
      echo "<td>".$row->gtag."".$row->gzeitraum."</td>";
      echo "<td>".$row->wahlspruch."</td>";
      //echo "<td>".$row->fusion."</td>";
  		//echo "<td>".$row->verbandname."</td>";

      $band = get_band($row);
  		echo "<td><table><tr>";
  		foreach ($band as $farb) {
  			echo "<td bgcolor=".$farb.">__</td>";
  		}
  		echo "</tr></td></table>";
  		echo "</tr>";
  	}
  }
  ?>
  </table>

  <?php include 'footer.php'; ?>
