<?php

if (isset($_POST['name'])) {

  $aktiv = isset($_POST['aktiv']) ? "1" : "0";

  if ($_POST['kid']!=0) {

    $sql = "UPDATE korporation SET name='".$_POST['name']."', strasse='".$_POST['strasse']."', ort='".$_POST['ort']."', telefonnummer='".$_POST['telefonnummer']."', emailadresse='".$_POST['emailadresse']."', internetseite='".$_POST['internetseite']."', aktiv='".$aktiv."', gruendungstag='".$_POST['gtag']."', gruendungszeitraum='".$_POST['gzeitraum']."', wahlspruch='".$_POST['wahlspruch']."', aufgegangenin_id='".$_POST['nachfolgerid']."', aufgegangenin_text='".$_POST['nachfolger']."', verband='".$_POST['verbandid']."', editor='".$_SESSION['userid']."' WHERE id=".$_POST['kid'];

    if ($mysqli->query($sql) === FALSE) {
        echo "Änderungen konnten nicht gespeichert werden." ;//. $mysqli->error;
    }
  } else {
    $sql = "INSERT INTO korporation (name, strasse, ort, telefonnummer, emailadresse, internetseite, aktiv, gruendungstag, gruendungszeitraum, wahlspruch, aufgegangenin_id, aufgegangenin_text, verband, editor) VALUES ('".$_POST['name']."', '".$_POST['strasse']."', '".$_POST['ort']."', '".$_POST['telefonnummer']."', '".$_POST['emailadresse']."', '".$_POST['internetseite']."', '".$aktiv."', '".$_POST['gtag']."', '".$_POST['gzeitraum']."', '".$_POST['wahlspruch']."', '".$_POST['nachfolgerid']."', '".$_POST['nachfolger']."', '".$_POST['verbandid']."', '".$_SESSION['userid']."')";

    if ($mysqli->query($sql) === FALSE) {
        echo "Neue Korporation konnte nicht angelegt werden.";//. $mysqli->error;
    } else {
      $kid = $mysqli->insert_id;
      header("Location: https://korpozoo.de/scc/details.php?kid=".$kid);
    }
  }

}

?>
