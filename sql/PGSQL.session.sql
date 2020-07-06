DROP FUNCTION IF EXISTS main();
CREATE FUNCTION main() RETURNS VOID AS $$
DECLARE totalSubsidy INTEGER;
totalUnitary INTEGER;
totalToPay INTEGER;
client Record;
payment Record;
previousCharges Record;
details Record;
services Record;
charge Record;
c_charges Cursor for
SELECT DISTINCT ON(charges.client_id) charges.*,
    clients.names,
    clients.address,
    clients.dni
FROM charges
    INNER JOIN clients ON clients.id = charges.client_id
ORDER BY charges.client_id,
    charges.final_date DESC;
BEGIN FOR charge IN c_charges LOOP Raise Notice 'ID# %',
charge.id;
END LOOP;
END;
$$ LANGUAGE plpgsql;