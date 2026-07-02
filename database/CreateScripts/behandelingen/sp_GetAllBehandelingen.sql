DROP PROCEDURE IF EXISTS sp_GetAllBehandelingen;

DELIMITER $$

CREATE PROCEDURE sp_GetAllBehandelingen()
BEGIN
    SELECT
        B.BehandelingId,
        B.Naam,
        B.Prijs,
        B.DuurMinuten,
        B.IsActief,
        B.Opmerking,
        B.DatumAangemaakt,
        B.DatumGewijzigd
    FROM Behandeling AS B
    ORDER BY B.Naam ASC;
END$$

DELIMITER ;
