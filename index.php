<?php
require_once("dati_generali.php");
require_once("connection.php");
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html>
<html lang="it">
<head>
   <title><?php echo $cinema_nome; ?> - Home</title>
   <link rel="stylesheet" href="./stylesheet.css" type="text/css" media="all"/>
</head>
<body>

   <?php include("menu.php"); ?>

   <div class="vetrina">
      <?php
      // Estraggo solo i film contrassegnati per la vetrina della Home
      $sqlVetrina = "SELECT * FROM film WHERE in_vetrina = 1";
      $resultQ = mysqli_query($mysqliConnection, $sqlVetrina);

      while ($row = mysqli_fetch_array($resultQ)) {
          echo '<div class="anteprima">
             <h2><a href="programmazione.php#' . $row['ancora'] . '">' . $row['titolo'] . '</a></h2>
             <div class="poster">
                <a href="programmazione.php#' . $row['ancora'] . '"><img src="' . $row['immagine'] . '" alt="' . $row['titolo'] . '" /></a>
             </div>
             <div class="text_ant">
                <p><span class="voce">Durata: </span>' . $row['durata'] . '</p>
                <p><span class="voce">Et&agrave;: </span>' . $row['eta'] . '</p>
                <p><span class="voce">Regia: </span>' . $row['regia'] . '</p>
                <p><span class="voce">Generi: </span>' . $row['generi'] . '</p>
             </div>
          </div>';
      }
      ?>
   </div>

   <?php include("footer.php"); ?>
</body>
</html>