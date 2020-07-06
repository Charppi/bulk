DROP FUNCTION IF EXISTS getServicesByStratumId(INTEGER);
CREATE FUNCTION getServicesByStratumId(INTEGER) RETURNS TABLE (
    id INTEGER,
    name TEXT,
    subsidy TEXT,
    stratum_id NUMERIC,
    price TEXT
) AS $$ BEGIN RETURN QUERY
SELECT *
FROM services
WHERE services.stratum_id = $1;
END;
$$ LANGUAGE plpgsql;