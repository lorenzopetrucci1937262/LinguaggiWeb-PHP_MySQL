<?php
require_once("dati_generali.php");
require_once("connection.php");
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html>
<html lang="it">
<head>
   <title><?php echo $cinema_nome; ?> - Programmazione</title>
   <link rel="stylesheet" href="./stylesheet.css" type="text/css" media="all"/>
</head>
<body>

   <?php include("menu.php"); ?>

   <div class="program">
      <div class="calendario">
         <?php
         $sqlTutti = "SELECT * FROM film ORDER BY id ASC";
         $resultQ = mysqli_query($mysqliConnection, $sqlTutti);

         while ($row = mysqli_fetch_array($resultQ)) {
             $url_trailer = $row['trailer'];
             $id_video = '';
             if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]+)%', $url_trailer, $match)) {
                 $id_video = $match[1];
             }
             $url_embed = "https://www.youtube.com/embed/" . $id_video;

             echo '<div class="cornice_scheda">
                <h2 id="' . $row['ancora'] . '">' . $row['titolo'] . '</h2>
                <div class="schedafilm">
                   <div class="text_scheda">
                      <p><span class="voce">Uscita: </span> <span class="uscita">' . $row['uscita'] . '</span></p>
                      <p><span class="voce">Durata: </span>' . $row['durata'] . '</p>
                      <p><span class="voce">Et&agrave;: </span>' . $row['eta'] . '</p>
                      <p><span class="voce">Regia: </span>' . $row['regia'] . '</p>
                      <p><span class="voce">Generi: </span>' . $row['generi'] . '</p>
                      <p><span class="voce">Con: </span>' . $row['cast_attori'] . '</p>
                      <p><span class="voce">Trama: </span>' . $row['trama'] . '</p>
                      <p><span class="voce">Guarda il Trailer:</span></p>
                      <div class="schedafilm">
                        <iframe width="100%" height="250" src="' . $url_embed . '" title="YouTube video player" 
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; 
                        picture-in-picture; web-share" allowfullscreen style="max-width: 440px; border: none;"></iframe>
                      </div>
                   </div>
                   <div class="cartellone">
                      <img src="' . $row['immagine'] . '" alt="' . $row['titolo'] . '" />
                   </div>
                </div>
             </div>';
         }
         ?>
      </div>

      <div class="laterale">
          <h2>Film in calendario</h2>
          <ul>
              <?php
              mysqli_data_seek($resultQ, 0);
              while ($rowLat = mysqli_fetch_array($resultQ)) {
                  echo '<li><a class="film" href="#' . $rowLat['ancora'] . '">' . $rowLat['titolo'] . '</a></li>';
              }
              ?>
          </ul>
          <hr />
          <p>Per biglietti e abbonamenti rivolgersi in biglietteria dopo le 16.<br />
             Gli spettacoli al momento seguono il seguente orario:<br />
             Pomeridiana 16:30, tutte le sale.<br />
             Serale 19:00 21:00 23:00 Sala Grande e Sala Gialla, 19:30 22:30 Sala Blu e Sala Rossa.
          </p>
      </div>
   </div>

   <?php include("footer.php"); ?>
</body>
</html>