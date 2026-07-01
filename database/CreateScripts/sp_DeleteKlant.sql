DROP PROCEDURE IF EXISTS sp_DeleteKlant;

DELIMITER $$

CREATE PROCEDURE sp_DeleteKlant(
    IN p_id INT
)
BEGIN
    DELETE FROM Klant
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
