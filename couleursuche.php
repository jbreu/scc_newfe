<?php
include 'header.php';

$mysqli = new mysqli($sccdbhost, $sccdbuser, $sccdbpassword, $sccdbname);
$mysqli->set_charset("utf8");

/* check connection */
if ($mysqli->connect_errno) {
	die('Connect failed: '.$mysqli->connect_error.'\n');
}

$sqlcolor = "SELECT * FROM farbe";
$statement = $mysqli->prepare($sqlcolor);
$statement->execute();
$result = $statement->get_result();
$colors;

while ($row = $result->fetch_object()) {
	$colors[$row->id]=$row;
}

echo '<form action="table.php" method="get" width="300px">';

echo '<select name="farbe1">';

foreach ($colors as $color) {
       $hexbgcolor = sprintf("#%02x%02x%02x", $color->rot, $color->gruen, $color->blau);
       $hextxtcolor = sprintf("#%02x%02x%02x", 255-$color->rot, 255-$color->gruen, 255-$color->blau);
       echo '<option value="'.$color->id.'" style="background:'.$hexbgcolor.';color:'.$hextxtcolor.'">'.$color->name.'</option>';
}
echo '</select><br/>';
	
for ($i = 2; $i < 11; ++$i) {
	echo '<select name="farbe"'.$i.'>';

	foreach ($colors as $color) {
        $hexbgcolor = sprintf("#%02x%02x%02x", $color->rot, $color->gruen, $color->blau);
        $hextxtcolor = sprintf("#%02x%02x%02x", 255-$color->rot, 255-$color->gruen, 255-$color->blau);
        echo '<option value="'.$color->id.'" style="background:'.$hexbgcolor.';color:'.$hextxtcolor.'">'.$color->name.'</option>';
	}
	echo '</select><br/>';
}

echo '<div class="checkbox"><label><input type="checkbox" name="aktiv" checked="checked">Aktive Korporationen</label></div>';

echo '<input type="submit" value="Suchen">';

echo '</form>';

include 'footer.php';
?>
