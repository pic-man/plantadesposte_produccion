<?php
include "../config.php";

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

if ($guia == "") {
    echo json_encode(array(
        "recordsTotal" => intval(0),
        "recordsFiltered" => intval(0),
        "data" => 0,
        "totalKilosDespresados" => 0,
        "totalUnidadesDespresadas" => 0,
        "totalPorcentajePart" => 0,
        "totalPrecioVenta" => 0,
        "totalCostoTotal" => 0
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
    $sqlMejora = "SELECT lote FROM mejora_items_despresado WHERE id_guia = '$guia' AND item = '$row[item]'";
    $rs_operationMejora = mysqli_query($link, $sqlMejora);

    if (mysqli_num_rows($rs_operationMejora) > 0) {
        $rowMejora = mysqli_fetch_assoc($rs_operationMejora);
    } else {
        $rowMejora["lote"] = "";
    }

    if ($rowMejora["lote"] == null) {
        $rowMejora["lote"] = "";
    }

    $items[$row["item"]] = $row;
    $items[$row["item"]]["lote"] = $rowMejora["lote"];

    $rowsItems[] = $row["item"];

    if (in_array($row["item"], $itemsNro)) {
        $totalKilosPart += $row["kilosNetos"];
    }

    $totalKilos += $row["kilosNetos"];
}

$items_str = implode(", ", $rowsItems);

// Datos Despacho
    $sqlIdDespacho = "SELECT id_guia FROM guiaspollo WHERE guiaDesprese = '$guia' AND tipoGuia = 'DESPRESADO'";
    $rs_operationIdDespacho = mysqli_query($link, $sqlIdDespacho);
    
    $totalKilosDespacho = 0;

    if (mysqli_num_rows($rs_operationIdDespacho) > 0) {
        $rowIdDespacho = mysqli_fetch_assoc($rs_operationIdDespacho);

        $idDespacho = $rowIdDespacho["id_guia"];

        $sqlItemsDespacho = "SELECT 
            item,
            peso_real AS pesoReal 
        FROM item_proveedorpollo 
        WHERE proveedor = '$idDespacho'";

        $rs_operationItemsDespacho = mysqli_query($link, $sqlItemsDespacho);

        while ($row = mysqli_fetch_assoc($rs_operationItemsDespacho)) {
            if (strlen($row["pesoReal"]) <= 6) {
                if (strpos($row["pesoReal"], ',') !== false) {
                    $row["pesoReal"] = str_replace(",", ".", $row["pesoReal"]);
                }
            }
            

            if (isset($items[$row["item"]])) {
                if (isset($items[$row["item"]]["kilosNetosDespacho"])) {
                    $items[$row["item"]]["kilosNetosDespacho"] += $row["pesoReal"];
                } else {
                    $items[$row["item"]]["kilosNetosDespacho"] = $row["pesoReal"];
                }
                
                $totalKilosDespacho += $row["pesoReal"];
            }
        }
    }
//

$totalPorcentajeDesprese = 0;
$totalPorcentajeEntero = 0;
$totalPorcentaje = 0;

foreach ($items as $item) {
    $kilosNetosDespacho = isset($item["kilosNetosDespacho"]) ? $item["kilosNetosDespacho"] : 0;
    $kilosRendimiento = $kilosNetosDespacho - $item["kilosNetos"];
    $Porcentaje = $kilosRendimiento / $item["kilosNetos"];

    if (in_array($item["item"], $itemsNro)) {
        $totalPorcentajeDesprese += $Porcentaje;
    } else {
        $totalPorcentajeEntero += $Porcentaje;
    }

    $totalPorcentaje += $Porcentaje;
}

$data = [
    [
        "Desprese",
        number_format($totalPorcentajeDesprese, 2, ".", "") . "%"
    ],
    [
        "Entero",
        number_format($totalPorcentajeEntero, 2, ".", "") . "%"
    ],
    [
        "Total",
        number_format($totalPorcentaje, 2, ".", "") . "%"
    ]
];

$TotalData = count($data);

$json_data = [
    "recordsTotal" => $TotalData,
    "recordsFiltered" => $TotalData,
    "data" => $data
];

echo json_encode($json_data);


