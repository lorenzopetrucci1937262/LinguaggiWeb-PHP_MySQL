<?php
session_start();
// Verifica autorizzazione
if (!isset($_SESSION['accessoPermesso']) || $_SESSION['accessoPermesso'] != 1000) {
    header("Location: login.php");
    exit();
}

require_once("dati_generali.php");
require_once("connection.php");

// 1. GESTIONE CANCELLAZIONE
if (isset($_GET['delete'])) {
    $idDel = intval($_GET['delete']);
    $sqlDel = "DELETE FROM film WHERE id=$idDel";
    mysqli_query($mysqliConnection, $sqlDel);
    header("Location: admin.php");
    exit();
}

// 2. GESTIONE SALVATAGGIO (INSERIMENTO O MODIFICA)
if (isset($_POST['salva_film'])) {
    $id = intval($_POST['id']);
    $titolo = mysqli_real_escape_string($mysqliConnection, $_POST['titolo']);
    $ancora = mysqli_real_escape_string($mysqliConnection, $_POST['ancora']);
    $uscita = mysqli_real_escape_string($mysqliConnection, $_POST['uscita']);
    $durata = mysqli_real_escape_string($mysqliConnection, $_POST['durata']);
    $eta = mysqli_real_escape_string($mysqliConnection, $_POST['eta']);
    $regia = mysqli_real_escape_string($mysqliConnection, $_POST['regia']);
    $generi = mysqli_real_escape_string($mysqliConnection, $_POST['generi']);
    $cast_attori = mysqli_real_escape_string($mysqliConnection, $_POST['cast_attori']);
    $trama = mysqli_real_escape_string($mysqliConnection, $_POST['trama']);
    $trailer = mysqli_real_escape_string($mysqliConnection, $_POST['trailer']);
    $in_vetrina = isset($_POST['in_vetrina']) ? 1 : 0;

    // Di default manteniamo l'immagine esistente
    $immagine = mysqli_real_escape_string($mysqliConnection, $_POST['immagine_attuale']);
    
    $errore_bloccante = "";

    // Controlliamo se l'utente ha provato a caricare un file
    if (isset($_FILES['immagine_file']) && $_FILES['immagine_file']['name'] != "") {
        

        if ($_FILES['immagine_file']['error'] != 0) {
            if ($_FILES['immagine_file']['error'] == 1 || $_FILES['immagine_file']['error'] == 2) {
                $errore_bloccante = "Il file è troppo grande! Supera il limite massimo consentito dal server.";
            } else {
                $errore_bloccante = "Errore nel caricamento del file (Codice PHP: " . $_FILES['immagine_file']['error'] . ").";
            }
        } else {
            $cartella_destinazione = "img/";
            
            // Verifica esistenza della cartella img/
            if (!is_dir($cartella_destinazione)) {
                $errore_bloccante = "La cartella definitiva 'img/' non esiste nella directory corrente. Creala prima di continuare.";
            } 
            // Verifica permessi di scrittura sulla cartella
            elseif (!is_writable($cartella_destinazione)) {
                $errore_bloccante = "La cartella 'img/' esiste ma non ha i permessi di scrittura abilitati.";
            } else {
                $nome_file = basename($_FILES['immagine_file']['name']);
                $percorso_completo = $cartella_destinazione . $nome_file;
                
                //Estrazione e verifica dell'estensione richiesta
                $estensione = strtolower(pathinfo($percorso_completo, PATHINFO_EXTENSION));
                $estensioni_consentite = array("jpg", "jpeg", "png");
                
                if (!in_array($estensione, $estensioni_consentite)) {
                    $errore_bloccante = "Formato file non valido! Puoi caricare solo immagini .jpg, .jpeg o .png (il tuo file è un .$estensione).";
                } else {
                    // Tentativo di spostamento del file
                    if (move_uploaded_file($_FILES['immagine_file']['tmp_name'], $percorso_completo)) {
                        $immagine = mysqli_real_escape_string($mysqliConnection, $percorso_completo);
                    } else {
                        $errore_bloccante = "Errore di sistema nel trasferimento del file nella cartella 'img/'.";
                    }
                }
            }
        }
    }

    // Se è stato riscontrato un errore, fermo il salvataggio e avviso l'utente
    if ($errore_bloccante != "") {
        $_SESSION['errore_upload'] = $errore_bloccante;
        // Ricarico la pagina mantenendo l'eventuale stato di modifica
        header("Location: admin.php" . ($id > 0 ? "?edit=$id" : ""));
        exit();
    }

    // Se non ci sono errori procediamo con il Database
    if ($id == 0) {
        $sqlIns = "INSERT INTO film (titolo, ancora, uscita, durata, eta, regia, generi, cast_attori, trama, trailer, immagine, in_vetrina) 
                   VALUES ('$titolo', '$ancora', '$uscita', '$durata', '$eta', '$regia', '$generi', '$cast_attori', '$trama', '$trailer', '$immagine', $in_vetrina)";
        mysqli_query($mysqliConnection, $sqlIns);
    } else {
        $sqlUpd = "UPDATE film SET titolo='$titolo', ancora='$ancora', uscita='$uscita', durata='$durata', eta='$eta', 
                   regia='$regia', generi='$generi', cast_attori='$cast_attori', trama='$trama', trailer='$trailer', 
                   immagine='$immagine', in_vetrina=$in_vetrina WHERE id=$id";
        mysqli_query($mysqliConnection, $sqlUpd);
    }
    header("Location: admin.php");
    exit();
}

// 3. RECUPERO DATI SE IN MODALITÀ MODIFICA
$edit_id = 0; $e_titolo = ""; $e_ancora = ""; $e_uscita = ""; $e_durata = ""; $e_eta = "Per tutti.";
$e_regia = ""; $e_generi = ""; $e_cast = ""; $e_trama = ""; $e_trailer = "https://"; $e_immagine = "img/"; $e_vetrina = 0;

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $sqlSelEdit = "SELECT * FROM film WHERE id=$edit_id";
    $resEdit = mysqli_query($mysqliConnection, $sqlSelEdit);
    if ($rowE = mysqli_fetch_array($resEdit)) {
        $e_titolo = $rowE['titolo']; $e_ancora = $rowE['ancora']; $e_uscita = $rowE['uscita'];
        $e_durata = $rowE['durata']; $e_eta = $rowE['eta']; $e_regia = $rowE['regia'];
        $e_generi = $rowE['generi']; $e_cast = $rowE['cast_attori']; $e_trama = $rowE['trama'];
        $e_trailer = $rowE['trailer']; $e_immagine = $rowE['immagine']; $e_vetrina = $rowE['in_vetrina'];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
   <meta charset="iso-8859-1">
   <title>Pannello Amministrativo</title>
   <link rel="stylesheet" href="./stylesheet.css" type="text/css" media="all"/>
   <style type="text/css">
      .admin-box { background: #222; color: #fff; padding: 20px; margin: 20px; border: 1px solid #444; }
      .tabella-film { width: 100%; border-collapse: collapse; margin-top: 15px; color: #fff; }
      .tabella-film th, .tabella-film td { border: 1px solid #444; padding: 10px; text-align: left; }
      .tabella-film th { background: #333; }
      .form-admin input, .form-admin textarea { width: 95%; padding: 6px; margin-bottom: 10px; }
      .form-admin input[type="submit"] { background: green; color: white; border: none; font-weight: bold; cursor: pointer; width: auto; padding: 10px 20px; }
      .form-admin input[type="checkbox"] { width: auto; }
      .btn-del { color: #ff3333; font-weight: bold; text-decoration: none; }
      .btn-edit { color: #33ff33; font-weight: bold; text-decoration: none; margin-right: 10px; }
      .alert-errore { background: #ff3333; color: white; padding: 12px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; }
   </style>
</head>
<body>
   <?php include("menu.php"); ?>

   <div class="admin-box">
      <h2>Pannello di Gestione Film (Benvenuto <?php echo $_SESSION['username']; ?>) | <a href="logout.php" style="color: #ff3333;">Disconnetti</a></h2>
      
      <h3><?php echo ($edit_id == 0) ? "Aggiungi Nuovo Film" : "Modifica Film Selezionato"; ?></h3>
      
      <?php 
      
      if (isset($_SESSION['errore_upload'])) {
          echo '<div class="alert-errore">' . $_SESSION['errore_upload'] . '</div>';
          unset($_SESSION['errore_upload']);
      }
      ?>
      
      <form action="admin.php" method="post" class="form-admin" enctype="multipart/form-data">
         <p>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>" />
            <label>Titolo Film:</label><br /><input type="text" name="titolo" value="<?php echo $e_titolo; ?>" /><br />
            <label>ID Ancora HTML (es: spiderman):</label><br /><input type="text" name="ancora" value="<?php echo $e_ancora; ?>" /><br />
            <label>Data di Uscita:</label><br /><input type="text" name="uscita" value="<?php echo $e_uscita; ?>" /><br />
            <label>Durata:</label><br /><input type="text" name="durata" value="<?php echo $e_durata; ?>" /><br />
            <label>Et&agrave; consigliata:</label><br /><input type="text" name="eta" value="<?php echo $e_eta; ?>" /><br />
            <label>Regia:</label><br /><input type="text" name="regia" value="<?php echo $e_regia; ?>" /><br />
            <label>Generi (separati da virgola):</label><br /><input type="text" name="generi" value="<?php echo $e_generi; ?>" /><br />
            <label>Cast / Attori:</label><br /><textarea name="cast_attori" rows="3" cols="40"><?php echo $e_cast; ?></textarea><br />
            <label>Trama:</label><br /><textarea name="trama" rows="4" cols="40"><?php echo $e_trama; ?></textarea><br />
            <label>URL Video Trailer Youtube:</label><br /><input type="text" name="trailer" value="<?php echo $e_trailer; ?>" /><br />
            
            <label>Percorso Locandina Corrente:</label><br />
            <input type="text" name="immagine_attuale" value="<?php echo $e_immagine; ?>" readonly="readonly" style="background-color: #333; color: #aaa;" /><br />
            
            <label>Carica Nuova Locandina (.jpg, .jpeg, .png):</label><br />
            <input type="file" name="immagine_file" accept=".jpg,.jpeg,.png" /><br />
            
            <label><input type="checkbox" name="in_vetrina" value="1" <?php if ($e_vetrina == 1) echo 'checked="checked"'; ?> /> Mostra questo film nella vetrina della Home Page (Massimo 3 consigliati)</label><br />
         </p>
         <p>
            <input type="submit" name="salva_film" value="Salva Film" />
            <?php if ($edit_id > 0) { echo '<a href="admin.php" style="color:#ccc; margin-left:10px;">Annulla Modifica</a>'; } ?>
         </p>
      </form>

      <hr />

      <h3>Film Attualmente Presenti a Database</h3>
      <table class="tabella-film">
         <thead>
            <tr>
               <th>Titolo</th>
               <th>Uscita</th>
               <th>Regia</th>
               <th>Vetrina</th>
               <th>Azioni</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $sqlList = "SELECT * FROM film ORDER BY id DESC";
            $resList = mysqli_query($mysqliConnection, $sqlList);
            while ($f = mysqli_fetch_array($resList)) {
                $vetStr = ($f['in_vetrina'] == 1) ? "S&igrave;" : "No";
                echo '<tr>
                   <td>' . $f['titolo'] . '</td>
                   <td>' . $f['uscita'] . '</td>
                   <td>' . $f['regia'] . '</td>
                   <td>' . $vetStr . '</td>
                   <td>
                      <a class="btn-edit" href="admin.php?edit=' . $f['id'] . '">Modifica</a>
                      <a class="btn-del" href="admin.php?delete=' . $f['id'] . '" onclick="return confirm(\'Sei sicuro di voler eliminare questo film?\');">Elimina</a>
                   </td>
                </tr>';
            }
            ?>
         </tbody>
      </table>
   </div>
</body>
</html>