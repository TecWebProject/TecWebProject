###########
### Schema:

DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Strumento;
DROP TABLE IF EXISTS Esperienza;

CREATE TABLE Utente (
	username			VARCHAR(30) PRIMARY KEY,
	password			CHAR(40) NOT NULL,			# criptata
	nascita				DATE,
	foto_profilo		VARCHAR(30)					# tipo "utente_1.jpg"
);

CREATE TABLE Strumento (
	nome				VARCHAR(30) PRIMARY KEY
);

CREATE TABLE Esperienza (
	utente				VARCHAR(30) REFERENCES Utente,
	strumento			VARCHAR(30) REFERENCES Strumento,
	PRIMARY KEY (utente, strumento)
);



################
### Popolamento:

INSERT INTO Utente VALUES
('pippo', '[pwd criptata con PHP]', '1994-02-23', NULL),
('paperino93', '[pwd criptata con PHP]', '1993-01-01', NULL);

INSERT INTO Strumento VALUES
('chitarra acustica'),
('basso elettrico'),
('tastiere'),
('batteria'),
('voce'),
('theremin'),
('...'); # eccetera

INSERT INTO Esperienza VALUES
('pippo', 'basso elettrico'); # (pippo suona il basso)
