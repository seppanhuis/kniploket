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