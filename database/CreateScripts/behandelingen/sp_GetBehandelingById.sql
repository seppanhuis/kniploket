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
