<?php
session_start();

ini_set("display_errors", false);
ini_set('display_errors', '0');
error_reporting(0);

function fsalida($cad2){
    $uno = substr($cad2, 11, 5);
    return $uno;
 }

 function fsalidaconsecutivo($cad,$cad2){
        $uno = "213DM";
        $dos = str_pad($cad, 6, '0', STR_PAD_LEFT);
        $tres = substr($cad2, 2, 2);
        return $uno."-".$dos."-".$tres;
     }

$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";
$fecha_actual = date("Y-m-d");
include("../config.php");

$sqlg = "SELECT id_recepcion, fecharec, remision, destino, beneficio, canales, consecutivog, fechasac, lotep, ica, guiat, certificadoc, responsable, conductor, placa, cph1, cph2, cph3, cph4, cph5, chv1, chv2, chv3, chv4, ccoh1, ccoh2, ccoh3, ccoh4, ccoh5, ccoh6, ccoh7, ccoh8, ccoh9, ccoh10, tipo, observaciones 
        FROM recepcion 
        WHERE id_recepcion = " . $id;

$c2 = mysqli_query($link, $sqlg) or die("aqui 1 ".mysqli_error($link));
$rs2 = mysqli_fetch_array($c2);

$sql3 = "SELECT empresa, sede, direccion, municipio 
         FROM destinos 
         WHERE id='".$rs2['destino']."'";
$c3 = mysqli_query($link, $sql3) or die("aqui 2 ".mysqli_error($link));
$rs3 = mysqli_fetch_array($c3);

$sql30 = "SELECT sede, municipio 
          FROM beneficio 
          WHERE id='".$rs2['beneficio']."'";
$c30 = mysqli_query($link, $sql30) or die("aqui 2 ".mysqli_error($link));
$rs30 = mysqli_fetch_array($c30);

$sql4 = "SELECT nombres, telefono 
         FROM responsables 
         WHERE cedula='".$rs2['responsable']."'";
$c4 = mysqli_query($link, $sql4) or die("aqui 3 ".mysqli_error($link));
$rs4 = mysqli_fetch_array($c4);

$sql40 = "SELECT nombres 
          FROM conductores_recepcion 
          WHERE cedula='".$rs2['conductor']."'";
$c40 = mysqli_query($link, $sql40) or die("aqui 3 ".mysqli_error($link));
$rs40 = mysqli_fetch_array($c40);

$sql = "SELECT DISTINCT p.item,ip.lote, p.descripcion 
        FROM plantilla p
        JOIN item_proveedor ip ON p.item = ip.item
        WHERE ip.proveedor = ".$id."
        ORDER BY p.descripcion ASC";        
$c = mysqli_query($link, $sql) or die("aqui 1:".mysqli_error($link));

$sql5 = "SELECT *
        FROM recepcion_pesos 
        WHERE id_recepcion = " . $id . "
        ORDER BY turno ASC";
$c5 = mysqli_query($link, $sql5) or die("aqui 1:".mysqli_error($link));
//$rs5 = mysqli_fetch_array($c5);

$resultado = [];

while ($row = mysqli_fetch_assoc($c5)) {
    $resultado[] = $row;
}
$canales = count($resultado);

$horaInicio = $resultado[0]; 
$horaFin = $resultado[$canales-1]; 

$inicio = date('H:i', strtotime($horaInicio['registro']));
$fin = date('H:i', strtotime($horaFin['registro']));

$inicioDateTime = new DateTime($inicio);
$finDateTime = new DateTime($fin);
$diferencia = $inicioDateTime->diff($finDateTime);

$tiempoTranscurrido = $diferencia->format('%H:%I');

$sql6 = "SELECT nombres, telefono 
        FROM conductores 
        WHERE cedula=".$rs2['conductor'];
$c6 = mysqli_query($link, $sql6) or die(mysqli_error($link));
$rs6 = mysqli_fetch_array($c6);

require('pdf/fpdf.php');
class PDF extends FPDF{}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');

$pdf->AddFont('DejaVu','','DejaVuSans.php',true);

$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);

$logo = '../assets/img/logo-mercamio.jpg';
$pdf->Image($logo, 10, 10, 50, 15);
$pdf->ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY($pdf->GetX() + 60, $pdf->GetY() - 12);
$pdf->multiCell(100, 6, utf8_decode('DIFERENCIA PESOS            '), 1, 0, 'C');
$pdf->SetXY($pdf->GetX() + 160, $pdf->GetY() - 12);
$pdf->Cell(30, 6, str_pad($rs2['id_recepcion'], 6, '0', STR_PAD_LEFT), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 160, $pdf->GetY());
$pdf->Cell(30, 12, utf8_decode('Página 1 de 1'), 1, 1, 'C');
$pdf->Cell(60, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('AREA'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('FECHA DE CREACIÓN'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('FECHA DE REVISIÓN'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('VERSIÓN'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('CÓDIGO'), 1, 1, 'C');
$pdf->Cell(60, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode('Producción'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('7/11/2023'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('7/11/2024'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('FTO-PDM-04'), 1, 1, 'C');
$pdf->ln(3);

$pdf->SetFont('Arial', 'B', 8);
//$pdf->Cell(150, 5, utf8_decode('INFORMACIÓN RECEPCIÓN'), 1, 0, 'C');
//$pdf->Cell(40, 5, utf8_decode('LOTE PLANTA DM'), 1, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(43, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(38, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(64, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(45, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(26, 5, utf8_decode('Fecha Recepción:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(17, 5, utf8_decode($rs2['fecharec']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(21, 5, utf8_decode('Nro Remisión:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(17, 5, utf8_decode($rs2['remision']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(21, 5, utf8_decode('Sede Destino:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(43, 5, utf8_decode($rs3['empresa']." - ".$rs3['sede']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(23,5, utf8_decode('Lote Producto:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(22, 5, utf8_decode($rs2['consecutivog']), 0, 1, '');

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(137, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(53, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(61, 5, utf8_decode('Nombre o Razón Social Planta de Beneficio:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(76, 5, utf8_decode($rs30['sede']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(16, 5, utf8_decode('Dirección:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(37, 5, utf8_decode($rs30['municipio']."-VALLE"), 0, 1, '');

$pdf->ln(7);

$pdf->Cell(190, 5, utf8_decode('PESOS CANALES'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$granTotal = 0;
//empieza 1
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(15, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Recibido Planta'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Frigorifico'), 1, 0, 'C');
$pdf->Cell(15, 5, utf8_decode('Dif. Kg.'), 1, 0, 'C');
$pdf->Cell(12, 5, utf8_decode('Dif. %'), 1, 0, 'C');
$pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');

$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(15, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Recibido Planta'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Frigorifico'), 1, 0, 'C');
$pdf->Cell(15, 5, utf8_decode('Dif. Kg.'), 1, 0, 'C');
$pdf->Cell(12, 5, utf8_decode('Dif. %'), 1, 1, 'C');

$r = $resultado[0];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('1'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[1];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('2'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[2];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('3'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[3];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('4'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[4];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('5'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[5];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('6'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[6];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('7'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[7];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('8'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[8];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('9'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[9];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('10'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[10];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('11'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[11];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('12'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[12];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('13'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[13];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('14'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[14];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('15'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[15];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('16'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[16];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('17'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[17];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('18'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[18];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('19'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[19];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('20'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[20];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('21'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[21];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('22'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[22];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('23'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[23];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('24'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[24];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('25'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[25];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('26'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[26];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('27'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[27];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('28'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[28];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('29'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[29];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('30'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[30];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('31'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[31];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('32'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[32];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('33'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[33];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('34'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[34];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('35'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[35];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('36'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[36];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('37'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[37];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('38'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[38];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('39'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[39];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('40'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[40];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('41'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[41];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('42'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[42];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('43'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[43];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('44'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[44];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('45'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[45];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('46'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[46];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('47'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[47];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('48'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[48];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('49'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[49];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('50'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[50];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('51'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[51];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('52'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[52];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('53'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[53];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('54'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[54];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('55'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[55];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('56'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[56];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('57'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[57];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('58'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[58];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('59'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}
$r = $resultado[59];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('60'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[60];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('61'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[61];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('62'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[62];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('63'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[63];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('64'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[64];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('65'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[65];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('66'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[66];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('67'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[67];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('68'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[68];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('69'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[69];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('70'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[70];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('71'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[71];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('72'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[72];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('73'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[73];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('74'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[74];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('75'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[75];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('76'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[76];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('77'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[77];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('78'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[78];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('79'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[79];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('80'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[80];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('81'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[81];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('82'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[82];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('83'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[83];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('84'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[84];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('85'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[85];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('86'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[86];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('87'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[87];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('88'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[88];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('89'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[89];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('90'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[90];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('91'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[91];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('92'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[92];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('93'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[93];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('94'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[94];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('95'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[95];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('96'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[96];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('97'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[97];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('98'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}

$r = $resultado[98];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('99'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 0, 'C');
        $pdf->Cell(11, 5, utf8_decode(''), 0, 0, 'C');
}

$r = $resultado[99];
if($r['turno']!=''){
        $granTotalPlanta = $granTotalPlanta + ($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']);
        $granTotalFrigorifico = $granTotalFrigorifico + $r['inventario'];
        $granTotal = $granTotal + (($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']);
        $pdf->Cell(7.5, 5, utf8_decode('100'), 1, 0, 'C');
        $pdf->Cell(15, 5, utf8_decode($r['turno']), 1, 0, 'C');
        $pdf->Cell(20, 5, number_format(($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode($r['inventario']), 1, 0, 'C');
        $pdf->Cell(15, 5, number_format((($r['estomago1']+$r['estomago2']+$r['piernas1']+$r['piernas2'])-$r['inventario']), 2, '.', ','), 1, 0, 'C');
        $pdf->Cell(12, 5, utf8_decode($r['diferencia']."%"), 1, 1, 'C');
}


/* $pdf->Cell(7.5, 5, utf8_decode('1'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno1), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura1), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso1), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('16'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno16), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura16), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso16), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('31'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno31), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura31), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso31), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('46'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno46), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura46), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso46), 1, 1, 'C'); */
//termina 1

$pdf->ln(10);

$porcentajeTotalEstomago = ($totalEstomagos/$granTotal)*100;
$porcentajeTotalpiernas= ($totalpiernass/$granTotal)*100;

$promedio = ($granTotalPlanta + $granTotalFrigorifico)/2;

$granTotalPorcentaje = ($granTotal/$promedio)*100;

$pdf->Cell(47.5, 5, utf8_decode('Total Recibido Planta: '.number_format($granTotalPlanta, 2, '.', ',').' Kg.'), 1, 0, '');
$pdf->Cell(47.5, 5, utf8_decode('Total Peso Frigorifico: '.number_format($granTotalFrigorifico, 2, '.', ',').' Kg.'), 1, 0, '');
$pdf->Cell(47.5, 5, utf8_decode('Total Diferencia: '.number_format($granTotal, 2, '.', ',').' Kg.'), 1, 0, '');
$pdf->Cell(47.5, 5, utf8_decode('Porcentaje Diferencia: '.number_format($granTotalPorcentaje, 2, '.', ',').' %'), 1, 0, '');

/* $pdf->Cell(34, 5, utf8_decode('Peso Promedio Canal: '.number_format(round($granTotal/$canales)).' Kg.'), 1, 1, '');
$pdf->ln(5);
$pdf->Cell(190, 5, utf8_decode('Observaciones: '.$rs2['observaciones']), 0, 1, '');
$pdf->Cell(35, 10, utf8_decode('Hora Inicial: '.$fin), 0, 0,'');
$pdf->Cell(31, 10, utf8_decode('Hora Final: '.$inicio), 0, 0, '');
$pdf->Cell(31, 10, utf8_decode('Tiempo Total: '.$tiempoTranscurrido), 0, 0, '');
$pdf->Cell(28, 10,'Canales: '.$canales, 0, 0, '');
$pdf->Cell(65, 10, utf8_decode('Firma Responsable:____________________________'), 0, 1, ''); */
$pdf->Output('formato_recepcion.pdf', 'D');

ob_end_clean();
?>