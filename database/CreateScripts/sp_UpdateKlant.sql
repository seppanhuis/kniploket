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
