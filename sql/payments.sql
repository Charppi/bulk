CREATE FUNCTION getPaymentsByChargeId(INTEGER) RETURNS TABLE (
    charge_id INTEGER,
    date BIGINT,
    payu BOOLEAN,
    cash BOOLEAN,
    id BIGINT,
    full_payed BOOLEAN,
    delivered TEXT,
    remaining TEXT,
    point_name TEXT,
    address TEXT
) AS $$ BEGIN RETURN QUERY
SELECT payments.*,
    users.point_name,
    users.address
FROM payments
    INNER JOIN users_payments ON users_payments.payment_id = payments.id
    INNER JOIN users ON users.id = users_payments.user_id
WHERE payments.charge_id = $1;
END;
$$ LANGUAGE plpgsql;