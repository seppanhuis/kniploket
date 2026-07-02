DELIMITER $$

DROP PROCEDURE IF EXISTS sp_GetAllProducten$$
CREATE PROCEDURE sp_GetAllProducten()
BEGIN
    SELECT
        p.Id,
        p.ProductNaam,
        p.EANCode,
        p.Voorraad,
        p.MinimumVoorraad,
        p.IsActief,
        p.Opmerking,
        p.LeverancierId,
        p.CategorieId,
        l.Naam AS leverancier_naam,
        c.Naam AS categorie_naam
    FROM Product AS p
    LEFT JOIN Leverancier AS l ON l.Id = p.LeverancierId
    LEFT JOIN ProductCategorie AS c ON c.Id = p.CategorieId
    ORDER BY p.ProductNaam ASC;
END$$

DROP PROCEDURE IF EXISTS sp_CreateProduct$$
CREATE PROCEDURE sp_CreateProduct(
    IN p_product_naam VARCHAR(100),
    IN p_ean_code VARCHAR(13),
    IN p_voorraad INT,
    IN p_minimum_voorraad INT,
    IN p_leverancier_id INT,
    IN p_categorie_id INT,
    IN p_is_actief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    INSERT INTO Product (
        ProductNaam,
        EANCode,
        Voorraad,
        MinimumVoorraad,
        LeverancierId,
        CategorieId,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_product_naam,
        p_ean_code,
        p_voorraad,
        p_minimum_voorraad,
        p_leverancier_id,
        p_categorie_id,
        p_is_actief,
        p_opmerking,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DROP PROCEDURE IF EXISTS sp_GetProductById$$
CREATE PROCEDURE sp_GetProductById(
    IN p_id INT
)
BEGIN
    SELECT
        p.Id,
        p.ProductNaam,
        p.EANCode,
        p.Voorraad,
        p.MinimumVoorraad,
        p.IsActief,
        p.Opmerking,
        p.LeverancierId,
        p.CategorieId
    FROM Product AS p
    WHERE p.Id = p_id;
END$$

DROP PROCEDURE IF EXISTS sp_UpdateProduct$$
CREATE PROCEDURE sp_UpdateProduct(
    IN p_id INT,
    IN p_product_naam VARCHAR(100),
    IN p_ean_code VARCHAR(13),
    IN p_voorraad INT,
    IN p_minimum_voorraad INT,
    IN p_leverancier_id INT,
    IN p_categorie_id INT,
    IN p_is_actief BIT,
    IN p_opmerking VARCHAR(255)
)
BEGIN
    UPDATE Product
    SET
        ProductNaam = p_product_naam,
        EANCode = p_ean_code,
        Voorraad = p_voorraad,
        MinimumVoorraad = p_minimum_voorraad,
        LeverancierId = p_leverancier_id,
        CategorieId = p_categorie_id,
        IsActief = p_is_actief,
        Opmerking = p_opmerking,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DROP PROCEDURE IF EXISTS sp_DeleteProduct$$
CREATE PROCEDURE sp_DeleteProduct(
    IN p_id INT
)
BEGIN
    UPDATE Product
    SET
        IsActief = 0,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DROP PROCEDURE IF EXISTS sp_GetTreatmentsForProduct$$
CREATE PROCEDURE sp_GetTreatmentsForProduct(
    IN p_product_id INT
)
BEGIN
    SELECT
        b.Naam
    FROM BehandelingProduct AS bp
    INNER JOIN Behandeling AS b ON b.BehandelingId = bp.BehandelingId
    WHERE bp.ProductId = p_product_id
      AND bp.IsActief = 1
    ORDER BY b.Naam ASC;
END$$

DELIMITER ;
