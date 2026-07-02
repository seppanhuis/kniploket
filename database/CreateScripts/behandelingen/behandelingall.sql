use kniploket;

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
        INSERT INTO Behandeling
        (
            Naam,
            Prijs,
            DuurMinuten,
            IsActief,
            Opmerking,
            DatumAangemaakt,
            DatumGewijzigd
        )
        VALUES
        (
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

DROP PROCEDURE IF EXISTS sp_GetAllBehandelingen;

DELIMITER $$

CREATE PROCEDURE sp_GetAllBehandelingen()
BEGIN
    SELECT
        B.BehandelingId,
        B.Naam,
        B.Prijs,
        B.DuurMinuten,
        B.Opmerking,
        B.IsActief
    FROM Behandeling AS B
    WHERE B.IsActief = 1
    ORDER BY B.Naam ASC;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetAllBehandelingen;

DELIMITER $$

CREATE PROCEDURE sp_GetAllBehandelingen()
BEGIN
    SELECT
        B.BehandelingId,
        B.Naam,
        B.Prijs,
        B.DuurMinuten,
        B.Opmerking,
        B.IsActief
    FROM Behandeling AS B
    WHERE B.IsActief = 1
    ORDER BY B.Naam ASC;
END$$

DELIMITER ;


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

DROP PROCEDURE IF EXISTS sp_UpdateBehandeling;

DELIMITER $$

CREATE PROCEDURE sp_UpdateBehandeling(
    IN p_id INT,
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
          AND BehandelingId <> p_id
    ) THEN
        SELECT 'exists' AS status, 0 AS affected;
    ELSE
        UPDATE Behandeling
        SET
            Naam = p_naam,
            Prijs = p_prijs,
            DuurMinuten = p_duur_minuten,
            Opmerking = p_opmerking,
            DatumGewijzigd = SYSDATE(6)
        WHERE BehandelingId = p_id;

        SELECT 'updated' AS status, ROW_COUNT() AS affected;
    END IF;
END$$

DELIMITER ;
