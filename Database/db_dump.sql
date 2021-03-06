### CREAZIONE DATABASE: ###

# SCHEMA DATABASE_ARTISTI
DROP SCHEMA IF EXISTS ggiuffre;
CREATE SCHEMA IF NOT EXISTS ggiuffre DEFAULT CHARACTER SET utf8;
USE ggiuffre;



### CREAZIONE SCHEMA: ###

# REGIONI
DROP TABLE IF EXISTS Regioni;
CREATE TABLE IF NOT EXISTS Regioni (
	nome			VARCHAR(25) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco delle regioni d\'Italia';



# PROVINCE
DROP TABLE IF EXISTS Province;
CREATE TABLE IF NOT EXISTS Province (
	sigla			CHAR(2) PRIMARY KEY,
	nome			VARCHAR(30) NOT NULL,
	regione			VARCHAR(25) NOT NULL COMMENT 'Regione di appartenenza',
	FOREIGN KEY	(regione) REFERENCES Regioni (nome)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Elenco delle province d\'Italia, con regione di appartenenza';



# GRUPPI
DROP TABLE IF EXISTS Gruppi;
CREATE TABLE IF NOT EXISTS Gruppi (
	idGruppo		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	nome			VARCHAR(45) NOT NULL,
	immagine		VARCHAR(45) DEFAULT NULL COMMENT 'Immagine profilo; NULL implica immagine di default',
	descrizione     VARCHAR(200) DEFAULT NULL COMMENT 'Descrizione del gruppo',
	dataIscrizione	DATETIME NOT NULL,
	provincia		CHAR(2) NOT NULL,
	FOREIGN KEY (provincia) REFERENCES Province (sigla)
	    ON DELETE NO ACTION
	    ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Dati generici dei gruppi';



# TIPICONTATTI
DROP TABLE IF EXISTS TipiContatti;
CREATE TABLE IF NOT EXISTS TipiContatti (
	nome			VARCHAR(20) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco delle tipologie di contatto: mail, facebook, whatsapp, ecc...';



# CONTATTIGRUPPI
DROP TABLE IF EXISTS ContattiGruppi;
CREATE TABLE IF NOT EXISTS ContattiGruppi (
	idContatto		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	gruppo		    INT(10) UNSIGNED NOT NULL,
	tipoContatto	VARCHAR(20) NOT NULL,
	contatto		VARCHAR(100) NOT NULL COMMENT 'Stringa contenente il recapito',
	FOREIGN KEY (gruppo) REFERENCES Gruppi (idGruppo)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (tipoContatto) REFERENCES TipiContatti (nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Recapiti dei gruppi';



# UTENTI
DROP TABLE IF EXISTS Utenti;
CREATE TABLE IF NOT EXISTS Utenti (
	username		VARCHAR(25) PRIMARY KEY,
	password	    CHAR(32) NOT NULL,
	email			VARCHAR(45) NOT NULL UNIQUE,
	nome            VARCHAR(20) DEFAULT NULL,
	cognome         VARCHAR(20) DEFAULT NULL,
	dataNascita		DATE NOT NULL,
	immagine		VARCHAR(45) DEFAULT NULL COMMENT 'Immagine profilo; NULL implica immagine di default',
	descrizione     VARCHAR(200) DEFAULT NULL COMMENT 'Descrizione dell\'utente',
	dataIscrizione	DATETIME NOT NULL,
	provincia		CHAR(2) DEFAULT NULL COMMENT 'Provincia di residenza',
	FOREIGN KEY (provincia) REFERENCES Province (sigla)
	    ON DELETE NO ACTION
	    ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Dati generici degli utenti';



# CONTATTIUTENTI
DROP TABLE IF EXISTS ContattiUtenti;
CREATE TABLE IF NOT EXISTS ContattiUtenti (
	idContatto		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	utente  		VARCHAR(25) NOT NULL,
	tipoContatto	VARCHAR(20) NOT NULL,
	contatto		VARCHAR(100) NOT NULL COMMENT 'Stringa contenente il recapito',
	FOREIGN KEY (utente) REFERENCES Utenti (username)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (tipoContatto) REFERENCES TipiContatti (nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Recapiti degli utenti';



# GENERIMUSICALI
DROP TABLE IF EXISTS GeneriMusicali;
CREATE TABLE IF NOT EXISTS GeneriMusicali (
	nome			VARCHAR(45) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco dei generi musicali riconosciuti dal sito';



# GENERIGRUPPI
DROP TABLE IF EXISTS GeneriGruppi;
CREATE TABLE IF NOT EXISTS GeneriGruppi (
	gruppo		    INT(10) UNSIGNED NOT NULL,
	genere			VARCHAR(45) NOT NULL,
	PRIMARY KEY (gruppo, genere),
	FOREIGN KEY (gruppo) REFERENCES Gruppi (idGruppo)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (genere) REFERENCES GeneriMusicali (nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Preferenze musicali dei gruppi';



# GENERIUTENTI
DROP TABLE IF EXISTS GeneriUtenti;
CREATE TABLE IF NOT EXISTS GeneriUtenti (
	utente			VARCHAR(25) NOT NULL,
	genere			VARCHAR(45) NOT NULL,
	PRIMARY KEY (utente, genere),
	FOREIGN KEY (utente) REFERENCES Utenti (username)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	FOREIGN KEY (genere) REFERENCES GeneriMusicali (nome)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Preferenze musicali degli utenti';



# STRUMENTI
DROP TABLE IF EXISTS Strumenti;
CREATE TABLE IF NOT EXISTS Strumenti (
	nome			VARCHAR(45) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco degli strumenti musicali (comprendente anche la voce)';



# CONOSCENZE
DROP TABLE IF EXISTS Conoscenze;
CREATE TABLE IF NOT EXISTS Conoscenze (
	idConoscenza	INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	utente			VARCHAR(25) NOT NULL,
	strumento		VARCHAR(45) NOT NULL,
	FOREIGN KEY (utente) REFERENCES Utenti (username)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (strumento) REFERENCES Strumenti (nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Conoscenza di uno strumento musicale da parte di un utente';



# ANNUNCI
DROP TABLE IF EXISTS Annunci;
CREATE TABLE IF NOT EXISTS Annunci (
	gruppo		    INT(10) UNSIGNED,
	ruoloRichiesto	VARCHAR(45),
	PRIMARY KEY (gruppo, ruoloRichiesto),
	FOREIGN KEY (gruppo) REFERENCES Gruppi (idGruppo)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (ruoloRichiesto) REFERENCES Strumenti (nome)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Annunci dei gruppi che hanno bisogno di un particolare strumento';



# RICHIESTEPARTECIPAZIONE
DROP TABLE IF EXISTS RichiestePartecipazione;
CREATE TABLE IF NOT EXISTS RichiestePartecipazione (
	utente		    VARCHAR(25),
	gruppo		    INT(10) UNSIGNED,
	richiestaDaGruppo TINYINT(1) NOT NULL COMMENT '1 se il gruppo ha chiesto all\'utente; 0 se l\'utente ha chiesto al gruppo',
	PRIMARY KEY (utente, gruppo),
	FOREIGN KEY (utente) REFERENCES Utenti (username)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (gruppo) REFERENCES Gruppi (idGruppo)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Richieste di partecipazione ad un gruppo (da parte dell\'utente o su invito del gruppo stesso)';



# FORMAZIONI
DROP TABLE IF EXISTS Formazioni;
CREATE TABLE IF NOT EXISTS Formazioni (
	gruppo		    INT(10) UNSIGNED COMMENT 'Gruppo a cui partecipa l\'utente in Ruolo',
	ruolo		    INT(10) UNSIGNED COMMENT 'Ruolo all\'interno del gruppo',
	PRIMARY KEY (gruppo, ruolo),
	FOREIGN KEY (gruppo) REFERENCES Gruppi (idGruppo)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
    FOREIGN KEY (ruolo) REFERENCES Conoscenze (idConoscenza)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Legami tra gli utenti e i gruppi (cioè come i gruppi sono formati)';



### POPOLAMENTO: ###

INSERT INTO Regioni (nome) VALUES
('Abruzzo'),
('Basilicata'),
('Calabria'),
('Campania'),
('Emilia-Romagna'),
('Friuli-Venezia Giulia'),
('Lazio'),
('Liguria'),
('Lombardia'),
('Marche'),
('Molise'),
('Piemonte'),
('Puglia'),
('Sardegna'),
('Sicilia'),
('Toscana'),
('Trentino-Alto Adige'),
('Umbria'),
('Valle d\'Aosta'),
('Veneto');

INSERT INTO Province (nome, sigla, regione) VALUES
('Chieti', 'CH', 'Abruzzo'),
('L\'Aquila', 'AQ', 'Abruzzo'),
('Pescara', 'PE', 'Abruzzo'),
('Teramo', 'TE', 'Abruzzo'),
('Matera', 'MT', 'Basilicata'),
('Potenza', 'PZ', 'Basilicata'),
('Catanzaro', 'CZ', 'Calabria'),
('Cosenza', 'CS', 'Calabria'),
('Crotone', 'KR', 'Calabria'),
('Reggio Calabria', 'RC', 'Calabria'),
('Vibo Valentia', 'VV', 'Calabria'),
('Avellino', 'AV', 'Campania'),
('Benevento', 'BN', 'Campania'),
('Caserta', 'CE', 'Campania'),
('Napoli', 'NA', 'Campania'),
('Salerno', 'SA', 'Campania'),
('Bologna', 'BO', 'Emilia-Romagna'),
('Ferrara', 'FE', 'Emilia-Romagna'),
('Forli Cesena', 'FC', 'Emilia-Romagna'),
('Modena', 'MO', 'Emilia-Romagna'),
('Parma', 'PR', 'Emilia-Romagna'),
('Piacenza', 'PC', 'Emilia-Romagna'),
('Ravenna', 'RA', 'Emilia-Romagna'),
('Reggio Emilia', 'RE', 'Emilia-Romagna'),
('Rimini', 'RN', 'Emilia-Romagna'),
('Gorizia', 'GO', 'Friuli-Venezia Giulia'),
('Pordenone', 'PN', 'Friuli-Venezia Giulia'),
('Trieste', 'TS', 'Friuli-Venezia Giulia'),
('Udine', 'UD', 'Friuli-Venezia Giulia'),
('Frosinone', 'FR', 'Lazio'),
('Latina', 'LT', 'Lazio'),
('Rieti', 'RI', 'Lazio'),
('Roma', 'RM', 'Lazio'),
('Viterbo', 'VT', 'Lazio'),
('Genova', 'GE', 'Liguria'),
('Imperia', 'IM', 'Liguria'),
('La Spezia', 'SP', 'Liguria'),
('Savona', 'SV', 'Liguria'),
('Bergamo', 'BG', 'Lombardia'),
('Brescia', 'BS', 'Lombardia'),
('Como', 'CO', 'Lombardia'),
('Cremona', 'CR', 'Lombardia'),
('Lecco', 'LC', 'Lombardia'),
('Lodi', 'LO', 'Lombardia'),
('Mantova', 'MN', 'Lombardia'),
('Milano', 'MI', 'Lombardia'),
('Monza e della Brianza', 'MB', 'Lombardia'),
('Pavia', 'PV', 'Lombardia'),
('Sondrio', 'SO', 'Lombardia'),
('Varese', 'VA', 'Lombardia'),
('Ancona', 'AN', 'Marche'),
('Ascoli Piceno', 'AP', 'Marche'),
('Fermo', 'FM', 'Marche'),
('Macerata', 'MC', 'Marche'),
('Pesaro e Urbino', 'PU', 'Marche'),
('Campobasso', 'CB', 'Molise'),
('Isernia', 'IS', 'Molise'),
('Alessandria', 'AL', 'Piemonte'),
('Asti', 'AT', 'Piemonte'),
('Biella', 'BI', 'Piemonte'),
('Cuneo', 'CN', 'Piemonte'),
('Novara', 'NO', 'Piemonte'),
('Torino', 'TO', 'Piemonte'),
('Verbano-Cusio-Ossola', 'VB', 'Piemonte'),
('Vercelli', 'VC', 'Piemonte'),
('Bari', 'BA', 'Puglia'),
('Barletta-Andria-Trani', 'BT', 'Puglia'),
('Brindisi', 'BR', 'Puglia'),
('Foggia', 'FG', 'Puglia'),
('Lecce', 'LE', 'Puglia'),
('Taranto', 'TA', 'Puglia'),
('Cagliari', 'CA', 'Sardegna'),
('Carbonia-Iglesias', 'CI', 'Sardegna'),
('Medio Campidano', 'VS', 'Sardegna'),
('Nuoro', 'NU', 'Sardegna'),
('Ogliastra', 'OG', 'Sardegna'),
('Olbia-Tempio', 'OT', 'Sardegna'),
('Oristano', 'OR', 'Sardegna'),
('Sassari', 'SS', 'Sardegna'),
('Agrigento', 'AG', 'Sicilia'),
('Caltanissetta', 'CL', 'Sicilia'),
('Catania', 'CT', 'Sicilia'),
('Enna', 'EN', 'Sicilia'),
('Messina', 'ME', 'Sicilia'),
('Palermo', 'PA', 'Sicilia'),
('Ragusa', 'RG', 'Sicilia'),
('Siracusa', 'SR', 'Sicilia'),
('Trapani', 'TP', 'Sicilia'),
('Arezzo', 'AR', 'Toscana'),
('Firenze', 'FI', 'Toscana'),
('Grosseto', 'GR', 'Toscana'),
('Livorno', 'LI', 'Toscana'),
('Lucca', 'LU', 'Toscana'),
('Massa-Carrara', 'MS', 'Toscana'),
('Pisa', 'PI', 'Toscana'),
('Pistoia', 'PT', 'Toscana'),
('Prato', 'PO', 'Toscana'),
('Siena', 'SI', 'Toscana'),
('Bolzano', 'BZ', 'Trentino-Alto Adige'),
('Trento', 'TN', 'Trentino-Alto Adige'),
('Perugia', 'PG', 'Umbria'),
('Terni', 'TR', 'Umbria'),
('Aosta', 'AO', 'Valle d\'Aosta'),
('Belluno', 'BL', 'Veneto'),
('Padova', 'PD', 'Veneto'),
('Rovigo', 'RO', 'Veneto'),
('Treviso', 'TV', 'Veneto'),
('Venezia', 'VE', 'Veneto'),
('Verona', 'VR', 'Veneto'),
('Vicenza', 'VI', 'Veneto');

INSERT INTO Gruppi (idGruppo, nome, immagine, descrizione, dataIscrizione, provincia) VALUES
(NULL, 'The Leatles', NULL, 'La nostra musica fa bene all\'anima!', '2007-02-21', 'PD'),
(NULL, 'Pitura Sekka', NULL, 'Molti anni di esperienza alle spalle, con un sacco di esibizioni dal vivo.', '2002-03-20', 'VE'),
(NULL, 'The Sailers', NULL, NULL, '2003-04-12', 'CA'),
(NULL, 'Left Zeppelin', NULL, 'Cover band (e grandi fan) dei Right Zeppelin.', '2003-12-01', 'VS'),
(NULL, 'De La Troll', NULL, 'Do re mi fa troll', '2012-02-28', 'AG'),
(NULL, 'Miles Travis Quintet', NULL, 'Il grande Miles Travis conta su di noi. Non lo abbiamo mai deluso!', '2004-10-31', 'FI'),
(NULL, 'Passive Attack', NULL, NULL, '2004-09-30', 'GR'),
(NULL, 'Perl Jam', NULL, 'Evviva Perl!', '2012-03-21', 'PD'),
(NULL, 'Radiobox', NULL, NULL, '2012-06-24', 'GR'),
(NULL, 'Proxy Music', NULL, 'Ci sarà sempre bisogno di un proxy...', '2016-12-17', 'BR'),
(NULL, 'Queries of the Stone Age', NULL, NULL, '2001-04-18', 'OR'),
(NULL, 'U3', NULL, 'Rock, pop e un po\' di beat elettronici...', '2016-02-07', 'RM'),
(NULL, 'Alice in JS', NULL, 'Cover band degli Alice in Chains', '2002-01-03', 'RM'),
(NULL, 'JSON Five', NULL, NULL, '1999-10-16', 'LE'),
(NULL, 'The Spiders from Google', NULL, NULL, '2001-10-13', 'CR'),
(NULL, 'Gruppo Anonimo', NULL, 'In missione per popolare il database di BandBoard', '2000-01-31', 'LE'),
(NULL, 'Files', NULL, 'Principalmente grunge e heavy metal.', '2002-04-10', 'MI'),
(NULL, 'Maroon 3', NULL, NULL, '2002-06-10', 'MI');

INSERT INTO TipiContatti (nome) VALUES
('email_pubblica'), # diversa dal campo email di Utenti (che è privato)
('whatsapp'),
('telegram'),
('youtube'),
('facebook');

INSERT INTO ContattiGruppi (idContatto, gruppo, tipoContatto, contatto) VALUES
(NULL, 4, 'facebook', 'https://www.facebook.com/ledzeppelin'),
(NULL, 4, 'youtube', 'https://www.youtube.com/user/ledzeppelin'),
(NULL, 3, 'facebook', 'https://www.facebook.com/wailers'),
(NULL, 1, 'facebook', 'https://www.facebook.com/thebeatles'),
(NULL, 7, 'youtube', 'https://www.youtube.com/user/MassiveAttackVEVO'),
(NULL, 8, 'youtube', 'https://www.youtube.com/user/PearljamVEVO'),
(NULL, 9, 'youtube', 'https://www.youtube.com/user/radiohead'),
(NULL, 11, 'facebook', 'https://www.facebook.com/QOTSA'),
(NULL, 18, 'facebook', 'https://www.facebook.com/maroon5'),
(NULL, 14, 'facebook', 'https://www.facebook.com/pages/The-Jackson-Five/21921713821'),
(NULL, 14, 'youtube', 'https://www.youtube.com/channel/UC61njVWfh29aExlrfE-2B5w'),
(NULL, 13, 'youtube', 'https://www.youtube.com/user/AliceInChainsVEVO');

INSERT INTO Utenti (username, password, email, nome, cognome, dataNascita, immagine, descrizione, dataIscrizione, provincia) VALUES
('miles26', '3a555c464988e33e52f96beffbe3b1ac', 'miles@milesinthesky.jazz', 'Miles', 'Travis', '1926-05-26', NULL, 'Jazz e Blues nel sangue dalla nascita.' ,'1998-11-04', 'FI'),
('McPaul42', '3a555c464988e33e52f96beffbe3b1ac', 'paulmcc@theleatles.lsd', 'Paul', 'McCartney', '1942-06-18', NULL, NULL, '2001-02-18', 'PD'),
('SuperPippo', '3a555c464988e33e52f96beffbe3b1ac', 'super.pippo@example.com', 'Pippo', 'Super', '1992-12-21', NULL, NULL, '2014-10-31', 'PD'),
('giorgio', '3a555c464988e33e52f96beffbe3b1ac', 'giorgio.giuffre@studenti.unipd.it', 'Giorgio', 'Giuffrè', '1994-02-23', NULL, 'Suonatore di pianoforte - ascoltatore eclettico.' ,'2016-12-19', 'PD'),
('rob_wyatt', '3a555c464988e33e52f96beffbe3b1ac', 'robert@softmachine.org', 'Robert', 'Wyatt', '1945-01-28', NULL, NULL, '1997-10-08', 'LU'),
('millenium_bug', '3a555c464988e33e52f96beffbe3b1ac', 'milbug@ctime.h', 'Milly', 'Bug', '1970-01-01', NULL, 'Ormai in pensione ma sempre sul pezzo.','1999-12-31', 'AG'),
('ennesimo', '3a555c464988e33e52f96beffbe3b1ac', 'ennesimo.utente@popolamento.db', 'Enrico', 'Nesimo', '1995-12-02', NULL, NULL, '2012-04-04', 'EN'),
('mrossi', '3a555c464988e33e52f96beffbe3b1ac', 'marta.rossi@nomifamosi.it', 'Marta', 'Rossi', '1989-02-02', NULL, 'Spolvero la mia chitarra solo in ferie ma sogno di poterla suonare un giorno anche per lavoro... Ho imparato a suonare l\'organo da piccolo e solo di recente la chitarra.', '2009-04-02', 'PD'),
('tverdi', '3a555c464988e33e52f96beffbe3b1ac', 'tizio.verdi@nomifamosi.it', 'Tizio', 'Verdi', '1994-12-27', NULL, NULL, '2016-08-30', 'RM'),
('cverdi', '3a555c464988e33e52f96beffbe3b1ac', 'caio.verdi@nomifamosi.it', 'Caio', 'Verdi', '1997-05-07', NULL, NULL, '2016-08-30', 'RM'),
('sempronio96', '3a555c464988e33e52f96beffbe3b1ac', 'sempronio@nomifamosi.it', 'Sempronio', 'Bianchi', '1996-02-14', NULL, 'DJ, con grande passione per gli anni \'90 (e primi 2000). Disponibile per feste: contattatemi via whatsapp o telegram', '2015-11-11', 'CR'),
('svaughan', '3a555c464988e33e52f96beffbe3b1ac', 'sarahv@example.com', 'Sarah', 'Vaughan', '1924-03-27', NULL, 'Adoro il Jazz e la musica, in generale. Cantare è stato il mio sogno sin dall\'infanzia e rimarrà per sempre una passione, forse ancor più che una professione.','2014-04-19', 'PD'),
('m_json_1', '3a555c464988e33e52f96beffbe3b1ac', 'mick.json@example.com', 'Michael', 'JSON', '1958-08-29', NULL, 'Ho cantato sin dalla più tenera età in gruppo con i miei fratelli; inizio ora una carriera da solista.', '1999-07-28', 'LE'),
('david', '3a555c464988e33e52f96beffbe3b1ac', 'dave_b@example.com', 'David', 'Browser', '1947-01-08', NULL, 'Ringrazio il mio amico Ziggy Stardust per avermi fatto conoscere BandBoard! :)', '1995-11-10', 'MI'),
('cb01', '3a555c464988e33e52f96beffbe3b1ac', 'carlob@nomifamosi.it', 'Carlo', 'Bianchi', '1989-12-07', NULL, NULL, '2012-02-27', 'LE'),
('fiamm-1', '3a555c464988e33e52f96beffbe3b1ac', 'fiamm_1@nomifamosi.it', 'Fiammetta', 'Rossi', '1995-10-01', NULL, 'Esperta in canto jazz, con buona conoscenza del pianoforte', '2016-12-24', 'PG'),
('tverdi_2', '3a555c464988e33e52f96beffbe3b1ac', 'tiziaverdi@nomifamosi.it', 'Tizia', 'Verdi', '1994-03-15', NULL, 'Chitarrista provetta - cantautrice', '2014-12-12', 'RM'),
('bosc', '3a555c464988e33e52f96beffbe3b1ac', 'mbosc@nomifamosi.it', 'Mario', 'Boscolo', '1994-05-07', NULL, NULL, '2015-08-30', 'VE'),
('anto', '3a555c464988e33e52f96beffbe3b1ac', 'anto_b@nomifamosi.it', 'Antonio', 'Boscolo', '1997-01-17', NULL, 'Bassista jazz', '2016-06-30', 'VE'),
('agrigi', '3a555c464988e33e52f96beffbe3b1ac', 'ale_gri@nomiacaso.it', 'Alessia', 'Grigi', '1994-10-21', NULL, 'Studente al conservatorio. Suono il basso e ascolto soprattutto jazz e reggae.', '2017-01-03', 'RN'),
('maria', '3a555c464988e33e52f96beffbe3b1ac', 'mariabianchi@popolamento.db', 'Maria', 'Bianchi', '1995-11-17', NULL, 'Mi ispiro da Big Man della E-Street Band; sunono il sax da 5 anni', '2012-10-01', 'RE'),
('Serena_Gialli', '3a555c464988e33e52f96beffbe3b1ac', 'serena@nomiacaso.it', 'Serena', 'Gialli', '1994-02-14', NULL, NULL, '2002-09-30', 'GO'),
('_carlo_', '3a555c464988e33e52f96beffbe3b1ac', 'cgialli@nomiacaso.it', 'Carlo', 'Gialli', '1999-05-18', NULL, NULL, '2006-09-01', 'GO'),
('user', '3a555c464988e33e52f96beffbe3b1ac', 'user@example.com', 'User', 'Rossi', '1900-01-01', NULL, 'Antico utente di prova, a disposizione di chi volesse accedere con credenziali \'user, user\'.', '2017-02-03', 'PD');

INSERT INTO ContattiUtenti (idContatto, utente, tipoContatto, contatto) VALUES
(NULL, 'giorgio', 'telegram', 'https://telegram.me/ggiuffre'),
(NULL, 'McPaul42', 'youtube', 'https://www.youtube.com/user/PaulMcCartneyVEVO'),
(NULL, 'ennesimo', 'whatsapp', '049 827 0000'),
(NULL, 'sempronio96', 'whatsapp', '340 123 4567'),
(NULL, 'sempronio96', 'telegram', 'https://telegram.me/sempronio96'),
(NULL, 'svaughan', 'whatsapp', '3660123456'),
(NULL, 'm_json_1', 'youtube', 'https://www.youtube.com/user/michaeljacksonVEVO'),
(NULL, 'david', 'youtube', 'https://www.youtube.com/user/DavidBowieVEVO'),
(NULL, 'david', 'facebook', 'https://www.facebook.com/davidbowie/'),
(NULL, 'anto', 'email_pubblica', 'antonio.boscolo@example.com'),
(NULL, 'agrigi', 'email_pubblica', 'alessia.grigi@example.com'),
(NULL, 'maria', 'whatsapp', '0491234567'),
(NULL, 'user', 'whatsapp', '049 0123456');

INSERT INTO GeneriMusicali (nome) VALUES
('Hard Rock'),
('Pop Rock'),
('Pop'),
('Metal'),
('Blues'),
('Classica'),
('Jazz'),
('Hip Hop'),
('Reggae'),
('Punk'),
('Country'),
('Disco'),
('Funk'),
('Grunge'),
('R&B'),
('Vocal'),
('Fusion'),
('House'),
('Gospel'),
('Soul'),
('Hardcore'),
('Techno'),
('Progressive'),
('Psychedelic');

INSERT INTO GeneriGruppi (gruppo, genere) VALUES
(1, 'Pop Rock'),
(1, 'Pop'),
(7, 'Hip Hop'),
(6, 'Jazz'),
(2, 'Reggae'),
(4, 'Hard Rock'),
(10, 'Pop Rock'),
(3, 'Reggae'),
(15, 'Grunge'),
(15, 'Metal');

INSERT INTO GeneriUtenti (utente, genere) VALUES
('miles26', 'Jazz'),
('miles26', 'Blues'),
('McPaul42', 'Pop Rock'),
('McPaul42', 'Blues'),
('SuperPippo', 'Soul'),
('giorgio', 'Psychedelic'),
('giorgio', 'Progressive'),
('giorgio', 'Techno'),
('giorgio', 'Pop'),
('millenium_bug', 'Disco'),
('ennesimo', 'Country'),
('mrossi', 'Hard Rock'),
('tverdi', 'Hard Rock'),
('sempronio96', 'Disco'),
('sempronio96', 'Techno'),
('sempronio96', 'Pop'),
('svaughan', 'Jazz'),
('svaughan', 'Fusion'),
('david', 'Pop Rock'),
('david', 'Psychedelic'),
('m_json_1', 'Pop'),
('m_json_1', 'Disco'),
('cb01', 'Metal'),
('fiamm-1', 'Jazz'),
('tverdi_2', 'Country'),
('anto', 'Jazz'),
('agrigi', 'Reggae'),
('agrigi', 'Jazz'),
('maria', 'Soul'),
('maria', 'Jazz'),
('maria', 'R&B'),
('maria', 'Hip Hop'),
('Serena_Gialli', 'Techno'),
('Serena_Gialli', 'Psychedelic'),
('_carlo_', 'Blues'),
('_carlo_', 'Hard Rock'),
('_carlo_', 'Funk'),
('user', 'Pop Rock'),
('user', 'Grunge'),
('user', 'Funk');

INSERT INTO Strumenti (nome) VALUES
('Chitarra Elettrica'),
('Chitarra Acustica'),
('Basso Elettrico'),
('Contrabbasso'),
('Batteria'),
('Organo'),
('Pianoforte'),
('Sassofono'),
('Voce'),
('Violino'),
('Tromba'),
('Computer');

INSERT INTO Conoscenze (idConoscenza, utente, strumento) VALUES
(NULL, 'miles26', 'Tromba'),
(NULL, 'millenium_bug', 'Computer'),
(NULL, 'ennesimo', 'Chitarra Elettrica'),
(NULL, 'ennesimo', 'Organo'),
(NULL, 'giorgio', 'Pianoforte'),
(NULL, 'ennesimo', 'Batteria'),
(NULL, 'rob_wyatt', 'Batteria'),
(NULL, 'rob_wyatt', 'Voce'),
(NULL, 'McPaul42', 'Chitarra Acustica'),
(NULL, 'McPaul42', 'Chitarra Elettrica'),
(NULL, 'svaughan', 'Voce'),
(NULL, 'sempronio96', 'Computer'),
(NULL, 'mrossi', 'Organo'),
(NULL, 'tverdi', 'Batteria'),
(NULL, 'cverdi', 'Computer'),
(NULL, 'mrossi', 'Chitarra Acustica'),
(NULL, 'm_json_1', 'Voce'),
(NULL, 'david', 'Voce'),
(NULL, 'david', 'Pianoforte'),
(NULL, 'cb01', 'Violino'),
(NULL, 'tverdi', 'Chitarra Acustica'),
(NULL, 'tverdi', 'Chitarra Elettrica'),
(NULL, 'anto', 'Basso Elettrico'),
(NULL, 'agrigi', 'Basso Elettrico'),
(NULL, 'maria', 'Sassofono'),
(NULL, 'Serena_Gialli', 'Computer'),
(NULL, '_carlo_', 'Voce'),
(NULL, '_carlo_', 'Batteria'),
(NULL, 'user', 'Violino'),
(NULL, 'user', 'Tromba');

# INSERT INTO Annunci (gruppo, ruoloRichiesto) VALUES
# inutile: funzionalità non ancora implementata

# INSERT INTO RichiestePartecipazione (utente, gruppo, richiestaDaGruppo) VALUES
# inutile: funzionalità non ancora implementata

INSERT INTO Formazioni (gruppo, ruolo) VALUES
(1, 9),
(1, 10),
(2, 3),
(3, 4),
(3, 11),
(4, 4),
(4, 3),
(5, 8),
(6, 7),
(7, 10),
(8, 9),
(9, 3),
(10, 8),
(13, 8),
(14, 8),
(15, 8),
(17, 14),
(18, 18),
(18, 24),
(18, 28),
(8, 30);
