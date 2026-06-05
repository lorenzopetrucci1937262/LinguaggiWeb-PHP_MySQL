<?php
session_start();
require_once("dati_generali.php");
require_once("connection.php");

$errore = "";

if (isset($_POST['invio'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = mysqli_real_escape_string($mysqliConnection, $_POST['username']);
        $insPassword = mysqli_real_escape_string($mysqliConnection, $_POST['password']);
        
        $sqlAuth = "SELECT * FROM utenti WHERE username='$username'";
        $resAuth = mysqli_query($mysqliConnection, $sqlAuth);
        
        if (mysqli_num_rows($resAuth) == 1) {
            $rigaUtente = mysqli_fetch_array($resAuth);
            $hash_database = $rigaUtente['password'];

            if (password_verify($insPassword, $hash_database)) {
               $_SESSION['accessoPermesso'] = 1000;
               $_SESSION['username'] = $username;
               header("Location: admin.php");
               exit();
            }
            else {
            $errore = "Credenziali errate. Riprova.";
            }
       } else {
            $errore = "Credenziali errate. Riprova.";
            }
    } else {
        $errore = "Tutti i campi sono obbligatori.";
      }
}

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html>
<html lang="it">
<head>
   <title>Login Amministrazione</title>
   <link rel="stylesheet" href="./stylesheet.css" type="text/css" media="all"/>
   <style type="text/css">
      .form-login { max-width: 400px; margin: 50px auto; padding: 20px; background: #222; color: #fff; border: 1px solid #444; }
      .form-login input { width: 95%; margin-bottom: 15px; padding: 8px; }
      .form-login input[type="submit"] { background: #e50914; color: #fff; border: none; cursor: pointer; font-weight: bold; }
      .error { color: #ff3333; margin-bottom: 15px; }
   </style>
</head>
<body>
   <?php include("menu.php"); ?>

   <div class="form-login">
      <h2>Accesso Amministrazione</h2>
      <?php if (!empty($errore)) { echo '<p class="error">' . $errore . '</p>'; } ?>
      <form action="login.php" method="post">
         <p>
            <label for="username">Username:</label><br />
            <input type="text" name="username" id="username" />
         </p>
         <p>
            <label for="password">Password:</label><br />
            <input type="password" name="password" id="password" />
         </p>
         <p>
            <input type="submit" name="invio" value="Accedi" />
         </p>
      </form>
   </div>
</body>
</html>