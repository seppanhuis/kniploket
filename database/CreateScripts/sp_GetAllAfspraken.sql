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