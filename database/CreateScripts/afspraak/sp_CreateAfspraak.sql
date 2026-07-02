DROP PROCEDURE IF EXISTS sp_CreateAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_CreateAfspraak(
    IN p_klantId INT,
    IN p_medewerkerId INT,
    IN p_behandelingId INT,
    IN p_afspraakStatusId INT,
    IN p_datum DATETIME(6),
    IN p_startTijd TIME,
    IN p_eindTijd TIME,
    IN p_isActief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN

    
    IF EXISTS (
        SELECT 1
        FROM Afspraak
        WHERE MedewerkerId = p_medewerkerId
          AND DATE(Datum) = DATE(p_datum)
          AND (
                p_startTijd < EindTijd
                AND p_eindTijd > StartTijd
          )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Deze medewerker heeft al een afspraak in dit tijdsblok';
    END IF;

    INSERT INTO Afspraak
    (
        KlantId,
        MedewerkerId,
        BehandelingId,
        AfspraakStatusId,
        Datum,
        StartTijd,
        EindTijd,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    )
    VALUES
    (
        p_klantId,
        p_medewerkerId,
        p_behandelingId,
        p_afspraakStatusId,
        p_datum,
        p_startTijd,
        p_eindTijd,
        p_isActief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;

END$$

DELIMITER ;