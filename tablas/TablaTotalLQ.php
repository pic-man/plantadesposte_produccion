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
    SUM(di.kilos) - (SUM(di.cajas) * 2 + SUM(di.canastilla_base) * 1.8) AS kilosNetos
FROM despresado_items di
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

$items_str = implode(", ", $rowsItems);

$sqlHeader = "SELECT * FROM datos_cf_despresado WHERE guia = '$guia'";
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

$sqlPrecioUnitario = "SELECT item, precio_unitario FROM precios_unitarios_cf_despresado WHERE guia = '$guia' AND item IN ($items_str)";
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