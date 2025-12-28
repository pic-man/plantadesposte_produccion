<?php
/* ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');*/
error_reporting(0); 

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";
/* $item = isset($_POST["item"]) ? $_POST["item"] : "";
$cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : "";
$lote = isset($_POST["lote"]) ? $_POST["lote"] : ""; */

if ($guia == "" /* || $item == "" || $cantidad == "" || $lote == "" */) {
    header("Location: ../desprese.php");
    exit();
}

require_once ("../config.php");
date_default_timezone_set("America/Bogota");

//$sqlItems = "SELECT p.descripcion AS item, i.lote, i.cantidad FROM impresiones_items i INNER JOIN plantilla p ON i.item = p.item WHERE i.guia = '$guia' AND i.status = 'PENDIENTE'";
$sqlItems = "SELECT p.descripcion AS item, i.lote, i.cantidad FROM impresiones_items i INNER JOIN plantilla p ON i.item = p.item WHERE i.guia = '$guia'";
$rs_operationItems = mysqli_query($link, $sqlItems);

if (mysqli_num_rows($rs_operationItems) > 0) {
    $sqlUpdate = "UPDATE impresiones_items SET status = 'IMPRESO' WHERE guia = '$guia'";
    $rs_operationUpdate = mysqli_query($link, $sqlUpdate);

    if (!$rs_operationUpdate) {
        header("Location: ../desprese.php");
        exit();
    }
}

$sql3 = "SELECT fecha_beneficio, fecha_vencimiento FROM lotes_guias_desprese WHERE guia = '$guia' ORDER BY id ASC LIMIT 1";
$rs_operation3 = mysqli_query($link, $sql3);
$datos = mysqli_fetch_assoc($rs_operation3);
$fechaBeneficio = date("d/m/Y", strtotime($datos["fecha_beneficio"]));
$fechaEmpaque = date("d/m/Y");
$fechaVencimiento = date("d/m/Y", strtotime("+8 days"));

$sql4 = "SELECT concentracion FROM desprese WHERE id = '$guia'";
$rs_operation4 = mysqli_query($link, $sql4);
$concentracion = mysqli_fetch_assoc($rs_operation4)["concentracion"];

$sql5 = "SELECT ingredientes FROM salmuera WHERE id = '$concentracion'";
$rs_operation5 = mysqli_query($link, $sql5);

if (mysqli_num_rows($rs_operation5) > 0) {
    $ingredientes = mysqli_fetch_assoc($rs_operation5)["ingredientes"];
} else {
    $ingredientes = "";
}

require('pdf/fpdf.php');
class PDF extends FPDF{}

$pdf = new PDF();

$pdf->AddPage('L', 'Legal'); // Página tamaño Legal horizontal
$pdf->AliasNbPages();
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(true, 0);

// Definir dimensiones y posiciones para las etiquetas
$anchoEtiqueta = 138.5;
$altoEtiqueta = 95;
$margenHorizontal = 20; // Espacio entre etiquetas horizontalmente
$margenVertical = 20;   // Espacio entre etiquetas verticalmente

$etiquetasPorFila = 2; // Dos etiquetas por fila
$etiquetasPorColumna = 2; // Tres filas por página (ajustar según el alto de la página y la etiqueta)
$posicionesX = [0, 0 + $anchoEtiqueta + $margenHorizontal]; // Posiciones X para cada columna
$posicionesY = [0.05, 1]; // Posiciones Y para cada fila
$posicionYInicial = 0; // Margen superior inicial

$cont = 0;
$fila = 0;
$columna = 0;
$yActual = $posicionYInicial;

while ($row = mysqli_fetch_assoc($rs_operationItems)) {
    for ($i = 0; $i < $row["cantidad"]; $i++) {
        // Calcular columna y fila actual
        $fila = $cont % $etiquetasPorFila;
        $columna = floor($cont / $etiquetasPorFila) % $etiquetasPorColumna;
        $yActual = $posicionYInicial + ($posicionesY[$fila] * ($altoEtiqueta + $margenVertical));
        $xActual = $posicionesX[$columna];
    
        /* ?> <script>
            console.log("<?= "xActual: " . $xActual . " yActual: " . $yActual . " fila: " . $fila . " columna: " . $columna; ?>");
        </script> <?php */
    
        // Si ya llenamos la página, agregamos una nueva
        if ($cont > 0 && $cont % ($etiquetasPorFila * $etiquetasPorColumna) == 0) {
            $pdf->AddPage('L', 'Legal');
            $yActual = $posicionYInicial + 5.75;
            $columna = 0;
            $fila = 0;
        }
    
        $pdf->SetFont('Arial', 'B', 16);
    
        // Dibujar el borde principal de la etiqueta
        $pdf->SetXY($xActual, $yActual);
        $pdf->Cell($anchoEtiqueta, $altoEtiqueta, '', 0, 0, 'C');
    
        // Coordenadas internas para el contenido
        $xBorde = $xActual;
        $yBorde = $yActual;
    
        // Nombre del ítem
        $pdf->SetXY($xBorde + 26.75, $yBorde);
        $pdf->MultiCell(85, 5.5, $row["item"], 0, 'C');
    
        $pdf->SetFont('Arial', 'B', 14);
        
        // Lote
        $pdf->SetXY($xBorde, $pdf->GetY() - 2);
        $pdf->Cell(30, 5, "LOTE:", 0, 0, "L");
        $pdf->SetXY($xBorde + 98.5, $pdf->GetY());
        $pdf->Cell(40, 5, $row["lote"], 0, 1, "R");
        
        $pdf->SetFont('Arial', 'B', 12);
        // Fechas
        $pdf->SetXY($xBorde, $pdf->GetY());
        $pdf->Cell(60, 5, "FECHA BENEFICIO:", 0, 0, "L");
        $pdf->SetXY($xBorde + 98.5, $pdf->GetY());
        $pdf->Cell(40, 5, $fechaBeneficio, 0, 1, "R");
        
        $pdf->SetXY($xBorde, $pdf->GetY());
        $pdf->Cell(60, 5, "FECHA EMPAQUE:", 0, 0, "L");
        $pdf->SetXY($xBorde + 98.5, $pdf->GetY());
        $pdf->Cell(40, 5, $fechaEmpaque, 0, 1, "R");
    
        $pdf->SetXY($xBorde, $pdf->GetY());
        $pdf->Cell(60, 5, "FECHA VENCIMIENTO:", 0, 0, "L");
        $pdf->SetXY($xBorde + 98.5, $pdf->GetY());
        $pdf->Cell(40, 5, $fechaVencimiento, 0, 1, "R");
    
        // Mensaje de conservación
        $pdf->SetXY($xBorde + 21.75, $pdf->GetY() + 3);
        $pdf->Cell(95, 4, utf8_decode('Conservar Refrigerado entre 0°C y 4°C'), 0, 1, 'C');
    
        // Mensaje de intrucciones
        $pdf->SetXY($xBorde + 21.75, $pdf->GetY() + 1);
        $pdf->Cell(95, 7, utf8_decode('Instrucciones de uso: Cocinar o Asar'), 0, 1, 'C');
        
        $pdf->SetXY($xBorde + 10, $pdf->GetY() + 1);
        $pdf->MultiCell(120, 4.5, utf8_decode('Carne de pollo marinado, inyectado con una solución (15%) de agua, proteina, sal y polifosfatos'), 0, 'C');
        
        // Ingredientes
        $pdf->SetXY($xBorde + 5, $pdf->GetY() + 3);
        $pdf->MultiCell(128.5, 4.5, utf8_decode($ingredientes), 0, 'C');
        
        $pdf->SetFont('Arial', 'B', 12);
        // Registro sanitario
        $pdf->SetXY($xBorde + 21.75, $pdf->GetY() + 3);
        $pdf->Cell(95, 4, utf8_decode('Registro Sanitario: RSA-0033503-2024'), 0, 1, 'C');
    
        // Fabricante
        $pdf->SetXY($xBorde + 21.75, $pdf->GetY());
        $pdf->Cell(95, 4, utf8_decode('Fabricado por: MERCAMIO SA'), 0, 1, 'C');
    
        // Dirección
        $pdf->SetXY($xBorde + 21.75, $pdf->GetY());
        $pdf->Cell(95, 4, utf8_decode('Calle 33B #17C-87 CALI-VALLE'), 0, 1, 'C');

        // Guía
        $pdf->SetXY($xBorde + 120.5, $yBorde + 87);
        $pdf->Cell(15, 4, $guia, 0, 1, 'R');
    
        $cont++;
    }
}

$pdf->Output('formato_item_'.$guia.'_'.date('Ymd').'.pdf', 'I'); //D