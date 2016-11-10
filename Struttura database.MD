Struttura database
==========
Classi principali e note
----------

# Utente
  Descrizione di un utente del sito
  - ID
  - Dati anagrafici
    - Nome
    - Cognome
    - Data di nascita
  - Luogo
    - Città di residenza
    - Regione
    - Paese
  - Contatti (alcuni possono essere molteplici)
    - email
    - telefono
    - Telegram
    - Skype
    - Facebook
  - Bio
    - Generi musicali preferiti
    - Strumenti suonati

# Admin/moderatori
  - ID
  - Poteri

# Gruppo
  - ID
  - NomeGruppo

# Gruppo-Utente
  - ID Gruppo
  - ID Utente
  - Ruolo

# Post
  - ID
  - Autore
  - Testo
  - GruppoDiImmagini

# Immagine
  - ID
  - GruppoDiImmagini
  - Descrizione
