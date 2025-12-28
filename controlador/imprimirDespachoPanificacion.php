<?php
include("../config.php");
date_default_timezone_set("America/Bogota");
ini_set('display_errors', '0');
error_reporting(0);

$id = $_GET["id"];

$sql = "SELECT * FROM despacho_panificacion WHERE id = $id";
$rs_operation = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($rs_operation);

$sql2 = "SELECT p.descripcion AS item, p.item AS item_id, i.lote, i.unidades, i.canastillas, i.fecha_venci FROM items_despachop i INNER JOIN plantilla p ON p.item = i.item WHERE guia = $id";
$rs_operation2 = mysqli_query($link, $sql2);
$items = array();
while ($row2 = mysqli_fetch_assoc($rs_operation2)) {
    $items[] = $row2;
}

$sql3 = "SELECT * FROM conductores WHERE cedula = $row[conductor]";
$rs_operation3 = mysqli_query($link, $sql3);
$conductor = mysqli_fetch_assoc($rs_operation3);

$sql4 = "SELECT * FROM destinos WHERE id = $row[destino]";
$rs_operation4 = mysqli_query($link, $sql4);
$destino = mysqli_fetch_assoc($rs_operation4);

$sql5 = "SELECT cedula, nombres, telefono FROM responsables WHERE cedula = '$row[responsable]'";
$rs_operation5 = mysqli_query($link, $sql5);
$responsable = mysqli_fetch_assoc($rs_operation5);

if ($row["hora_fin"] == "") {
    $hora_final = date("H:i");
    $sql6 = "UPDATE despacho_panificacion SET hora_fin = '$hora_final' WHERE id = $id";
    mysqli_query($link, $sql6);
} else {
    $hora_final = $row["hora_fin"];
}

function calcular_tiempo($horaInicio, $horaFin) {
    list($hIni, $mIni) = explode(':', $horaInicio);
    list($hFin, $mFin) = explode(':', $horaFin);
    
    $segundosInicio = $hIni * 3600 + $mIni * 60;
    $segundosFin = $hFin * 3600 + $mFin * 60;
    
    $diferencia = $segundosFin - $segundosInicio;
    
    if ($diferencia < 0) {
        $diferencia += 24 * 3600;
    }

    $horas = floor($diferencia / 3600);
    $minutos = floor(($diferencia % 3600) / 60);
    
    return sprintf("%02d:%02d", $horas, $minutos);
}

include("pdf/fpdf.php");

class PDF extends FPDF {}

$pdf = new PDF();
$pdf->AddPage('P', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(40, 22, "", 1, 0, 'C');
$logo = "../assets/img/logo-mercamio.jpg";
$pdf->Image($logo, 14.5, 15.5, 31.5, 9.7);
$pdf->Cell(120, 7, "FORMATO DESPACHO PRODUCTO TERMINADO", 1, 0, 'C');
$pdf->Cell(30, 7, utf8_decode("     Página " . $pdf->PageNo() . " de {nb}"), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 40, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 8, utf8_decode("ÁREA"), 1, 0, 'C');
$pdf->MultiCell(30, 4, utf8_decode("FECHA DE CREACIÓN"), 1, 'C');
$pdf->SetXY($pdf->GetX() + 100, $pdf->GetY() - 8);
/* $pdf->Cell(30, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 30, $pdf->GetY());
$pdf->Cell(30, 4, utf8_decode("FECHA DE"), 0, 0, "C");
$pdf->SetXY($pdf->GetX() - 30, $pdf->GetY() + 4);
$pdf->Cell(30, 4, utf8_decode("CREACIÓN"), 0, 0, 'C');
$pdf->SetXY($pdf->GetX(), $pdf->GetY() - 4); */
$pdf->Cell(30, 8, utf8_decode("FECHA DE REVISIÓN"), 1, 0, 'C');
$pdf->Cell(30, 8, utf8_decode("VERSIÓN"), 1, 0, 'C');
$pdf->Cell(30, 8, utf8_decode("CÓDIGO"), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 40, $pdf->GetY());
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(30, 7, "Calidad", 1, 0, 'C');
$pdf->Cell(30, 7, "24/02/2025", 1, 0, 'C');
$pdf->Cell(30, 7, "24/02/2026", 1, 0, 'C');
$pdf->Cell(30, 7, "03", 1, 0, 'C');
$pdf->Cell(30, 7, "FTO-PPA-18", 1, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(47.5, 5, "Fecha Despacho", 1, 0, 'C', true);
$pdf->Cell(47.5, 5, "Placa Vehiculo", 1, 0, 'C', true);
$pdf->Cell(70, 5, "Conductor", 1, 0, 'C', true);
$pdf->Cell(25, 5, "Consecutivo", 1, 1, 'C', true);
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(47.5, 5, date("d/m/Y", strtotime($row["fecha_registro"])), 1, 0, 'C');
$pdf->Cell(47.5, 5, $row["placa"], 1, 0, 'C');
$pdf->Cell(70, 5, $conductor["nombres"], 1, 0, 'C');
$pdf->Cell(25, 5, $row["id_tipo"], 1, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(190, 6, utf8_decode("IDENTIFICACIÓN DEL ESTABLECIMIENTO DE PROCEDENCIA"), 1, 1, "C", true);
$pdf->Cell(42, 5, utf8_decode("Razón Social"), 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(148, 5, "MERCAMIO PLANTA PANIFICADORA", 1, 1, '');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(42, 5, utf8_decode("Dirección"), 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(148, 5, "CALLE 33B # 17 C-87 BARRIO: FLORESTA", 1, 1, '');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(42, 5, "Departamento", 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(49.3, 5, "VALLE", 1, 0, 'L');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(49.3, 5, "Municipio", 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(49.4, 5, "CALI", 1, 1, 'L');
$pdf->Ln(2);
$pdf->Cell(190, 6, utf8_decode("IDENTIFICACIÓN DEL ESTABLECIMIENTO DE DESTINO"), 1, 1, "C", true);
$pdf->Cell(42, 5, utf8_decode("Razón Social"), 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(148, 5, $destino["empresa"] . " - " . $destino["sede"], 1, 1, '');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(42, 5, utf8_decode("Dirección"), 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(148, 5, $destino["direccion"], 1, 1, '');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(42, 5, "Departamento", 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(49.3, 5, "VALLE", 1, 0, '');
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(49.3, 5, "Municipio", 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(49.4, 5, $destino["municipio"], 1, 1, '');
$pdf->Ln(2);

$pdf->Cell(190, 6, utf8_decode("DESCRIPCIÓN DE LOS PRODUCTOS TRANSPORTADOS"), 1, 1, "C", true);
$pdf->Cell(8, 5, utf8_decode("N°"), 1, 0, 'C');
$pdf->Cell(18, 5, "Item", 1, 0, 'C');
$pdf->Cell(86, 5, "Producto", 1, 0, 'C');
$pdf->Cell(25, 5, "Lote", 1, 0, 'C');
$pdf->Cell(16, 5, "Unidades", 1, 0, 'C');
$pdf->Cell(18, 5, "Canastillas", 1, 0, 'C');
$pdf->Cell(19, 5, "Fecha Venci", 1, 1, 'C');
$unidades = 0;
$canastillas = 0;
$pdf->SetFont("Arial", "", 8);
for ($i=0; $i < count($items) ; $i++) { 
    $pdf->Cell(8, 5, $i + 1, 1, 0, 'C');
    $pdf->Cell(18, 5, $items[$i]["item_id"], 1, 0, 'C');
    $pdf->Cell(86, 5, substr($items[$i]["item"], 0, 30), 1, 0, 'L');
    $pdf->Cell(25, 5, $items[$i]["lote"], 1, 0, 'L');
    $pdf->Cell(16, 5, $items[$i]["unidades"], 1, 0, 'C');
    $pdf->Cell(18, 5, $items[$i]["canastillas"], 1, 0, 'C');
    $pdf->Cell(19, 5, $items[$i]["fecha_venci"], 1, 1, 'C');
    $unidades += $items[$i]["unidades"];
    $canastillas += $items[$i]["canastillas"];
}
$pdf->Ln(2);
$pdf->Cell(190, 6, "", 1, 0, "C");
$pdf->SetXY($pdf->GetX() - 190, $pdf->GetY());
$pdf->Cell(11, 6, "TOTAL", 0, 0, "C");
$pdf->SetXY($pdf->GetX() + 126, $pdf->GetY());
$pdf->Cell(16, 6, $unidades, 0, 0, "C");
$pdf->Cell(18, 6, $canastillas, 0, 1, "C");
$pdf->Ln(2);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(190, 6, utf8_decode("VERIFICACIÓN DE CONDICIONES HIGIENICO SANITARIAS VEHICULO"), 1, 1, "C", true);
$pdf->Cell(40, 7, "Vehiculo Limpio", 1, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
if ($row["check1"] == "1") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(10, 7, chr(51), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetFont("Arial", "", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
if ($row["check1"] == "0") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(10, 7, chr(53), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(50, 7, "Ausencia de Sustancias Peligrosas", 1, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
if ($row["check2"] == "1") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(10, 7, chr(51), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetFont("Arial", "", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
if ($row["check2"] == "0") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(10, 7, chr(53), 1, 1, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 1, 'C');
}
$pdf->SetFont("Arial", "B", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 7, "Libre de Olores Fuertes", 1, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
if ($row["check3"] == "1") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(10, 7, chr(51), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetFont("Arial", "", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
if ($row["check3"] == "0") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(10, 7, chr(53), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(50, 7, "Usa Estibas", 1, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
if ($row["check4"] == "1") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(10, 7, chr(51), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetFont("Arial", "", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
if ($row["check4"] == "0") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(10, 7, chr(53), 1, 1, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 1, 'C');
}
$pdf->SetFont("Arial", "B", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(40, 7, "Ausencia de Plagas", 1, 0, 'L');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
if ($row["check5"] == "1") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(10, 7, chr(51), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetFont("Arial", "", 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
if ($row["check5"] == "0") {
    $pdf->SetFont("ZapfDingbats", "", 14);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(10, 7, chr(53), 1, 0, 'C');
} else {
    $pdf->Cell(10, 7, "", 1, 0, 'C');
}
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(50, 7, "-", 1, 0, 'C');
$pdf->SetFont("Arial", "", 8);
$pdf->Cell(13, 7, "Cumple", 1, 0, 'C');
$pdf->Cell(10, 7, "", 1, 0, 'C');
$pdf->Cell(17, 7, "No Cumple", 1, 0, 'C');
$pdf->Cell(10, 7, "", 1, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(190, 6, ("OBSERVACIONES"), 1, 1, "C", true);
$pdf->MultiCell(190, 6, $row["observaciones"], 1, "L");
$pdf->Cell(190, 6, "RESPONSABLE DESPACHO", 1, 1, "C", true);
$pdf->Cell(35, 7, "Nombres y Apellidos", 1, 0, 'C', true);
$pdf->Cell(80, 7, $responsable["nombres"], 1, 0, 'L');
$pdf->Cell(30, 7, "Hora Inicio", 1, 0, 'C', true);
$pdf->Cell(45, 7, $row["hora_inicio"], 1, 1, 'C');
$pdf->Cell(35, 7, utf8_decode("Teléfono de contacto"), 1, 0, 'C', true);
$pdf->Cell(80, 7, $responsable["telefono"], 1, 0, 'L');
$pdf->Cell(30, 7, "Hora Final", 1, 0, 'C', true);
$pdf->Cell(45, 7, $hora_final, 1, 1, 'C');
$pdf->Cell(35, 7, "Firma", 1, 0, 'C', true);
$pdf->Cell(80, 7, "", 1, 0, 'C');
$firma = "../assets/img/firmas/" . $responsable["cedula"] . ".jpg";
$pdf->Image($firma, $pdf->GetX() - 57.5, $pdf->GetY() + 0.5, 35, 6);
$pdf->Cell(30, 7, "Tiempo Total", 1, 0, 'C', true);
$pdf->Cell(45, 7, calcular_tiempo($row["hora_inicio"], $hora_final), 1, 1, 'C');

$pdf->Output("GuiaPanes.pdf", "D");

