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

// Consulta para obtener datos de mejora
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
    $sqlMejora = "SELECT lote FROM mejora_items WHERE id_guia = '$guia' AND item = '$row[item]'";
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

$sqlTipo = "SELECT lgd.tipo AS tipo FROM lotes_guias_desprese lgd INNER JOIN pesos_pollo pp ON lgd.guia = pp.guia AND lgd.lote = pp.lote WHERE lgd.guia = '$guia' GROUP BY lgd.lote ORDER BY pp.kilos DESC LIMIT 1";
$rs_operationTipo = mysqli_query($link, $sqlTipo);
$rowTipo = mysqli_fetch_assoc($rs_operationTipo);

if ($rowTipo == null) {
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

    if ($rowEntero["descripcion"] != null) {
        if ($tipo == "CAMPO") {
            $itemPollo = "059755";
        } else {
            $itemPollo = "059759";
        }

        $items[$itemPollo] = ["item" => $itemPollo, "descripcion" => $rowEntero["descripcion"], "kilosNetos" => $rowEntero["kilosNetos"]];
        $rowsItems[] = $itemPollo;

        $sqlMejora = "SELECT lote FROM mejora_items WHERE id_guia = '$guia' AND item = '$itemPollo'";
        $rs_operationMejora = mysqli_query($link, $sqlMejora);
        $rowMejora = mysqli_fetch_assoc($rs_operationMejora);

        if ($rowMejora["lote"] == null) {
            $rowMejora["lote"] = "";
        }

        $items[$itemPollo]["lote"] = $rowMejora["lote"];
    
        $totalKilos += $rowEntero["kilosNetos"];
    }
}

$items_str = implode(", ", $rowsItems);

$totalPorcentaje = 0;

foreach ($items as $item) {
    if ($item["item"] == "059755" || $item["item"] == "059759") {
        $items[$item["item"]]["porcentaje"] = "";
        continue;
    }
    $porcentaje = $item["kilosNetos"] / $totalKilosPart * 100;

    $items[$item["item"]]["porcentaje"] = number_format($porcentaje, 2, ".", "");
    $totalPorcentaje += $porcentaje;
}

// Consultas unicas
$sqlUnd = "SELECT tipo_pollo, SUM(nro_pollo) AS nroPollo FROM pesos_pollo WHERE guia = '$guia' GROUP BY tipo_pollo";
$rs_operationUnd = mysqli_query($link, $sqlUnd);

$totalDesprese = 0;
$totalEntero = 0;

while ($row = mysqli_fetch_assoc($rs_operationUnd)) {
    if ($row["tipo_pollo"] == "DESPRESE") {
        $totalDesprese += $row["nroPollo"];
    } else {
        $totalEntero += $row["nroPollo"];
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

// Datos Despacho
    $sqlIdDespacho = "SELECT id_guia FROM guiaspollo WHERE guiaDesprese = '$guia' AND tipoGuia = 'DESPRESE'";
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

$data = [];

$totalCostoTotal = 0;
$totalUnds = 0;
$totalKilosMejora = 0;
$totalKilosSalmuera = 0;
$totalCostoSalmuera = 0;
$totalKilosRendimiento = 0;

foreach ($items as $item) {
    $precioVentaUnitario = isset($rowsPrecioUnitario[$item["item"]]) ? $rowsPrecioUnitario[$item["item"]] : 0;
    $precioVenta = $precioVentaUnitario * $item["kilosNetos"];

    $costoTotal = ($precioVenta / ($totalPrecioVenta > 0 ? $totalPrecioVenta : 1)) * $rowHeader["costo_factura"];
    $totalCostoTotal += $costoTotal;

    $kilosNetosDespacho = isset($item["kilosNetosDespacho"]) ? $item["kilosNetosDespacho"] : 0;

    // Cálculos para las nuevas columnas
    $kilosMejorados = isset($itemsMejora[$item["item"]]) ? $itemsMejora[$item["item"]]["kilosNetos"] : 0;
    $kilosSalmuera = ($kilosMejorados - $item["kilosNetos"]) * ($rowHeader["porcentaje_salmuera"] / 100);
    $kilosRendimiento = $kilosNetosDespacho - $item["kilosNetos"];
    
    $totalKilosMejora += $kilosMejorados;
    $totalKilosSalmuera += $kilosSalmuera;
    $totalCostoSalmuera += $kilosSalmuera * $rowHeader["costo_promedio_salmuera"];
    $totalKilosRendimiento += $kilosRendimiento;

    $porcentajeMejoraDespacho = ($kilosNetosDespacho - $kilosMejorados) / $kilosMejorados * 100;
    if ($porcentajeMejoraDespacho > 2) {
        $porcentajeMejoraDespacho = "<span style='color: red;'>" . number_format($porcentajeMejoraDespacho, 2, ".", "") . "</span>";
    } else {
        $porcentajeMejoraDespacho = number_format($porcentajeMejoraDespacho, 2, ".", "");
    }

    $subData = [];

    $subData[] = $item["item"];
    $subData[] = $item["descripcion"];
    $subData[] = number_format($item["kilosNetos"], 2, ".", "");
    $subData[] = number_format($kilosNetosDespacho, 2, ".", "");

    if (in_array($item["item"], $itemsNro)) {
        if ($item["item"] == "059757" || $item["item"] == "059761") {
            $subData[] = $totalDesprese * 2;
            $totalUnds += $totalDesprese * 2;
        } else {
            $subData[] = $totalDesprese;
            $totalUnds += $totalDesprese;
        }
    } else {
        $subData[] = $totalEntero;
        $totalUnds += $totalEntero;
    }

    $subData[] = $item["porcentaje"];
    $subData[] = number_format($costoTotal / (isset($item["kilosNetos"]) ? $item["kilosNetos"] : 1), 2, ".", ",");
    $subData[] = "<a onclick='InsertVentaCF(\"".$item["item"]."\")' style='cursor: pointer;'>". number_format($precioVentaUnitario, 2, ".", ",") ."<i class='bi bi-pencil ms-1'></i></a>";
    $subData[] = number_format($precioVenta, 2, ".", ",");
    $subData[] = number_format($costoTotal, 2, ".", ",");
    $subData[] = $item["lote"];
    
    // Nuevas columnas de TablaConPEK_CF
    $subData[] = number_format($kilosMejorados, 2, ".", ",");
    $subData[] = number_format($kilosRendimiento, 2, ".", ",");
    $subData[] = number_format($kilosSalmuera, 3, ".", ",");
    $subData[] = number_format($kilosSalmuera * $rowHeader["costo_promedio_salmuera"], 2, ".", ",");

    $subData[] = number_format((($kilosMejorados / $item["kilosNetos"]) - 1) * 100, 2, ".", "") . "%";
    $subData[] = $porcentajeMejoraDespacho . "%";

    $data[] = $subData;
}

$subData = [];

$subData[] = "";
$subData[] = "Subtotal Pollo con Proceso";
$subData[] = number_format($totalKilos, 2, ".", ",");
$subData[] = number_format($totalKilosDespacho, 2, ".", ",");
$subData[] = number_format($totalUnds, 0, ".", ",");
$subData[] = number_format($totalPorcentaje, 2, ".", "") . "%";
$subData[] = "";
$subData[] = "";
$subData[] = number_format($totalPrecioVenta, 2, ".", ",");
$subData[] = number_format($totalCostoTotal, 2, ".", ",");
$subData[] = "";
$subData[] = number_format($totalKilosMejora, 2, ".", ",");
$subData[] = number_format($totalKilosRendimiento, 2, ".", ",");
$subData[] = number_format($totalKilosSalmuera, 2, ".", ",");
$subData[] = number_format($totalCostoSalmuera, 2, ".", ",");
$subData[] = "";
$subData[] = "";

$data[] = $subData;

$TotalData = count($data);

$json_data = [
    "recordsTotal" => $TotalData,
    "recordsFiltered" => $TotalData,
    "data" => $data,
    "totalKilosDespresados" => number_format($totalKilos, 2, ".", ","),
    "totalUnidadesDespresadas" => number_format($totalUnds, 0, ".", ","),
    "totalPorcentajePart" => number_format($totalPorcentaje, 2, ".", ""),
    "totalPrecioVenta" => number_format($totalPrecioVenta, 2, ".", ","),
    "totalCostoTotal" => number_format($totalCostoTotal, 2, ".", ",")
];

echo json_encode($json_data);


