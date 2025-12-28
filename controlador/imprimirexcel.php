<?php

require_once ('../PHPExcel/Classes/PHPExcel.php');
set_time_limit(0);

ini_set('display_errors', '0');
error_reporting(0);

$id = $_REQUEST['id'];

$objPHPExcel = new PHPExcel();

// Propiedades del documento
$objPHPExcel->getProperties()
    ->setCreator("Cattivo")
    ->setLastModifiedBy("Cattivo")
    ->setTitle("")
    ->setSubject("")
    ->setDescription("")
    ->setKeywords("Excel Office 2007 openxml php")
    ->setCategory("");

// Configuración inicial de la hoja de cálculo
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('L11', 'MUNICIPIO')
    ->setCellValue('L16', 'MUNICIPIO')
    ->setCellValue('A19', '#')
    ->setCellValue('B19', 'ITEM')
    ->setCellValue('C19', 'PRODUCTO')
    ->setCellValue('D19', 'LOTE')
    ->setCellValue('E19', 'TEMPERATURA')
    ->setCellValue('F19', 'UNDS')
    ->setCellValue('G19', 'CAJAS')
    ->setCellValue('H19', 'PESO');

// Estilos y formato
$from = "A5";
$to = "W5";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);    
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G3');

// Insertar imagen
$ruta ="../images/logo-dual.jpg"; 
$gdImage = imagecreatefromjpeg($ruta);
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setCoordinates('A1');
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(81); // 10% más pequeña
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Insertar y dar formato al texto principal
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:T1');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'GUÍA DE TRANSPORTE Y DESTINO DE CARNE Y PRODUCTOS CÁRNICOS COMESTIBLES');
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(14); // Tamaño del texto
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Formatear los títulos de las columnas
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:F5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G5:N5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O5:S5');
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'FECHA DE EXPEDICION');
$objPHPExcel->getActiveSheet()->setCellValue('G5', 'CODIGO AUTORIZACION PLANTA');
$objPHPExcel->getActiveSheet()->setCellValue('O5', 'CONSECUTIVO GUIA');

// Estilo para los títulos de las columnas
$from = "A5";
$to = "S5";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

// Formatear y centrar los títulos adicionales
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:S8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A13:S13');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A18:S18');
$objPHPExcel->getActiveSheet()->setCellValue('A8', 'IDENTIFICACION DEL ESTABLECIMIENTO DE PROCEDENCIA');
$objPHPExcel->getActiveSheet()->setCellValue('A13', 'IDENTIFICACION DEL ESTABLECIMIENTO DE DESTINO');
$objPHPExcel->getActiveSheet()->setCellValue('A18', 'DESCRIPCION DE LOS PRODUCTOS TRANSPORTADOS');

$from = "A8";
$to = "S8";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A13";
$to = "S13";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A18";
$to = "S18";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

// Formatear y centrar los títulos para Razón Social, Dirección y Departamento
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A9:F9');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A14:F14');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A10:F10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A15:F15');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:F11');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A16:F16');
$objPHPExcel->getActiveSheet()->setCellValue('A9', 'RAZON SOCIAL');
$objPHPExcel->getActiveSheet()->setCellValue('A14', 'RAZON SOCIAL');
$objPHPExcel->getActiveSheet()->setCellValue('A10', 'DIRECCION');
$objPHPExcel->getActiveSheet()->setCellValue('A15', 'DIRECCION');
$objPHPExcel->getActiveSheet()->setCellValue('A11', 'DEPARTAMENTO');
$objPHPExcel->getActiveSheet()->setCellValue('A16', 'DEPARTAMENTO');


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C19:H19');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I19:K19');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q19:S19');

$objPHPExcel->getActiveSheet()->setCellValue('A19', '#');
$objPHPExcel->getActiveSheet()->setCellValue('B19', 'Item');
$objPHPExcel->getActiveSheet()->setCellValue('C19', 'Producto');
$objPHPExcel->getActiveSheet()->setCellValue('I19', 'Lote');
$objPHPExcel->getActiveSheet()->setCellValue('M19', 'Temperatura');
$objPHPExcel->getActiveSheet()->setCellValue('O19', 'Unds');
$objPHPExcel->getActiveSheet()->setCellValue('P19', 'Cajas');
$objPHPExcel->getActiveSheet()->setCellValue('Q19', 'Peso');

$from = "A9";
$to = "F9";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A14";
$to = "F14";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A10";
$to = "F10";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A15";
$to = "F15";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A11";
$to = "F11";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A16";
$to = "F16";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

$from = "A19";
$to = "S19";
$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'e7e6e6')
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 11,
            'name'  => 'Calibri'
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'), // Borde negro
            )
        )
    )
);

include('../config.php');
$sqlg = "SELECT codigog, codigoa, fechaexp, consecutivog, procedencia, destino, conductor, placa, observaciones, tipo  
        FROM guias 
        WHERE id_guia = " . $id;

$rs_operaciong = mysqli_query($link, $sqlg);

if ($rowg = mysqli_fetch_assoc($rs_operaciong)) {
  if($rowg['procedencia']=="Frigovalle SAS"){
    $direccion = "KM 2 via Buga Palmira vda Zanjon Hondo";
    $municipio = "Buga";
  }elseif($rowg['procedencia']=="Cooperativa de Trabajo Asociado Progresar"){
    $direccion = "CR 16 Callejon de Balboa";
    $municipio = "Buga";
  }elseif($rowg['procedencia']=="Frigotimana SAS"){
    $direccion = "CR 30 via Morales BRR Morales";
    $municipio = "Tulua";
  }

  if($rowg['destino']=="Mercamio SA - La 5a"){
    $direcciond = "Calle 6 No. 59 A -30";
    $municipiod = "Cali";
  
  }elseif($rowg['destino']=="Cooperativa de Trabajo Asociado Progresar"){
    $direccion = "CR 16 Callejon de Balboa";
    $municipio = "Buga";
  }elseif($rowg['destino']=="Frigotimana SAS"){
    $direccion = "CR 30 via Morales BRR Morales";
    $municipio = "Tulua";
  }

  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G9:S9');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G10:S10');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G11:K11');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N11:P11');

  $objPHPExcel->getActiveSheet()->setCellValue('G9', $rowg['procedencia']);
  $objPHPExcel->getActiveSheet()->setCellValue('G10', $direccion);
  $objPHPExcel->getActiveSheet()->setCellValue('G11', 'Valle');
  $objPHPExcel->getActiveSheet()->setCellValue('N11', $municipio);
  
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G14:S14');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G15:S15');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G16:K16');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N16:P16');
  
  $objPHPExcel->getActiveSheet()->setCellValue('G14', $rowg['destino']);
  $objPHPExcel->getActiveSheet()->setCellValue('G15', $direcciond);
  $objPHPExcel->getActiveSheet()->setCellValue('G16', 'Valle');
  $objPHPExcel->getActiveSheet()->setCellValue('N16', $municipiod);
  
}


include('../config.php');
$sql = "SELECT plantilla.item, descripcion, lote, temperatura, unidades, cajas, peso 
        FROM plantilla, item_proveedor 
        WHERE plantilla.item = item_proveedor.item 
        AND item_proveedor.proveedor = " . $id . "
        ORDER BY descripcion ASC";

$rs_operacion = mysqli_query($link, $sql);

if (!$rs_operacion) {
    die("Error en la consulta: " . mysqli_error($link));
}

// Insertar los datos en la hoja de cálculo
$fila = 20;
while ($row = mysqli_fetch_assoc($rs_operacion)) {

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$fila.':H'.$fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.$fila.':K'.$fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q'.$fila.':S'.$fila);

    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $fila - 19);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $row['item']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $row['descripcion']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, $row['lote']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, $row['temperatura']);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, $row['unidades']);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, $row['cajas']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, $row['peso']);
    $fila++;
}

mysqli_free_result($rs_operacion);
mysqli_close($link);

// Aplicar borde negro a todas las celdas activas
$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

for ($row = 1; $row <= $highestRow; $row++) {
    for ($col = 0; $col < $highestColumnIndex; $col++) {
        $cell = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row);
        $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $cell->getStyle()->getBorders()->getAllBorders()->getColor()->setRGB('000000');
    }
}

// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('Guia');

// Establecer la hoja activa
$objPHPExcel->setActiveSheetIndex(0);

// Modificar encabezados HTTP para enviar el archivo de Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="plantillaCotizacion.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
ob_end_clean();
$objWriter->save('php://output');
exit;
?>
