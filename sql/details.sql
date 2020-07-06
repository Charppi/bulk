DROP FUNCTION IF EXISTS getChargeDetails(INTEGER);
CREATE FUNCTION getChargeDetails(INTEGER) RETURNS TABLE (id INTEGER) AS $$ BEGIN RETURN QUERY
SELECT services.id
FROM services
WHERE services.id IN (
        SELECT DISTINCT charge_details.service_id
        FROM charge_details
        WHERE charge_details.charge_id = $1
    );
END;
$$ LANGUAGE plpgsql;