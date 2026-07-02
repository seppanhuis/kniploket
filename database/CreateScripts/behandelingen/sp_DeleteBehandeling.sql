DROP PROCEDURE IF EXISTS sp_DeleteBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_DeleteBehandeling(
    IN p_id INT
)
BEGIN
    DELETE FROM Behandeling
    WHERE BehandelingId = p_id;

    SELECT 'deleted' AS status, ROW_COUNT() AS affected;
END$$

DELIMITER ;
