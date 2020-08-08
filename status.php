<?php
session_start();

function sende_kommando($_befehl,$_value)
{

  $fp=fsockopen($_SESSION['hostname'], $_SESSION['port']);

  // Sende Nachricht
  $nachricht = '!1' . $_befehl . $_value;
  $kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

  fwrite($fp, $kommando);

  fclose($fp);
}

if (array_key_exists('Track_back', $_POST)) {
  sende_kommando("NTC","TRDN");
}
if (array_key_exists('Track_next', $_POST)) {
  sende_kommando("NTC","TRUP");
}
if (array_key_exists('Pause', $_POST)) {
  sende_kommando("NTC","PAUSE");
}
if (array_key_exists('Play', $_POST)) {
  sende_kommando("NTC","PLAY");
}
if (array_key_exists('Level_up', $_POST)) {
  sende_kommando("MVL","UP");
}
if (array_key_exists('Level_down', $_POST)) {
  sende_kommando("MVL","DOWN");
}

if (array_key_exists('Ausschalten', $_POST)) {
  sende_kommando("PWR","00");
}

echo 'Aktionen:';
echo '<form method="post">';
echo '<input type="submit" name="Track_back" class="button" value="Track zurück" />';
echo '<input type="submit" name="Track_next" class="button" value="Track vor" />';
echo '<input type="submit" name="Pause" class="button" value="Pause" />';
echo '<input type="submit" name="Play" class="button" value="Play" />';
echo '<input type="submit" name="Level_up" class="button" value="Lauter" />';
echo '<input type="submit" name="Level_down" class="button" value="Leiser" />';

echo '<input type="submit" name="Ausschalten" class="button" value="Ausschalten" />';
echo '</form>';
?>
