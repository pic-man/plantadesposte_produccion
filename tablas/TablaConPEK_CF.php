<?php
include "../config.php";

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

if ($guia == "") {
    echo json_encode(array(
        "recordsTotal"      => intval(0),         
        "recordsFiltered"   => intval(0),       
        "data"              => 0                     
    ));
    exit;
}

$sqlMejora = "SELECT mi.item, SUM(mi.kilos_mejora) - (SUM(mi.cajas_mejora) * 2 + SUM(mi.cajas_mejora_base) * 1.8) AS kilosNetos, p.descripcion AS descripcion
FROM mejora_items mi
INNER JOIN plantilla p ON p.item = mi.item
WHERE mi.id_guia = '$guia'
GROUP BY mi.item
ORDER BY p.descripcion ASC";

$rs_operationMejora = mysqli_query($link, $sqlMejora);

$itemsMejora = [];

while ($row = mysqli_fetch_assoc($rs_operationMejora)) {
    $itemsMejora[$row["item"]] = $row;
}

$sqlDesprese = "SELECT 
    di.item AS item,
    CASE WHEN di.status = 1 THEN
        SUM(di.kilos)
    ELSE
        SUM(di.kilos) - (SUM(di.cajas) * 2 + SUM(di.canastilla_base) * 1.8)
    END AS kilosNetos
FROM desprese_items di
INNER JOIN plantilla p ON p.item = di.item
WHERE di.guia = '$guia'
GROUP BY di.item
ORDER BY p.descripcion ASC";

$rs_operationDesprese = mysqli_query($link, $sqlDesprese);

$itemsDesprese = [];

while ($row = mysqli_fetch_assoc($rs_operationDesprese)) {
    $itemsDesprese[$row["item"]] = $row;
}

// Datos Despacho
    $sqlIdDespacho = "SELECT id_guia FROM guiaspollo WHERE guiaDesprese = '$guia'";
    $rs_operationIdDespacho = mysqli_query($link, $sqlIdDespacho);

    $totalKilosDespacho = 0;

    if (mysqli_num_rows($rs_operationIdDespacho) > 0) {
        $rowIdDespacho = mysqli_fetch_assoc($rs_operationIdDespacho);
        $idDespacho = $rowIdDespacho["id_guia"];

        $sqlItemsDespacho = "SELECT 
            item,
            REPLACE(REPLACE(peso_real, '.', ''), ',', '.') AS pesoReal 
        FROM item_proveedorpollo 
        WHERE proveedor = '$idDespacho'";

        $rs_operationItemsDespacho = mysqli_query($link, $sqlItemsDespacho);

        while ($rowDespacho = mysqli_fetch_assoc($rs_operationItemsDespacho)) {
            if (isset($itemsMejora[$rowDespacho["item"]])) {
                $itemsMejora[$rowDespacho["item"]]["kilosNetosDespacho"] = $rowDespacho["pesoReal"];
                $totalKilosDespacho += $rowDespacho["pesoReal"];
            }
        }
    }
//

$sqlTipo = "SELECT lgd.tipo AS tipo FROM lotes_guias_desprese lgd INNER JOIN pesos_pollo pp ON lgd.guia = pp.guia AND lgd.lote = pp.lote WHERE lgd.guia = '$guia' GROUP BY lgd.lote ORDER BY pp.kilos DESC LIMIT 1";
$rs_operationTipo = mysqli_query($link, $sqlTipo);
$rowTipo = mysqli_fetch_assoc($rs_operationTipo);

if ($rowTipo == null) {
    echo json_encode(array(
        "recordsTotal"      => intval(0),         
        "recordsFiltered"   => intval(0),       
        "data"              => 0                     
    ));
    exit;
}

$tipo = $rowTipo["tipo"];

if ($tipo == "CAMPO") {
    $item = "059755";
} else {
    $item = "059759";
}

$sqlEntero = "SELECT 
    SUM(pp.kilos) - (SUM(pp.cajas) * 2 + SUM(pp.cajas_base) * 1.8) AS kilosNetos 
FROM pesos_pollo pp
INNER JOIN plantilla p ON p.item = '$item'
WHERE pp.guia = '$guia' AND pp.tipo_pollo = 'ENTERO'";
$rs_operationEntero = mysqli_query($link, $sqlEntero);

if (mysqli_num_rows($rs_operationEntero) > 0) {
    $rowEntero = mysqli_fetch_assoc($rs_operationEntero);

    if ($tipo == "CAMPO") {
        $itemsDesprese["059755"] = ["item" => "059755", "kilosNetos" => $rowEntero["kilosNetos"]];
    } else {
        $itemsDesprese["059759"] = ["item" => "059759", "kilosNetos" => $rowEntero["kilosNetos"]];
    }
}

$sqlHeader = "SELECT * FROM datos_cf WHERE guia = '$guia'";
$rs_operationHeader = mysqli_query($link, $sqlHeader);
$rowHeader = mysqli_fetch_assoc($rs_operationHeader);

$sqlDL = "SELECT * FROM datosliquidacion";
$rs_operationDL = mysqli_query($link, $sqlDL);
$rowDL = mysqli_fetch_assoc($rs_operationDL);

if ($rowDL == null) {
    $rowDL["costoPromedioSalmuera"] = 0;
    $rowDL["porcentajeSalmuera"] = 0;
}

if ($rowHeader == null) {
    $rowHeader["costo_factura"] = 0;
    $rowHeader["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"];
    $rowHeader["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"];
} else {
    $rowHeader["costo_factura"] == 0 ? $rowHeader["costo_factura"] = 0 : $rowHeader["costo_factura"];
    $rowHeader["costo_promedio_salmuera"] == 0 ? $rowHeader["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"] : $rowHeader["costo_promedio_salmuera"];
    $rowHeader["porcentaje_salmuera"] == 0 ? $rowHeader["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"] : $rowHeader["porcentaje_salmuera"];
}

$data = [];

$totalKilosMejora = 0;
$totalKilosSalmuera = 0;
$totalCostoSalmuera = 0;

$totalKilosRendimiento = 0;

foreach ($itemsMejora as $item) {
    $kilosSalmuera = ($item["kilosNetos"] - $itemsDesprese[$item["item"]]["kilosNetos"]) * ($rowHeader["porcentaje_salmuera"] / 100);

    $totalKilosMejora += $item["kilosNetos"];
    $totalKilosSalmuera += $kilosSalmuera;
    $totalCostoSalmuera += $kilosSalmuera * $rowHeader["costo_promedio_salmuera"];

    $kilosNetosDespacho = isset($item["kilosNetosDespacho"]) ? $item["kilosNetosDespacho"] : $itemsDesprese[$item["item"]]["kilosNetos"];
    $kilosRendimiento = $kilosNetosDespacho - $itemsDesprese[$item["item"]]["kilosNetos"];
    $totalKilosRendimiento += $kilosRendimiento;

    $subData = [];

    $subData[] = number_format($item["kilosNetos"], 2, ".", ",");
    $subData[] = number_format($kilosRendimiento, 2, ".", ",");
    $subData[] = number_format($kilosSalmuera, 3, ".", ",");
    $subData[] = number_format($kilosSalmuera * $rowHeader["costo_promedio_salmuera"], 2, ".", ",");

    $data[] = $subData;
}

$subData = [];

$subData[] = number_format($totalKilosMejora, 2, ".", ",");
$subData[] = number_format($totalKilosRendimiento, 2, ".", ",");
$subData[] = number_format($totalKilosSalmuera, 2, ".", ",");
$subData[] = number_format($totalCostoSalmuera, 2, ".", ",");

$data[] = $subData;

$json_data = [
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
];

echo json_encode($json_data);