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
    especie = 'RES'
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
    especie = 'RES'
";
$rs_operation2 = mysqli_query($link, $sql2);
if (!$rs_operation2) {
    echo "Error: " . mysqli_error($link);
}

$Items = [
    "073617" => "AMPOLLETA NORMAL", 
    "073618" => "BOLA NEGRA NORMAL",
    "073619" => "CADERITA NORMA",
    "073620" => "COSTILLA NORMAL",
    "073624" => "MORRILLO*KILO",
    "073625" => "MUCHACHO",
    "073626" => "PECHO*KILO",
    "073627" => "PEPINO*KILO",
    "073628" => "PULPA NORMAL",
    "073631" => "SOBACO*KILO",
    "006411" => "HUESO COGOTE",
    "037508" => "HUESO PROMOCION",
    "073633" => "LOMO VICHE",
    "073621" => "ESPALDILLA",
    "073622" => "LOMO CARACHA",
    "073623" => "LOMO REDONDO",
    "073629" => "PUNTA ANCA",
    "073630" => "PUNTA FALDA"
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
        header("Content-Disposition: attachment;filename=$sede - RES - semana-$semana.xls");
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
    