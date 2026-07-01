DROP PROCEDURE IF EXISTS sp_DeleteAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_DeleteAfspraak(IN p_id INT)
BEGIN

    DELETE FROM Afspraak
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;

END$$

DELIMITER ;