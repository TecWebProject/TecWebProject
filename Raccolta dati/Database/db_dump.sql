### CREAZIONE DATABASE:

# Schema database_artisti
DROP SCHEMA IF EXISTS database_artisti;
CREATE SCHEMA IF NOT EXISTS database_artisti DEFAULT CHARACTER SET utf8;
USE database_artisti;



### CREAZIONE SCHEMA:

# Regione
DROP TABLE IF EXISTS Regione;
CREATE TABLE IF NOT EXISTS Regione (
	Nome			VARCHAR(25) PRIMARY KEY COMMENT 'Nome completo'
)
ENGINE = InnoDB
COMMENT = 'Elenco delle regioni d\'Italia';



# Provincia
DROP TABLE IF EXISTS Provincia;
CREATE TABLE IF NOT EXISTS Provincia (
	Sigla			CHAR(2) PRIMARY KEY COMMENT 'Sigla',
	Nome			VARCHAR(30) NOT NULL COMMENT 'Nome completo',
	Regione			VARCHAR(25) NOT NULL COMMENT 'Regione di appartenenza'
					REFERENCES Regione (Nome)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION
)
ENGINE = InnoDB
COMMENT = 'Elenco delle provincie d\'Italia, con regione di appartenenza';



# Gruppo
DROP TABLE IF EXISTS Gruppo;
CREATE TABLE IF NOT EXISTS Gruppo (
	idGruppo		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	Nome			VARCHAR(45) NOT NULL COMMENT 'Nome del gruppo; possono esserci più gruppi con lo stesso nome (sono distinti grazie a idGruppo)',
	Provincia		CHAR(2) NOT NULL REFERENCES Provincia (Sigla)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
	Immagine		VARCHAR(45) DEFAULT NULL COMMENT 'Immagine profilo; NULL implica immagine di default',
	DataIscrizione	DATETIME NOT NULL
)
ENGINE = InnoDB
COMMENT = 'Dati generici dei gruppi';



# TipoContatto
DROP TABLE IF EXISTS TipoContatto;
CREATE TABLE IF NOT EXISTS TipoContatto (
	Nome			VARCHAR(20) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco delle tipologie di contatto: mail, facebook, whatsapp, ecc...';



# ContattoGruppo
DROP TABLE IF EXISTS ContattoGruppo;
CREATE TABLE IF NOT EXISTS ContattoGruppo (
	idContatto		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	idGruppo		INT(10) UNSIGNED NOT NULL REFERENCES Gruppo (idGruppo)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	TipoContatto	VARCHAR(20) NOT NULL REFERENCES TipoContatto (Nome)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	Contatto		VARCHAR(100) NOT NULL COMMENT 'Stringa contenente il recapito'
)
ENGINE = InnoDB
COMMENT = 'Recapiti dei gruppi';



# Utente
DROP TABLE IF EXISTS Utente;
CREATE TABLE IF NOT EXISTS Utente (
	Username		VARCHAR(25) PRIMARY KEY COMMENT 'Username',
	Email			VARCHAR(45) NOT NULL UNIQUE COMMENT 'Email',
	DataNascita		DATE NOT NULL COMMENT 'Anno di nascita',
	Provincia		CHAR(2) NOT NULL COMMENT 'Provincia di abitazione'
					REFERENCES Provincia (Sigla)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
	DataIscrizione	DATE NOT NULL,
	Immagine		VARCHAR(45) DEFAULT NULL COMMENT 'Immagine profilo; NULL implica immagine di default',
	HashedPassword	VARCHAR(45) DEFAULT NULL COMMENT 'Password criptata'
)
ENGINE = InnoDB
COMMENT = 'Dati generici degli utenti';



# ContattoUtente
DROP TABLE IF EXISTS ContattoUtente;
CREATE TABLE IF NOT EXISTS ContattoUtente (
	idContatto		INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID univoco del contatto',
	Username		VARCHAR(25) NOT NULL REFERENCES Utente (Username)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	TipoContatto	VARCHAR(20) NOT NULL REFERENCES TipoContatto (Nome)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	Contatto		VARCHAR(45) NOT NULL COMMENT 'Stringa contenente il recapito'
)
ENGINE = InnoDB
COMMENT = 'Recapiti degli utenti';



# GenereMusicale
DROP TABLE IF EXISTS GenereMusicale;
CREATE TABLE IF NOT EXISTS GenereMusicale (
	Nome			VARCHAR(45) PRIMARY KEY COMMENT 'Nome completo del genere musicale'
)
ENGINE = InnoDB
COMMENT = 'Elenco dei generi musicali riconosciuti dal sito';



# GenereGruppo
DROP TABLE IF EXISTS GenereGruppo;
CREATE TABLE IF NOT EXISTS GenereGruppo (
	idGruppo		INT(10) UNSIGNED NOT NULL REFERENCES Gruppo (idGruppo)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	Genere			VARCHAR(45) NOT NULL REFERENCES GenereMusicale (Nome)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	PRIMARY KEY (idGruppo, Genere)
)
ENGINE = InnoDB
COMMENT = 'Preferenze musicali dei gruppi';



# GenereUtente
DROP TABLE IF EXISTS GenereUtente;
CREATE TABLE IF NOT EXISTS GenereUtente (
	Utente			VARCHAR(25) NOT NULL REFERENCES Utente (Username)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	Genere			VARCHAR(45) NOT NULL REFERENCES GenereMusicale (Nome)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	PRIMARY KEY (Utente, Genere)
)
ENGINE = InnoDB
COMMENT = 'Preferenze musicali degli utenti';



# Strumento
DROP TABLE IF EXISTS Strumento;
CREATE TABLE IF NOT EXISTS Strumento (
	Nome			VARCHAR(45) PRIMARY KEY
)
ENGINE = InnoDB
COMMENT = 'Elenco degli strumenti musicali (comprendente anche la voce)';



# Conoscenza
DROP TABLE IF EXISTS Conoscenza;
CREATE TABLE IF NOT EXISTS Conoscenza (
	idConoscenza	INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID univoco',
	Utente			VARCHAR(25) NOT NULL REFERENCES Utente (Username)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	Strumento		VARCHAR(45) NOT NULL REFERENCES Strumento (Nome)
					ON DELETE CASCADE
					ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Conoscenza di uno strumento musicale da parte di un utente';



# Annuncio
DROP TABLE IF EXISTS Annuncio;
CREATE TABLE IF NOT EXISTS Annuncio (
	idGruppo		INT(10) UNSIGNED PRIMARY KEY REFERENCES Gruppo (idGruppo)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
	RuoloRichiesto	VARCHAR(45) NOT NULL REFERENCES Strumento (Nome)
					ON DELETE CASCADE
					ON UPDATE CASCADE
)
ENGINE = InnoDB
COMMENT = 'Annunci dei gruppi che hanno bisogno di un particolare strumento';



# RichiestaPartecipazione
DROP TABLE IF EXISTS RichiestaPartecipazione;
CREATE TABLE IF NOT EXISTS RichiestaPartecipazione (
	Utente			VARCHAR(25) NOT NULL REFERENCES Utente (Username)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
	idGruppo		INT(10) UNSIGNED NOT NULL REFERENCES Gruppo (idGruppo)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	RichiestaDaGruppo TINYINT(1) NOT NULL COMMENT 'Vero se il gruppo ha chiesto all\'utente; falso se l\'utente ha chiesto al gruppo',
	PRIMARY KEY (Utente, idGruppo)
)
ENGINE = InnoDB
COMMENT = 'Richieste di partecipazione ad un gruppo (da parte dell\'utente o su invito del gruppo stesso)';



# Formazione
DROP TABLE IF EXISTS Formazione;
CREATE TABLE IF NOT EXISTS Formazione (
	idGruppo		INT(10) UNSIGNED NOT NULL COMMENT 'ID gruppo a cui partecipa l\'utente in Ruolo'
					REFERENCES Gruppo (idGruppo)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	Ruolo			VARCHAR(45) NOT NULL COMMENT 'Ruolo all\'interno del gruppo'
					REFERENCES Conoscenza (idConoscenza)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
	PRIMARY KEY (idGruppo, Ruolo)
)
ENGINE = InnoDB
COMMENT = 'Legami tra gli utenti e i gruppi (cioè come i gruppi sono formati)';



### POPOLAMENTO:

INSERT INTO Regione (Nome) VALUES
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

INSERT INTO Provincia (Nome, Sigla, Regione) VALUES
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

INSERT INTO Gruppo (idGruppo, Nome, Provincia, Immagine, DataIscrizione) VALUES
(NULL, 'The Leatles', 'PD', NULL, '2007-02-21'),
(NULL, 'Pintura Sekka', 'VE', NULL, '2002-03-20'),
(NULL, 'The Sailers', 'CA', NULL, '2003-04-12'),
(NULL, 'Left Zeppelin', 'VS', NULL, '2003-12-01'),
(NULL, 'De La Troll', 'AG', NULL, '2012-02-28'),
(NULL, 'Miles Travis Quintet', 'FI', NULL, '2004-10-31'),
(NULL, 'Passive Attack', 'GR', NULL, '2004-09-30'),
(NULL, 'Perl Jam', 'PD', NULL, '2012-03-21'),
(NULL, 'Radiobread', 'GR', NULL, '2012-06-24'),
(NULL, 'Foxy Music', 'BR', NULL, '2016-12-17');

INSERT INTO TipoContatto VALUES
('email'),
('whatsapp'),
('telegram'),
('youtube'),
('facebook');

INSERT INTO ContattoGruppo (idContatto, idGruppo, TipoContatto, Contatto) VALUES
(NULL, 4, 'facebook', 'https://www.facebook.com/ledzeppelin'),
(NULL, 4, 'youtube', 'https://www.youtube.com/user/ledzeppelin'),
(NULL, 3, 'facebook', 'https://www.facebook.com/wailers'),
(NULL, 1, 'facebook', 'https://www.facebook.com/thebeatles'),
(NULL, 7, 'youtube', 'https://www.youtube.com/user/MassiveAttackVEVO'),
(NULL, 8, 'youtube', 'https://www.youtube.com/user/PearljamVEVO'),
(NULL, 9, 'youtube', 'https://www.youtube.com/user/radiohead');

INSERT INTO Utente (Username, Email, DataNascita, Provincia, DataIscrizione, Immagine, HashedPassword) VALUES
('miles26', 'miles@milesinthesky.jazz', '1926-05-26', 'FI', '1998-11-04', NULL, SHA1('user')),
('McPaul42', 'paulmcc@theleatles.lsd', '1942-06-18', 'PD', '2001-02-18', NULL, SHA1('user')),
('SuperPippo', 'super.pippo@example.com', '1992-12-21', 'PD', '2014-10-31', NULL, SHA1('user')),
('giorgio', 'giorgio.giuffre@studenti.unipd.it', '1994-02-23', 'PD', '2016-12-19', NULL, SHA1('user')),
('millenium_bug', 'milbug@ctime.org', '1970-01-01', 'AG', '1999-12-31', NULL, SHA1('user')),
('ennesimo', 'ennesimo.utente@popolamento.db', '1995-12-02', 'EN', '2012-04-04', NULL, SHA1('user'));

INSERT INTO ContattoUtente (idContatto, Username, TipoContatto, Contatto) VALUES
(NULL, 'giorgio', 'telegram', 'telegram.me/ggiuffre');

INSERT INTO GenereMusicale (Nome) VALUES
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
('Progressive'),
('Psychedelic');

INSERT INTO GenereGruppo (idGruppo, Genere) VALUES
(1, 'Pop Rock'),
(1, 'Pop'),
(7, 'Hip Hop'),
(6, 'Jazz'),
(2, 'Reggae'),
(4, 'Hard Rock'),
(10, 'Pop Rock'),
(3, 'Reggae');

INSERT INTO GenereUtente (Utente, Genere) VALUES
('miles26', 'Jazz'),
('miles26', 'Blues'),
('McPaul42', 'Pop Rock'),
('McPaul42', 'Blues'),
('SuperPippo', 'Soul'),
('giorgio', 'Psychedelic'),
('millenium_bug', 'Disco'),
('ennesimo', 'Country');

INSERT INTO Strumento (Nome) VALUES
('Chitarra Elettrica'),
('Chitarra Acustica'),
('Basso Elettrico'),
('Batteria'),
('Organo'),
('Pianoforte'),
('Sassofono'),
('Voce'),
('Violino'),
('Tromba'),
('Computer');

INSERT INTO Conoscenza (Utente, Strumento) VALUES
('miles26', 'Tromba'),
('millenium_bug', 'Computer'),
('ennesimo', 'Chitarra Elettrica'),
('ennesimo', 'Organo'),
('giorgio', 'Pianoforte'),
('ennesimo', 'Batteria'),
('McPaul42', 'Chitarra Acustica'),
('McPaul42', 'Chitarra Elettrica');

# TODO: riempire Annuncio, RichiestaPartecipazione e Formazione
