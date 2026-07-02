DROP PROCEDURE IF EXISTS sp_GetKlantById;

DELIMITER $$

CREATE PROCEDURE sp_GetKlantById(
    IN p_id INT
)
BEGIN
    SELECT
        K.Id,
        K.GebruikerId,
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
