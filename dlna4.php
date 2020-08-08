<?php
session_start();

$fp=fsockopen($_SESSION['hostname'], $_SESSION['port']);

if (array_key_exists('DLNA_Server', $_POST)){
 // Sende Nachricht
 $nachricht = '!1' . "NLSL" . $_POST["DLNA_Server"];
 $kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

 fwrite($fp, $kommando);
}

if (array_key_exists('xml_Eintrag', $_POST)){
 $nachricht = '!1' . "NLSI" . sprintf("%'.05d\n", $_POST["xml_Eintrag"]+1);
 $kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

 fwrite($fp, $kommando);
 echo 'XML-Eintrag '.$nachricht .' gewählt.<br>';
}

if (array_key_exists('zurueck', $_POST)){
 $nachricht = '!1' . "NTCRETURN";
 $kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

 fwrite($fp, $kommando);
 echo 'Zurück gewählt<br>';
}

if (!array_key_exists('Ebene', $_POST)){
 $Ebene = 1;
}else{
 $Ebene = $_POST["Ebene"];
}

echo "Aktuelle Ebene: " .$Ebene. "!<br>";

sleep(1);
// Sende Nachricht
$nachricht = '!1' . "NLAL00010" . $Ebene . "000000FF";
$kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

fwrite($fp, $kommando);

$raw = '';
$input = '';
$response = '';
$xmlstr = '';
$position = '';

// Lese Antwort
$oldtime = time();
stream_set_timeout($fp,10);
while (($xmlstr == '') and (time()-$oldtime)<5) {
  $raw = '';
  $input = '';
  $oldtime2 = time();
  while ((ord($input) != 0x1A) and ((time()-$oldtime2)<5)) {
    $input = fread($fp, 1);
    $raw .= $input;
  }
  $beginn = strpos($raw, "!1");
  $response = substr($raw, $beginn+2, -1);
  echo $response."<br>";
  if (strpos($response, "NLAX") === 0) {
    $xmlstr = substr($response, strpos($response, "<"));
  }
}

if ($xmlstr != '') {
  $xml_response = new SimpleXMLElement($xmlstr);
  echo $xml_response->items['totalitems']. 'xml-Einträge empfangen.<br>';
  echo '<form method="post">';
  echo '<p>Eintrag auswählen:</p><select name="xml_Eintrag" size="1">';
  for ($i=0; $i<$xml_response->items['totalitems']; $i++) {
    echo '<option value ="' . $i . '">'. $xml_response->items->item[$i]['title'] . '</option>\n';
  }
  echo '</select>';
  echo '<input type="hidden" name="Ebene" value="'.strval($Ebene+1).'">';
  echo '<button type="submit" name="action">Wähle</button></form>';
}

if ($Ebene > 1){
 echo '<form method="post"><input type="submit" name="zurueck" class="button" value="Eine Ebene höher" /><input type="hidden" name="Ebene" value="'.strval($Ebene-1).'"></form>';
}

fclose($fp);
?>
