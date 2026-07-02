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
