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
