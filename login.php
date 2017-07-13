<?php
include 'settings.php';

header("Content-Type: text/html; charset=utf-8");
session_start();
$pdo = new PDO('mysql:host='.$logindbhost.';dbname='.$logindbname, $logindbuser, $logindbpassword);
$pdo->query("SET CHARACTER SET utf8");

if(isset($_GET['login'])) {
 $username = $_POST['username'];
 $passwort = $_POST['passwort'];

 $statement = $pdo->prepare("SELECT * FROM users WHERE username = ".$pdo->quote($username));
 $result = $statement->execute(array('username' => $username));
 $user = $statement->fetch();

 //Ueberprüfung des Passworts
 if ($user !== false && password_verify($passwort, $user['user_password'])) {
  $_SESSION['userid'] = $user['user_id'];
  $_SESSION['username'] = $user['username'];
  die('Login erfolgreich. Weiter zu <a href="index.php">internen Bereich</a>');
 } else {
  $errorMessage = "Die Kombination aus Benutzername und Passwort war ungültig<br>";
 }

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>

<?php
if(isset($errorMessage)) {
 echo $errorMessage;
}
?>

<form action="?login=1" method="post">
Benutzername:<br>
<input type="username" size="40" maxlength="250" name="username"><br><br>

Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>

<input type="submit" value="Anmelden">
</form>
</body>
</html>
