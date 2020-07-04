<?php
ini_set('max_execution_time', 0);
require 'models/charges.php';
require 'services/snippets.php';
require __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 20,
    'margin_right' => 15,
    'margin_top' => 10,
    'margin_bottom' => 25,
    'margin_header' => 0,
    'margin_footer' => 10,
    'tempDir' => 'temp'
]);

$Charges = new Charges();
$Snippets = new Snippets();
$distinctCharges = $Charges->getDistinctCharges();


foreach ($distinctCharges as $k => $charge) {
    $client = $Charges->getClientsBy($charge["client_id"]); //Datos del cliente
    $payments = $Charges->getPaymentByChargeId($charge["id"]); //Pagos y abonos de la factura selecionada
    $previousCharges = $Charges->getDistinctNotPayedCharges($charge["client_id"], $charge["id"]); //Facturas anteriores con saldos
    $details = $Charges->getChargeDetails($charge["id"]);
    $services = $Charges->getServicesByStratum($client['stratum_id']);

    $html = $Snippets->main($details, $previousCharges, $payments, $charge, $client, $services);

    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Facturas bulk");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML($html);
    $mpdf->AddPage();

    // if ($k == 20) break;
}

$mpdf->Output('bulk.pdf', 'I');
