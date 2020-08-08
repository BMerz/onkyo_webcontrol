<?php
session_start();

if (array_key_exists('hostname', $_POST)) {
 $_SESSION['hostname'] = $_POST["hostname"];
 $_SESSION['port'] = 60128;
}

if (array_key_exists('hostname', $_SESSION)) {
 echo 'Gewählter Verstärker: '.$_SESSION['hostname'];
}else{
 echo 'Kein Verstärker gewählt!';
}

echo '<form method="post">';
echo '<label for="IP">IP:</label><input id="IP" name="hostname" size="15" maxlength="15" value="192.168.0.120">';
echo '<input type="submit" name="startconnection" class="button" value="Wählen" />';
echo '</form>';

echo '<form action="dlna2.php" method="post">';
echo '<input type="submit" name="next" class="button" value="Weiter" />';
echo '</form>';
?>

