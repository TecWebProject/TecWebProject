-- MySQL Script generated by MySQL Workbench
-- sab 19 nov 2016 08:15:10 CET
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema database_artisti
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema database_artisti
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `database_artisti` DEFAULT CHARACTER SET utf8 ;
USE `database_artisti` ;

-- -----------------------------------------------------
-- Table `Utente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Utente` ;

CREATE TABLE IF NOT EXISTS `Utente` (
  `idUtente` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID univoco dell\'utente',
  `Username` VARCHAR(25) NOT NULL COMMENT 'Username dell\'utente',
  `Email` VARCHAR(45) NOT NULL COMMENT 'email dell\'utente',
  `AnnoNascita` VARCHAR(45) NOT NULL COMMENT 'Anno di nascita dell\'utente',
  PRIMARY KEY (`idUtente`),
  UNIQUE INDEX `username_UNIQUE` (`Username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`Email` ASC))
ENGINE = InnoDB
COMMENT = 'Tabella dei dati base degli utenti';


-- -----------------------------------------------------
-- Table `Ruolo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Ruolo` ;

CREATE TABLE IF NOT EXISTS `Ruolo` (
  `Nome` VARCHAR(45) NOT NULL COMMENT 'Nome dello strumento',
  PRIMARY KEY (`Nome`))
ENGINE = InnoDB
COMMENT = 'Tabella degli strumenti';


-- -----------------------------------------------------
-- Table `UtenteEsegueRuolo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UtenteEsegueRuolo` ;

CREATE TABLE IF NOT EXISTS `UtenteEsegueRuolo` (
  `idUtente` INT UNSIGNED NOT NULL,
  `Ruolo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idUtente`, `Ruolo`),
  INDEX `fk_Strumento_UtenteEsegueRuolo_idx` (`Ruolo` ASC),
  CONSTRAINT `fk_idUtente_UtenteEsegueRuolo`
    FOREIGN KEY (`idUtente`)
    REFERENCES `Utente` (`idUtente`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ruolo_UtenteEsegueRuolo`
    FOREIGN KEY (`Ruolo`)
    REFERENCES `Ruolo` (`Nome`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Tabella di collegamento tra gli utenti e gli strumenti che suonano';


-- -----------------------------------------------------
-- Table `TipoContatto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TipoContatto` ;

CREATE TABLE IF NOT EXISTS `TipoContatto` (
  `Nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Nome`))
ENGINE = InnoDB
COMMENT = 'Tabella dei tipi di contatto, per distinguere tra mail, facebook, whatsapp, ecc... i vari contatti forniti';


-- -----------------------------------------------------
-- Table `ContattoUtente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ContattoUtente` ;

CREATE TABLE IF NOT EXISTS `ContattoUtente` (
  `idContatto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUtente` INT UNSIGNED NOT NULL,
  `tipoContatto` VARCHAR(20) NOT NULL,
  `Contatto` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idContatto`),
  INDEX `fk_tipoContatto_ContattoUtente_idx` (`tipoContatto` ASC),
  CONSTRAINT `fk_idUtente_ContattoUtente`
    FOREIGN KEY (`idUtente`)
    REFERENCES `Utente` (`idUtente`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tipoContatto_ContattoUtente`
    FOREIGN KEY (`tipoContatto`)
    REFERENCES `TipoContatto` (`Nome`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Tabella dei contatti degli utenti';


-- -----------------------------------------------------
-- Table `Band`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Band` ;

CREATE TABLE IF NOT EXISTS `Band` (
  `idBand` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idBand`))
ENGINE = InnoDB
COMMENT = 'Tabella dati generici di una band\n';


-- -----------------------------------------------------
-- Table `UtenteMembroBand`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `UtenteMembroBand` ;

CREATE TABLE IF NOT EXISTS `UtenteMembroBand` (
  `idUtente` INT UNSIGNED NOT NULL,
  `idBand` INT UNSIGNED NOT NULL,
  `StrumentoSuonato` VARCHAR(45) NULL,
  PRIMARY KEY (`idUtente`, `idBand`),
  INDEX `fk_idBand_idx` (`idBand` ASC),
  INDEX `fk_Ruolo_idx` (`StrumentoSuonato` ASC),
  CONSTRAINT `fk_idUtente_UtenteMembroBand`
    FOREIGN KEY (`idUtente`)
    REFERENCES `Utente` (`idUtente`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_idBand_UtenteMembroBand`
    FOREIGN KEY (`idBand`)
    REFERENCES `Band` (`idBand`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ruolo_UtenteMembroBand`
    FOREIGN KEY (`StrumentoSuonato`)
    REFERENCES `UtenteEsegueRuolo` (`Ruolo`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Tabella dei legami tra gli utenti e le band';


-- -----------------------------------------------------
-- Table `RichiestaMembro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `RichiestaMembro` ;

CREATE TABLE IF NOT EXISTS `RichiestaMembro` (
  `idBand` INT UNSIGNED NOT NULL,
  `RuoloRichiesto` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idBand`),
  CONSTRAINT `fk_idBand`
    FOREIGN KEY (`idBand`)
    REFERENCES `Band` (`idBand`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ContattoBand`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ContattoBand` ;

CREATE TABLE IF NOT EXISTS `ContattoBand` (
  `idContatto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idBand` INT UNSIGNED NOT NULL,
  `tipoContatto` VARCHAR(45) NOT NULL,
  `Contatto` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idContatto`),
  INDEX `fk_tipoContatto_ContattoBand_idx` (`tipoContatto` ASC),
  CONSTRAINT `fk_idBand_ContattoBand`
    FOREIGN KEY (`idBand`)
    REFERENCES `Band` (`idBand`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tipoContatto_ContattoBand`
    FOREIGN KEY (`tipoContatto`)
    REFERENCES `TipoContatto` (`Nome`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `RichiestaPartecipazione`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `RichiestaPartecipazione` ;

CREATE TABLE IF NOT EXISTS `RichiestaPartecipazione` (
  `idRichiestaGruppoUtente` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUtente` INT UNSIGNED NOT NULL,
  `idGruppo` INT UNSIGNED NOT NULL,
  `RichiestaDaGruppo` TINYINT(1) NOT NULL COMMENT 'Vero se il gruppo ha chiesto all\'utente,\nFalso se l\'utente ha chiesto al gruppo',
  PRIMARY KEY (`idRichiestaGruppoUtente`),
  INDEX `fk_idUtente_RichiestaGruppoUtente_idx` (`idUtente` ASC),
  INDEX `fk_idBand_RichiestaGruppoUtente_idx` (`idGruppo` ASC),
  CONSTRAINT `fk_idUtente_RichiestaGruppoUtente`
    FOREIGN KEY (`idUtente`)
    REFERENCES `Utente` (`idUtente`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_idBand_RichiestaGruppoUtente`
    FOREIGN KEY (`idGruppo`)
    REFERENCES `Band` (`idBand`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `TipoContatto`
-- -----------------------------------------------------
START TRANSACTION;
USE `database_artisti`;
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Profilo Facebook');
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Pagina Facebook');
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Telefono');
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Email');
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Whatsapp');
INSERT INTO `TipoContatto` (`Nome`) VALUES ('Telegram');

COMMIT;

