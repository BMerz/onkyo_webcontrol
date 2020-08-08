<?php
session_start();

$response = '';

$fp=fsockopen($_SESSION['hostname'], $_SESSION['port']);

// Sende Nachricht
$nachricht = '!1' . "SLI" . "2B";
$kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

fwrite($fp, $kommando);

sleep(1);

// Sende Nachricht
$nachricht = '!1' . "SLI" . "QSTN";
$kommando = "ISCP\x00\x00\x00\x10\x00\x00\x00" . chr(strlen($nachricht) + 1) . "\x01\x00\x00\x00" . $nachricht . "\x0D";

fwrite($fp, $kommando);

// Lese Antwort
$oldtime = time();
stream_set_timeout($fp,5);
while ((strpos($response, "SLI") !== 0 ) and (time()-$oldtime)<2) {
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
}

if ($response == "SLI2B"){
 echo "Verstärker-Eingang: NET";
 echo '<form action="dlna3.php" method="post"><button type="submit" name="action">Weiter</button></form>';
}else{
 echo "Konnte Verstärker-Eingang nicht wählen. Eingeschaltet?";
 echo '<form method="post"><button type="submit" name="action">Retry</button></form>';
}

fclose($fp);

?>
