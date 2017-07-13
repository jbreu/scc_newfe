<?php
include 'settings.php';

header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['userid'])) {
 die('Bitte zuerst <a href="login.php">einloggen</a>');
}

//Abfrage der Nutzer ID vom Login
$username = $_SESSION['username'];

echo "Angemeldet als ".$username;

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/default.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
		$( function() {
    			$( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
  		} );
 	 </script>
</head>

<a href=logout.php>Abmelden</a> <a href=index.php>Startseite</a>
<hr />
