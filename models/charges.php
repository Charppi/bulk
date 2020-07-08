<?php
require 'connection.php';
class Charges extends Connection
{
    public function getDistinctCharges($offset)
    {
        return $this->genericQuery("SELECT DISTINCT ON(charges.client_id) charges.*, clients.names, clients.address, clients.dni FROM charges INNER JOIN clients ON clients.id = charges.client_id ORDER BY charges.client_id,charges.final_date DESC OFFSET $offset;", true);
    }
    public function getDistinctNotPayedCharges($client_id, $charge_id)
    {
        return $this->genericQuery("SELECT * FROM charges WHERE client_id = $client_id AND id != $charge_id AND NOT payed ORDER BY id ASC;", true);
    }
    public function getPaymentByChargeId($charge_id)
    {
        return $this->genericQuery("SELECT payments.*, users.point_name, users.address FROM payments INNER JOIN users_payments ON users_payments.payment_id = payments.id INNER JOIN users ON users.id = users_payments.user_id  WHERE charge_id = $charge_id;", true);
    }
    public function getChargeDetails($charge_id)
    {
        return $this->genericQuery("SELECT id FROM services WHERE id IN (SELECT DISTINCT service_id FROM charge_details WHERE charge_id = $charge_id)", true);
    }
    public function getClientsBy($client_id)
    {
        return $this->genericQuery("SELECT clients.*, stratums.name AS stratum_name FROM clients INNER JOIN stratums ON clients.stratum_id = stratums.id WHERE clients.id = $client_id");
    }
    public function  getSingleCharge($chargeId)
    {
        return $this->genericQuery("SELECT * FROM charges WHERE id = $chargeId");
    }
    public function getServicesByStratum($stratum)
    {
        return $this->genericQuery("SELECT * FROM services WHERE stratum_id = $stratum", true);
    }
}
