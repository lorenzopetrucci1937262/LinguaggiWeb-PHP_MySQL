# Homework 2 per Linguaggi per il Web - PHP e MySQL

## Autore: Lorenzo Petrucci

## Credenziali di Accesso Amministratore (per il docente):
* **Username:** `admin`
* **Password:** `password123`

---

## Tecniche del sito
Il sito sviluppa la precedente struttura statica XHTML/CSS in una piattaforma web dinamica basata su **PHP** e un database **MySQL**. 

Le principali tecniche implementate comprendono:
* **Separazione della logica:** Uso estensivo di costrutti `require_once` per centralizzare la configurazione dei parametri del database (`dati_generali.php`) e l'apertura delle connessioni (`connection.php`).
* **Modularità dei componenti:** L'header e la navigazione sono centralizzati in un unico file `menu.php`, il footer nel file `footer.php`, inclusi dinamicamente in tutte le pagine tramite `include()`.
* **Persistenza dello stato:** Gestione delle sessioni di autenticazione di PHP (`session_start()`) per proteggere l'area riservata ed estendere le funzionalità globali dell'amministratore.
* **Sicurezza dei dati:** Nel database la password non è memorizzata in chiaro, ma viene protetta tramite un algoritmo di hashing sicuro. Le credenziali fornite consentono l'accesso completo al pannello gestionale.

---

## Funzione del sito
Il sistema trasforma le vecchie sezioni statiche in pagine dinamiche generate in tempo reale partendo dai dati del database. I clienti possono consultare in tempo reale i film contrassegnati in "vetrina" nella Home Page o l'intero palinsesto nella pagina della programmazione. 

Agli amministratori del cinema viene fornito un pannello di controllo protetto (`admin.php`) per la gestione autonoma del catalogo con tutte le operazioni CRUD: Creazione, Lettura, Aggiornamento e Cancellazione dei film, eliminando la necessità di modificare manualmente il codice sorgente del sito.

---

## Nuove Funzionalità Dinamiche
* **Installazione Automatica (`install.php`):** Uno script dedicato si occupa di resettare, creare e popolare il database da zero, configurando le tabelle `film` e `utenti` e inserendo i dati dimostrativi iniziali.
* **Sistema CRUD Completo:** All'interno dell'area amministrativa è presente un unico modulo intelligente che è efficiente sia per la modalità di inserimento di un nuovo film che per la modifica di un film preesistente (riproducendo i dati nei campi del form), oltre a consentire l'eliminazione dei record con feedback di conferma in JavaScript.
* **Caricamento Immagini Protetto:** Il sistema gestisce l'upload dei poster dei film verificando le dimensioni massime, la corrispondenza delle estensioni ammesse (JPEG, PNG) e l'effettiva presenza della cartella di destinazione, memorizzando nel database solo il percorso relativo.
* **Parsing URL YouTube integrato:** Tramite espressioni regolari (`preg_match`), il sistema isola l'ID alfanumerico del trailer inserito dall'utente (supportando formati desktop, mobile ed embed) per generare dinamicamente un lettore multimediale Iframe integrato nella scheda del film.
* **Navigazione Globale Admin:** Lo stato della sessione viene analizzato direttamente all'interno dell'header globale, inserendo in tutte le pagine un link che permette di accedere al login o di tornare direttamente al pannello di controllo se si è già autenticati.
* **Componente di Usabilità "Torna Su":** Inserito in modalità fissa in basso a destra un pulsante minimale e geometrico che consente agli utenti di ritornare in cima alla pagina tramite un'ancora.
---

## Note di Sviluppo

### GESTIONE DELLE PASSWORD (HASHING VS IN CHIARO)
Durante la creazione del database la memorizzazione delle password ha richiesto particolare attenzione. Evitando l'approccio iniziale del salvataggio della password nel db, è stato poi implementato il binomio nativo `password_hash()` (in fase di installazione) e `password_verify()` (in fase di autenticazione), in modo da rendere più sicuro il database e il sito. Nel file `install.php` la password sorgente è visibile esclusivamente in chiaro all'amministratore durante la configurazione iniziale del sistema su server locale.

### ESPRESSIONI REGOLARI PER I VIDEO
L'integrazione dei trailer ha rappresentato una sfida a causa delle differenze strutturali degli URL di YouTube generati dagli utenti (link abbreviati `youtu.be` o stringhe di condivisione con parametri di tracking commerciali). L'utilizzo di una espressione regolare robusta ha permesso di standardizzare l'input catturando selettivamente solo l'identificativo del video, rendendo l'esperienza utente solida ed esente da interruzioni del layout.

### COMPORTAMENTO DINAMICO DEL MENU
Nel precedente homework, i link della barra di navigazione erano statici. Nel nuovo file `menu.php` è stata introdotta una logica di controllo basata sulla variabile globale `$_SERVER['PHP_SELF']`. Leggendo il nome del file in esecuzione, il sistema applica condizionalmente la classe CSS `.link-disattivato` per inibire i click e gli effetti di hover sul link corrispondente alla pagina in cui l'utente si trova già, migliorando l'orientamento all'interno dell'applicazione.