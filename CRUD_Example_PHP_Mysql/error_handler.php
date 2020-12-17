  <?php
  //metoder for error-skriving og -logging
  error_reporting(0); //skrur av PHP-meldinger
  set_error_handler("printError",E_ALL); //definerer ny error-funksjon
  set_error_handler("myErrorHandler",E_ALL); //definerer ny error-funksjon

  function printError($error_message){
    echo $error_message;
    error_log(date("d-m-y H:i").": ".$error_message."\n", 3, "error_log.txt"); //loggmeldingen kan utvides til det uendelige med feilkoder, feiltyper, filen feilen kom i, linje osv. Sjekk forelesningsnotater og -kode fra forelesning for mer info
    die();
  }

  function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
?>