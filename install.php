<?php
require_once("dati_generali.php");

// Connessione iniziale senza specificare il database
$connection = new mysqli($db_host, $db_user, $db_pass);

if (mysqli_connect_errno()) {
    printf("Errore di connessione iniziale: %s\n", mysqli_connect_error());
    exit();
}

// 1. Eliminazione se esiste e creazione del Database
$sqlDrop = "DROP DATABASE IF EXISTS `$db_name`";
mysqli_query($connection, $sqlDrop);

$sqlCreateDb = "CREATE DATABASE `$db_name`";
if (mysqli_query($connection, $sqlCreateDb)) {
    echo "Database '$db_name' creato con successo.<br />";
} else {
    echo "Errore durante la creazione del database.<br />";
}

// Seleziona il database appena creato
mysqli_select_db($connection, $db_name);

// 2. Creazione della tabella 'film'
$sqlTableFilm = "CREATE TABLE film (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    ancora VARCHAR(100) NOT NULL,
    uscita VARCHAR(100) NOT NULL,
    durata VARCHAR(50) NOT NULL,
    eta VARCHAR(50) NOT NULL,
    regia VARCHAR(255) NOT NULL,
    generi VARCHAR(255) NOT NULL,
    cast_attori TEXT NOT NULL,
    trama TEXT NOT NULL,
    trailer VARCHAR(255) NOT NULL,
    immagine VARCHAR(255) NOT NULL,
    in_vetrina TINYINT(1) DEFAULT 0
)";

if (mysqli_query($connection, $sqlTableFilm)) {
    echo "Tabella 'film' creata correttamente.<br />";
}

// 3. Creazione della tabella 'utenti' per l'amministrazione
$sqlTableUtenti = "CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL UNIQUE
)";

if (mysqli_query($connection, $sqlTableUtenti)) {
    echo "Tabella 'utenti' creata correttamente.<br />";
}

// 4. Inserimento dell'utente Amministratore
$admin = "admin";
$clear = "password123";
$cript = password_hash($clear, PASSWORD_DEFAULT);

$sqlAdmin = "INSERT INTO utenti (username, password) VALUES ('$admin', '$cript')";
mysqli_query($connection, $sqlAdmin);

// 5. Inserimento dei Film iniziali (Vetrina e Calendario)
$films = array(
    array("Project Hail Mary", "hailmary", "19 Marzo 2026", "157 minuti", "Per tutti.", "Phil Lord, Chris Miller", "Fantascienza, Drammatico, Avventura", "Ryan Gosling, Sandra Huller, Milana Vayntrub, James Ortiz, Lionel Boyce, Liz Kingsman, Ken Yeung, Priya Kansara", "L'insegnante di scienze della scuola media Ryland Grace (Ryan Gosling) si risveglia su un'astronave ad anni luce da casa senza ricordare chi sia o come ci sia arrivato. Quando la sua memoria ritorna, inizia a scoprire la sua missione: risolvere l'enigma della misteriosa sostanza che sta causando l'estinzione del sole.", "https://www.youtube.com/watch?v=44LvWH_P4Rw", "img/Hailmary.png", 1),
    array("The Drama", "drama", "1 Aprile 2026", "106 minuti", "Per tutti.", "Kristoffer Brogli", "Commedia, Romance", "Robert Pattinson, Zendaya, Alana Haim", "The Drama racconta la storia di Emma e Charlie, una coppia apparentemente perfetta vicina al matrimonio. I preparativi per le nozze precipitano nel caos quando un segreto scioccante emerge, trasformando la loro storia d'amore in paranoia.", "https://www.youtube.com/watch?v=9O0_pVjK17A", "img/drama.png", 1),
    array("Super Mario Galaxy - Il Film", "supermario", "1 Aprile 2026", "99 minuti", "Per tutti.", "Michael Jelenic, Aaron Hovarth", "Animazione, Commedia, Fantasy, Famiglia", "Chris Pratt, Anya Taylor Joy, Jack Black, Brie Larson, Benny Safdie", "Dopo gli eventi del primo film, Bowser è stato sconfitto. Stavolta è suo figlio, Bowser Jr., a raccogliere il testimone. Mario, Luigi, Peach e Toad si lanciano in un'avventura nello spazio aperto.", "https://www.youtube.com/watch?v=MbCiKLTkQPU", "img/supermario.png", 1),
    array("Michael", "michael", "22 Aprile 2026", "127 minuti", "Per tutti.", "Antoine Fuqua", "Biografico, Musical, Drammatico", "Jaafar Jackson, Kat Graham, Miles Teller, Colman Domingo", "Michael è la rappresentazione cinematografica della vita e dell'eredità di uno degli artisti più influenti che il mondo abbia mai conosciuto. Il film racconta la storia della vita di Michael Jackson al di là della musica.", "https://www.youtube.com/watch?v=fF-t5VdkPEI", "img/michael.png", 0),
    array("Il diavolo veste prada 2", "prada", "29 Aprile 2026", "119 minuti", "Per tutti.", "David Frankel", "Commedia, Drammatico", "Anne Hathaway, Meryl Streep, Emily Blunt, Simone Ashley", "Il film segue la lotta di Miranda Priestly contro Emily Charlton, la sua ex assistente diventata dirigente rivale, mentre competono per gli introiti pubblicitari in un periodo di declino della carta stampata.", "https://www.youtube.com/watch?v=zmBg3eo3PKM", "img/prada.png", 0),
    array("Star Wars - the Mandalorian and Grogu", "starwars", "20 Maggio 2026", "132 minuti", "Per tutti.", "Jon Favreau", "Fantascienza, Fantasy, Avventura", "Pedro Pascal, Jeremy White, Sigourney Weaver", "Il malvagio Impero è caduto e i signori della guerra imperiali sono sparsi per la galassia. La Nuova Repubblica lavora per proteggere la galassia con l'aiuto del cacciatore Din Djarin e Grogu.", "https://www.youtube.com/watch?v=1M61SW1kdYU", "img/starwars.png", 0),
    array("Disclosure day", "disclosureday", "10 Giugno 2026", "Non nota", "Per tutti.", "Steven Spielberg", "Fantascienza", "Emily Blunt, Eve Hewson, Colin Firth, Wyatt Russell", "Se tu scoprissi che non siamo soli, se qualcuno ti mostrasse, te lo dimostrasse, questo ti spaventerebbe? Quest'estate, la verità appartiene a siete miliardi di persone.", "https://www.youtube.com/watch?v=6Ssxl3hmLWs", "img/disclosureday.png", 0),
    array("The Odyssey", "odyssey", "16 Luglio 2026", "Non nota", "Per tutti.", "Christopher Nolan", "Azione, Fantasy, Drammatico, Avventura", "Matt Damon, Tom Holland, Zendaya, Elliot Page", "The Odyssey è un adattamento cinematografico del poema epico omerico. Narra il periglioso viaggio decennale di Odisseo (Ulisse) per tornare a Itaca dopo la guerra di Troia.", "https://www.youtube.com/watch?v=6SbP6gvsrlk", "img/odyssey.png", 0),
    array("Spider-Man - Brand new day", "spiderman", "29 Luglio 2026", "Non nota", "Per tutti.", "Destin Daniel Cretton", "Supereroi, Avventura, Fantascienza, Azione", "Tom Holland, Sadie Sink, Zendaya, Krondon", "Dopo lo straordinario successo mondiale di Spider-Man: No Way Home, Spider-Man: Brand New Day segna un capitolo completamente nuovo per Peter Parker e Spider-Man. Sono passati quattro anni.", "https://www.youtube.com/watch?v=OHg1vv9NNXo", "img/spiderman.png", 0),
    array("Digger", "digger", "1 Ottobre 2026", "Non nota", "Per tutti.", "Alejandro G. Inarritu", "Commedia, Umorismo nero", "Tom Cruise, Sandra Huller, Riz Ahmed, Jesse Plemons", "Digger è una 'commedia catastrofica' in cui un potente e controverso magnate del petrolio (Digger Rockwell) provoca un enorme disastro ecologico.", "https://www.youtube.com/watch?v=qLotWPrWZmY", "img/digger.png", 0),
    array("Hunger Games - L'alba sulla mietitura", "hungergames", "19 Novembre 2026", "Non nota", "Per tutti.", "Francis Lawrence", "Avventura, Fantascienza, Drammatico", "Mckenna Grace, Jennifer Lawrence, Maya Thurman-Hawke", "All'alba dei Cinquantesimi Hunger Games, nei 12 distretti di capital city l'atmosfera è di puro terrore. Per il ricorre del 50esimo anniversario Panem organizes la seconda Edizione della Memoria.", "https://www.youtube.com/watch?v=JHh7_klJXgk", "img/hungergames.png", 0),
    array("Avengers - Doomsday", "doomsday", "18 Dicembre 2026", "Non nota", "Per tutti.", "Joe Russo, Anthony Russo", "Supereroi, Azione, Drammatico, Fantascienza", "Robert Downey Jr., Vanessa Kirby, Chris Evans, Florence Pugh", "Trama non ancora resa nota al pubblico internazionale.", "https://www.youtube.com/watch?v=2-wMsXgA1YM", "img/doomsday.png", 0),
    array("Dune - Part 3", "dune", "18 Dicembre 2026", "Non nota", "Per tutti.", "Denis Villeneuve", "Fantascienza, Avventura, Azione, Drammatico", "Timothee Chalamet, Zendaya, Rebecca Ferguson, Anya Taylor Joy", "Dune - Parte Tre chiuderà la trilogia basandosi sul romanzo Messia di Dune. Ambientato circa 12 anni dopo il secondo film, vede Paul Atreides come Imperatore assolutista.", "https://www.youtube.com/watch?v=KWIZ0cwSAeU", "img/dune3.png", 0)
);

foreach ($films as $f) {
    // Escaping manuale delle stringhe per evitare errori SQL
    $titolo = mysqli_real_escape_string($connection, $f[0]);
    $ancora = mysqli_real_escape_string($connection, $f[1]);
    $uscita = mysqli_real_escape_string($connection, $f[2]);
    $durata = mysqli_real_escape_string($connection, $f[3]);
    $eta = mysqli_real_escape_string($connection, $f[4]);
    $regia = mysqli_real_escape_string($connection, $f[5]);
    $generi = mysqli_real_escape_string($connection, $f[6]);
    $cast = mysqli_real_escape_string($connection, $f[7]);
    $trama = mysqli_real_escape_string($connection, $f[8]);
    $trailer = mysqli_real_escape_string($connection, $f[9]);
    $immagine = mysqli_real_escape_string($connection, $f[10]);
    $vetrina = $f[11];

    $sqlIns = "INSERT INTO film (titolo, ancora, uscita, durata, eta, regia, generi, cast_attori, trama, trailer, immagine, in_vetrina) 
               VALUES ('$titolo', '$ancora', '$uscita', '$durata', '$eta', '$regia', '$generi', '$cast', '$trama', '$trailer', '$immagine', $vetrina)";
    mysqli_query($connection, $sqlIns);
}

echo "Popolamento iniziale dei film completato con successo!<br />";
echo "<strong>Installazione completata. <a href='index.php'>Vai alla Home</a></strong>";
mysqli_close($connection);
?>