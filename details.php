<?php
  include 'header.php';

	$kid=1;
	if (!isset($_GET['kid']) || !is_numeric($_GET["kid"]))
		die("Keine (valide) Korporation angegeben!");
	else
		$kid=$_GET["kid"];

  $mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
    		die('Connect failed: '.$mysqli->connect_error.'\n');
	}

  // Make changes if corresponding POST variables are set;
  include 'change.php';

	$sql = "SELECT korporation.name as name, korporation.sccid as sccid, ort.name as ortname, ort.region as region, korporation.aktiv as aktiv, korporation.gruendungstag as gtag, korporation.gruendungszeitraum as gzeitraum, korporation.wahlspruch as wahlspruch, korporation.aufgegangenin_text as fusion_text, korporation.aufgegangenin_id as fusion_id, verband.name as verbandname, band.farbe1 as farbe1, band.farbe2 as farbe2, band.farbe3 as farbe3, band.farbe4 as farbe4, band.farbe5 as farbe5, band.farbe6 as farbe6, band.farbe7 as farbe7, band.farbe8 as farbe8, band.farbe9 as farbe9, band.farbe10 as farbe10, band.id as bid FROM korporation LEFT JOIN ort ON korporation.ort=ort.id LEFT JOIN verband on korporation.verband=verband.id LEFT JOIN band on band.korporation=korporation.id WHERE korporation.id=".$kid;
	$statement = $mysqli->prepare($sql);
	$statement->execute();

	$result = $statement->get_result();

  $sccid=0;

	while($row = $result->fetch_object()) {
    // Zeige Folio-PDF (falls vorhanden)
    $filestr = 'files/folios/*'.strtolower(trim($row->sccid)).'.pdf';
    $list = glob($filestr);
    if (sizeof($list)==1) {
    echo '<div style="float:right"><object data="'.$list[0].'" type="application/pdf" width=600px height=400px>
            <embed src="'.$list[0].'" type="application/pdf" />
        </object></div>';
    }

		echo "<h1>".$row->name."</h1>";
    echo '<a href="edit.php?kid='.$kid.'" class="btn btn-info" role="button">Bearbeiten</a> <a class="btn btn-danger" role="button" onclick="return confirm_click();" href="https://korpozoo.de/scc/delete.php?kid='.$kid.'">Löschen</a><br/>';

    echo '<script>
      function confirm_click() {
          return confirm("Wollen Sie diese Korporation wirklich entfernen?\nEine Löschung kann nur vom Administrator rückgängig gemacht werden!");
      }
    </script>';

		echo "Ort: ".$row->ortname." (".$row->region.")<br/>";
  	echo "Aktiv: ".($row->aktiv?"Ja":"Nein")."<br/>";
  	echo "Gründung: ".$row->gtag."".$row->gzeitraum."<br/>";
  	echo "Wahlspruch: ".$row->wahlspruch."<br/>";

    $sccid = str_replace(" ", "", str_replace("-", "", $row->sccid));

    if (!empty($row->fusion_id)) {
      echo "Aufgegangen in: <a href='details.php?kid=".$row->fusion_id."'>".get_korporation($row->fusion_id)->name."</a><br/>";
    } else if (!empty($row->fusion_text)) {
      echo "Aufgegangen in: ".$row->fusion_text."<br/>";
    }

		echo "Verband: ".$row->verbandname."<br/><br/>";

    $band = get_band($row);
    if (!empty($band)) {
  		echo "<table style='display:inline-table'><tr>";
  		foreach ($band as $farb) {
  			echo "<td bgcolor=".$farb.">__</td>";
  		}
  		echo "</tr></table>";
      echo "   <a href='coloredit.php?kid=".$kid."&bid=".$row->bid."'>(Ändern)</a>";
    } else {
      echo "<a href='coloredit.php?kid=".$kid."&bid=0'>Farben hinzufügen</a>";
    }

		echo "<br/>";
	}

	$sql = "SELECT * FROM wappen WHERE wappen.korporation=".$kid;
  $statement = $mysqli->prepare($sql);
  $statement->execute();
  $statement->store_result();

  echo '<button class="accordion">Wappen ('.$statement->num_rows.')</button>
  <div class="panel">';

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

 echo '</div>';

 $sql = "SELECT * FROM zirkel WHERE sccid='".$sccid."'";
 $statement = $mysqli->prepare($sql);
 $statement->execute();
 $statement->store_result();

 echo '<button class="accordion">Zirkel ('.$statement->num_rows.')</button>
 <div class="panel">';

 $statement->execute();

 $result = $statement->get_result();

 while($row = $result->fetch_object()) {
     echo "Zirkel:<br><div style='padding-left:5em'>";
        echo "<img src='files/zirkel/".$row->dateiname."' style='width:100px;height:100px;'> ";
     echo "</div>";
}
echo '</div>';

?>

<?php
   $sql = "SELECT ereignis.datum as datum, ereignis.text as text, ereignis.jahr as jahr, ereignistyp.name as typname, ereignis.fremdeKorporation1 as fremdeKorporation1_id, korporationt1.name as fremdeKorporation1_name, ereignis.fremdeKorporation2 as fremdeKorporation2_id, korporationt2.name as fremdeKorporation2_name, ereignis.verband as verbandid, verband.name as verbandname, korporationstyp.name as ktypname, quelle.id as quelleid, quelle.kuerzel as quellekuerzel FROM ereignis LEFT JOIN ereignistyp ON ereignis.type=ereignistyp.id LEFT JOIN korporation as korporationt1 ON ereignis.fremdeKorporation1=korporationt1.id LEFT JOIN korporation as korporationt2 ON ereignis.fremdeKorporation2=korporationt2.id LEFT JOIN verband ON ereignis.verband=verband.id LEFT JOIN korporationstyp ON ereignis.korporationstyp=korporationstyp.id LEFT JOIN quelle ON ereignis.quelle=quelle.id WHERE ereignis.korporation=".$kid." ORDER BY datum";
   $statement = $mysqli->prepare($sql);
   $statement->execute();
   $statement->store_result();

   echo '<button class="accordion">Ereignisse ('.$statement->num_rows.')</button>
   <div class="panel">';
?>

<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css" />
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="scripts/autocomplete.js"></script>
<script src="scripts/ajaxereignis.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<script src="scripts/moment-with-locales.min.js"></script>
<script src="scripts/bootstrap-datetimepicker.min.js"></script>

<table id=ereignisse class="table table-hover table-responsive">
<tbody>
 <tr class="active">
   <th>Zeitraum</th>
   <th>Details</th>
   <th>Quelle</th>
 </tr>

<?php
   $statement->execute();

   $result = $statement->get_result();

   while($row = $result->fetch_object()) {
     echo "<tr><td>";
     if (!empty($row->datum) && $row->datum!="0000-00-00") {
       echo $row->datum;
     } else {
      echo  $row->jahr;
     }
     echo "</td><td>".$row->typname.": ";

     $felder = array();

     if (!empty($row->text)) {
       $felder[] = $row->text;
     }

     if (!empty($row->fremdeKorporation1_id)) {
       $felder[]= "<a href='details.php?kid=".$row->fremdeKorporation1_id."'>".$row->fremdeKorporation1_name."</a>";
     }

     if (!empty($row->fremdeKorporation2_id)) {
       $felder[]= "<a href='details.php?kid=".$row->fremdeKorporation2_id."'>".$row->fremdeKorporation2_name."</a>";
     }

     if (!empty($row->verbandname)) {
       $felder[]= $row->verbandname;
     }

     if (!empty($row->ktypname)) {
       $felder[]= $row->ktypname;
     }

     echo implode(", ", $felder);

     if (!empty($row->quellekuerzel) && !empty($row->quelleid)) {
       echo "</td><td><a href='quellen.php#anker".$row->quelleid."'>".$row->quellekuerzel."</a></td></tr>";
     } else {
       echo "</td><td></td></tr>";
     }
   }
?>
 </tbody>
 <script type="text/javascript">
   var fields = <?php echo json_encode(get_ereignistypen()); ?>;
   function changedEreignis(control) {
     if (fields[control.value-1].hatKorporation1==1) {
       document.getElementById("divkid1").style.display="block";
     } else {
       document.getElementById("divkid1").style.display="none";
     }
     if (fields[control.value-1].hatKorporation2==1) {
       document.getElementById("divkid2").style.display="block";
     } else {
       document.getElementById("divkid2").style.display="none";
     }
     if (fields[control.value-1].hatVerband==1) {
       document.getElementById("divverband").style.display="block";
     } else {
       document.getElementById("divverband").style.display="none";
     }
     if (fields[control.value-1].hatTyp==1) {
       document.getElementById("divktyp").style.display="block";
     } else {
       document.getElementById("divktyp").style.display="none";
     }
   }
 </script>
</table>
<button class="btn btn-primary" onclick="toggler('editevents');">Ereignisse: Bearbeitungsmodus ein/ausschalten</button>
<div class="form-group row" style="display:none" id="editevents">
 <div class="col-xs-2">
   <label for="ereignistyp">Ereignistyp</label>
   <select class="form-control" id="ereignistyp" onchange="changedEreignis(this)">
     <?php
       foreach (get_ereignistypen() as $et) {
         echo '<option value="'.$et->id.'">'.$et->name.'</option>';
       }
     ?>
   </select>
 </div>
 <div class="col-xs-2">
   <label for="evgtagpicker">Datum (optional)</label>
   <div class="input-group date" id="evgtagpicker">
      <input type="text" class="form-control" id="evgtag" name="evgtag">
      <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
      </span>
  </div>
  <script type='text/javascript'>
     $(function () {
         $('#evgtagpicker').datetimepicker({
           format: 'YYYY-MM-DD'
         });
     });
 </script>
 </div>
 <div class="col-xs-2">
   <label for="evgzeitraum">Jahr (falls Datum unbek., opt.)</label>
   <input class='form-control' id='evgzeitraum' name='evgzeitraum'>
 </div>
 <div class="col-xs-2">
   <label for="text">Text (optional)</label>
   <input type=text class="form-control" id="text" />
 </div>
 <div class="col-xs-2" style='display:none' id='divkid1'>
   <label for="kid1">Korporation (optional)</label>
   <input class='form-control txt-auto' id='kid1'>
   <input type=hidden name='kid1id' id='kid1id'>
 </div>
 <div class="col-xs-2" style='display:none' id='divkid2'>
   <label for="kid2">Weitere Korporation (optional)</label>
   <input class='form-control txt-auto' id='kid2'>
   <input type=hidden name='kid2id' id='kid2id'>
 </div>
 <div class="col-xs-2" style='display:none' id='divverband'>
   <label for="evverband">Verband (optional)</label>
   <input class='form-control txt-auto' id='evverband'>
   <input type=hidden name='evverbandvid' id='evverbandvid'>
 </div>
 <div class="col-xs-2" style='display:none' id='divktyp'>
   <label for="ktyp">Korporationstyp (optional)</label>
   <input class='form-control txt-auto' id='ktyp'>
   <input type=hidden name='ktypid' id='ktypid'>
 </div>
 <div class="col-xs-2">
   <label for="quelle">Quelle (optional)</label>
   <input class='form-control txt-auto' id='quelle'>
   <input type=hidden name='quelleid' id='quelleid'>
 </div>
 <div class="col-xs-2">
   <label for="add">Abschließen</label>
   <input type=hidden name='kid' id='kid' value='<?php echo $kid; ?>'>
   <input type=hidden name='editor' id='editor' value='<?php echo $_SESSION['userid'];?>' />
   <button class="btn btn-primary" id="add">Ereignis hinzufügen</button>
 </div>
</div>
</div>

<?php
if ($kid!=0) {

   $sql = "SELECT name FROM korporation WHERE korporation.aufgegangenin_id=".$kid;

   $statement = $mysqli->prepare($sql);
   $statement->execute();

   $statement->store_result();
   $count = $statement->num_rows;

   echo '<button class="accordion">Vorgänger ('.$count.')</button>
   <div class="panel">';

   $statement->execute();

   $result = $statement->get_result();

   if($count>0) {

     echo 'In dieser Korporation aufgegangen:<br />

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

    echo "</table>";
  }
}
echo '</div>';
?>


<?php include 'footer.php'; ?>
