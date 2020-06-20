<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>

<body class="container">
    <div class="row justify-content-betwen">
        <div class="col">
            <img src="http://34.75.209.144:3010/img/logo.png" alt="" width="300">
        </div>
        <div class="col">
            <img src="http://34.75.209.144:3010/img/right-logo.png" alt="" width="300">
        </div>
    </div>
    <div class="row justify-content-between">
        <div class="col">
            <h5><?php echo $client["names"] ?></h5>
            <h5>Fecha inicial: <?php echo $charge["initial_date"] ?></h5>
            <h5>Fecha final: <?php echo $charge["final_date"] ?></h5>
            <h5>Documento: <?php echo $client["dni"] ?></h5>
            <h5>Telefono: +57 <?php echo $client["phone"] ?></h5>
            <h5>Dirección: <?php echo $client["address"] ?></h5>
            <h5>Barrio: <?php echo $client["neighborhood"] ?></h5>
            <h5>Estrato: <?php echo $client["stratum_name"] ?></h5>
            <h5>Estado: <?php echo $charge["payed"] ? "Pagada" : "Sin pagar" ?> </h5>
        </div>
        <div class="col">
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
                    <th>V. Subsidio</th>
                    <th>V. Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $detail) : ?>
                    <tr>
                        <td><?php echo $detail["name"]; ?></td>
                        <td><?php echo $detail["subsidy"]; ?></td>
                        <td><?php echo $detail["price"]; ?></td>
                        <td><?php echo $valorAPagar; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $totalSubsidy; ?></td>
                    <td><?php echo $totalUnitary; ?></td>
                    <td><strong><?php echo $totalToPay; ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class="row justify-content-between">
        <div></div>
        <div class="card">
            <div class="card-body">
                <h1>VALOR A PAGAR</h1>
                <h1>$<?php echo $globalTotalToPay; ?></h1>
            </div>
        </div>
    </div>
    <img src="http://34.75.209.144:3010/img/footer.png" alt="" width="300" style="margin-top:10px;" />
</body>

</html>