DROP FUNCTION IF EXISTS getDistinctNotPayedCharges(INTEGER, INTEGER);
CREATE FUNCTION getDistinctNotPayedCharges(INTEGER, INTEGER) RETURNS TABLE (
    id INTEGER,
    client_id INTEGER,
    payed BOOLEAN,
    initial_date BIGINT,
    final_date BIGINT,
    charge_number TEXT,
    state TEXT,
    total_amount TEXT
) AS $$ BEGIN RETURN QUERY
SELECT *
FROM charges
WHERE charges.client_id = $1
    AND charges.id != $2
    AND NOT charges.payed
ORDER BY id ASC;
END;
$$ LANGUAGE plpgsql;