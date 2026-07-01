DROP PROCEDURE IF EXISTS sp_UpdateAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_UpdateAfspraak(
    IN p_id INT,
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
          AND Id <> p_id
          AND (
                (p_startTijd < EindTijd AND p_eindTijd > StartTijd)
              )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Medewerker heeft al een afspraak in deze tijdsperiode';
    END IF;

    
    UPDATE Afspraak
    SET
        KlantId = p_klantId,
        MedewerkerId = p_medewerkerId,
        BehandelingId = p_behandelingId,
        AfspraakStatusId = p_afspraakStatusId,
        Datum = p_datum,
        StartTijd = p_startTijd,
        EindTijd = p_eindTijd,
        IsActief = p_isActief,
        Opmerking = p_opmerking,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;

END$$

DELIMITER ;