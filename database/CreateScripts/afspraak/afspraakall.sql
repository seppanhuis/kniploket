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

DROP PROCEDURE IF EXISTS sp_DeleteAfspraak;

DELIMITER $$

CREATE PROCEDURE sp_DeleteAfspraak(IN p_id INT)
BEGIN

    DELETE FROM Afspraak
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;

END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetAfspraakById;

DELIMITER $$

CREATE PROCEDURE sp_GetAfspraakById(
    IN p_id INT
)
BEGIN

    SELECT
        A.Id,
        A.KlantId,
        A.MedewerkerId,
        A.BehandelingId,
        A.AfspraakStatusId,
        A.Datum,
        A.StartTijd,
        A.EindTijd,
        A.IsActief,
        A.Opmerking
    FROM Afspraak A
    WHERE A.Id = p_id;

END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS sp_GetAllAfspraken;

DELIMITER $$

CREATE PROCEDURE sp_GetAllAfspraken()
BEGIN

    SELECT
        A.Id,
        CONCAT(GK.Voornaam,' ',GK.Achternaam) AS Klant,
        CONCAT(GM.Voornaam,' ',GM.Achternaam) AS Medewerker,
        B.Naam AS Behandeling,
        S.Naam AS Status,

        DATE_FORMAT(A.Datum, '%d-%m-%Y') AS Datum,
        DATE_FORMAT(A.StartTijd, '%H:%i') AS StartTijd,
        DATE_FORMAT(A.EindTijd, '%H:%i') AS EindTijd,

        A.IsActief,
        A.Opmerking,
        A.DatumAangemaakt,
        A.DatumGewijzigd

    FROM Afspraak A
    INNER JOIN Klant K
        ON K.Id = A.KlantId
    INNER JOIN Gebruiker GK
        ON GK.Id = K.GebruikerId
    INNER JOIN Medewerker M
        ON M.Id = A.MedewerkerId
    INNER JOIN Gebruiker GM
        ON GM.Id = M.GebruikerId
    INNER JOIN Behandeling B
        ON B.BehandelingId = A.BehandelingId
    INNER JOIN AfspraakStatus S
        ON S.Id = A.AfspraakStatusId

    ORDER BY A.Datum, A.StartTijd;

END$$

DELIMITER ;

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
