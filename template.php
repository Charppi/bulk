<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .d-flex {
            display: flex;
        }

        .d-column {
            flex-direction: column;
        }

        .d-inline-flex {
            display: inline-flex;
        }

        .d-block {
            display: block;
        }

        h5 {
            margin: 0;
        }

        table {
            border-radius: 13px;
            background-color: hsl(0, 0%, 96%);
            color: black;
        }

        thead {
            /* border-radius: 10px; */
            background-color: #d7d7d7;
        }

        table thead th {
            padding: 5px 0px;
            border-bottom: 1px solid #cfcfcf;
        }

        table tbody th {
            padding: 5px 0px;
            border-bottom: 1px solid #cfcfcf;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        .global-total {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border: 2px solid #80808033;
            margin-top: 10px;
            border-radius: 10px;
        }

        .tirilla {
            border: 2px solid #80808033;
            padding: 20px;
            bottom: 50px;
            position: absolute;
        }

        .tirilla div {
            padding-left: 10px !important;
            margin-left: 10px !important;
        }

        h5 {
            margin: 0px
        }

        .col-print-1 {
            width: 8%;
            float: left;
        }

        .col-print-2 {
            width: 16%;
            float: left;
        }

        .col-print-3 {
            width: 25%;
            float: left;
        }

        .col-print-4 {
            width: 33%;
            float: left;
        }

        .col-print-5 {
            width: 42%;
            float: left;
        }

        .col-print-6 {
            width: 50%;
            float: left;
        }

        .col-print-7 {
            width: 58%;
            float: left;
        }

        .col-print-8 {
            width: 66%;
            float: left;
        }

        .col-print-9 {
            width: 75%;
            float: left;
        }

        .col-print-10 {
            width: 83%;
            float: left;
        }

        .col-print-11 {
            width: 92%;
            float: left;
        }

        .col-print-12 {
            width: 100%;
            float: left;
        }
    </style>
</head>

<body class="container">
    <div class="header">
        <img src="http://localhost/bulk/images/logo.png" alt="" width="300">
        <img src="http://localhost/bulk/images/right-logo.png" alt="" width="300">
    </div>
    <div class="header">
        <div class="d-block col-print-6">
            <h5><?php echo $client["names"] ?></h5>
            <h5>Código de factura: <strong><?php echo $charge["charge_number"] ?></strong></h5>
            <h5>Fecha inicial: <?php echo $charge["initial_date"] ?></h5>
            <h5>Fecha final: <?php echo $charge["final_date"] ?></h5>
            <h5>Documento: <?php echo $client["dni"] ?></h5>
            <h5>Telefono: +57 <?php echo $client["phone"] ?></h5>
            <h5>Dirección: <?php echo $client["address"] ?></h5>
            <h5>Barrio: <?php echo $client["neighborhood"] ?></h5>
            <h5>Estrato: <?php echo $client["stratum_name"] ?></h5>
            <h5>Estado: <?php echo $charge["payed"] ? "Pagada" : "Sin pagar" ?> </h5>
            <h5>Fecha límite de pago: <?php echo $charge["limit_date"]; ?> </h5>
        </div>
        <div class="d-block col-print-6">
            <?php echo $barcode; ?>
        </div>
    </div>
    <?php if (!empty($payments)) : ?>
        <br>
        <strong>
            <h5>Pagos / Abonos</h5>
        </strong>
        <div class="d-flex">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th>Tipo de pago</th>
                        <th>Nombre punto de pago</th>
                        <th>Dirección punto de pago</th>
                        <th>Fecha de pago</th>
                        <th>Paga con</th>
                        <th>Restante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) : ?>
                        <tr>
                            <td>Punto de recaudo</td>
                            <td> <?php echo $payment["ponit_name"]; ?> </td>
                            <td><?php echo $payment["address"]; ?></td>
                            <td><?php echo $payment["date"]; ?></td>
                            <td><?php echo $payment["delivered"]; ?></td>
                            <td><?php echo $payment["remaining"]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (!empty($previousCharges)) : ?>
        <strong>
            <h5>Facturas anteriores sin pagar.</h5>
        </strong>
        <br>
        <div class="d-flex">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Inicial</th>
                        <th>Fecha Final</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($previousCharges as $charge) : ?>
                        <tr>
                            <td><?php echo $charge["charge_number"]; ?></td>
                            <td><?php echo $charge["initial_date"]; ?></td>
                            <td><?php echo $charge["final_date"]; ?></td>
                            <td><?php echo $charge["total_amount"]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <br>
    <div class="d-flex">
        <table style="width: 100%">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>V. Unitario</th>
                    <th>V. Subsidio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $detail) : ?>
                    <tr>
                        <td><?php echo $detail["name"]; ?></td>
                        <td><?php echo $detail["price"]; ?></td>
                        <td><?php echo $detail["subsidy"]; ?></td>
                        <td><?php echo $detail["valorAPagar"]; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $totalUnitary; ?></td>
                    <td><?php echo $totalSubsidy; ?></td>
                    <td><strong><?php echo $totalToPay; ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class="global-total d-flex">
        <div class="col-print-6">
            <h2 style="padding:0px;">Total a pagar:</h2>
        </div>
        <div class="col-print-6">
            <h2>$<?php echo $globalTotalToPay; ?></h2>
        </div>
    </div>
    <img src="http://localhost/bulk/images/footer.png" alt="" width="300" style="margin-top:10px;" />
    <br>
    <div class="d-flex tirilla">
        <br>
        <div>
            <div class="col-print-6">
                <h5><?php echo $client["names"] ?></h5>
                <h5>Documento: <?php echo $client["dni"] ?></h5>
                <h5>Dirección: <?php echo $client["address"] ?></h5>
                <h5>Barrio: <?php echo $client["neighborhood"] ?></h5>
                <h5>Estrato: <?php echo $client["stratum_name"] ?></h5>
            </div>
            <div class="col-print-6">
                <h5>Código de factura: <strong><?php echo $charge["charge_number"] ?></strong></h5>
                <h5>Fecha inicial: <?php echo $charge["initial_date"] ?></h5>
                <h5>Fecha final: <?php echo $charge["final_date"] ?></h5>
                <h5>Fecha límite de pago: <?php echo date("Y") . "-" . date("m") . "-" . "13"; ?> </h5>
                <h2 style="padding:0px;">Total a pagar: <strong>$<?php echo $globalTotalToPay; ?></strong></h2>
            </div>
            <div class="col-print-6" style="margin-left:20px;">
                <?php echo $tirilla_barcode; ?>
            </div>
        </div>
    </div>
</body>

</html>