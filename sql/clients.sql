DROP FUNCTION IF EXISTS getClientById(INTEGER);
CREATE FUNCTION getClientById(INTEGER) RETURNS TABLE (
    id INTEGER,
    names TEXT,
    code TEXT,
    address TEXT,
    counter TEXT,
    phone TEXT,
    dni TEXT,
    email TEXT,
    neighborhood TEXT,
    stratum_id INTEGER,
    active BOOLEAN,
    cut_date INTEGER,
    stratum_name TEXT
) AS $$ BEGIN RETURN QUERY
SELECT clients.*,
    stratums.name AS stratum_name
FROM clients
    INNER JOIN stratums ON clients.stratum_id = stratums.id
WHERE clients.id = $1;
END;
$$ LANGUAGE plpgsql;