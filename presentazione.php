<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("dati_generali.php");
echo '<?xml version = "1.0" encoding="iso-8859-1"?>';
?>
<!DOCTYPE html>

<html lang="it">

<head>
   <title><?php echo $cinema_nome; ?> - Il nostro Cinema</title>
   <link rel="stylesheet" href="./stylesheet.css" type="text/css"  media="all"/>
</head>

<body>
    
   <?php include("menu.php"); ?>

   <div class="sezione">
    <h2 class="presentazione">Le nostre sale</h2>
    <table summary="Le caratteristiche che distinguono le nostre sale">
        <thead>
            <tr>
                <th scope="col">Sale</th>
                <th scope="col">Proiezioni</th>
                <th scope="col">Posti</th>
                <th scope="col">File</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="grande">Sala Grande</th>
                <td>
                    <span class="on">Normali</span>
                    <span class="on">3D</span>
                    <span class="off">V.O.</span>
                </td>
                <td>384</td>
                <td>20</td>
            </tr>
            <tr>
                <th class="blu">Sala Blu</th>
                <td>
                    <span class="on">Normali</span>
                    <span class="on">3D</span>
                    <span class="off">V.O.</span>
                </td>
                <td>288</td>
                <td>12</td>
            </tr>
            <tr>
                <th class="rosso">Sala Rossa</th>
                <td>
                    <span class="on">Normali</span>
                    <span class="off">3D</span>
                    <span class="on">V.O.</span>
                </td>
                <td>216</td>
                <td>18</td>
            </tr>
            <tr>
                <th class="giallo">Sala Gialla</th>
                <td>
                    <span class="off">Normali</span>
                    <span class="off">3D</span>
                    <span class="on">V.O.</span>
                </td>
                <td>140</td>
                <td>14</td>
            </tr>
        </tbody>
    </table>
   </div>

   <div class="sezione">
    <h2 class="presentazione">I nostri prezzi</h2>
    <table summary="I nostri prezzi in funzione del calendario">
        <thead>
            <tr>
                <th scope="col">Periodo</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Prezzo Intero</th>
                <th scope="col">Ridotto*</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Pomeridiana</th>
                <td>Tutti gli spettacoli nei giorni feriali con inizio entro le 18:59</td>
                <td>6,50&euro;</td>
                <td>5&euro;</td>
            </tr>
            <tr>
                <th>Serale</th>
                <td>Tutti gli spettacoli nei giorni feriali con inizio dalle 19:00</td>
                <td>8,50&euro;</td>
                <td>7&euro;</td>
            </tr>
            <tr>
                <th>Festivi</th>
                <td>Tutti gli spettacoli del sabato, della domenica e festivi</td>
                <td>9&euro;</td>
                <td>7,50&euro;</td>
            </tr>
        </tbody>
    </table>
    <p>Tutte le proiezioni 3D apportano un supplemento del prezzo di 2&euro;, con noleggio incluso degli occhialini, 
        il supplemento non &egrave; facoltativo ed &egrave; escluso dagli abbonamenti.
        <br />
        * I prezzi ridotti sono rivolti ai senior over 65 e a studenti di tutte le et&agrave;; provvisti di certificazione.  
    </p>
   </div>
   <div class="sezione">
    <h2 class="presentazione">I nostri abbonamenti</h2>
    <table summary="I nostri abbonamenti per affezionati">
        <thead>
            <tr>
                <th scope="col">Abbonamenti</th>
                <th scope="col">Validit&agrave;</th>
                <th scope="col">Prezzo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><dl>
                    <dt class="abb">Sun</dt>
                    <dd>Valido per tutte le proiezioni, supplemento 3D escluso.</dd>
                </dl></td>
                <td>10 proiezioni</td>
                <td>50,00&euro;</td>
            </tr>
            <tr>
                <td><dl>
                    <dt class="abb">Moon</dt>
                    <dd>Valido per tutte le proiezioni, supplemento 3D escluso.</dd>
                </dl></td>
                <td>5 proiezioni</td>
                <td>29,00&euro;</td>
            </tr>
            <tr>
                <td><dl>
                    <dt class="abb">Earth</dt>
                    <dd>Riservato a studenti e over 65. Valido per tutte le proiezioni, supplemento 3D escluso.</dd>
                </dl></td>
                <td>5 proiezioni</td>
                <td>24,00&euro;</td>
            </tr>
        </tbody>
    </table>
    <p>Per gli abbonamenti rivolgersi alla biglietteria del cinema.</p>
   </div>
   
   <?php include("footer.php"); ?>
</body>
</html>