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
    ORDER BY K.Id ASC;
END$$

DELIMITER ;
