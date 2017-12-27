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

<link href="bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="scripts/login.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-md-offset-5 col-md-3">
            <div class="form-login">
            <form action="?login=1" method="post">
            <h4>Willkommen beim SCC</h4>
            <input type="text" id="userName" name="username" class="form-control input-sm chat-input" placeholder="Benutzername" />
            </br>
            <input type="password" id="userPassword" name="passwort" class="form-control input-sm chat-input" placeholder="Passwort" />
            </br>
            <div class="wrapper">
            <span class="group-btn">
                  <input type="submit" class="btn btn-primary btn-md" value="Anmelden" />
                <!--<a href="#" class="btn btn-primary btn-md">Anmelden <i class="fa fa-sign-in"></i></a>-->
            </span>
            </form>
            </div>
            </div>
            Die Zugangsdaten sind die gleichen wie bei <a href=korpozoo.de>Korpozoo.de</a>. Falls Sie Ihre Zugangsdaten vergessen haben, verwenden Sie bitte diesen <a href=https://korpozoo.de/ucp.php?mode=sendpassword>Link</a>.
        </div>
    </div>
</div>

</body>
</html>
