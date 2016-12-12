### CREAZIONE DATABASE:

# Schema database_artisti
DROP SCHEMA IF EXISTS database_artisti;
CREATE SCHEMA IF NOT EXISTS database_artisti DEFAULT CHARACTER SET utf8;
USE database_artisti;





### CREAZIONE SCHEMA:

# Regione
DROP TABLE IF EXISTS Regione;
CREATE TABLE IF NOT EXISTS Regione (
	AbbNome			CHAR(3) PRIMARY KEY COMMENT 'ID univoco',
	Nome			VARCHAR(25) NOT NULL COMMENT 'Nome completo'
)
ENGINE = InnoDB
COMMENT = 'Elenco delle regioni d\'Italia';



# Provincia
DROP TABLE IF EXISTS Provincia;
CREATE TABLE IF NOT EXISTS Provincia (
	Sigla			CHAR(2) PRIMARY KEY COMMENT 'Sigla',
	Nome			VARCHAR(30) NOT NULL COMMENT 'Nome completo',
	Regione			CHAR(3) NOT NULL COMMENT 'Regione di appartenenza'
					REFERENCES Regione (Abbr)
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
	Contatto		VARCHAR(45) NOT NULL COMMENT 'Stringa contenente il recapito'
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
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
	PRIMARY KEY (idGruppo, Ruolo)
)
ENGINE = InnoDB
COMMENT = 'Legami tra gli utenti e i gruppi (cioè come i gruppi sono formati)';





### POPOLAMENTO:

# Regione
INSERT INTO Regione (AbbNome, Nome) VALUES
('ABR', 'Abruzzo'),
('BAS', 'Basilicata'),
('CAL', 'Calabria'),
('CAM', 'Campania'),
('EMR', 'Emilia-Romagna'),
('FVG', 'Friuli-Venezia Giulia'),
('LAZ', 'Lazio'),
('LIG', 'Liguria'),
('LOM', 'Lombardia'),
('MAR', 'Marche'),
('MOL', 'Molise'),
('PIE', 'Piemonte'),
('PUG', 'Puglia'),
('SAR', 'Sardegna'),
('SIC', 'Sicilia'),
('TOS', 'Toscana'),
('TAA', 'Trentino-Alto Adige'),
('UMB', 'Umbria'),
('VDA', 'Valle d\'Aosta'),
('VEN', 'Veneto');



# Provincia
INSERT INTO Provincia (Nome, Sigla, Regione) VALUES
('Chieti', 'CH', 'ABR'),
('L\'Aquila', 'AQ', 'ABR'),
('Pescara', 'PE', 'ABR'),
('Teramo', 'TE', 'ABR'),
('Matera', 'MT', 'BAS'),
('Potenza', 'PZ', 'BAS'),
('Catanzaro', 'CZ', 'CAL'),
('Cosenza', 'CS', 'CAL'),
('Crotone', 'KR', 'CAL'),
('Reggio Calabria', 'RC', 'CAL'),
('Vibo Valentia', 'VV', 'CAL'),
('Avellino', 'AV', 'CAM'),
('Benevento', 'BN', 'CAM'),
('Caserta', 'CE', 'CAM'),
('Napoli', 'NA', 'CAM'),
('Salerno', 'SA', 'CAM'),
('Bologna', 'BO', 'EMR'),
('Ferrara', 'FE', 'EMR'),
('Forli Cesena', 'FC', 'EMR'),
('Modena', 'MO', 'EMR'),
('Parma', 'PR', 'EMR'),
('Piacenza', 'PC', 'EMR'),
('Ravenna', 'RA', 'EMR'),
('Reggio Emilia', 'RE', 'EMR'),
('Rimini', 'RN', 'EMR'),
('Gorizia', 'GO', 'FVG'),
('Pordenone', 'PN', 'FVG'),
('Trieste', 'TS', 'FVG'),
('Udine', 'UD', 'FVG'),
('Frosinone', 'FR', 'LAZ'),
('Latina', 'LT', 'LAZ'),
('Rieti', 'RI', 'LAZ'),
('Roma', 'RM', 'LAZ'),
('Viterbo', 'VT', 'LAZ'),
('Genova', 'GE', 'LIG'),
('Imperia', 'IM', 'LIG'),
('La Spezia', 'SP', 'LIG'),
('Savona', 'SV', 'LIG'),
('Bergamo', 'BG', 'LOM'),
('Brescia', 'BS', 'LOM'),
('Como', 'CO', 'LOM'),
('Cremona', 'CR', 'LOM'),
('Lecco', 'LC', 'LOM'),
('Lodi', 'LO', 'LOM'),
('Mantova', 'MN', 'LOM'),
('Milano', 'MI', 'LOM'),
('Monza e della Brianza', 'MB', 'LOM'),
('Pavia', 'PV', 'LOM'),
('Sondrio', 'SO', 'LOM'),
('Varese', 'VA', 'LOM'),
('Ancona', 'AN', 'MAR'),
('Ascoli Piceno', 'AP', 'MAR'),
('Fermo', 'FM', 'MAR'),
('Macerata', 'MC', 'MAR'),
('Pesaro e Urbino', 'PU', 'MAR'),
('Campobasso', 'CB', 'MOL'),
('Isernia', 'IS', 'MOL'),
('Alessandria', 'AL', 'PIE'),
('Asti', 'AT', 'PIE'),
('Biella', 'BI', 'PIE'),
('Cuneo', 'CN', 'PIE'),
('Novara', 'NO', 'PIE'),
('Torino', 'TO', 'PIE'),
('Verbano-Cusio-Ossola', 'VB', 'PIE'),
('Vercelli', 'VC', 'PIE'),
('Bari', 'BA', 'PUG'),
('Barletta-Andria-Trani', 'BT', 'PUG'),
('Brindisi', 'BR', 'PUG'),
('Foggia', 'FG', 'PUG'),
('Lecce', 'LE', 'PUG'),
('Taranto', 'TA', 'PUG'),
('Cagliari', 'CA', 'SAR'),
('Carbonia-Iglesias', 'CI', 'SAR'),
('Medio Campidano', 'VS', 'SAR'),
('Nuoro', 'NU', 'SAR'),
('Ogliastra', 'OG', 'SAR'),
('Olbia-Tempio', 'OT', 'SAR'),
('Oristano', 'OR', 'SAR'),
('Sassari', 'SS', 'SAR'),
('Agrigento', 'AG', 'SIC'),
('Caltanissetta', 'CL', 'SIC'),
('Catania', 'CT', 'SIC'),
('Enna', 'EN', 'SIC'),
('Messina', 'ME', 'SIC'),
('Palermo', 'PA', 'SIC'),
('Ragusa', 'RG', 'SIC'),
('Siracusa', 'SR', 'SIC'),
('Trapani', 'TP', 'SIC'),
('Arezzo', 'AR', 'TOS'),
('Firenze', 'FI', 'TOS'),
('Grosseto', 'GR', 'TOS'),
('Livorno', 'LI', 'TOS'),
('Lucca', 'LU', 'TOS'),
('Massa-Carrara', 'MS', 'TOS'),
('Pisa', 'PI', 'TOS'),
('Pistoia', 'PT', 'TOS'),
('Prato', 'PO', 'TOS'),
('Siena', 'SI', 'TOS'),
('Bolzano', 'BZ', 'TAA'),
('Trento', 'TN', 'TAA'),
('Perugia', 'PG', 'UMB'),
('Terni', 'TR', 'UMB'),
('Aosta', 'AO', 'VDA'),
('Belluno', 'BL', 'VEN'),
('Padova', 'PD', 'VEN'),
('Rovigo', 'RO', 'VEN'),
('Treviso', 'TV', 'VEN'),
('Venezia', 'VE', 'VEN'),
('Verona', 'VR', 'VEN'),
('Vicenza', 'VI', 'VEN');

INSERT INTO GenereMusicale (Nome) VALUES
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

INSERT INTO TipoContatto VALUES
('email'),
('whatsapp'),
('telegram'),
('youtube'),
('facebook');
