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

if (isset($_POST['bid']) && is_numeric($_POST['bid'])) {
  if ($_POST['bid']!=0) {

    if ($_POST['farbe1']=='NULL') {
      if (delete_from_table("band", $_POST['bid'], check_numeric($_SESSION['userid'])) === FALSE) {
        echo "Farben konnten nicht gelöscht werden.";
      } else {
        echo "Farben wurden gelöscht.";
      }
    } else {

      $sql = "UPDATE band SET typ='1', farbe1='".check_numeric($_POST['farbe1'])."', farbe2='".check_numeric($_POST['farbe2'])."', farbe3='".check_numeric($_POST['farbe3'])."', farbe4='".check_numeric($_POST['farbe4'])."', farbe5='".check_numeric($_POST['farbe5'])."', farbe6='".check_numeric($_POST['farbe6'])."', farbe7='".check_numeric($_POST['farbe7'])."', farbe8='".check_numeric($_POST['farbe8'])."', farbe9='".check_numeric($_POST['farbe9'])."', farbe10='".check_numeric($_POST['farbe10'])."', korporation='".check_numeric($_POST['kid'])."', editor='".check_numeric($_SESSION['userid'])."' WHERE id=".check_numeric($_POST['bid']);

      if ($mysqli->query($sql) === FALSE) {
          echo "Änderungen konnten nicht gespeichert werden." ;//. $mysqli->error;
      }
    }
  } else {
    $sql = "INSERT INTO band (typ, farbe1, farbe2, farbe3, farbe4, farbe5, farbe6, farbe7, farbe8, farbe9, farbe10, korporation, vonunten, unsicher, editor) VALUES (1, '".check_numeric($_POST['farbe1'])."', '".check_numeric($_POST['farbe2'])."', '".check_numeric($_POST['farbe3'])."', '".check_numeric($_POST['farbe4'])."', '".check_numeric($_POST['farbe5'])."', '".check_numeric($_POST['farbe6'])."', '".check_numeric($_POST['farbe7'])."', '".check_numeric($_POST['farbe8'])."', '".check_numeric($_POST['farbe9'])."', '".check_numeric($_POST['farbe10'])."', '".check_numeric($_POST['kid'])."', 0, 0, '".check_numeric($_SESSION['userid'])."')";

    if ($mysqli->query($sql) === FALSE) {
        echo "Neue Farben konnten nicht angelegt werden.". $mysqli->error;
    }
  }
}

?>
