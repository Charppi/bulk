<?php
require __DIR__ . '/../vendor/autoload.php';

\Moment\Moment::setLocale('es_ES');

class Snippets
{
    public function main(array $details, array $previousCharges, array $payments, array $charge, array $client, array $services)
    {
        $totalSubsidy = 0;
        $totalUnitary = 0;
        $totalToPay = 0;

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = '<img style="float:left;" width="300" src="data:image/png;base64,' . base64_encode($generator->getBarcode($charge["charge_number"], $generator::TYPE_CODE_128)) . '">';
        $tirilla_barcode = '<img style="float:left;" width="150" src="data:image/png;base64,' . base64_encode($generator->getBarcode($charge["charge_number"], $generator::TYPE_CODE_128)) . '">';



        foreach ($services as $o => $service) {
            foreach ($details as $detail) {
                if ($detail['id'] == $service['id']) {
                    //Todos los servicios del estrato del usuario
                    $valorAPagar = floatval($service["price"] - $service["subsidy"]);
                    $totalSubsidy = $service["subsidy"] + $totalSubsidy;
                    $totalUnitary = $service["price"] + $totalUnitary;
                    $totalToPay = $totalToPay + $valorAPagar;
                    $services[$o]['valorAPagar'] = $this->nf($valorAPagar);
                    $services[$o]['subsidy'] = $this->nf($service["subsidy"]);
                    $services[$o]['price'] = $this->nf($service["price"]);
                    break;
                } else {
                    $services[$o]['valorAPagar'] = $this->nf(0);
                    $services[$o]['subsidy'] = $this->nf(0);
                    $services[$o]['price'] = $this->nf(0);
                }
            }
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

        if(!empty($previousCharges)){
            $charge_initial_date = new \Moment\Moment($previousCharges[0]["initial_date"]);
            $charge["initial_date"] = $charge_initial_date->format('lll', new \Moment\CustomFormats\MomentJs());    
        }else{
            $charge_initial_date = new \Moment\Moment($charge["initial_date"]);
            $charge["initial_date"] = $charge_initial_date->format('lll', new \Moment\CustomFormats\MomentJs());    
        }


        $charge_final_date = new \Moment\Moment($charge["final_date"]);
        $charge["final_date"] = $charge_final_date->format('lll', new \Moment\CustomFormats\MomentJs());
        $charge["limit_date"] = $charge_final_date->addDays(13)->format('lll', new \Moment\CustomFormats\MomentJs());


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
