<?php

if (isset($_POST['name'])) {

  $aktiv = isset($_POST['aktiv']) ? "1" : "0";

  $sql = "UPDATE korporation SET name='".$_POST['name']."', strasse='".$_POST['strasse']."', telefonnummer='".$_POST['telefonnummer']."', emailadresse='".$_POST['emailadresse']."', internetseite='".$_POST['internetseite']."', aktiv='".$aktiv."', gruendungstag='".$_POST['gtag']."', gruendungszeitraum='".$_POST['gzeitraum']."', wahlspruch='".$_POST['wahlspruch']."', aufgegangenin_id='".$_POST['nachfolgerid']."', verband='".$_POST['verbandid']."', editor='".$_SESSION['userid']."' WHERE id=".$_POST['kid'];

  if ($mysqli->query($sql) === FALSE) {
      echo "Änderungen konnten nicht gespeichert werden." ;//. $mysqli->error;
  }

}

?>
