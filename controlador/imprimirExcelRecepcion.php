<?php
require_once ('../PHPExcel/Classes/PHPExcel.php');
set_time_limit(0);
date_default_timezone_set("America/Bogota");

ini_set('display_errors', '0');
error_reporting(0);

$datosExcel = isset($_POST['datosExcel']) ? $_POST['datosExcel'] : "";

if ($datosExcel == "") {
    header("Location: ../recepcion.php");
    exit;
}

$datosExcel = json_decode($datosExcel, true);

$proveedor = $datosExcel["proveedor"];
$fechaInicio = $datosExcel["fechaInicio"];
$fechaFin = $datosExcel["fechaFin"];

require_once('../config.php');

// Obtener Guias de Recepcion
    $sqlRecepcion = "SELECT r.id_recepcion AS id_recepcion, r.fecharec AS fecharec, d.empresa AS empresa, d.sede AS sede FROM recepcion r INNER JOIN destinos d ON r.destino = d.id WHERE r.proveedor_cerdo = '$proveedor' AND r.fecharec BETWEEN '$fechaInicio' AND '$fechaFin' ORDER BY r.fecharec ASC, r.id_recepcion ASC";
    $rsRecepcion = mysqli_query($link, $sqlRecepcion);
    
    $guias = [];

    $datosGuia = [];

    while ($rowRecepcion = mysqli_fetch_assoc($rsRecepcion)) {
        $guias[] = $rowRecepcion["id_recepcion"];
        $datosGuia[$rowRecepcion["fecharec"]][] = $rowRecepcion;
    }

    $guias = implode(", ", $guias);
//

// Obtener datos de la guía
    $sqlGuia = "SELECT id_recepcion, turno, estomago1 FROM recepcion_pesos WHERE id_recepcion IN ($guias)";
    $rsGuia = mysqli_query($link, $sqlGuia);

    $rowsGuia = [];

    while ($rowGuia = mysqli_fetch_assoc($rsGuia)) {
        $rowsGuia[$rowGuia["id_recepcion"]][] = $rowGuia;
    }
//

/* print_r($datosGuia);
echo "\n";

print_r($rowsGuia);
echo "\n"; */

// Obtener nombre del proveedor
    $sqlProveedor = "SELECT sede FROM proveedor_cerdo WHERE id = '$proveedor'";
    $rsProveedor = mysqli_query($link, $sqlProveedor);
    $rowProveedor = mysqli_fetch_assoc($rsProveedor);
    $nombreProveedor = $rowProveedor["sede"];
//
//

$objPHPExcel = new PHPExcel();

// Propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("Cattivo")
    ->setLastModifiedBy("Cattivo")
    ->setTitle("Pesos Recepcion")
    ->setSubject("Pesos Recepcion")
    ->setDescription("Pesos Recepcion")
    ->setKeywords("Excel Office 2007 openxml php pesos recepcion")
    ->setCategory("Pesos");

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
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'PESOS RECEPCIÓN');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Proveedor: ' . $nombreProveedor);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:D3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Fecha Inicio: ' . $fechaInicio);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:D4');
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Fecha Fin: ' . $fechaFin);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

/* print_r($rowsGuia); */

$fila = 6;

/* foreach ($rowsGuia as $guias) {
    foreach ($guias as $pesos) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A" . $fila, $pesos["id_recepcion"])
            ->setCellValue("B" . $fila, isset($pesos["turno"]) ? $pesos["turno"] : '')
            ->setCellValue("C" . $fila, isset($pesos["estomago1"]) ? $pesos["estomago1"] : '');

        $fila++;
    }
} */

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A". $fila, "Sede")
->setCellValue("B". $fila, "Guia")
->setCellValue("C". $fila, "Turno")
->setCellValue("D". $fila, "Peso");

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->getStartColor()->setRGB("D9D9D9");
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->applyFromArray($styleArray);
$fila++;

foreach ($datosGuia as $fecha => $guias) {
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'. $fila .':D'. $fila);
    $objPHPExcel->getActiveSheet()->setCellValue('A'. $fila, $fecha);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->getStartColor()->setRGB("D9D9D9");
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->applyFromArray($styleArray);
    $fila++;

    // Arrays para acumular totales
    $totalesPorSede = [];
    $totalesPorEmpresa = [];
    $totalGeneralFecha = 0;
    $sedeActual = '';
    $totalSedeActual = 0;

    foreach ($guias as $datos) {
        if (isset($rowsGuia[$datos["id_recepcion"]])) {
            // Si cambiamos de sede, mostrar subtotal de la sede anterior
            if ($sedeActual !== '' && $sedeActual !== $datos["sede"]) {
                // Mostrar subtotal de la sede anterior
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A" . $fila, "Subtotal " . $sedeActual)
                    ->setCellValue("B" . $fila, "")
                    ->setCellValue("C" . $fila, "")
                    ->setCellValue("D" . $fila, $totalSedeActual);
                
                // Aplicar formato en negrilla
                $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->getStartColor()->setRGB("F2F2F2");
                
                $fila++;
                
                // Reiniciar total de sede actual
                $totalSedeActual = 0;
            }
            
            $sedeActual = $datos["sede"];
            $totalEmpresa = 0;
            
            foreach ($rowsGuia[$datos["id_recepcion"]] as $pesos) {
                $peso = isset($pesos["estomago1"]) ? floatval($pesos["estomago1"]) : 0;
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A" . $fila, $datos["empresa"] ." - ". $datos["sede"])
                    ->setCellValue("B" . $fila, $datos["id_recepcion"])
                    ->setCellValue("C" . $fila, isset($pesos["turno"]) ? $pesos["turno"] : '')
                    ->setCellValue("D" . $fila, $peso);

                $fila++;
                
                // Acumular totales
                $totalSedeActual += $peso;
                $totalEmpresa += $peso;
                $totalGeneralFecha += $peso;
            }
            
            // Guardar totales por sede
            if (!isset($totalesPorSede[$datos["sede"]])) {
                $totalesPorSede[$datos["sede"]] = 0;
            }
            $totalesPorSede[$datos["sede"]] += $totalSedeActual;
            
            // Guardar totales por empresa
            if (!isset($totalesPorEmpresa[$datos["empresa"]])) {
                $totalesPorEmpresa[$datos["empresa"]] = 0;
            }
            $totalesPorEmpresa[$datos["empresa"]] += $totalEmpresa;
        }
    }

    // Mostrar subtotal de la última sede procesada
    if ($sedeActual !== '') {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A" . $fila, "Subtotal " . $sedeActual)
            ->setCellValue("B" . $fila, "")
            ->setCellValue("C" . $fila, "")
            ->setCellValue("D" . $fila, $totalSedeActual);
        
        // Aplicar formato en negrilla
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->getStartColor()->setRGB("F2F2F2");
        
        $fila++;
    }

    // Mostrar totales por empresa (en negrilla)
    foreach ($totalesPorEmpresa as $empresa => $totalEmpresa) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A" . $fila, "Total " . $empresa)
            ->setCellValue("B" . $fila, "")
            ->setCellValue("C" . $fila, "")
            ->setCellValue("D" . $fila, $totalEmpresa);
        
        // Aplicar formato en negrilla
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fila .':D'. $fila)->getFill()->getStartColor()->setRGB("E6E6E6");
        
        $fila++;
    }

    // Espacio entre fechas
    $fila++;
}

/* // Establecer ancho fijo para cada columna
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
} */

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Pesos Recepcion');

// Establecer la hoja activa
$objPHPExcel->setActiveSheetIndex(0);

// Modificar encabezados HTTP para enviar el archivo de Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="pesosRecepcion_'.$nombreProveedor.'_'.$fechaInicio.'_'.$fechaFin.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
ob_end_clean();
$objWriter->save('php://output');
exit;
?>
