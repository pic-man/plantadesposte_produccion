<?php
require_once ('../PHPExcel/Classes/PHPExcel.php');
set_time_limit(0);
ini_set('display_errors', '0');
error_reporting(0);
date_default_timezone_set("America/Bogota");

$semana = isset($_GET['semana']) ? $_GET['semana'] : "";

if ($semana == "") {
    header("Location: ../produccionDia.php");
    exit;
}

$anioActual = date('Y');
$dto = new DateTime();
$dto->setISODate($anioActual, $semana);
$nombresDias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo', "LunesSD");
$fechaLunesSemana = $dto->format('Y-m-d');
$diasConFechas = array();
foreach ($nombresDias as $i => $nombreDia) {
    $fechaDelDia = date('d', strtotime($fechaLunesSemana . " +$i days"));
    $diasConFechas[$nombreDia] = $fechaDelDia;
}

require_once('../config.php');

$sqlSedes = "SELECT cedula FROM responsables WHERE tipo = '3'";
$rs_operation = mysqli_query($link, $sqlSedes);

$dataCanalesSemana = array();

$totalCerdo = 0;
$totalRes = 0;

while ($rowSedes = mysqli_fetch_assoc($rs_operation)) {
    $sede = strtoupper($rowSedes["cedula"]);
    $dataCanalesSemana[$sede]["Nombre"] = $rowSedes["cedula"];

    $sqlCanales = "SELECT 
        SUM(CASE WHEN especie = 'CERDO' THEN cantidad ELSE 0 END) AS totalCerdo,
        SUM(CASE WHEN especie = 'RES' THEN cantidad ELSE 0 END) AS totalRes
    FROM programacioncanales WHERE sede = '$sede' AND semana = '$semana'";
    $queryCanales = mysqli_query($link, $sqlCanales);
    $rowCanales = mysqli_fetch_assoc($queryCanales);

    $dataCanalesSemana[$sede]["TotalCerdo"] = $rowCanales["totalCerdo"] ?? 0;
    $dataCanalesSemana[$sede]["TotalRes"] = $rowCanales["totalRes"] ?? 0;

    $totalCerdo += $rowCanales["totalCerdo"];
    $totalRes += $rowCanales["totalRes"];
}

$diasSemana = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
$totalDias = ["Lunes" => 0, "Martes" => 0, "Miercoles" => 0, "Jueves" => 0, "Viernes" => 0, "Sabado" => 0];

$objPHPExcel = new PHPExcel();
$richText = new PHPExcel_RichText();

// Propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("Planta de Desposte")
    ->setLastModifiedBy("Planta de Desposte")
    ->setTitle("Produccion Dia Canales")
    ->setSubject("Produccion Dia Canales")
    ->setDescription("Produccion Dia Canales")
    ->setKeywords("Excel Office 2007 openxml php produccion dia canales")
    ->setCategory("Produccion Dia Canales");

$objPHPExcel->getActiveSheet()->getStyle('A:O')->getFont()->setName('Arial');

// Configuración inicial de la hoja de cálculo
$objPHPExcel->setActiveSheetIndex(0);

// Estilos para las celdas
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    )
);

// Información de la guía
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');

$richText->createText("PRODUCCION * DIA ");
$redText = $richText->createTextRun("SEMANA ". ($semana + 30) . "-" . $semana);
$redText->getFont()->getColor()->setRGB('FF0000');
$redText->getFont()->setBold(true);
$redText->getFont()->setSize(14);
$redText->getFont()->setName('Arial');

$objPHPExcel->getActiveSheet()->setCellValue('A1', $richText);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:D2');
$objPHPExcel->getActiveSheet()->setCellValue("A2", "TOTAL CANALES SEMANA");
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
/* $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14); */
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:E3');
$objPHPExcel->getActiveSheet()->setCellValue("E2", "Sede / Fecha");
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
/* $objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setSize(14); */
$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E2:E3')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("F2", "Lunes / " . $diasConFechas["Lunes"])
    ->setCellValue("G2", "Martes / " . $diasConFechas["Martes"])
    ->setCellValue("H2", "Miercoles / " . $diasConFechas["Miercoles"])
    ->setCellValue("I2", "Jueves / " . $diasConFechas["Jueves"])
    ->setCellValue("J2", "Viernes / " . $diasConFechas["Viernes"])
    ->setCellValue("K2", "Sabado / " . $diasConFechas["Sabado"]);

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('F2:K2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F2:K2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F2:K2')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F2:K2')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A3", "CERDO")
    ->setCellValue("B3", "PART.")
    ->setCellValue("C3", "RES")
    ->setCellValue("D3", "PART.");

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("F3", "CERDO")
    ->setCellValue("G3", "RES")
    ->setCellValue("H3", "CERDO")
    ->setCellValue("I3", "RES")
    ->setCellValue("J3", "CERDO")
    ->setCellValue("K3", "RES");

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('F3:K3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3:K3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F3:K3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F3:K3')->applyFromArray($styleArray);

$fila = 4;

$letrasDias = ['F', 'G', 'H', 'I', 'J', 'K'];

foreach ($dataCanalesSemana as $canales) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $canales["TotalCerdo"]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, round($canales["TotalCerdo"] / ($totalCerdo == 0 ? 1 : $totalCerdo) * 100, 2) . "%");
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $canales["TotalRes"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, round($canales["TotalRes"] / ($totalRes == 0 ? 1 : $totalRes) * 100, 2) . "%");
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $canales["Nombre"]);

    $sede = strtoupper($canales["Nombre"]);

    for ($i=0; $i < count($diasSemana); $i++) { 
        $diaSemana = $diasSemana[$i];
        $especie = $i % 2 == 0 ? "CERDO" : "RES";

        $sqlProduccionDia = "SELECT 
            id,
            cantidad 
        FROM produccion_dia_canales 
        WHERE 
            sede = '$sede' AND 
            semana = '$semana' AND 
            dia = '$diaSemana' AND 
            especie = '$especie'
        ";

        $rs_operation = mysqli_query($link, $sqlProduccionDia);

        if (mysqli_num_rows($rs_operation) > 0) {
            $rowProduccionDia = mysqli_fetch_assoc($rs_operation);
            $cantidad = $rowProduccionDia["cantidad"];
        } else {
            $cantidad = 0;
        }

        $objPHPExcel->getActiveSheet()->setCellValue("" . $letrasDias[$i] . $fila . "", "" . $cantidad . "");
        $totalDias[$diaSemana] += $cantidad;
    }
    

    $fila++;
}

$objPHPExcel->getActiveSheet()->getStyle('A4:K' . ($fila - 1))->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:K' . ($fila - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A4:K' . ($fila - 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('E4:E' . ($fila - 1))->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E4:E' . ($fila - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('E4:E' . ($fila - 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A". $fila, strval($totalCerdo))
    ->setCellValue("B". $fila, "")
    ->setCellValue("C". $fila, strval($totalRes))
    ->setCellValue("D". $fila, "")
    ->setCellValue("E". $fila, "Ingreso * DIa")
    ->setCellValue("F". $fila, strval($totalDias["Lunes"]))
    ->setCellValue("G". $fila, strval($totalDias["Martes"]))
    ->setCellValue("H". $fila, strval($totalDias["Miercoles"]))
    ->setCellValue("I". $fila, strval($totalDias["Jueves"]))
    ->setCellValue("J". $fila, strval($totalDias["Viernes"]))
    ->setCellValue("K". $fila, strval($totalDias["Sabado"]));

$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getFont()->setColor(new PHPExcel_Style_Color("FF0000"));
$objPHPExcel->getActiveSheet()->getStyle('C'. $fila)->getFont()->setColor(new PHPExcel_Style_Color("FF0000"));
$objPHPExcel->getActiveSheet()->getStyle('F'. $fila .':K'. $fila)->getFont()->setColor(new PHPExcel_Style_Color("FF0000"));

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->applyFromArray($styleArray);

$fila++;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'. $fila . ':D'. $fila);
$objPHPExcel->getActiveSheet()->setCellValue("A". $fila, "SEMANA ". ($semana + 30) . "-" . $semana);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getFont()->setColor(new PHPExcel_Style_Color("FF0000"));
/* $objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getFill()->getStartColor()->setRGB("690000"); */
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':D'. $fila)->applyFromArray($styleArray);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("E". $fila, "Fecha Distribucion")
    ->setCellValue("F". $fila, "Martes / " . $diasConFechas["Martes"])
    ->setCellValue("G". $fila, "Miercoles / " . $diasConFechas["Miercoles"])
    ->setCellValue("H". $fila, "Jueves / " . $diasConFechas["Jueves"])
    ->setCellValue("I". $fila, "Viernes / " . $diasConFechas["Viernes"])
    ->setCellValue("J". $fila, "Sabado / " . $diasConFechas["Sabado"])
    ->setCellValue("K". $fila, "Lunes / " . $diasConFechas["LunesSD"]);

$objPHPExcel->getActiveSheet()->getStyle('E'. $fila)->getFont()->setColor(new PHPExcel_Style_Color("FF0000"));

// Estilos para la información de la guía
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'. $fila . ':K'. $fila)->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getStyle('A3:A'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A3:A'. ($fila - 1))->getFill()->getStartColor()->setRGB("8fd24e");

$objPHPExcel->getActiveSheet()->getStyle('C3:C'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C3:C'. ($fila - 1))->getFill()->getStartColor()->setRGB("fbc108");

$objPHPExcel->getActiveSheet()->getStyle('F2:F'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F2:F'. ($fila - 1))->getFill()->getStartColor()->setRGB("8fd24e");

$objPHPExcel->getActiveSheet()->getStyle('G2:G'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('G2:G'. ($fila - 1))->getFill()->getStartColor()->setRGB("fbc108");

$objPHPExcel->getActiveSheet()->getStyle('H2:H'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('H2:H'. ($fila - 1))->getFill()->getStartColor()->setRGB("8fd24e");

$objPHPExcel->getActiveSheet()->getStyle('I2:I'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('I2:I'. ($fila - 1))->getFill()->getStartColor()->setRGB("fbc108");

$objPHPExcel->getActiveSheet()->getStyle('J2:J'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('J2:J'. ($fila - 1))->getFill()->getStartColor()->setRGB("8fd24e");

$objPHPExcel->getActiveSheet()->getStyle('K2:K'. ($fila - 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('K2:K'. ($fila - 1))->getFill()->getStartColor()->setRGB("fbc108");


// Aplicar bordes a todas las celdas activas
$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);


// Establecer ancho fijo para cada columna
$columnWidths = array(
    'A' => 12,  
    'B' => 12,  
    'C' => 12,  
    'D' => 12,  
    'E' => 22, 
    'F' => 15,  
    'G' => 15,  
    'H' => 15,  
    'I' => 15,  
    'J' => 15,  
    'K' => 15
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
$objPHPExcel->getActiveSheet()->setTitle('Produccion Dia Canales');

// Establecer la hoja activa
$objPHPExcel->setActiveSheetIndex(0);

// Modificar encabezados HTTP para enviar el archivo de Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="produccionDiaCanales_'.$semana.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
ob_end_clean();
$objWriter->save('php://output');
exit;
?>
