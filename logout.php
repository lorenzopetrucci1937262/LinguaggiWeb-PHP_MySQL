<?php
session_start();
// Distrugge la sessione corrente pulendo l'array
$_SESSION = array();
session_destroy();
// Ritorna alla home page pubblica
header("Location: index.php");
exit();