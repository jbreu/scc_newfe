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
		echo "<form action='details.php' method='post'>";
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
  	echo "Aufgegangen in: <input class='form-control txt-auto' id='nachfolger' value='".$row->fusion."'>";
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
		echo "</form>";

    $filestr = 'files/*'.strtolower(trim($row->sccid)).'.pdf';
    $list = glob($filestr);
    if (sizeof($list)==1) {
      echo '<a target="_blank" href="'.$list[0].'">Folio in neuem Tab öffnen</a>';
    }
	}
?>

<script src="scripts/ajaxereignis.js"></script>

<table id=ereignisse class="table table-hover table-responsive">
<tbody>
  <tr class="active">
    <th>Zeitraum</th>
    <th>Details</th>
    <th>Quelle</th>
  </tr>

<?php
    $sql = "SELECT ereignis.datum as datum, ereignis.text as text, ereignis.jahr as jahr, ereignistyp.name as typname, ereignis.fremdeKorporation1 as fremdeKorporation1_id, korporationt1.name as fremdeKorporation1_name, ereignis.fremdeKorporation2 as fremdeKorporation2_id, korporationt2.name as fremdeKorporation2_name, ereignis.verband as verbandid, verband.name as verbandname, korporationstyp.name as ktypname, quelle.id as quelleid, quelle.kuerzel as quellekuerzel FROM ereignis LEFT JOIN ereignistyp ON ereignis.type=ereignistyp.id LEFT JOIN korporation as korporationt1 ON ereignis.fremdeKorporation1=korporationt1.id LEFT JOIN korporation as korporationt2 ON ereignis.fremdeKorporation2=korporationt2.id LEFT JOIN verband ON ereignis.verband=verband.id LEFT JOIN korporationstyp ON ereignis.korporationstyp=korporationstyp.id LEFT JOIN quelle ON ereignis.quelle=quelle.id WHERE ereignis.korporation=".$kid;
    $statement = $mysqli->prepare($sql);
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
  <div class="form-group row">
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
      <label for="evgzeitraum">Jahr (falls Datum unbekannt, optional)</label>
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
      <input type=hidden name='kid' id='kid' value='<?php echo $kid; ?>'>
      <button class="btn btn-primary" id="add">Ereignis hinzufügen</button>
    </div>
  </div>
</table>

<?php include 'footer.php'; ?>
