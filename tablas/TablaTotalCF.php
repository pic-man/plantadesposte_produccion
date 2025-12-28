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

$sql = "SELECT 
    di.item AS item,
    p.descripcion AS descripcion,
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

$rs_operation = mysqli_query($link, $sql);

$items = array();
$totalKilosPart = 0;
$totalKilos = 0;

$itemsNro = ["059762", "059760", "059761", "059758", "059756", "059757"];

$rowsItems = [];

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $items[$row["item"]] = $row;
    $rowsItems[] = $row["item"];

    if (in_array($row["item"], $itemsNro)) {
        $totalKilosPart += $row["kilosNetos"];
    }

    $totalKilos += $row["kilosNetos"];
}

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
    p.descripcion,
    SUM(pp.kilos) - (SUM(pp.cajas) * 2 + SUM(pp.cajas_base) * 1.8) AS kilosNetos 
FROM pesos_pollo pp
INNER JOIN plantilla p ON p.item = '$item'
WHERE pp.guia = '$guia' AND pp.tipo_pollo = 'ENTERO'";
$rs_operationEntero = mysqli_query($link, $sqlEntero);

if (mysqli_num_rows($rs_operationEntero) > 0) {
    $rowEntero = mysqli_fetch_assoc($rs_operationEntero);

    if ($tipo == "CAMPO") {
        $items["059755"] = ["item" => "059755", "descripcion" => $rowEntero["descripcion"], "kilosNetos" => $rowEntero["kilosNetos"]];
        $rowsItems[] = "059755";
    } else {
        $items["059759"] = ["item" => "059759", "descripcion" => $rowEntero["descripcion"], "kilosNetos" => $rowEntero["kilosNetos"]];
        $rowsItems[] = "059759";
    }

    $totalKilos += $rowEntero["kilosNetos"];
}

$items_str = implode(", ", $rowsItems);

$sqlHeader = "SELECT * FROM datos_cf WHERE guia = '$guia'";
$rs_operationHeader = mysqli_query($link, $sqlHeader);
$rowHeader = mysqli_fetch_assoc($rs_operationHeader);

if ($rowHeader == null) {
    $rowHeader["costo_factura"] = 12958517.4;
    $rowHeader["costo_promedio_salmuera"] = 10367.143;
    $rowHeader["porcentaje_salmuera"] = 3.3;
} else {
    $rowHeader["costo_factura"] == 0 ? $rowHeader["costo_factura"] = 12958517.4 : $rowHeader["costo_factura"];
    $rowHeader["costo_promedio_salmuera"] == 0 ? $rowHeader["costo_promedio_salmuera"] = 10367.143 : $rowHeader["costo_promedio_salmuera"];
    $rowHeader["porcentaje_salmuera"] == 0 ? $rowHeader["porcentaje_salmuera"] = 3.3 : $rowHeader["porcentaje_salmuera"];
}

$sqlPrecioUnitario = "SELECT item, precio_unitario FROM precios_unitarios_cf WHERE guia = '$guia' AND item IN ($items_str)";
$rs_operationPrecioUnitario = mysqli_query($link, $sqlPrecioUnitario);

$rowsPrecioUnitario = [];

$rowsTotalPrecioVenta = [];
$totalPrecioVenta = 0;

while ($row = mysqli_fetch_assoc($rs_operationPrecioUnitario)) {
    $rowsPrecioUnitario[$row["item"]] = $row["precio_unitario"];

    $precioVenta = $row["precio_unitario"] * $items[$row["item"]]["kilosNetos"];

    $rowsTotalPrecioVenta[$row["item"]] = $precioVenta;
    $totalPrecioVenta += $precioVenta;
}

$sqlPrecioUnitarioI = "SELECT item, ValorUnitario FROM plantilla WHERE item IN ($items_str)";
$rs_operationPrecioUnitarioI = mysqli_query($link, $sqlPrecioUnitarioI);

while ($rowPUI = mysqli_fetch_assoc($rs_operationPrecioUnitarioI)) {
    if (!isset($rowsPrecioUnitario[$rowPUI["item"]])) {
        $rowsPrecioUnitario[$rowPUI["item"]] = $rowPUI["ValorUnitario"];

        $precioVenta = $rowPUI["ValorUnitario"] * $items[$rowPUI["item"]]["kilosNetos"];

        $rowsTotalPrecioVenta[$rowPUI["item"]] = $precioVenta;
        $totalPrecioVenta += $precioVenta;
    }
}

$totalCostoTotal = 0;
$totalUnds = 0;

foreach ($items as $item) {
    $precioVentaUnitario = isset($rowsPrecioUnitario[$item["item"]]) ? $rowsPrecioUnitario[$item["item"]] : 0;
    $precioVenta = $precioVentaUnitario * $item["kilosNetos"];

    $costoTotal = ($precioVenta / ($totalPrecioVenta > 0 ? $totalPrecioVenta : 1)) * $rowHeader["costo_factura"];
    $totalCostoTotal += $costoTotal;
}

$totalPrecioVenta == 0 ? $totalPrecioVenta = 1 : $totalPrecioVenta;

$data = [
    [
        "Kilos Despresados",
        number_format($totalKilos, 2, ".", ",")
    ],
    [
        "Recibidos",
        number_format($totalKilos, 2, ".", ",")
    ],
    [
        "MERMA KILOS",
        number_format(($totalKilos - $totalKilos), 2, ".", ",")
    ],
    [
        "TOTAL KILOS DESPRESADOS",
        number_format(($totalKilos - $totalKilos) + $totalKilos, 2, ".", ",")
    ],
    [
        "% MERMA",
        number_format(($totalKilos - $totalKilos) / $totalKilos, 2, ".", ",") . "%"
    ],
    [
        "MARGEN %",
        number_format(($totalPrecioVenta - $totalCostoTotal) / $totalPrecioVenta * 100, 2, ".", ",") . "%"
    ]
];

$json_data = [
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data,
    "totalPrecioVenta" => number_format($totalPrecioVenta, 2, ".", ","),
    "totalCostoTotal" => number_format($totalCostoTotal, 2, ".", ","),
];

echo json_encode($json_data);