-- Step: 01
-- ***************************************************************
-- Doel : Verwijder bestaande tabellen en maak ze opnieuw aan
-- ***************************************************************
-- Versie       Datum           Auteur              Omschrijving
-- ******       *****           ******              ************
-- 01           01-07-2026      sep                 Genormaliseerd create-script kniploket
-- ***************************************************************

USE kniploket2;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Bestelregel;
DROP TABLE IF EXISTS Bestelling;
DROP TABLE IF EXISTS Afspraak;
DROP TABLE IF EXISTS BestellingStatus;
DROP TABLE IF EXISTS AfspraakStatus;
DROP TABLE IF EXISTS BehandelingProduct;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS ProductCategorie;
DROP TABLE IF EXISTS Leverancier;
DROP TABLE IF EXISTS BehandelingSpecialisatie;
DROP TABLE IF EXISTS Behandeling;
DROP TABLE IF EXISTS MedewerkerSpecialisatie;
DROP TABLE IF EXISTS Specialisatie;
DROP TABLE IF EXISTS Werktijd;
DROP TABLE IF EXISTS KlantAllergie;
DROP TABLE IF EXISTS Allergie;
DROP TABLE IF EXISTS Medewerker;
DROP TABLE IF EXISTS Klant;
DROP TABLE IF EXISTS Gebruiker;
DROP TABLE IF EXISTS Rol;

SET FOREIGN_KEY_CHECKS = 1;

-- Step: 02
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Rol
-- *****************************************************************************************************

CREATE TABLE Rol
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,RolNaam                 VARCHAR(30)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Rol_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_Rol_RolNaam UNIQUE (RolNaam)

) ENGINE=InnoDB;

-- Step: 03
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Gebruiker
-- *****************************************************************************************************

CREATE TABLE Gebruiker
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,RolId                   INT                 UNSIGNED    NOT NULL
        ,Gebruikersnaam          VARCHAR(50)                     NOT NULL
        ,Wachtwoord              VARCHAR(255)                    NOT NULL
        ,Voornaam                VARCHAR(50)                     NOT NULL
        ,Tussenvoegsel           VARCHAR(20)                         NULL DEFAULT NULL
        ,Achternaam              VARCHAR(50)                     NOT NULL
        ,Straat                  VARCHAR(100)                    NOT NULL
        ,Huisnummer              INT                             NOT NULL
        ,Toevoeging              VARCHAR(5)                          NULL DEFAULT NULL
        ,Postcode                VARCHAR(6)                      NOT NULL
        ,Woonplaats              VARCHAR(50)                     NOT NULL
        ,Telefoonnummer          VARCHAR(20)                     NOT NULL
        ,Email                   VARCHAR(100)                    NOT NULL
        ,LaatsteLogin            DATETIME(6)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Gebruiker_Id PRIMARY KEY (Id)

        ,CONSTRAINT UQ_Gebruiker_Gebruikersnaam UNIQUE (Gebruikersnaam)
        ,CONSTRAINT UQ_Gebruiker_Email UNIQUE (Email)

        ,CONSTRAINT FK_Gebruiker_Rol
            FOREIGN KEY (RolId)
            REFERENCES Rol(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 04
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Klant
-- *****************************************************************************************************

CREATE TABLE Klant
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,GebruikerId             INT                 UNSIGNED    NOT NULL
        ,Wensen                  VARCHAR(255)                    NULL DEFAULT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Klant_Id PRIMARY KEY (Id)

        ,CONSTRAINT FK_Klant_Gebruiker
            FOREIGN KEY (GebruikerId)
            REFERENCES Gebruiker(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 05
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Medewerker
-- *****************************************************************************************************

CREATE TABLE Medewerker
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,GebruikerId             INT                 UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Medewerker_Id PRIMARY KEY (Id)

        ,CONSTRAINT FK_Medewerker_Gebruiker
            FOREIGN KEY (GebruikerId)
            REFERENCES Gebruiker(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 06
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Allergie
-- *****************************************************************************************************

CREATE TABLE Allergie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(100)                    NOT NULL
        ,Beschrijving            VARCHAR(255)                    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Allergie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_Allergie_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 07
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam KlantAllergie
-- *****************************************************************************************************

CREATE TABLE KlantAllergie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,KlantId                 INT                 UNSIGNED    NOT NULL
        ,AllergieId              INT                 UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_KlantAllergie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_KlantAllergie UNIQUE (KlantId, AllergieId)

        ,CONSTRAINT FK_KlantAllergie_Klant
            FOREIGN KEY (KlantId)
            REFERENCES Klant(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_KlantAllergie_Allergie
            FOREIGN KEY (AllergieId)
            REFERENCES Allergie(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 08
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Werktijd
-- *****************************************************************************************************

CREATE TABLE Werktijd
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,MedewerkerId            INT                 UNSIGNED    NOT NULL
        ,Dag                     ENUM('Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag')
                                                            NOT NULL
        ,BeginTijd               DATETIME(6)                     NOT NULL
        ,EindTijd                DATETIME(6)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Werktijd_Id PRIMARY KEY (Id)

        ,CONSTRAINT FK_Werktijd_Medewerker
            FOREIGN KEY (MedewerkerId)
            REFERENCES Medewerker(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 09
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Specialisatie
-- *****************************************************************************************************

CREATE TABLE Specialisatie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(50)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Specialisatie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_Specialisatie_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 10
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam MedewerkerSpecialisatie
-- *****************************************************************************************************

CREATE TABLE MedewerkerSpecialisatie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,MedewerkerId            INT                 UNSIGNED    NOT NULL
        ,SpecialisatieId         INT                 UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_MedewerkerSpecialisatie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_MedewerkerSpecialisatie UNIQUE (MedewerkerId, SpecialisatieId)

        ,CONSTRAINT FK_MedewerkerSpecialisatie_Medewerker
            FOREIGN KEY (MedewerkerId)
            REFERENCES Medewerker(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_MedewerkerSpecialisatie_Specialisatie
            FOREIGN KEY (SpecialisatieId)
            REFERENCES Specialisatie(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 11
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Behandeling
-- *****************************************************************************************************

CREATE TABLE Behandeling
(
         BehandelingId           INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(100)                    NOT NULL
        ,Prijs                   DECIMAL(6,2)                    NOT NULL
        ,DuurMinuten             SMALLINT            UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Behandeling_Id PRIMARY KEY (BehandelingId)
        ,CONSTRAINT UQ_Behandeling_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 12
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam BehandelingSpecialisatie
-- *****************************************************************************************************

CREATE TABLE BehandelingSpecialisatie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,BehandelingId           INT                 UNSIGNED    NOT NULL
        ,SpecialisatieId         INT                 UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_BehandelingSpecialisatie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_BehandelingSpecialisatie UNIQUE (BehandelingId, SpecialisatieId)

        ,CONSTRAINT FK_BehandelingSpecialisatie_Behandeling
            FOREIGN KEY (BehandelingId)
            REFERENCES Behandeling(BehandelingId)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_BehandelingSpecialisatie_Specialisatie
            FOREIGN KEY (SpecialisatieId)
            REFERENCES Specialisatie(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 13
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Leverancier
-- *****************************************************************************************************

CREATE TABLE Leverancier
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(100)                    NOT NULL
        ,Telefoonnummer          VARCHAR(20)                     NOT NULL
        ,Email                   VARCHAR(100)                    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Leverancier_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

-- Step: 14
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam ProductCategorie
-- *****************************************************************************************************

CREATE TABLE ProductCategorie
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(50)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_ProductCategorie_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_ProductCategorie_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 15
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Product
-- *****************************************************************************************************

CREATE TABLE Product
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,ProductNaam             VARCHAR(100)                    NOT NULL
        ,EANCode                 VARCHAR(13)                     NOT NULL
        ,Voorraad                SMALLINT                        NOT NULL
        ,MinimumVoorraad         SMALLINT                        NOT NULL
        ,LeverancierId           INT                 UNSIGNED    NOT NULL
        ,CategorieId             INT                 UNSIGNED    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Product_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_Product_ProductNaam UNIQUE (ProductNaam)
        ,CONSTRAINT UQ_Product_EANCode UNIQUE (EANCode)

        ,CONSTRAINT FK_Product_Leverancier
            FOREIGN KEY (LeverancierId)
            REFERENCES Leverancier(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Product_ProductCategorie
            FOREIGN KEY (CategorieId)
            REFERENCES ProductCategorie(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 16
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam BehandelingProduct
-- *****************************************************************************************************

CREATE TABLE BehandelingProduct
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,BehandelingId           INT                 UNSIGNED    NOT NULL
        ,ProductId               INT                 UNSIGNED    NOT NULL
        ,Aantal                  DECIMAL(6,2)                    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_BehandelingProduct_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_BehandelingProduct UNIQUE (BehandelingId, ProductId)

        ,CONSTRAINT FK_BehandelingProduct_Behandeling
            FOREIGN KEY (BehandelingId)
            REFERENCES Behandeling(BehandelingId)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_BehandelingProduct_Product
            FOREIGN KEY (ProductId)
            REFERENCES Product(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 17
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam AfspraakStatus
-- *****************************************************************************************************

CREATE TABLE AfspraakStatus
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(50)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_AfspraakStatus_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_AfspraakStatus_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 18
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam BestellingStatus
-- *****************************************************************************************************

CREATE TABLE BestellingStatus
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,Naam                    VARCHAR(50)                     NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_BestellingStatus_Id PRIMARY KEY (Id)
        ,CONSTRAINT UQ_BestellingStatus_Naam UNIQUE (Naam)

) ENGINE=InnoDB;

-- Step: 19
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Afspraak
-- *****************************************************************************************************

CREATE TABLE Afspraak
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,KlantId                 INT                 UNSIGNED    NOT NULL
        ,MedewerkerId            INT                 UNSIGNED    NOT NULL
        ,BehandelingId           INT                 UNSIGNED    NOT NULL
        ,AfspraakStatusId        INT                 UNSIGNED    NOT NULL
        ,Datum                   DATETIME(6)                     NOT NULL
        ,StartTijd               TIME                            NOT NULL
        ,EindTijd                TIME                            NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Afspraak_Id PRIMARY KEY (Id)

        ,CONSTRAINT FK_Afspraak_Klant
            FOREIGN KEY (KlantId)
            REFERENCES Klant(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Afspraak_Medewerker
            FOREIGN KEY (MedewerkerId)
            REFERENCES Medewerker(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Afspraak_Behandeling
            FOREIGN KEY (BehandelingId)
            REFERENCES Behandeling(BehandelingId)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Afspraak_AfspraakStatus
            FOREIGN KEY (AfspraakStatusId)
            REFERENCES AfspraakStatus(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 20
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Bestelling
-- *****************************************************************************************************

CREATE TABLE Bestelling
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,KlantId                 INT                 UNSIGNED    NOT NULL
        ,BestellingStatusId      INT                 UNSIGNED    NOT NULL
        ,OrderDatum              DATETIME(6)                     NOT NULL
        ,VerwachteLeverdatum     DATETIME(6)                         NULL DEFAULT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Bestelling_Id PRIMARY KEY (Id)

        ,CONSTRAINT FK_Bestelling_Klant
            FOREIGN KEY (KlantId)
            REFERENCES Klant(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Bestelling_BestellingStatus
            FOREIGN KEY (BestellingStatusId)
            REFERENCES BestellingStatus(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 21
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Bestelregel
-- *****************************************************************************************************

CREATE TABLE Bestelregel
(
         Id                      INT                 UNSIGNED    NOT NULL AUTO_INCREMENT
        ,BestellingId            INT                 UNSIGNED    NOT NULL
        ,ProductId               INT                 UNSIGNED    NOT NULL
        ,Aantal                  SMALLINT            UNSIGNED    NOT NULL
        ,VerkoopPrijs            DECIMAL(6,2)                    NOT NULL
        ,IsActief                BIT                             NOT NULL DEFAULT b'1'
        ,Opmerking               VARCHAR(255)                        NULL DEFAULT NULL
        ,DatumAangemaakt         DATETIME(6)                     NOT NULL
        ,DatumGewijzigd          DATETIME(6)                     NOT NULL

        ,CONSTRAINT PK_Bestelregel_Id PRIMARY KEY (Id)

        ,CONSTRAINT UQ_Bestelregel UNIQUE (BestellingId, ProductId)

        ,CONSTRAINT FK_Bestelregel_Bestelling
            FOREIGN KEY (BestellingId)
            REFERENCES Bestelling(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

        ,CONSTRAINT FK_Bestelregel_Product
            FOREIGN KEY (ProductId)
            REFERENCES Product(Id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT

) ENGINE=InnoDB;

