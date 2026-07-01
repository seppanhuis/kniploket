-- **********************************************************************************
-- Step: 01
-- Goal: Create a new database kniploket
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 New Database
-- **********************************************************************************

CREATE DATABASE IF NOT EXISTS KnipLoket;
USE KnipLoket;



-- **********************************************************************************
-- Step: 02
-- Goal: Drop all existing tables
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Drop old tables
-- **********************************************************************************

DROP TABLE IF EXISTS Bestelregel;
DROP TABLE IF EXISTS BehandelingProduct;
DROP TABLE IF EXISTS BehandelingSpecialisatie;
DROP TABLE IF EXISTS MedewerkerSpecialisatie;
DROP TABLE IF EXISTS KlantAllergie;
DROP TABLE IF EXISTS Afspraak;
DROP TABLE IF EXISTS Bestelling;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Werktijd;
DROP TABLE IF EXISTS Medewerker;
DROP TABLE IF EXISTS Klant;
DROP TABLE IF EXISTS Gebruiker;
DROP TABLE IF EXISTS Rol;
DROP TABLE IF EXISTS Allergie;
DROP TABLE IF EXISTS Specialisatie;
DROP TABLE IF EXISTS Behandeling;
DROP TABLE IF EXISTS Leverancier;
DROP TABLE IF EXISTS ProductCategorie;
DROP TABLE IF EXISTS AfspraakStatus;
DROP TABLE IF EXISTS BestellingStatus;



-- **********************************************************************************
-- Step: 03
-- Goal: Create table Rol
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Rol table
-- **********************************************************************************

CREATE TABLE Rol (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    RolNaam VARCHAR(30) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 04
-- Goal: Create table Allergie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Allergie table
-- **********************************************************************************

CREATE TABLE Allergie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(100) NOT NULL UNIQUE,
    Beschrijving VARCHAR(255) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 05
-- Goal: Create table Specialisatie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Specialisatie table
-- **********************************************************************************

CREATE TABLE Specialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 06
-- Goal: Create table Behandeling
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Behandeling table
-- **********************************************************************************

CREATE TABLE Behandeling (
    BehandelingId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(100) NOT NULL UNIQUE,
    Prijs DECIMAL(6,2) NOT NULL,
    DuurMinuten SMALLINT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);
-- **********************************************************************************
-- Step: 07
-- Goal: Create table Leverancier
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Leverancier table
-- **********************************************************************************

CREATE TABLE Leverancier (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(100) NOT NULL,
    Telefoonnummer VARCHAR(20) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 08
-- Goal: Create table ProductCategorie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create ProductCategorie table
-- **********************************************************************************

CREATE TABLE ProductCategorie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 09
-- Goal: Create table AfspraakStatus
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create AfspraakStatus table
-- **********************************************************************************

CREATE TABLE AfspraakStatus (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 10
-- Goal: Create table BestellingStatus
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create BestellingStatus table
-- **********************************************************************************

CREATE TABLE BestellingStatus (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- **********************************************************************************
-- Step: 11
-- Goal: Create table Gebruiker
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Gebruiker table
-- **********************************************************************************

CREATE TABLE Gebruiker (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    RolId INT UNSIGNED NOT NULL,
    Gebruikersnaam VARCHAR(50) NOT NULL UNIQUE,
    Wachtwoord VARCHAR(255) NOT NULL,
    Voornaam VARCHAR(50) NOT NULL,
    Tussenvoegsel VARCHAR(20),
    Achternaam VARCHAR(50) NOT NULL,
    Straat VARCHAR(100) NOT NULL,
    Huisnummer VARCHAR(10) NOT NULL,
    Postcode VARCHAR(6) NOT NULL,
    Woonplaats VARCHAR(50) NOT NULL,
    Telefoonnummer VARCHAR(20) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    LaatsteLogin DATETIME(6) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (RolId)
        REFERENCES Rol(Id)
);



-- **********************************************************************************
-- Step: 12
-- Goal: Create table Klant
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Klant table
-- **********************************************************************************

CREATE TABLE Klant (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT UNSIGNED NOT NULL,
    Wensen VARCHAR(255),
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (GebruikerId)
        REFERENCES Gebruiker(Id)
);



-- **********************************************************************************
-- Step: 13
-- Goal: Create table Medewerker
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Medewerker table
-- **********************************************************************************

CREATE TABLE Medewerker (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (GebruikerId)
        REFERENCES Gebruiker(Id)
);
-- **********************************************************************************
-- Step: 14
-- Goal: Create table Werktijd
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Werktijd table
-- **********************************************************************************

CREATE TABLE Werktijd (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MedewerkerId INT UNSIGNED NOT NULL,
    Dag ENUM('Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag') NOT NULL,
    BeginTijd DATETIME(6) NOT NULL,
    EindTijd DATETIME(6) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (MedewerkerId)
        REFERENCES Medewerker(Id)
);



-- **********************************************************************************
-- Step: 15
-- Goal: Create table KlantAllergie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create KlantAllergie table
-- **********************************************************************************

CREATE TABLE KlantAllergie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantId INT UNSIGNED NOT NULL,
    AllergieId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (KlantId)
        REFERENCES Klant(Id),

    FOREIGN KEY (AllergieId)
        REFERENCES Allergie(Id)
);



-- **********************************************************************************
-- Step: 16
-- Goal: Create table MedewerkerSpecialisatie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create MedewerkerSpecialisatie table
-- **********************************************************************************

CREATE TABLE MedewerkerSpecialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MedewerkerId INT UNSIGNED NOT NULL,
    SpecialisatieId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (MedewerkerId)
        REFERENCES Medewerker(Id),

    FOREIGN KEY (SpecialisatieId)
        REFERENCES Specialisatie(Id)
);



-- **********************************************************************************
-- Step: 17
-- Goal: Create table BehandelingSpecialisatie
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create BehandelingSpecialisatie table
-- **********************************************************************************

CREATE TABLE BehandelingSpecialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BehandelingID INT UNSIGNED NOT NULL,
    SpecialisatieID INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (BehandelingID)
        REFERENCES Behandeling(BehandelingId),

    FOREIGN KEY (SpecialisatieID)
        REFERENCES Specialisatie(Id)
);



-- **********************************************************************************
-- Step: 18
-- Goal: Create table Product
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Product table
-- **********************************************************************************

CREATE TABLE Product (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProductNaam VARCHAR(100) NOT NULL UNIQUE,
    EANCode VARCHAR(13) NOT NULL UNIQUE,
    Voorraad SMALLINT UNSIGNED NOT NULL,
    MinimumVoorraad SMALLINT UNSIGNED NOT NULL,
    LeverancierId INT UNSIGNED NOT NULL,
    CategorieId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (LeverancierId)
        REFERENCES Leverancier(Id),

    FOREIGN KEY (CategorieId)
        REFERENCES ProductCategorie(Id)
);



-- **********************************************************************************
-- Step: 19
-- Goal: Create table BehandelingProduct
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create BehandelingProduct table
-- **********************************************************************************

CREATE TABLE BehandelingProduct (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BehandelingID INT UNSIGNED NOT NULL,
    ProductID INT UNSIGNED NOT NULL,
    Aantal DECIMAL(6,2) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (BehandelingID)
        REFERENCES Behandeling(BehandelingId),

    FOREIGN KEY (ProductID)
        REFERENCES Product(Id)
);



-- **********************************************************************************
-- Step: 20
-- Goal: Create table Afspraak
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Afspraak table
-- **********************************************************************************

CREATE TABLE Afspraak (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantID INT UNSIGNED NOT NULL,
    MedewerkerID INT UNSIGNED NOT NULL,
    BehandelingID INT UNSIGNED NOT NULL,
    AfspraakStatusID INT UNSIGNED NOT NULL,
    Datum DATETIME(6) NOT NULL,
    StartTijd TIME NOT NULL,
    EindTijd TIME NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (KlantID)
        REFERENCES Klant(Id),

    FOREIGN KEY (MedewerkerID)
        REFERENCES Medewerker(Id),

    FOREIGN KEY (BehandelingID)
        REFERENCES Behandeling(BehandelingId),

    FOREIGN KEY (AfspraakStatusID)
        REFERENCES AfspraakStatus(Id)
);



-- **********************************************************************************
-- Step: 21
-- Goal: Create table Bestelling
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Bestelling table
-- **********************************************************************************

CREATE TABLE Bestelling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantID INT UNSIGNED NOT NULL,
    BestellingStatusID INT UNSIGNED NOT NULL,
    OrderDatum DATETIME(6) NOT NULL,
    VerwachteLeverdatum DATETIME(6),
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (KlantID)
        REFERENCES Klant(Id),

    FOREIGN KEY (BestellingStatusID)
        REFERENCES BestellingStatus(Id)
);



-- **********************************************************************************
-- Step: 22
-- Goal: Create table Bestelregel
-- **********************************************************************************
-- Version       Date:           Author:                     Description:
-- *******       **********      ****************            ******************
-- 01            01-07-2026      Özer Yavuz                 Create Bestelregel table
-- **********************************************************************************

CREATE TABLE Bestelregel (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BestellingId INT UNSIGNED NOT NULL,
    ProductId INT UNSIGNED NOT NULL,
    Aantal SMALLINT UNSIGNED NOT NULL,
    VerkoopPrijs DECIMAL(6,2) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,

    FOREIGN KEY (BestellingId)
        REFERENCES Bestelling(Id),

    FOREIGN KEY (ProductId)
        REFERENCES Product(Id)
);