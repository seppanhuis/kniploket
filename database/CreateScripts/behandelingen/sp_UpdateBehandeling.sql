DROP PROCEDURE IF EXISTS sp_UpdateBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_UpdateBehandeling(
    IN p_id INT,
    IN p_naam VARCHAR(100),
    IN p_prijs DECIMAL(6,2),
    IN p_duur_minuten SMALLINT UNSIGNED,
    IN p_is_actief TINYINT(1),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Behandeling
        WHERE Naam = p_naam
          AND BehandelingId <> p_id
    ) THEN
        SELECT 'exists' AS status, 0 AS affected;
    ELSE
        UPDATE Behandeling
        SET
            Naam = p_naam,
            Prijs = p_prijs,
            DuurMinuten = p_duur_minuten,
            IsActief = p_is_actief,
            Opmerking = p_opmerking,
            DatumGewijzigd = SYSDATE(6)
        WHERE BehandelingId = p_id;

        SELECT 'updated' AS status, ROW_COUNT() AS affected;
    END IF;
END$$

DELIMITER ;
