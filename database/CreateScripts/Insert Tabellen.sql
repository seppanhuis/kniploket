CREATE DATABASE IF NOT EXISTS KnipLoket;
USE KnipLoket;


-- =========================
-- Oude tabellen verwijderen
-- =========================

DROP TABLE IF EXISTS Bestelregel;
DROP TABLE IF EXISTS BehandelingProduct;
DROP TABLE IF EXISTS BehandelingSpecialisatie;
DROP TABLE IF EXISTS MedewerkerSpecialisatie;
DROP TABLE IF EXISTS KlantAllergie;
DROP TABLE IF EXISTS Afspraak;
DROP TABLE IF EXISTS Bestelling;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Werktijd;
DROP TABLE IF EXISTS Behandeling;
DROP TABLE IF EXISTS Specialisatie;
DROP TABLE IF EXISTS Allergie;
DROP TABLE IF EXISTS Medewerker;
DROP TABLE IF EXISTS Klant;
DROP TABLE IF EXISTS Gebruiker;
DROP TABLE IF EXISTS Rol;
DROP TABLE IF EXISTS Leverancier;
DROP TABLE IF EXISTS ProductCategorie;
DROP TABLE IF EXISTS AfspraakStatus;
DROP TABLE IF EXISTS BestellingStatus;



-- =========================
-- Basis tabellen
-- =========================

CREATE TABLE Rol (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    RolNaam VARCHAR(30) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


CREATE TABLE Allergie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(100) NOT NULL UNIQUE,
    Beschrijving VARCHAR(255) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


CREATE TABLE Specialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


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


CREATE TABLE ProductCategorie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


CREATE TABLE AfspraakStatus (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


CREATE TABLE BestellingStatus (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Naam VARCHAR(50) NOT NULL UNIQUE,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);
-- =========================
-- Gebruiker
-- =========================

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
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Klant
-- =========================

CREATE TABLE Klant (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT UNSIGNED NOT NULL,
    Wensen VARCHAR(255),
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Medewerker
-- =========================

CREATE TABLE Medewerker (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Werktijd
-- =========================

CREATE TABLE Werktijd (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MedewerkerId INT UNSIGNED NOT NULL,
    Dag ENUM(
        'Maandag',
        'Dinsdag',
        'Woensdag',
        'Donderdag',
        'Vrijdag',
        'Zaterdag',
        'Zondag'
    ) NOT NULL,
    BeginTijd DATETIME(6) NOT NULL,
    EindTijd DATETIME(6) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- KlantAllergie
-- =========================

CREATE TABLE KlantAllergie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantId INT UNSIGNED NOT NULL,
    AllergieId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- MedewerkerSpecialisatie
-- =========================

CREATE TABLE MedewerkerSpecialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    MedewerkerId INT UNSIGNED NOT NULL,
    SpecialisatieId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- BehandelingSpecialisatie
-- =========================

CREATE TABLE BehandelingSpecialisatie (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BehandelingID INT UNSIGNED NOT NULL,
    SpecialisatieID INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Product
-- =========================

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
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- BehandelingProduct
-- =========================

CREATE TABLE BehandelingProduct (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BehandelingID INT UNSIGNED NOT NULL,
    ProductID INT UNSIGNED NOT NULL,
    Aantal DECIMAL(6,2) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);
-- =========================
-- Afspraak
-- =========================

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
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Bestelling
-- =========================

CREATE TABLE Bestelling (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    KlantID INT UNSIGNED NOT NULL,
    BestellingStatusID INT UNSIGNED NOT NULL,
    OrderDatum DATETIME(6) NOT NULL,
    VerwachteLeverdatum DATETIME(6),
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);


-- =========================
-- Bestelregel
-- =========================

CREATE TABLE Bestelregel (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    BestellingId INT UNSIGNED NOT NULL,
    ProductId INT UNSIGNED NOT NULL,
    Aantal SMALLINT UNSIGNED NOT NULL,
    VerkoopPrijs DECIMAL(6,2) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255),
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL
);



-- =========================
-- FOREIGN KEYS
-- =========================


ALTER TABLE Gebruiker
ADD CONSTRAINT FK_Gebruiker_Rol
FOREIGN KEY (RolId)
REFERENCES Rol(Id);



ALTER TABLE Klant
ADD CONSTRAINT FK_Klant_Gebruiker
FOREIGN KEY (GebruikerId)
REFERENCES Gebruiker(Id);



ALTER TABLE Medewerker
ADD CONSTRAINT FK_Medewerker_Gebruiker
FOREIGN KEY (GebruikerId)
REFERENCES Gebruiker(Id);



ALTER TABLE Werktijd
ADD CONSTRAINT FK_Werktijd_Medewerker
FOREIGN KEY (MedewerkerId)
REFERENCES Medewerker(Id);



ALTER TABLE KlantAllergie
ADD CONSTRAINT FK_KlantAllergie_Klant
FOREIGN KEY (KlantId)
REFERENCES Klant(Id),

ADD CONSTRAINT FK_KlantAllergie_Allergie
FOREIGN KEY (AllergieId)
REFERENCES Allergie(Id);



ALTER TABLE MedewerkerSpecialisatie
ADD CONSTRAINT FK_MedewerkerSpecialisatie_Medewerker
FOREIGN KEY (MedewerkerId)
REFERENCES Medewerker(Id),

ADD CONSTRAINT FK_MedewerkerSpecialisatie_Specialisatie
FOREIGN KEY (SpecialisatieId)
REFERENCES Specialisatie(Id);



ALTER TABLE BehandelingSpecialisatie
ADD CONSTRAINT FK_BehandelingSpecialisatie_Behandeling
FOREIGN KEY (BehandelingID)
REFERENCES Behandeling(BehandelingId),

ADD CONSTRAINT FK_BehandelingSpecialisatie_Specialisatie
FOREIGN KEY (SpecialisatieID)
REFERENCES Specialisatie(Id);



ALTER TABLE Product
ADD CONSTRAINT FK_Product_Leverancier
FOREIGN KEY (LeverancierId)
REFERENCES Leverancier(Id),

ADD CONSTRAINT FK_Product_Categorie
FOREIGN KEY (CategorieId)
REFERENCES ProductCategorie(Id);



ALTER TABLE BehandelingProduct
ADD CONSTRAINT FK_BehandelingProduct_Behandeling
FOREIGN KEY (BehandelingID)
REFERENCES Behandeling(BehandelingId),

ADD CONSTRAINT FK_BehandelingProduct_Product
FOREIGN KEY (ProductID)
REFERENCES Product(Id);



ALTER TABLE Afspraak
ADD CONSTRAINT FK_Afspraak_Klant
FOREIGN KEY (KlantID)
REFERENCES Klant(Id),

ADD CONSTRAINT FK_Afspraak_Medewerker
FOREIGN KEY (MedewerkerID)
REFERENCES Medewerker(Id),

ADD CONSTRAINT FK_Afspraak_Behandeling
FOREIGN KEY (BehandelingID)
REFERENCES Behandeling(BehandelingId),

ADD CONSTRAINT FK_Afspraak_Status
FOREIGN KEY (AfspraakStatusID)
REFERENCES AfspraakStatus(Id);



ALTER TABLE Bestelling
ADD CONSTRAINT FK_Bestelling_Klant
FOREIGN KEY (KlantID)
REFERENCES Klant(Id),

ADD CONSTRAINT FK_Bestelling_Status
FOREIGN KEY (BestellingStatusID)
REFERENCES BestellingStatus(Id);



ALTER TABLE Bestelregel
ADD CONSTRAINT FK_Bestelregel_Bestelling
FOREIGN KEY (BestellingId)
REFERENCES Bestelling(Id),

ADD CONSTRAINT FK_Bestelregel_Product
FOREIGN KEY (ProductId)
REFERENCES Product(Id);