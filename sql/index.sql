DROP FUNCTION IF EXISTS getServicesByStratumId(INTEGER);
DROP FUNCTION IF EXISTS getPaymentsByChargeId(INTEGER);
DROP FUNCTION IF EXISTS getClientById(INTEGER);
DROP FUNCTION IF EXISTS getDistinctNotPayedCharges(INTEGER, INTEGER);
DROP FUNCTION IF EXISTS getChargeDetails(INTEGER);
DROP FUNCTION IF EXISTS getServicesByStratumId(INTEGER);
DROP FUNCTION IF EXISTS main();
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
BEGIN FOR charge IN c_charges LOOP -- LOGICCCCCC
client := getClientById(charge.client_id);
payment := getPaymentsByChargeId(charge.id);
previousCharges := getDistinctNotPayedCharges(charge.client_id, charge.id);
details := getChargeDetails(charge.id);
services := getServicesByStratumId(charge.client_id);
Raise Notice 'Cliente %',
client;
-- LOGICCCCCC
END LOOP;
END;
$$ LANGUAGE plpgsql;


 CREATE OR REPLACE FUNCTION setDetails(INTEGER,INTEGER) RETURNS TABLE (
  	id INTEGER, 
 	name TEXT,  	
	subsidy TEXT,
 	stratum_id NUMERIC, 
	price TEXT, 
	total FLOAT
 ) AS $$
  DECLARE
 		c_services refcursor;
		c_subscribed_services refcursor;
		service Record;
		subscibed_services Record;
  BEGIN
for service in SELECT services.*, false as subscribed, (CAST(services.price AS float) - CAST(services.subsidy AS float)) AS total FROM services WHERE services.stratum_id = $1 loop
 for subscibed_services in SELECT services.id FROM services WHERE services.id IN (SELECT DISTINCT charge_details.service_id FROM charge_details WHERE charge_details.charge_id = $2) loop 
	if (CAST(subscibed_services.id as INTEGER) = CAST(service.id as INTEGER)) then
 		service.subscribed := true;
 		EXIT;
 	else
		service.total := 0;
	end if;
 end loop;
 RETURN NEXT;
end loop;
RETURN;
END;
$$ LANGUAGE plpgsql;



 CREATE OR REPLACE FUNCTION setDetails(INTEGER,INTEGER) RETURNS TABLE (
  	id INTEGER, 
 	name TEXT,  	
	subsidy TEXT,
 	stratum_id NUMERIC, 
	price TEXT, 
	total FLOAT
 ) AS $$
  DECLARE
 		c_services refcursor;
		c_subscribed_services refcursor;
		service Record;
		subscibed_services Record;
  BEGIN
   OPEN c_services FOR SELECT services.*, false as subscribed, (CAST(services.price AS float) - CAST(services.subsidy AS float)) AS total  FROM services WHERE services.stratum_id = $1;
   WHILE  (FOUND) LOOP
   RETURN NEXT;
   END LOOP;
   CLOSE c_services;

   
 for subscibed_services in SELECT services.id FROM services WHERE services.id IN (SELECT DISTINCT charge_details.service_id FROM charge_details WHERE charge_details.charge_id = $2) loop 
	if (CAST(subscibed_services.id as INTEGER) = CAST(service.id as INTEGER)) then
 		service.subscribed := true;
 		EXIT;
 	else
		service.total := 0;
	end if;
 end loop;
 RETURN NEXT;
end loop;
RETURN;
END;
$$ LANGUAGE plpgsql;


DO $$
DECLARE 
 		c_services refcursor;
		service Record;
BEGIN
   OPEN c_services FOR SELECT services.*, false as subscribed, (CAST(services.price AS float) - CAST(services.subsidy AS float)) AS total  FROM services WHERE services.stratum_id = $1;
   FETCH c_services INTO service;
   WHILE  (FOUND) LOOP
   	  RAIS
      FETCH c_services INTO service;
   END LOOP;
   CLOSE c_services;
END; 
$$ LANGUAGE plpgsql;



SELECT * from setDetails(1,6320)











 CREATE OR REPLACE FUNCTION setDetails(INTEGER,INTEGER) RETURNS TABLE (
  	id INTEGER, 
 	name TEXT,  	
	subsidy TEXT,
 	stratum_id NUMERIC, 
	price TEXT, 
	total FLOAT
 ) AS $$
  DECLARE
 		c_services refcursor;
		c_subscribed_services refcursor;
		service Record;
		subscibed_services Record;
  BEGIN
for service in SELECT services.*, false as subscribed, (CAST(services.price AS float) - CAST(services.subsidy AS float)) AS total FROM services WHERE services.stratum_id = $1 loop
 for subscibed_services in SELECT services.id FROM services WHERE services.id IN (SELECT DISTINCT charge_details.service_id FROM charge_details WHERE charge_details.charge_id = $2) loop 

 end loop;
 RETURN NEXT;
end loop;
RETURN;
END;
$$ LANGUAGE plpgsql;

SELECT * FROM setDetails(1,6320);





















-- DO $$
-- DECLARE 
--  		c_services refcursor;
-- 		c_subscribed_services refcursor;
-- 		service Record;
--         subscribed_service Record;

-- BEGIN
--    OPEN c_services FOR SELECT services.*, false as subscribed, (CAST(services.price AS float) - CAST(services.subsidy AS float)) AS total  FROM services WHERE services.stratum_id = 1;
--    FETCH c_services INTO service;
--    WHILE  (FOUND) LOOP 
-- 	 FOR subscribed_service IN SELECT services.* FROM services WHERE services.id IN (SELECT DISTINCT charge_details.service_id FROM charge_details WHERE charge_details.charge_id = 6320) LOOP
-- 	 	 RAISE NOTICE 'Servicio %',subscribed_service;
-- 	 END LOOP;
--      FETCH c_services INTO service;
--    END LOOP;
--    CLOSE c_services;
-- END; 
-- $$ LANGUAGE plpgsql;

-- SELECT services.* FROM services WHERE services.id IN (SELECT DISTINCT charge_details.service_id FROM charge_details WHERE charge_details.charge_id = 6320)
