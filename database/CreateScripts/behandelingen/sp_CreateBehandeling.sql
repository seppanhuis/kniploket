DROP PROCEDURE IF EXISTS sp_CreateBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_CreateBehandeling(
    IN p_naam VARCHAR(100),
    IN p_prijs DECIMAL(6,2),
    IN p_duur_minuten SMALLINT UNSIGNED,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Behandeling
        WHERE Naam = p_naam
    ) THEN
        SELECT 'exists' AS status, 0 AS new_id;
    ELSE
        INSERT INTO Behandeling (
            Naam,
            Prijs,
            DuurMinuten,
            IsActief,
            Opmerking,
            DatumAangemaakt,
            DatumGewijzigd
        )
        VALUES (
            p_naam,
            p_prijs,
            p_duur_minuten,
            1,
            p_opmerking,
            SYSDATE(6),
            SYSDATE(6)
        );

        SELECT 'created' AS status, LAST_INSERT_ID() AS new_id;
    END IF;
END$$

DELIMITER ;
