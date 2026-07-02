DROP PROCEDURE IF EXISTS sp_DeleteBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_DeleteBehandeling(
    IN p_id INT
)
BEGIN
    -- Verwijder eerst alle gekoppelde gegevens
    DELETE FROM Afspraak
    WHERE BehandelingId = p_id;

    DELETE FROM BehandelingProduct
    WHERE BehandelingId = p_id;

    DELETE FROM BehandelingSpecialisatie
    WHERE BehandelingId = p_id;

    -- Verwijder daarna de behandeling zelf
    DELETE FROM Behandeling
    WHERE BehandelingId = p_id;

    SELECT 'deleted' AS status, ROW_COUNT() AS affected;
END$$

DELIMITER ;
