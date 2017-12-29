<?php

if (isset($_POST['name'])) {

  $aktiv = isset($_POST['aktiv']) ? "1" : "0";

  if ($_POST['kid']!=0) {

    $sql = "UPDATE korporation SET name='".check_string($_POST['name'])."', strasse='".check_string($_POST['strasse'])."', ort='".check_string($_POST['ort'])."', telefonnummer='".check_string($_POST['telefonnummer'])."', emailadresse='".check_string($_POST['emailadresse'])."', internetseite='".check_string($_POST['internetseite'])."', aktiv='".$aktiv."', gruendungstag='".check_string($_POST['gtag'])."', gruendungszeitraum='".check_string($_POST['gzeitraum'])."', wahlspruch='".check_string($_POST['wahlspruch'])."', aufgegangenin_id='".check_numeric($_POST['nachfolgerid'])."', aufgegangenin_text='".check_string($_POST['nachfolger'])."', verband='".check_numeric($_POST['verbandid'])."', editor='".check_numeric($_SESSION['userid'])."' WHERE id=".check_numeric($_POST['kid']);

    if ($mysqli->query($sql) === FALSE) {
        echo "Änderungen konnten nicht gespeichert werden." ;//. $mysqli->error;
    }
  } else {
    $sql = "INSERT INTO korporation (name, strasse, ort, telefonnummer, emailadresse, internetseite, aktiv, gruendungstag, gruendungszeitraum, wahlspruch, aufgegangenin_id, aufgegangenin_text, verband, editor) VALUES ('".check_string($_POST['name'])."', '".check_string($_POST['strasse'])."', '".check_string($_POST['ort'])."', '".check_string($_POST['telefonnummer'])."', '".check_string($_POST['emailadresse'])."', '".check_string($_POST['internetseite'])."', '".$aktiv."', '".check_string($_POST['gtag'])."', '".check_string($_POST['gzeitraum'])."', '".check_string($_POST['wahlspruch'])."', '".check_numeric($_POST['nachfolgerid'])."', '".check_string($_POST['nachfolger'])."', '".check_numeric($_POST['verbandid'])."', '".check_numeric($_SESSION['userid'])."')";

    if ($mysqli->query($sql) === FALSE) {
        echo "Neue Korporation konnte nicht angelegt werden.";//. $mysqli->error;
    } else {
      $kid = $mysqli->insert_id;
      header("Location: https://korpozoo.de/scc/details.php?kid=".$kid);
    }
  }

}

?>
