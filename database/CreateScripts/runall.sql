-- Step: 01
-- ***************************************************************
-- Doel : Verwijder bestaande tabellen en maak ze opnieuw aan
-- ***************************************************************
-- Versie       Datum           Auteur              Omschrijving
-- ******       *****           ******              ************
-- 01           01-07-2026      sep                 Genormaliseerd create-script kniploket
-- ***************************************************************

USE kniploket;

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

-- Step: 22
-- *****************************************************************
-- Doel : Vul de tabel Rol met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Rol
(
         RolNaam
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
     ('Administrator',1,NULL,SYSDATE(6),SYSDATE(6))
    ,('Manager',1,NULL,SYSDATE(6),SYSDATE(6))
    ,('Medewerker',1,NULL,SYSDATE(6),SYSDATE(6))
    ,('Klant',1,NULL,SYSDATE(6),SYSDATE(6))
    ,('Stagiair',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 23
-- *****************************************************************
-- Doel : Vul de tabel Gebruiker met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Gebruiker
(
         RolId
        ,Gebruikersnaam
        ,Wachtwoord
        ,Voornaam
        ,Tussenvoegsel
        ,Achternaam
        ,Straat
        ,Huisnummer
        ,Toevoeging
        ,Postcode
        ,Woonplaats
        ,Telefoonnummer
        ,Email
        ,LaatsteLogin
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,'admin','admin123','Sven',NULL,'Peters','Hoofdstraat',12,NULL,'5461AA','Veghel','0611111111','admin@salon.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(2,'manager','manager123','Linda',NULL,'Jansen','Markt',8,NULL,'5211AB','Den Bosch','0622222222','manager@salon.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(3,'emma','welkom123','Emma',NULL,'de Vries','Schoolstraat',25,NULL,'5481CD','Schijndel','0633333333','emma@salon.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'sophie','welkom123','Sophie',NULL,'Bakker','Dorpsstraat',41,NULL,'5473EF','Heeswijk','0644444444','sophie@mail.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'tom','welkom123','Tom','van','Dijk','Stationsweg',9,'A','5271GH','Sint-Michielsgestel','0655555555','tom@mail.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'lisa','welkom123','Lisa',NULL,'de Jong','Kerkstraat',14,NULL,'5432IJ','Oss','0666666666','lisa@mail.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'max','welkom123','Max',NULL,'Visser','Lindelaan',7,NULL,'5462KL','Veghel','0677777777','max@mail.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'sara','welkom123','Sara',NULL,'Meijer','Beekstraat',3,NULL,'5471MN','Heeswijk','0688888888','sara@mail.nl',SYSDATE(6),1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 24
-- *****************************************************************
-- Doel : Vul de tabel Klant met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Klant
(
         GebruikerId
        ,Wensen
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (4,'Geen parfum',1,NULL,SYSDATE(6),SYSDATE(6))
,(5,'Extra gevoelige huid',1,NULL,SYSDATE(6),SYSDATE(6))
,(6,'Liever ochtendafspraak',1,NULL,SYSDATE(6),SYSDATE(6))
,(7,'Geen gelnagels',1,NULL,SYSDATE(6),SYSDATE(6))
,(8,NULL,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 25
-- *****************************************************************
-- Doel : Vul de tabel Medewerker met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Medewerker
(
         GebruikerId
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,1,NULL,SYSDATE(6),SYSDATE(6))
,(1,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 26
-- *****************************************************************
-- Doel : Vul de tabel Allergie met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Allergie
(
         Naam
        ,Beschrijving
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Parfum','Allergisch voor parfum',1,NULL,SYSDATE(6),SYSDATE(6))
,('Latex','Allergisch voor latex',1,NULL,SYSDATE(6),SYSDATE(6))
,('Notenolie','Allergisch voor notenolie',1,NULL,SYSDATE(6),SYSDATE(6))
,('Aloë Vera','Allergisch voor aloë vera',1,NULL,SYSDATE(6),SYSDATE(6))
,('Tea Tree','Allergisch voor tea tree olie',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 27
-- *****************************************************************
-- Doel : Vul de tabel KlantAllergie met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO KlantAllergie
(
         KlantId
        ,AllergieId
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,2,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,3,1,NULL,SYSDATE(6),SYSDATE(6))
,(4,4,1,NULL,SYSDATE(6),SYSDATE(6))
,(5,5,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 28
-- *****************************************************************
-- Doel : Vul de tabel Werktijd met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Werktijd
(
         MedewerkerId
        ,Dag
        ,BeginTijd
        ,EindTijd
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,'Maandag','2026-07-06 09:00:00','2026-07-06 17:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(2,'Dinsdag','2026-07-07 09:00:00','2026-07-07 17:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(3,'Woensdag','2026-07-08 10:00:00','2026-07-08 18:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(4,'Donderdag','2026-07-09 09:00:00','2026-07-09 17:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(5,'Vrijdag','2026-07-10 09:00:00','2026-07-10 17:00:00',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 29
-- *****************************************************************
-- Doel : Vul de tabel Specialisatie met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Specialisatie
(
         Naam
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Gezichtsbehandeling',1,NULL,SYSDATE(6),SYSDATE(6))
,('Manicure',1,NULL,SYSDATE(6),SYSDATE(6))
,('Pedicure',1,NULL,SYSDATE(6),SYSDATE(6))
,('Massage',1,NULL,SYSDATE(6),SYSDATE(6))
,('Waxen',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 30
-- *****************************************************************
-- Doel : Vul de tabel MedewerkerSpecialisatie met gegevens
-- *****************************************************************

INSERT INTO MedewerkerSpecialisatie
(
         MedewerkerId
        ,SpecialisatieId
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,1,NULL,SYSDATE(6),SYSDATE(6))
,(1,2,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,3,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,4,1,NULL,SYSDATE(6),SYSDATE(6))
,(4,5,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 31
-- *****************************************************************
-- Doel : Vul de tabel Behandeling met gegevens
-- *****************************************************************

INSERT INTO Behandeling
(
         Naam
        ,Prijs
        ,DuurMinuten
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Basis gezichtsbehandeling',49.95,60,1,NULL,SYSDATE(6),SYSDATE(6))
,('Luxe gezichtsbehandeling',79.95,90,1,NULL,SYSDATE(6),SYSDATE(6))
,('Manicure Deluxe',39.95,45,1,NULL,SYSDATE(6),SYSDATE(6))
,('Pedicure',42.50,45,1,NULL,SYSDATE(6),SYSDATE(6))
,('Ontspanningsmassage',65.00,60,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 32
-- *****************************************************************
-- Doel : Vul de tabel BehandelingSpecialisatie met gegevens
-- *****************************************************************

INSERT INTO BehandelingSpecialisatie
(
         BehandelingId
        ,SpecialisatieId
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,1,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,2,1,NULL,SYSDATE(6),SYSDATE(6))
,(4,3,1,NULL,SYSDATE(6),SYSDATE(6))
,(5,4,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 33
-- *****************************************************************
-- Doel : Vul de tabel Leverancier met gegevens
-- *****************************************************************

INSERT INTO Leverancier
(
         Naam
        ,Telefoonnummer
        ,Email
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Beauty Supply Nederland','0413123456','info@beautysupply.nl',1,NULL,SYSDATE(6),SYSDATE(6))
,('Salon Products BV','0734567890','verkoop@salonproducts.nl',1,NULL,SYSDATE(6),SYSDATE(6))
,('Cosmetic World','0401234567','info@cosmeticworld.nl',1,NULL,SYSDATE(6),SYSDATE(6))
,('NailPro','0204455667','contact@nailpro.nl',1,NULL,SYSDATE(6),SYSDATE(6))
,('Massage Groothandel','0109988776','info@massagegroothandel.nl',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 34
-- *****************************************************************
-- Doel : Vul de tabel ProductCategorie met gegevens
-- *****************************************************************

INSERT INTO ProductCategorie
(
         Naam
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Crèmes',1,NULL,SYSDATE(6),SYSDATE(6))
,('Nagelproducten',1,NULL,SYSDATE(6),SYSDATE(6))
,('Massageolie',1,NULL,SYSDATE(6),SYSDATE(6))
,('Waxproducten',1,NULL,SYSDATE(6),SYSDATE(6))
,('Reiniging',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 35
-- *****************************************************************
-- Doel : Vul de tabel Product met gegevens
-- *****************************************************************

INSERT INTO Product
(
         ProductNaam
        ,EANCode
        ,Voorraad
        ,MinimumVoorraad
        ,LeverancierId
        ,CategorieId
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Dagcrème','8711111111111',50,10,1,1,1,NULL,SYSDATE(6),SYSDATE(6))
,('Nachtcrème','8711111111112',40,10,1,1,1,NULL,SYSDATE(6),SYSDATE(6))
,('Nagellak Rood','8711111111113',35,5,4,2,1,NULL,SYSDATE(6),SYSDATE(6))
,('Massageolie Lavendel','8711111111114',20,5,5,3,1,NULL,SYSDATE(6),SYSDATE(6))
,('Gezichtsreiniger','8711111111115',45,10,3,5,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 36
-- *****************************************************************
-- Doel : Vul de tabel BehandelingProduct met gegevens
-- *****************************************************************

INSERT INTO BehandelingProduct
(
         BehandelingId
        ,ProductId
        ,Aantal
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,1.00,1,NULL,SYSDATE(6),SYSDATE(6))
,(1,5,1.00,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,1,2.00,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,3,1.00,1,NULL,SYSDATE(6),SYSDATE(6))
,(5,4,1.00,1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 37
-- *****************************************************************
-- Doel : Vul de tabel AfspraakStatus met gegevens
-- *****************************************************************

INSERT INTO AfspraakStatus
(
         Naam
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Gepland',1,NULL,SYSDATE(6),SYSDATE(6))
,('Bevestigd',1,NULL,SYSDATE(6),SYSDATE(6))
,('Afgerond',1,NULL,SYSDATE(6),SYSDATE(6))
,('Geannuleerd',1,NULL,SYSDATE(6),SYSDATE(6))
,('No Show',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 38
-- *****************************************************************
-- Doel : Vul de tabel BestellingStatus met gegevens
-- *****************************************************************

INSERT INTO BestellingStatus
(
         Naam
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 ('Nieuw',1,NULL,SYSDATE(6),SYSDATE(6))
,('In behandeling',1,NULL,SYSDATE(6),SYSDATE(6))
,('Verzonden',1,NULL,SYSDATE(6),SYSDATE(6))
,('Afgeleverd',1,NULL,SYSDATE(6),SYSDATE(6))
,('Geannuleerd',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 39
-- *****************************************************************
-- Doel : Vul de tabel Afspraak met gegevens
-- *****************************************************************

INSERT INTO Afspraak
(
         KlantId
        ,MedewerkerId
        ,BehandelingId
        ,AfspraakStatusId
        ,Datum
        ,StartTijd
        ,EindTijd
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,1,3,'2026-07-10 09:00:00','09:00:00','10:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(2,2,3,2,'2026-07-11 10:00:00','10:00:00','10:45:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(3,3,5,1,'2026-07-12 13:00:00','13:00:00','14:00:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(4,1,2,1,'2026-07-13 11:00:00','11:00:00','12:30:00',1,NULL,SYSDATE(6),SYSDATE(6))
,(5,2,4,4,'2026-07-14 15:00:00','15:00:00','15:45:00',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 40
-- *****************************************************************
-- Doel : Vul de tabel Bestelling met gegevens
-- *****************************************************************

INSERT INTO Bestelling
(
         KlantId
        ,BestellingStatusId
        ,OrderDatum
        ,VerwachteLeverdatum
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,4,'2026-07-01','2026-07-03',1,NULL,SYSDATE(6),SYSDATE(6))
,(2,2,'2026-07-02','2026-07-05',1,NULL,SYSDATE(6),SYSDATE(6))
,(3,1,'2026-07-03','2026-07-06',1,NULL,SYSDATE(6),SYSDATE(6))
,(4,3,'2026-07-04','2026-07-07',1,NULL,SYSDATE(6),SYSDATE(6))
,(5,4,'2026-07-05','2026-07-08',1,NULL,SYSDATE(6),SYSDATE(6));

-- Step: 41
-- *****************************************************************
-- Doel : Vul de tabel Bestelregel met gegevens
-- *****************************************************************

INSERT INTO Bestelregel
(
         BestellingId
        ,ProductId
        ,Aantal
        ,VerkoopPrijs
        ,IsActief
        ,Opmerking
        ,DatumAangemaakt
        ,DatumGewijzigd
)
VALUES
 (1,1,2,19.95,1,NULL,SYSDATE(6),SYSDATE(6))
,(2,3,1,7.95,1,NULL,SYSDATE(6),SYSDATE(6))
,(3,4,2,15.95,1,NULL,SYSDATE(6),SYSDATE(6))
,(4,5,1,14.95,1,NULL,SYSDATE(6),SYSDATE(6))
,(5,2,3,24.95,1,NULL,SYSDATE(6),SYSDATE(6));

DELIMITER $$

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_GetAllProducten$$
CREATE PROCEDURE sp_GetAllProducten()
BEGIN
    SELECT
        p.Id,
        p.ProductNaam,
        p.EANCode,
        p.Voorraad,
        p.MinimumVoorraad,
        p.IsActief,
        p.Opmerking,
        p.LeverancierId,
        p.CategorieId,
        l.Naam AS leverancier_naam,
        c.Naam AS categorie_naam
    FROM Product AS p
    LEFT JOIN Leverancier AS l ON l.Id = p.LeverancierId
    LEFT JOIN ProductCategorie AS c ON c.Id = p.CategorieId
    ORDER BY p.ProductNaam ASC;
END$$

DROP PROCEDURE IF EXISTS sp_CreateProduct$$
CREATE PROCEDURE sp_CreateProduct(
    IN p_product_naam VARCHAR(100),
    IN p_ean_code VARCHAR(13),
    IN p_voorraad INT,
    IN p_minimum_voorraad INT,
    IN p_leverancier_id INT,
    IN p_categorie_id INT,
    IN p_is_actief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    INSERT INTO Product (
        ProductNaam,
        EANCode,
        Voorraad,
        MinimumVoorraad,
        LeverancierId,
        CategorieId,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_product_naam,
        p_ean_code,
        p_voorraad,
        p_minimum_voorraad,
        p_leverancier_id,
        p_categorie_id,
        p_is_actief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DROP PROCEDURE IF EXISTS sp_GetProductById$$
CREATE PROCEDURE sp_GetProductById(
    IN p_id INT
)
BEGIN
    SELECT
        p.Id,
        p.ProductNaam,
        p.EANCode,
        p.Voorraad,
        p.MinimumVoorraad,
        p.IsActief,
        p.Opmerking,
        p.LeverancierId,
        p.CategorieId
    FROM Product AS p
    WHERE p.Id = p_id;
END$$

DROP PROCEDURE IF EXISTS sp_UpdateProduct$$
CREATE PROCEDURE sp_UpdateProduct(
    IN p_id INT,
    IN p_product_naam VARCHAR(100),
    IN p_ean_code VARCHAR(13),
    IN p_voorraad INT,
    IN p_minimum_voorraad INT,
    IN p_leverancier_id INT,
    IN p_categorie_id INT,
    IN p_is_actief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    UPDATE Product
    SET
        ProductNaam = p_product_naam,
        EANCode = p_ean_code,
        Voorraad = p_voorraad,
        MinimumVoorraad = p_minimum_voorraad,
        LeverancierId = p_leverancier_id,
        CategorieId = p_categorie_id,
        IsActief = p_is_actief,
        Opmerking = p_opmerking,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DROP PROCEDURE IF EXISTS sp_DeleteProduct$$
CREATE PROCEDURE sp_DeleteProduct(
    IN p_id INT
)
BEGIN
    SELECT CASE
        WHEN EXISTS (
            SELECT 1
            FROM Product
            WHERE Id = p_id
              AND IsActief = 1
        ) THEN 1
        ELSE 0
    END AS affected;

    IF EXISTS (
        SELECT 1
        FROM Product
        WHERE Id = p_id
          AND IsActief = 1
    ) THEN
        DELETE FROM BehandelingProduct
        WHERE ProductId = p_id;

        DELETE FROM Bestelregel
        WHERE ProductId = p_id;

        DELETE FROM Product
        WHERE Id = p_id;
    END IF;

    SELECT ROW_COUNT() AS affected;
END$$

DROP PROCEDURE IF EXISTS sp_GetTreatmentsForProduct$$
CREATE PROCEDURE sp_GetTreatmentsForProduct(
    IN p_product_id INT
)
BEGIN
    SELECT
        b.Naam
    FROM BehandelingProduct AS bp
    INNER JOIN Behandeling AS b ON b.BehandelingId = bp.BehandelingId
    WHERE bp.ProductId = p_product_id
      AND bp.IsActief = 1
    ORDER BY b.Naam ASC;
END$$

DELIMITER ;



DROP PROCEDURE IF EXISTS sp_CreateKlant;

DELIMITER $$

CREATE PROCEDURE sp_CreateKlant(
    IN p_voornaam VARCHAR(50),
    IN p_achternaam VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_telefoonnummer VARCHAR(20),
    IN p_wensen VARCHAR(255),
    IN p_opmerking VARCHAR(255),
    IN p_straat VARCHAR(100),
    IN p_huisnummer VARCHAR(10),
    IN p_toevoeging VARCHAR(5),
    IN p_postcode VARCHAR(6),
    IN p_woonplaats VARCHAR(50),
    IN p_is_actief BIT,
    IN p_wachtwoord VARCHAR(255)
)
BEGIN
    DECLARE v_gebruiker_id INT UNSIGNED;

    INSERT INTO Gebruiker (
        RolId,
        Gebruikersnaam,
        Wachtwoord,
        Voornaam,
        Tussenvoegsel,
        Achternaam,
        Straat,
        Huisnummer,
        Toevoeging,
        Postcode,
        Woonplaats,
        Telefoonnummer,
        Email,
        LaatsteLogin,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        4,
        LOWER(CONCAT(p_voornaam, '.', p_achternaam)),
        p_wachtwoord,
        p_voornaam,
        NULL,
        p_achternaam,
        p_straat,
        p_huisnummer,
        p_toevoeging,
        p_postcode,
        p_woonplaats,
        p_telefoonnummer,
        p_email,
        SYSDATE(6),
        p_is_actief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SET v_gebruiker_id = LAST_INSERT_ID();

    INSERT INTO Klant (
        GebruikerId,
        Wensen,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        v_gebruiker_id,
        p_wensen,
        p_is_actief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_DeleteKlant;

DELIMITER $$

CREATE PROCEDURE sp_DeleteKlant(
    IN p_id INT
)
BEGIN
    DELETE FROM Klant
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetAllKlanten;

DELIMITER $$

CREATE PROCEDURE sp_GetAllKlanten()
BEGIN
    SELECT
        K.Id,
        G.Voornaam,
        G.Achternaam,
        G.Email,
        G.Telefoonnummer,
        K.Wensen,
        K.Opmerking,
        K.IsActief,
        K.DatumAangemaakt,
        K.DatumGewijzigd
    FROM Klant AS K
    INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId
    ORDER BY K.Id DESC;
END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_GetKlantById;

DELIMITER $$

CREATE PROCEDURE sp_GetKlantById(
    IN p_id INT
)
BEGIN
    SELECT
        K.Id,
        G.Voornaam,
        G.Achternaam,
        G.Email,
        G.Telefoonnummer,
        G.Straat,
        G.Huisnummer,
        G.Toevoeging,
        G.Postcode,
        G.Woonplaats,
        K.Wensen,
        K.Opmerking,
        K.IsActief
    FROM Klant AS K
    INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId
    WHERE K.Id = p_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_UpdateKlant;

DELIMITER $$

CREATE PROCEDURE sp_UpdateKlant(
    IN p_id INT,
    IN p_voornaam VARCHAR(50),
    IN p_achternaam VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_telefoonnummer VARCHAR(20),
    IN p_wensen VARCHAR(255),
    IN p_opmerking VARCHAR(255),
    IN p_straat VARCHAR(100),
    IN p_huisnummer VARCHAR(10),
    IN p_toevoeging VARCHAR(5),
    IN p_postcode VARCHAR(6),
    IN p_woonplaats VARCHAR(50),
    IN p_is_actief BIT
)
BEGIN
    UPDATE Gebruiker AS G
    INNER JOIN Klant AS K ON K.GebruikerId = G.Id
    SET
        G.Voornaam = p_voornaam,
        G.Achternaam = p_achternaam,
        G.Email = p_email,
        G.Telefoonnummer = p_telefoonnummer,
        G.Straat = p_straat,
        G.Huisnummer = p_huisnummer,
        G.Toevoeging = p_toevoeging,
        G.Postcode = p_postcode,
        G.Woonplaats = p_woonplaats,
        G.IsActief = p_is_actief,
        G.Opmerking = p_opmerking,
        G.DatumGewijzigd = SYSDATE(6),
        K.Wensen = p_wensen,
        K.Opmerking = p_opmerking,
        K.IsActief = p_is_actief,
        K.DatumGewijzigd = SYSDATE(6)
    WHERE K.Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_CreateAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_CreateAfspraak(
    IN p_klantId INT,
    IN p_medewerkerId INT,
    IN p_behandelingId INT,
    IN p_afspraakStatusId INT,
    IN p_datum DATETIME(6),
    IN p_startTijd TIME,
    IN p_eindTijd TIME,
    IN p_isActief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN


    IF EXISTS (
        SELECT 1
        FROM Afspraak
        WHERE MedewerkerId = p_medewerkerId
          AND DATE(Datum) = DATE(p_datum)
          AND (
                p_startTijd < EindTijd
                AND p_eindTijd > StartTijd
          )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Deze medewerker heeft al een afspraak in dit tijdsblok';
    END IF;

    INSERT INTO Afspraak
    (
        KlantId,
        MedewerkerId,
        BehandelingId,
        AfspraakStatusId,
        Datum,
        StartTijd,
        EindTijd,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    )
    VALUES
    (
        p_klantId,
        p_medewerkerId,
        p_behandelingId,
        p_afspraakStatusId,
        p_datum,
        p_startTijd,
        p_eindTijd,
        p_isActief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_DeleteAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_DeleteAfspraak(IN p_id INT)
BEGIN

    DELETE FROM Afspraak
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetAfspraakById;

DELIMITER $$

CREATE PROCEDURE sp_GetAfspraakById(
    IN p_id INT
)
BEGIN

    SELECT
        A.Id,
        A.KlantId,
        A.MedewerkerId,
        A.BehandelingId,
        A.AfspraakStatusId,
        A.Datum,
        A.StartTijd,
        A.EindTijd,
        A.IsActief,
        A.Opmerking
    FROM Afspraak A
    WHERE A.Id = p_id;

END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_GetAllAfspraken;

DELIMITER $$

CREATE PROCEDURE sp_GetAllAfspraken()
BEGIN

    SELECT
        A.Id,
        CONCAT(GK.Voornaam,' ',GK.Achternaam) AS Klant,
        CONCAT(GM.Voornaam,' ',GM.Achternaam) AS Medewerker,
        B.Naam AS Behandeling,
        S.Naam AS Status,

        DATE_FORMAT(A.Datum, '%d-%m-%Y') AS Datum,

        DATE_FORMAT(A.StartTijd, '%H:%i') AS StartTijd,
        DATE_FORMAT(A.EindTijd, '%H:%i') AS EindTijd,

        A.IsActief,
        A.Opmerking,
        A.DatumAangemaakt,
        A.DatumGewijzigd

    FROM Afspraak A
    INNER JOIN Klant K
        ON K.Id = A.KlantId
    INNER JOIN Gebruiker GK
        ON GK.Id = K.GebruikerId
    INNER JOIN Medewerker M
        ON M.Id = A.MedewerkerId
    INNER JOIN Gebruiker GM
        ON GM.Id = M.GebruikerId
    INNER JOIN Behandeling B
        ON B.BehandelingId = A.BehandelingId
    INNER JOIN AfspraakStatus S
        ON S.Id = A.AfspraakStatusId

    ORDER BY A.Datum, A.StartTijd;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_UpdateAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_UpdateAfspraak(
    IN p_id INT,
    IN p_klantId INT,
    IN p_medewerkerId INT,
    IN p_behandelingId INT,
    IN p_afspraakStatusId INT,
    IN p_datum DATETIME(6),
    IN p_startTijd TIME,
    IN p_eindTijd TIME,
    IN p_isActief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN


    IF EXISTS (
        SELECT 1
        FROM Afspraak
        WHERE MedewerkerId = p_medewerkerId
          AND DATE(Datum) = DATE(p_datum)
          AND Id <> p_id
          AND (
                (p_startTijd < EindTijd AND p_eindTijd > StartTijd)
              )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Medewerker heeft al een afspraak in deze tijdsperiode';
    END IF;


    UPDATE Afspraak
    SET
        KlantId = p_klantId,
        MedewerkerId = p_medewerkerId,
        BehandelingId = p_behandelingId,
        AfspraakStatusId = p_afspraakStatusId,
        Datum = p_datum,
        StartTijd = p_startTijd,
        EindTijd = p_eindTijd,
        IsActief = p_isActief,
        Opmerking = p_opmerking,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_CreateBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_CreateBehandeling(
    IN p_naam VARCHAR(100),
    IN p_prijs DECIMAL(6,2),
    IN p_duur_minuten SMALLINT UNSIGNED,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Behandeling
        WHERE Naam = p_naam
    ) THEN
        SELECT 'exists' AS status, 0 AS new_id;
    ELSE
        INSERT INTO Behandeling (
            Naam,
            Prijs,
            DuurMinuten,
            IsActief,
            Opmerking,
            DatumAangemaakt,
            DatumGewijzigd
        )
        VALUES (
            p_naam,
            p_prijs,
            p_duur_minuten,
            1,
            p_opmerking,
            SYSDATE(6),
            SYSDATE(6)
        );

        SELECT 'created' AS status, LAST_INSERT_ID() AS new_id;
    END IF;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_DeleteBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_DeleteBehandeling(
    IN p_id INT
)
BEGIN
    DELETE FROM Behandeling
    WHERE BehandelingId = p_id;

    SELECT 'deleted' AS status, ROW_COUNT() AS affected;
END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_GetAllBehandelingen;

DELIMITER $$

CREATE PROCEDURE sp_GetAllBehandelingen()
BEGIN
    SELECT
        B.BehandelingId,
        B.Naam,
        B.Prijs,
        B.DuurMinuten,
        B.IsActief,
        B.Opmerking,
        B.DatumAangemaakt,
        B.DatumGewijzigd
    FROM Behandeling AS B
    ORDER BY B.DatumGewijzigd DESC;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetBehandelingById;

DELIMITER $$

CREATE PROCEDURE sp_GetBehandelingById(
    IN p_id INT
)
BEGIN
    SELECT
        B.BehandelingId,
        B.Naam,
        B.Prijs,
        B.DuurMinuten,
        B.Opmerking,
        B.IsActief
    FROM Behandeling AS B
    WHERE B.BehandelingId = p_id
    LIMIT 1;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_UpdateBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_UpdateBehandeling(
    IN p_id INT,
    IN p_naam VARCHAR(100),
    IN p_prijs DECIMAL(6,2),
    IN p_duur_minuten SMALLINT UNSIGNED,
    IN p_is_actief TINYINT(1),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Behandeling
        WHERE Naam = p_naam
          AND BehandelingId <> p_id
    ) THEN
        SELECT 'exists' AS status, 0 AS affected;
    ELSE
        UPDATE Behandeling
        SET
            Naam = p_naam,
            Prijs = p_prijs,
            DuurMinuten = p_duur_minuten,
            IsActief = p_is_actief,
            Opmerking = p_opmerking,
            DatumGewijzigd = SYSDATE(6)
        WHERE BehandelingId = p_id;

        SELECT 'updated' AS status, ROW_COUNT() AS affected;
    END IF;
END$$

DELIMITER ;
