<?php
require_once("dati_generali.php");

$mysqliConnection = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    printf("Oops, abbiamo problemi con la connessione al db: %s\n", mysqli_connect_error());
    exit();
}
?>