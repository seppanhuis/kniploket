DROP PROCEDURE IF EXISTS sp_DeleteBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_DeleteBehandeling(
    IN p_id INT
)
BEGIN
    -- Controleer of de behandeling inactief is
    IF EXISTS (
        SELECT 1
        FROM Behandeling
        WHERE BehandelingId = p_id
          AND IsActief = 0
    ) THEN

        SELECT 'inactive' AS status, 0 AS affected;

    ELSE

        -- Verwijder eerst gekoppelde gegevens
        DELETE FROM Afspraak
        WHERE BehandelingId = p_id;

        DELETE FROM BehandelingProduct
        WHERE BehandelingId = p_id;

        DELETE FROM BehandelingSpecialisatie
        WHERE BehandelingId = p_id;

        -- Verwijder daarna de behandeling
        DELETE FROM Behandeling
        WHERE BehandelingId = p_id;

        SELECT 'deleted' AS status, ROW_COUNT() AS affected;

    END IF;

END$$

DELIMITER ;
