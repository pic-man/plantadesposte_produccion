<?php
require_once ('../PHPExcel/Classes/PHPExcel.php');
set_time_limit(0);
ini_set('display_errors', '0');
error_reporting(0);
date_default_timezone_set("America/Bogota");

$guia = isset($_GET['guia']) ? $_GET['guia'] : "";

if ($guia == "") {
    $datosExcel = isset($_POST["datosPlanoExcel"]) ? $_POST["datosPlanoExcel"] : "";

    if ($datosExcel == "") {
        header("Location: ../desprese.php");
        exit;
    }

    $datosExcel = json_decode($datosExcel, true);
    $guias = [];
    $guiaUnit = "";

    foreach ($datosExcel["planos"] as $plano) {
        $guias[] = $plano["guia"];
        $guiaUnit = $plano["guia"];
    }

    $guia = implode(", ", $guias);
    $guiaTitle = "(" . implode(" - ", $guias) . ")";
} else {
    $guiaUnit = $guia;
    $guiaTitle = $guia;
}

require_once('../config.php');

// Obtener datos de la guía
    $sqlGuia = "SELECT * FROM desprese WHERE id IN ($guia)";
    $rsGuia = mysqli_query($link, $sqlGuia);
    $rowGuia = mysqli_fetch_assoc($rsGuia);
//

// Obtener datos de la sede
    $sqlSede = "SELECT empresa, sede FROM destinos WHERE id = '$rowGuia[destino]'";
    $rsSede = mysqli_query($link, $sqlSede);
    $rowSede = mysqli_fetch_assoc($rsSede);
//

// Obtener el Tipo de guia
    $sqlTipo = "SELECT lgd.tipo AS tipo FROM lotes_guias_desprese lgd INNER JOIN pesos_pollo pp ON lgd.guia = pp.guia AND lgd.lote = pp.lote WHERE lgd.guia IN ($guia) GROUP BY lgd.lote ORDER BY pp.kilos DESC LIMIT 1";
    $rs_operationTipo = mysqli_query($link, $sqlTipo);
    $rowTipo = mysqli_fetch_assoc($rs_operationTipo);

    $tipo = $rowTipo["tipo"];
//

// Obtener item y descripcion del pollo general
    if ($tipo == "CAMPO") {
        $itemPollo = "059763";
        $itemNamePollo = "POLLO CAMPO MERCAMIO NATURAL";
    } else {
        $itemPollo = "";
        $itemNamePollo = "POLLO CAMPO MERCAMIO NATURAL";
    }
//

// Obtener items de la guia y total de kilos netos
    $sqlItems = "SELECT 
    di.item AS item,
    p.descripcion AS descripcion,
    CASE WHEN di.status = 1 THEN
        SUM(di.kilos)
    ELSE
        SUM(di.kilos) - (SUM(di.cajas) * 2 + SUM(di.canastilla_base) * 1.8)
    END AS kilosNetos
    FROM desprese_items di
    INNER JOIN plantilla p ON p.item = di.item
    WHERE di.guia IN ($guia)
    GROUP BY di.item
    ORDER BY p.descripcion ASC";

    $rs_operationItems = mysqli_query($link, $sqlItems);

    $items = array();

    $totalKilosPart = 0;
    $totalKilosNetos = 0;

    $itemsNro = ["059762", "059760", "059761", "059758", "059756", "059757"];

    $rowsItems = [];

    while ($row = mysqli_fetch_assoc($rs_operationItems)) {
        $items[$row["item"]] = $row;
        $rowsItems[] = $row["item"];

        if (in_array($row["item"], $itemsNro)) {
            $totalKilosPart += $row["kilosNetos"];
        }

        $totalKilosNetos += $row["kilosNetos"];
    }

    if ($tipo == "CAMPO") {
        $itemPolloEntero = "059755";
    } else {
        $itemPolloEntero = "059759";
    }

    $sqlEntero = "SELECT 
        p.descripcion,
        SUM(pp.kilos) - (SUM(pp.cajas) * 2 + SUM(pp.cajas_base) * 1.8) AS kilosNetos 
        FROM pesos_pollo pp
        INNER JOIN plantilla p ON p.item = '$itemPolloEntero'
        WHERE pp.guia IN ($guia) AND pp.tipo_pollo = 'ENTERO'
        GROUP BY pp.tipo_pollo";

    $rs_operationEntero = mysqli_query($link, $sqlEntero);

    $pesosIniciales = 0;

    if (mysqli_num_rows($rs_operationEntero) > 0) {    
        $rowEntero = mysqli_fetch_assoc($rs_operationEntero);

        if ($rowEntero["descripcion"] != null) {
            if ($tipo == "CAMPO") {
                $items["059755"] = ["item" => "059755", "descripcion" => $rowEntero["descripcion"], "kilosNetos" => $rowEntero["kilosNetos"]];
                $rowsItems[] = "059755";
            } else {
                $items["059759"] = ["item" => "059759", "descripcion" => $rowEntero["descripcion"], "kilosNetos" => $rowEntero["kilosNetos"]];
                $rowsItems[] = "059759";
            }
    
            $totalKilosNetos += $rowEntero["kilosNetos"];
            $pesosIniciales += $rowEntero["kilosNetos"];
        }
    }

    $items_str = implode(", ", $rowsItems);
//

// Obtener pesos iniciales
    $sqlPI = "SELECT 
        SUM(pp.kilos) - (SUM(pp.cajas) * 2 + SUM(pp.cajas_base) * 1.8) AS kilosNetos 
    FROM pesos_pollo pp
    WHERE pp.guia IN ($guia) AND pp.tipo_pollo = 'DESPRESE'
    GROUP BY pp.tipo_pollo";

    $rs_operationPI = mysqli_query($link, $sqlPI);

    if (mysqli_num_rows($rs_operationPI) > 0) {    
        $rowPI = mysqli_fetch_assoc($rs_operationPI);

        $pesosIniciales += $rowPI["kilosNetos"];
    }
//

// Obtener datos de la liquidacion
    $sqlDatos = "SELECT 
        SUM(costo_factura) AS costo_factura, 
        costo_promedio_salmuera, 
        porcentaje_salmuera
    FROM datos_cf WHERE guia IN ($guia)";

    $rs_operationDatos = mysqli_query($link, $sqlDatos);
    $datosCF = mysqli_fetch_assoc($rs_operationDatos);

    $sqlDL = "SELECT * FROM datosliquidacion";

    $rs_operationDL = mysqli_query($link, $sqlDL);
    $rowDL = mysqli_fetch_assoc($rs_operationDL);

    if ($rowDL == null) {
        $rowDL["costoPromedioSalmuera"] = 0;
        $rowDL["porcentajeSalmuera"] = 0;
    }

    if ($datosCF == null) {
        $datosCF["costo_factura"] = 0;
        $datosCF["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"];
        $datosCF["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"];
    } else {
        $datosCF["costo_factura"] == 0 ? $datosCF["costo_factura"] = 0 : $datosCF["costo_factura"];
        $datosCF["costo_promedio_salmuera"] == 0 ? $datosCF["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"] : $datosCF["costo_promedio_salmuera"];
        $datosCF["porcentaje_salmuera"] == 0 ? $datosCF["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"] : $datosCF["porcentaje_salmuera"];
    }
//

// Obtener Total de unidades
    $sqlUnd = "SELECT tipo_pollo, SUM(nro_pollo) AS nroPollo FROM pesos_pollo WHERE guia IN ($guia) GROUP BY tipo_pollo";
    $rs_operationUnd = mysqli_query($link, $sqlUnd);

    $totalDesprese = 0;
    $totalEntero = 0;

    while ($row = mysqli_fetch_assoc($rs_operationUnd)) {
        if ($row["tipo_pollo"] == "DESPRESE") {
            $totalDesprese += intval($row["nroPollo"]);
        } else {
            $totalEntero += intval($row["nroPollo"]);
        }
    }

    $totalUnidades = $totalDesprese + $totalEntero;
//

// Obtener precios unitarios y total de precio venta
    $sqlPrecioUnitario = "SELECT item, precio_unitario FROM precios_unitarios_cf WHERE guia = '$guiaUnit' AND item IN ($items_str)";
    $rs_operationPrecioUnitario = mysqli_query($link, $sqlPrecioUnitario);

    $rowsPrecioUnitario = [];

    $rowsTotalPrecioVenta = [];
    $totalPrecioVenta = 0;

    echo "== Precio Unitarios || Precios Ventas ==<br><br>";

    while ($row = mysqli_fetch_assoc($rs_operationPrecioUnitario)) {
        $kilosNetosRound = round($items[$row["item"]]["kilosNetos"], 2);

        $rowsPrecioUnitario[$row["item"]] = $row["precio_unitario"];

        $precioVenta = $row["precio_unitario"] * $kilosNetosRound;

        $rowsTotalPrecioVenta[$row["item"]] = $precioVenta;
        $totalPrecioVenta += $precioVenta;

        echo "Item: " . $row["item"] . " - Precio Unitario: " . $row["precio_unitario"] . " - Precio Venta: " . $precioVenta . " - Kilos Netos: " . $items[$row["item"]]["kilosNetos"] . "<br>";
    }

    echo "<br>== FIN Precio Unitarios || Precios Ventas ==<br><br>";

    $sqlPrecioUnitarioI = "SELECT item, ValorUnitario FROM plantilla WHERE item IN ($items_str)";
    $rs_operationPrecioUnitarioI = mysqli_query($link, $sqlPrecioUnitarioI);

    echo "== (Plantilla) Precio Unitarios || Precios Ventas ==<br><br>";

    while ($rowPUI = mysqli_fetch_assoc($rs_operationPrecioUnitarioI)) {
        if (!isset($rowsPrecioUnitario[$rowPUI["item"]])) {
            $kilosNetosRound = round($items[$rowPUI["item"]]["kilosNetos"], 2);

            $rowsPrecioUnitario[$rowPUI["item"]] = $rowPUI["ValorUnitario"];

            $precioVenta = $rowPUI["ValorUnitario"] * $kilosNetosRound;

            $rowsTotalPrecioVenta[$rowPUI["item"]] = $precioVenta;
            $totalPrecioVenta += $precioVenta;

            echo "Item: " . $rowPUI["item"] . " - Kilos Netos: " . $kilosNetosRound . " - Precio Unitario: " . $rowPUI["ValorUnitario"] . " - Precio Venta: " . $precioVenta . "<br>";
        }
    }

    echo "<br>== FIN (Plantilla) Precio Unitarios || Precios Ventas ==<br><br>";
    
//

// Datos Despacho
    $sqlIdDespacho = "SELECT id_guia, fechaexp FROM guiaspollo WHERE guiaDesprese IN ($guia)";
    $rs_operationIdDespacho = mysqli_query($link, $sqlIdDespacho);

    $totalKilosDespacho = 0;

    if (mysqli_num_rows($rs_operationIdDespacho) > 0) {
        $idDespacho = [];
        $fechaDespacho = "";

        while ($rowDespacho = mysqli_fetch_assoc($rs_operationIdDespacho)) {
            $idDespacho[] = $rowDespacho["id_guia"];
            $fechaDespacho = $rowDespacho["fechaexp"];
        }

        if (count($idDespacho) > 0) {
            $idDespacho = implode(", ", $idDespacho);
        } else {
            $idDespacho = $idDespacho[0];
        }

        $sqlItemsDespacho = "SELECT 
            item,
            SUM(peso_real) AS pesoReal 
        FROM item_proveedorpollo 
        WHERE proveedor IN ($idDespacho)
        GROUP BY item";

        $rs_operationItemsDespacho = mysqli_query($link, $sqlItemsDespacho);

        while ($row = mysqli_fetch_assoc($rs_operationItemsDespacho)) {
            if (isset($items[$row["item"]])) {
                $items[$row["item"]]["kilosNetosDespacho"] = $row["pesoReal"];
                $totalKilosDespacho += $row["pesoReal"];
            }
        }
    } else {
        $fechaDespacho = "";
    }
//

// Variables de Totales
    $totalCostoTotal = 0;
    $totalUnds = 0;
    $totalPorcentaje = 0;

    $totalKilosMejora = 0;
    $totalKilosSalmuera = 0;
    $totalCostoSalmuera = 0;

    $totalKiloRendimiento = 0;
//

// Obtenes items de mejoramiento
    $sqlMejora = "SELECT mi.item, SUM(mi.kilos_mejora) - (SUM(mi.cajas_mejora) * 2 + SUM(mi.cajas_mejora_base) * 1.8) AS kilosNetos, p.descripcion AS descripcionl, mi.lote
    FROM mejora_items mi
    INNER JOIN plantilla p ON p.item = mi.item
    WHERE mi.id_guia IN ($guia)
    GROUP BY mi.item
    ORDER BY p.descripcion ASC";

    $rs_operationMejora = mysqli_query($link, $sqlMejora);

    $itemsMejora = [];

    while ($row = mysqli_fetch_assoc($rs_operationMejora)) {
        $itemsMejora[$row["item"]] = $row;
    }
//

// Variables creadas para el proceso
    /* 
        $tipo // Tipo de guia
        $itemPollo // Item del pollo general
        $itemNamePollo // Descripcion del pollo general
        $items // Array de items de la guia
        $totalKilosPart // Total de kilos (solo desprese) para calcular el % part
        $totalKilosNetos // Total de kilos netos de la guia
        $rowsItems // Array de items de la guia
        $itemPolloEntero // Item del pollo entero
        $items_str // String de items de la guia
        $datosCF // Datos de la liquidacion
        $totalDesprese // Total de unidades de desprese
        $totalEntero // Total de unidades de entero
        $totalUnidades // Total de unidades de la guia

        $rowsPrecioUnitario // Array de precios unitarios
        $rowsTotalPrecioVenta // Array de total de precio venta
        $totalPrecioVenta // Total de precio venta

        $totalKilosDespacho // Total de kilos de despacho
    */
//

$objPHPExcel = new PHPExcel();

// Propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("Cattivo")
    ->setLastModifiedBy("Cattivo")
    ->setTitle("Liquidación Guía Desprese")
    ->setSubject("Liquidación Guía Desprese")
    ->setDescription("Liquidación de Guía de Desprese y Mejoramiento")
    ->setKeywords("Excel Office 2007 openxml php liquidacion desprese")
    ->setCategory("Liquidación");

// Configuración inicial de la hoja de cálculo
$objPHPExcel->setActiveSheetIndex(0);

// Estilos para las celdas
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);

// Información de la guía
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'LIQUIDACIÓN GUÍA DE DESPRESE Y MEJORAMIENTO No. ' . $guiaTitle);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Insertar datos de la guía
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A3", "FECHA DESPRESE")
    ->setCellValue("B3", $rowGuia["fecha_registro"])
    ->setCellValue("C3", "FECHA DESPACHO")
    ->setCellValue("D3", $fechaDespacho)
    ->setCellValue("E3", "SEDE")
    ->setCellValue("F3", $rowSede["empresa"] . " - " . $rowSede["sede"]);

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Encabezados de la tabla de encabezado
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A5', 'Item')
    ->setCellValue('B5', 'Descripción Item')
    ->setCellValue('C5', 'Kilos a Procesar')
    ->setCellValue('D5', 'No. Pollos')
    ->setCellValue('E5', 'Vr. Promedio Kilo a Despostar')
    ->setCellValue('F5', 'Costo Factura Pollo')
    ->setCellValue('G5', 'Costo Promedio Salmuera')
    ->setCellValue('H5', '% Salmuera');

// Estilo para los encabezados
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->getStartColor()->setRGB("0C305E");
$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($styleArray);

// Insertar datos del encabezado
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A6', $itemPollo)
    ->setCellValue('B6', $itemNamePollo)
    ->setCellValue('C6', number_format($totalKilosNetos, 2, ',', ''))
    ->setCellValue('D6', strval($totalUnidades))
    ->setCellValue('E6', number_format($datosCF["costo_factura"] / $totalKilosNetos, 2, ',', ''))
    ->setCellValue('F6', number_format($datosCF["costo_factura"], 2, ',', ''))
    ->setCellValue('G6', number_format($datosCF["costo_promedio_salmuera"], 2, ',', ''))
    ->setCellValue('H6', number_format($datosCF["porcentaje_salmuera"], 2, ',', '') . '%');

// Estilos para el encabezado
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFill()->getStartColor()->setRGB("D9D9D9");
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray($styleArray);

// Encabezados de la tabla de productos con proceso
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A9', 'Código Item')
    ->setCellValue('B9', 'Descripción')
    ->setCellValue('C9', 'Peso en Kilos Piezas Despresadas')
    ->setCellValue('D9', 'Kilos Despacho')
    ->setCellValue('E9', 'No. Piezas Despresadas')
    ->setCellValue('F9', '% part')
    ->setCellValue('G9', 'COSTO X KL')
    ->setCellValue('H9', 'Precio Venta Unitario')
    ->setCellValue('I9', 'Total Precio Venta')
    ->setCellValue('J9', 'Costo Total')
    ->setCellValue('K9', 'Lote');

// Estilo para los encabezados de la tabla de productos con proceso
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->getFill()->getStartColor()->setRGB("690000");
$objPHPExcel->getActiveSheet()->getStyle('A9:O9')->applyFromArray($styleArray);

$fila = 10;

foreach ($items as $item) {
    $precioVentaUnitario = isset($rowsPrecioUnitario[$item["item"]]) ? $rowsPrecioUnitario[$item["item"]] : 0;
    $precioVenta = $precioVentaUnitario * round($item["kilosNetos"], 2);

    $costoTotal = (round($precioVenta, 2) / ($totalPrecioVenta > 0 ? $totalPrecioVenta : 1)) * $datosCF["costo_factura"];
    $totalCostoTotal += $costoTotal;

    $unidades = 0;

    if (in_array($item["item"], $itemsNro)) {
        if ($item["item"] == "059757" || $item["item"] == "059761") {
            $unidades = $totalDesprese * 2;
            $totalUnds += $totalDesprese * 2;
        } else {
            $unidades = $totalDesprese;
            $totalUnds += $totalDesprese;
        }
    } else {
        $unidades = $totalEntero;
        $totalUnds += $totalEntero;
    }

    if (in_array($item["item"], $itemsNro)) {
        $porcentaje = $item["kilosNetos"] / $totalKilosPart * 100;
    } else {
        $porcentaje = 0;
    }
    
    $totalPorcentaje += $porcentaje;

    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $item["item"]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $item["descripcion"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, number_format($item["kilosNetos"], 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, number_format($item["kilosNetosDespacho"], 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, number_format($unidades, 0, ",", "."));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, number_format($porcentaje, 2, ",", "") . "%");
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, number_format($costoTotal / round((isset($item["kilosNetos"]) ? $item["kilosNetos"] : 1), 2), 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, number_format($precioVentaUnitario, 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, number_format($precioVenta, 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, number_format($costoTotal, 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, $itemsMejora[$item["item"]]["lote"]);

    $fila++;
}

// Estilos para la tabla de productos con proceso
$objPHPExcel->getActiveSheet()->getStyle('A10:O' . ($fila - 1))->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('C10:J' . ($fila - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('L10:O' . ($fila - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Encabezados de la tabla de productos con proceso
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('L9', "Kilos Mejorados")
    ->setCellValue('M9', "Kilos Rendimiento Proceso")
    ->setCellValue('N9', "Kilos Salmuera")
    ->setCellValue('O9', "Costo Kilos Salmuera");

// Estilo para los encabezados de la tabla de productos con proceso

$fila = 10;

foreach ($itemsMejora as $itemMejora) {
    $kilosSalmuera = (round($itemMejora["kilosNetos"], 2) - round($items[$itemMejora["item"]]["kilosNetos"], 2)) * ($datosCF["porcentaje_salmuera"] / 100);

    $totalKilosMejora += round($itemMejora["kilosNetos"], 2);
    $totalKilosSalmuera += $kilosSalmuera;
    $totalCostoSalmuera += $kilosSalmuera * $datosCF["costo_promedio_salmuera"];

    $kiloRendimiento = $items[$itemMejora["item"]]["kilosNetosDespacho"] - round($items[$itemMejora["item"]]["kilosNetos"], 2);
    $totalKiloRendimiento += $kiloRendimiento;

    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, number_format($itemMejora["kilosNetos"], 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, number_format($kiloRendimiento, 2, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, number_format($kilosSalmuera, 3, ",", ""));
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, number_format($kilosSalmuera * $datosCF["costo_promedio_salmuera"], 2, ",", ""));

    $fila++;
}

$fila++;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A". $fila .":B". $fila);
$objPHPExcel->getActiveSheet()->setCellValue("A". $fila, "Subtotal Pollo con Proceso");
$objPHPExcel->getActiveSheet()->getStyle("A". $fila)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, number_format($totalKilosNetos, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, number_format($totalKilosDespacho, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, number_format($totalUnds, 0, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, number_format($totalPorcentaje, 2, ",", "") . "%");
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, number_format($totalPrecioVenta, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, number_format($totalCostoTotal, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, "");

$objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, number_format($totalKilosMejora, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, number_format($totalKiloRendimiento, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, number_format($totalKilosSalmuera, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, number_format($totalCostoSalmuera, 2, ",", ""));

// Estilos para el subtotal de pollo con proceso
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B' . $fila . ':O' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFill()->getStartColor()->setRGB("690000");

$fila += 2;
$primeraFilaSP = $fila;

// Insertar datos de items sin proceso
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, '043874');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, 'COSTILLAR');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, '0,00%');
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, '');

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, '043874');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, 'COSTILLAR ALA MP');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, '0,00%');
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, '');

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, '045588');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, 'PUNTA DE ALA');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, '0,00%');
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, '0');
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, '');

// Estilos para la tabla de productos sin proceso
$objPHPExcel->getActiveSheet()->getStyle('A' . $primeraFilaSP . ':K' . $fila)->applyFromArray($styleArray);

$fila += 2;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A". $fila .":B". $fila);
$objPHPExcel->getActiveSheet()->setCellValue("A". $fila, "Subtotal Pollo sin Proceso");
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, "0");
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, "0");
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, "0");
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, "0,00%");
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, "0");
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, "0");
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, "");

// Estilos para el subtotal de pollo sin proceso
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':K' . $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':K' . $fila)->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':K' . $fila)->getFill()->getStartColor()->setRGB("690000");

$fila += 2;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A". $fila .":B". $fila);
$objPHPExcel->getActiveSheet()->setCellValue("A". $fila, "GRAN TOTAL DESPRESADO");
$objPHPExcel->getActiveSheet()->getStyle("A". $fila)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, number_format($totalKilosNetos, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, number_format($totalKilosDespacho, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, number_format($totalUnds, 0, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, number_format($totalPorcentaje, 2, ",", "") . "%");
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, "");
$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, number_format($totalPrecioVenta, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, number_format($totalCostoTotal, 2, ",", ""));
$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, "");

// Estilos para el el gran total
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B' . $fila . ':O' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':O' . $fila)->getFill()->getStartColor()->setRGB("690000");

$fila += 3;

// Título de la tabla de totales
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':B' . $fila);
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'RESUMEN TOTALES');
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Encabezados de la tabla de totales
$fila++;
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A' . $fila, 'Concepto')
    ->setCellValue('B' . $fila, 'Valor');

// Estilo para los encabezados
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getFont()->setColor(new PHPExcel_Style_Color("FFFFFF"));
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':B' . $fila)->getFill()->getStartColor()->setRGB("103E7D");

// Insertar datos de totales
$fila++;
$primeraFilaTotales = $fila;

$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'Kilos Despresados');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, number_format($totalKilosNetos, 2, ',', ''));

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'Recibidos');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, number_format($pesosIniciales, 2, ',', ''));

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'MERMA KILOS');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, number_format($totalKilosNetos - $totalKilosNetos, 2, ',', ''));

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'TOTAL KILOS DESPRESADOS');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, number_format(($totalKilosNetos - $pesosIniciales) + $totalKilosNetos, 2, ',', ''));

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, '% MERMA');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, '0,00%');

$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, 'MARGEN %');
$margenPorcentaje = $totalPrecioVenta != 0 ? (($totalPrecioVenta - $totalCostoTotal) / $totalPrecioVenta * 100) : 0;
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, number_format($margenPorcentaje, 2, ',', '') . '%');

// Estilos para la tabla de totales
$objPHPExcel->getActiveSheet()->getStyle('A' . ($primeraFilaTotales - 1) . ':B' . $fila)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B' . $primeraFilaTotales . ':B' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A' . $primeraFilaTotales . ':B' . $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A' . $primeraFilaTotales . ':B' . $fila)->getAlignment()->setWrapText(true);

// Aplicar bordes a todas las celdas activas
$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

/* // Aplicar bordes solo a las celdas con contenido
for ($row = 1; $row <= $highestRow; $row++) {
    for ($col = 0; $col < $highestColumnIndex; $col++) {
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($col);
        $cellValue = $objPHPExcel->getActiveSheet()->getCell($columnLetter . $row)->getCalculatedValue();
        
        // Solo aplicar bordes si la celda tiene contenido (incluyendo valores 0 y cadenas vacías pero no null)
        if ($cellValue !== null && $cellValue !== '') {
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle($columnLetter . $row)->applyFromArray($styleArray);
        }
    }
} */

// Establecer ancho fijo para cada columna
$columnWidths = array(
    'A' => 21,  
    'B' => 38,  
    'C' => 12,  
    'D' => 11,  
    'E' => 14, 
    'F' => 15,  
    'G' => 13,  
    'H' => 13,  
    'I' => 16,  
    'J' => 16,  
    'K' => 16,
    'L' => 13,
    'M' => 13,
    'N' => 13,
    'O' => 13
);

for ($col = 0; $col < $highestColumnIndex; $col++) {
    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($col);
    
    // Establecer ancho específico si está definido, sino usar ancho por defecto
    if (isset($columnWidths[$columnLetter])) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnLetter)->setWidth($columnWidths[$columnLetter]);
    } else {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnLetter)->setWidth(15);
    }
}

// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Liquidación Desprese');

// Establecer la hoja activa
$objPHPExcel->setActiveSheetIndex(0);

// Modificar encabezados HTTP para enviar el archivo de Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="liquidacionDesprese_'.$guia.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
ob_end_clean();
$objWriter->save('php://output');
exit;
?>
