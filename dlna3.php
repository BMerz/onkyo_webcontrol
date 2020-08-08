<?php
session_start();

$fp=fsockopen($_SESSION['hostname'], $_SESSION['port']);

// Sende Nachricht
$nachricht = '!1' . "NSV" . "00";
$kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

fwrite($fp, $kommando);

// Lese Antwort
$oldtime = time();
stream_set_timeout($fp,5);

$response = '';
$liste_server = array();
$liste_index = array();
$i=0;

while ((time()-$oldtime)<2) {
    $raw = '';
    $input = '';
    $oldtime2 = time();
    while ((ord($input) != 0x1A) and ((time()-$oldtime2)<2)) {
      $input = fread($fp, 1);
      $raw .= $input;
    }
    $beginn = strpos($raw, "!1");
    $response = substr($raw, $beginn+2, -1);
    echo $response."<br>";
    if (strpos($response, "NLSU") === 0) {
      $liste_index[$i]=substr($response, 4,1);
      $liste_server[$i]=substr($response, 6);
      $i=$i+1;
    }
}
for ($i=0; $i<sizeof($liste_index); $i++){
 echo $liste_index[$i]." ".$liste_server[$i];
}

if (sizeof($liste_index)>0){
 echo "DLNA ausgewählt. Bitte Server wählen oder nochmal suchen.";
 echo '<form method="post">';
 echo '<button type="submit" name="action">Retry</button></form>';
 echo '<form action="dlna4.php" method="post">';
 echo '<p>DLNA-Server auswählen:</p><fieldset>';
 for ($i=0; $i<sizeof($liste_index); $i++){
  echo '<input type="radio" name="DLNA_Server" value="'.$liste_index[$i].'">'.$liste_server[$i].'<br>';
 }
 echo '</fieldset><button type="submit" name="action">Weiter</button></form>';
}else{
 echo "Keine Server gefunden.";
 echo '<form method="post"><button type="submit" name="action">Retry</button></form>';
}

fclose($fp);

?>
