<?php
require __DIR__ . '/../vendor/autoload.php';

\Moment\Moment::setLocale('es_ES');

class Snippets
{
    public function main(array $details, array $previousCharges, array $payments, array $charge, array $client)
    {
        $totalSubsidy = 0;
        $totalUnitary = 0;
        $totalToPay = 0;

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = '<img style="float:left;" width="300" src="data:image/png;base64,' . base64_encode($generator->getBarcode($charge["charge_number"], $generator::TYPE_CODE_128)) . '">';


        foreach ($details as $k => $detail) {
            $valorAPagar = floatval($detail["price"] - $detail["subsidy"]);
            $totalSubsidy =   $detail["subsidy"] + $totalSubsidy;
            $totalUnitary =   $detail["price"] + $totalUnitary;
            $totalToPay = $totalToPay + $valorAPagar;
            $details[$k]['valorAPagar'] = $this->nf($valorAPagar);
            $details[$k]['subsidy'] = $this->nf($detail["subsidy"]);
            $details[$k]['price'] = $this->nf($detail["price"]);
        }
        $globalTotalToPay = $totalToPay;
        if (!empty($previousCharges)) {
            foreach ($previousCharges as $i => $previousCharge) {
                $globalTotalToPay += $previousCharge["total_amount"];
                $i_d = new \Moment\Moment($previousCharge["initial_date"]);
                $previousCharges[$i]["initial_date"] = $i_d->format('lll', new \Moment\CustomFormats\MomentJs());
                $f_d = new \Moment\Moment($previousCharge["final_date"]);
                $previousCharges[$i]["final_date"] = $f_d->format('lll', new \Moment\CustomFormats\MomentJs());
                $previousCharges[$i]["total_amount"] = $this->nf($previousCharge["total_amount"]);
            }
        }
        if (!empty($payments)) {
            foreach ($payments as $j => $payment) {
                $dt = new \Moment\Moment($payment["date"]);
                $previousCharge[$i]["initial_date"] = $dt->format('lll', new \Moment\CustomFormats\MomentJs());
                $payments[$j]["date "] = $dt->format('lll', new \Moment\CustomFormats\MomentJs());
            }
        }
        $charge_initial_date = new \Moment\Moment($charge["initial_date"]);
        $charge["initial_date"] = $charge_initial_date->format('lll', new \Moment\CustomFormats\MomentJs());

        $charge_final_date = new \Moment\Moment($charge["final_date"]);
        $charge["final_date"] = $charge_final_date->format('lll', new \Moment\CustomFormats\MomentJs());

        $totalSubsidy = $this->nf($totalSubsidy);
        $totalUnitary = $this->nf($totalUnitary);
        $totalToPay = $this->nf($totalToPay);
        $globalTotalToPay = $this->nf($globalTotalToPay);
        $title = "Factura #" . $charge["charge_number"];
        ob_start();
        include(__DIR__ . '/../template.php');
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
    // Funcion number_format reducida para no poner los puntos y comas por todos lados ;)
    public function nf($n)
    {
        return number_format($n, 2, '.', ',');
    }
}
