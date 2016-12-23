### CREAZIONE DATABASE: ###

# SCHEMA DATABASE_ARTISTI
DROP SCHEMA IF EXISTS database_artisti;
CREATE SCHEMA IF NOT EXISTS database_artisti DEFAULT CHARACTER SET utf8;
USE database_artisti;



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
	contatto		VARCHAR(45) NOT NULL COMMENT 'Stringa contenente il recapito',
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
	password	    VARCHAR(45) DEFAULT NULL,
	email			VARCHAR(45) NOT NULL UNIQUE,
	nome            VARCHAR(20) DEFAULT NULL,
	cognome         VARCHAR(20) DEFAULT NULL,
	dataNascita		DATE NOT NULL,
	immagine		VARCHAR(45) DEFAULT NULL COMMENT 'Immagine profilo; NULL implica immagine di default',
	descrizione     VARCHAR(200) DEFAULT NULL COMMENT 'Descrizione dell\'utente',
	dataIscrizione	DATETIME NOT NULL,
	provincia		CHAR(2) NOT NULL COMMENT 'Provincia di residenza',
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
	contatto		VARCHAR(45) NOT NULL COMMENT 'Stringa contenente il recapito',
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

# REGIONI
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



# PROVINCIE
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



# GENERIMUSICALI
INSERT INTO GeneriMusicali (nome) VALUES
('Rock'),
('Pop'),
('Pop Rock'),
('Metal'),
('Blues'),
('Classica'),
('Jazz'),
('Acustica'),
('Minimal'),
('Hip-hop'),
('Reggae'),
('Punk'),
('Goth'),
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
('Polka'),
('Progressive'),
('Psychedelic'),
('Britpop');



# TIPICONTATTI
INSERT INTO TipiContatti VALUES
('email'),
('whatsapp'),
('telegram'),
('youtube'),
('facebook');

