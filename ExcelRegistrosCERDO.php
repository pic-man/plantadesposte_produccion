<?php
//date_default_timezone_set("America/bogota");
//error_reporting (0);
include("config.php");

$semana = isset($_GET["semana"]) ? $_GET["semana"] : "";
$sede = isset($_GET["sede"]) ? $_GET["sede"] : "";

$sql = "SELECT 
    sede_origen, 
    item, 
    enviado, 
    sede_destino,
    CASE 
        WHEN status = 0 THEN 'MANUAL'
        WHEN status = 1 THEN 'AUTOMATICO'
    END AS status
FROM registro_cambios
WHERE 
    semana = '$semana' AND 
    sede_origen = '$sede' AND 
    especie = 'CERDO'
";
$rs_operation = mysqli_query($link, $sql);
if (!$rs_operation) {
    echo "Error: " . mysqli_error($link);
}

$sql2 = "SELECT 
    sede_origen, 
    item, 
    enviado, 
    sede_destino,
    CASE 
        WHEN status = 0 THEN 'MANUAL'
        WHEN status = 1 THEN 'AUTOMATICO'
    END AS status
FROM registro_cambios
WHERE 
    semana = '$semana' AND 
    sede_destino = '$sede' AND 
    especie = 'CERDO'
";

$rs_operation2 = mysqli_query($link, $sql2);
if (!$rs_operation2) {
    echo "Error: " . mysqli_error($link);
}

$Items = [
    "073612" => "BRAZO CERDO", 
    "073613" => "COSTILLA CERDO", 
    "073614" => "LOMO CERDO", 
    "073615" => "PERNIL CERDO", 
    "073616" => "TOCINETA", 
    "009251" => "ESPINAZO", 
    "073559" => "HUESO BLANCO CERDO", 
    "005800" => "EMPELLLA CERDO", 
    "009270" => "OREJA CERDO", 
    "005819" => "PAPADA", 
    "007844" => "PEZUNA", 
    "040959" => "TOCINO", 
    "033700" => "CABEZA SIN OREJA"
];
?>
<html lang="es">
<head>
</head>
<body>
    <?php 
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=$sede - CERDO - semana-$semana.xls");
        header("Content-Transfer-Encoding: binary ");
    ?>
    <table border="1" width="50%">
        <tr>
            <th colspan="5">
                <font size="4"><?= $sede ?><br>Semana: <?= $semana ?></font>
            </th>
        </tr>
        <tr>
            <th colspan="5">
                <font size="4">Enviado</font>
            </th>
        </tr>
        <tr>
            <th style="width: 3.60cm;"><font size="4">Sede Origen</font></th>
            <th style="width: 4.5cm;"><font size="4">Item</font></th>
            <th style="width: 3.60cm;"><font size="4">Cantidad</font></th>
            <th style="width: 3.60cm;"><font size="4">Sede Destino</font></th>
            <th style="width: 3.60cm;"><font size="4">Proceso</font></th>
        </tr>
        <?php while ($row = mysqli_fetch_row($rs_operation)): ?>
            <tr>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row[0] ?></font></td>
                <td style="width: 4.5cm; text-align: center;"><font size="3"><?= $Items[$row[1]] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row[2] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row[3] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row[4] ?></font></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <th colspan="5">
                <font size="4">Recibido</font>
            </th>
        </tr>
        <tr>
            <th style="width: 3.60cm;"><font size="4">Sede Origen</font></th>
            <th style="width: 4.5cm;"><font size="4">Item</font></th>
            <th style="width: 3.60cm;"><font size="4">Cantidad</font></th>
            <th style="width: 3.60cm;"><font size="4">Sede Destino</font></th>
            <th style="width: 3.60cm;"><font size="4">Proceso</font></th>
        </tr>
        <?php while ($row2 = mysqli_fetch_row($rs_operation2)): ?>
            <tr>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row2[0] ?></font></td>
                <td style="width: 4.5cm; text-align: center;"><font size="3"><?= $Items[$row2[1]] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row2[2] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row2[3] ?></font></td>
                <td style="width: 3.60cm; text-align: center;"><font size="4"><?= $row2[4] ?></font></td>
            </tr>
        <?php endwhile; ?>
    </table>
    