<?php
session_start();

date_default_timezone_set("America/bogota");
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

$sqlg = "SELECT id_recepcion, fecharec, remision, destino, beneficio, canales, consecutivog, fechasac, lotep, ica, guiat, certificadoc, responsable, conductor, placa, cph1, cph2, cph3, cph4, cph5, chv1, chv2, chv3, chv4, ccoh1, ccoh2, ccoh3, ccoh4, ccoh5, ccoh6, ccoh7, ccoh8, ccoh9, ccoh10, tipo, observaciones,hora_inicial,hora_final 
        FROM recepcion 
        WHERE id_recepcion = " . $id;
$c2 = mysqli_query($link, $sqlg) or die("aqui 1 ".mysqli_error($link));
$rs2 = mysqli_fetch_array($c2);

if($rs2['hora_final']=='00:00:00'){
   $hora = date("H:i:s");
   $sql_hora = "select hora_recepcion_carne as hora_inicial from hora_temporal";
   $c_hora = mysqli_query($link, $sql_hora) or die("aqui 1 ".mysqli_error($link));
   $rs_hora = mysqli_fetch_array($c_hora);

   $sql = "UPDATE recepcion set 
              hora_inicial = '" . $rs_hora['hora_inicial'] . "',
              hora_final = '" . $hora . "'
           WHERE id_recepcion = " . $id;

    $rs_operacion = mysqli_query($link, $sql) or die(mysqli_error($link));

    $hora_i = $rs_hora['hora_inicial'];
    $hora_f = $hora;
}else{
    $hora_i = $rs2['hora_inicial'];
    $hora_f = $rs2['hora_final'];
}

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

/* $sql = "SELECT distinct plantilla.item, descripcion, lote
        FROM plantilla, item_proveedor 
        WHERE plantilla.item = item_proveedor.item 
        AND item_proveedor.proveedor = " . $id . "
        ORDER BY registro DESC"; */
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

$inicio = date('H:i', strtotime($hora_i));
$fin = date('H:i', strtotime($hora_f));

$inicioDateTime = new DateTime($inicio);
$finDateTime = new DateTime($fin);
$diferencia = $inicioDateTime->diff($finDateTime);

$tiempoTranscurrido = $diferencia->format('%H:%I');

$sql6 = "SELECT nombres, telefono 
        FROM conductores 
        WHERE cedula=".$rs2['conductor'];
$c6 = mysqli_query($link, $sql6) or die(mysqli_error($link));
$rs6 = mysqli_fetch_array($c6);

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
    
$hora = $_SESSION["fechaInicio"];
$horaActual = date("G:i");

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
$pdf->multiCell(100, 6, utf8_decode('FORMATO RECEPCIÓN CANALES BOVINAS            '), 1, 0, 'C');
$pdf->SetXY($pdf->GetX() + 160, $pdf->GetY() - 12);
$pdf->Cell(30, 6, str_pad($rs2['id_recepcion'], 6, '0', STR_PAD_LEFT), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 160, $pdf->GetY());
$pdf->Cell(30, 6, utf8_decode('Página 1 de 1'), 1, 1, 'C');
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

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(53, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(72, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(65, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(18, 5, utf8_decode('No Canales:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(35, 5, utf8_decode($rs2['canales']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(21, 5, utf8_decode('Lote Beneficio:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(64, 5, utf8_decode($rs2['lotep']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(33, 5, utf8_decode('Fecha Beneficio:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(32, 5, utf8_decode($rs2['fechasac']), 0, 1, '');

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(46, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(81, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(63, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(15, 5, utf8_decode('Guia ICA:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(31, 5, utf8_decode($rs2['ica']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(50, 5, utf8_decode('Guia Transporte de Carne en Canal:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(31, 5, utf8_decode($rs2['guiat']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(33, 5, utf8_decode('Certificado de Calidad:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(30, 5, utf8_decode($rs2['certificadoc']), 0, 1, '');

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(77, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(76, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(37, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(32, 5, utf8_decode('Nombre Responsable:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(45, 5, utf8_decode($rs4['nombres']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(28, 5, utf8_decode('Nombre Conductor:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(48, 5, utf8_decode($rs40['nombres']), 0, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(23, 5, utf8_decode('Placa Vehiculo:'), 0, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(40, 5, utf8_decode($rs2['placa']), 0, 1, '');
$pdf->ln(2);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, utf8_decode('Cumplimiento Practicas Higiénicas Personal Manipulador'), 1, 1, '');

//$pdf->SetFont('DejaVu', '', 6);
$pdf->SetFont('ZapfDingbats', '', 6);
//$checkmark = html_entity_decode('&#10004;', ENT_COMPAT, 'UTF-8');
//$checkmark = mb_convert_encoding(chr(10004), 'UTF-8', 'UTF-32BE');
//$checkmark = '✔';

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(34, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(41, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(43, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(46, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(26, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 6);

$rs2['cph1'] == 1 ? $checkmark1 = chr(51) : $checkmark1 = chr(53);
$rs2['cph2'] == 1 ? $checkmark2 = chr(51) : $checkmark2 = chr(53);
$rs2['cph3'] == 1 ? $checkmark3 = chr(51) : $checkmark3 = chr(53);
$rs2['cph4'] == 1 ? $checkmark4 = chr(51) : $checkmark4 = chr(53);
$rs2['cph5'] == 1 ? $checkmark5 = chr(51) : $checkmark5 = chr(53);

$pdf->Cell(25, 5, 'Usa cofia y tapabocas: ', 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 10);
$pdf->Cell(9, 5, $checkmark1  , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32, 5, utf8_decode('Capa limpia y en buen estado:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 10);
$pdf->Cell(9, 5,  $checkmark2 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(34, 5, utf8_decode('Botas limpias y en buen estado:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 10);
$pdf->Cell(9, 5,  $checkmark3 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(37, 5, utf8_decode('Uñas cortas limpias y sin esmalte:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 10);
$pdf->Cell(9, 5,  $checkmark4 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(21, 5, utf8_decode('No usa accesorios:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 10);
$pdf->Cell(5, 5,  $checkmark5 , 0, 1, '');

$pdf->SetFont('Arial', 'B', 6);

$pdf->ln(2);

$rs2['chv2'] == 1 ? $checkmark1 = chr(51) : $checkmark1 = chr(53);
$rs2['chv3'] == 1 ? $checkmark2 = chr(51) : $checkmark2 = chr(53);
$rs2['chv4'] == 1 ? $checkmark3 = chr(51) : $checkmark3 = chr(53);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, utf8_decode('Cumplimento Condiciones Higiénico Sanitarias Del Vehículo De Transporte'), 1, 1, '');

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(32, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(45, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(62, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(51, 5,'', 1, 1, '');
$pdf->ln(-5);
$pdf->SetFont('Arial', 'B', 6);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 5, utf8_decode('Temperatura del Vehículo:'), 0, 0, '');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(4, 5, utf8_decode($rs2['chv1']."°"), 1, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(41, 5, utf8_decode('¿Vehículo limpio y ausente de plagas?:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 9);
$pdf->Cell(4, 5,  $checkmark1 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(58, 5, utf8_decode('¿Se transportan sustancias químicas con las canales?:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 9);
$pdf->Cell(4, 5,  $checkmark2 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(47, 5, utf8_decode('¿Se transporta canales de una sola especie?:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 9);
$pdf->Cell(3, 5,  $checkmark3 , 0, 1, '');

$pdf->SetFont('Arial', 'B', 6);

$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('Cumplimiento Características Organolépticas e Higiénico Sanitarias De La Canales'), 1, 1, '');
$pdf->SetFont('Arial', '', 6);

$rs2['ccoh1'] == 1 ? $checkmark1 = chr(51) : $checkmark1 = chr(53);
$rs2['ccoh2'] == 1 ? $checkmark2 = chr(51) : $checkmark2 = chr(53);
$rs2['ccoh3'] == 1 ? $checkmark3 = chr(51) : $checkmark3 = chr(53);
$rs2['ccoh4'] == 1 ? $checkmark4 = chr(51) : $checkmark4 = chr(53);
$rs2['ccoh5'] == 1 ? $checkmark5 = chr(51) : $checkmark5 = chr(53);
$rs2['ccoh6'] == 1 ? $checkmark6 = chr(51) : $checkmark6 = chr(53);
$rs2['ccoh7'] == 1 ? $checkmark7 = chr(51) : $checkmark7 = chr(53);
$rs2['ccoh8'] == 1 ? $checkmark8 = chr(51) : $checkmark8 = chr(53);
$rs2['ccoh9'] == 1 ? $checkmark9 = chr(51) : $checkmark9 = chr(53);
$rs2['ccoh10'] == 1 ? $checkmark10 = chr(51) : $checkmark10 = chr(53);

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(9, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(11, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(13, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(27, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(22, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(24, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(16, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(20, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(33, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(15, 5,'', 1, 1, '');
$pdf->ln(-5);

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5, 5, utf8_decode('Olor:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark1 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7, 5, utf8_decode('Color:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark2 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(9, 5, utf8_decode('Sin Pelo:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark3 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(23, 5, utf8_decode('Sin contenido Rumial:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark4 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 5, utf8_decode('Sin Materia Fecal:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark5 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(20, 5, utf8_decode('Sin Medula Espinal:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark6 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(12, 5, utf8_decode('Sin Nuches:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark7 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 5, utf8_decode('Sin Garrapatas:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark8 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(29, 5, utf8_decode('Sin Coagulos ni Hematomas:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark9 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(11, 5, utf8_decode('Sin Leche:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark10 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->ln(7);

$pdf->Cell(190, 5, utf8_decode('PESOS CANALES'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$menor= '';
$mayor= '';

$totalPiernas = 0;
$totalEstomagos = 0;
$granTotal = 0;
//empieza 1
$r = $resultado[0];
$temperatura1 = $r['temperatura'];
$turno1 = $r['turno'];
$total =$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->Cell(5.5, 10, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->SetXY(29.5, 102);$pdf->Cell (38, 5, utf8_decode('Peso'), 1, 0, 'C');
$pdf->SetXY(29.5, 107);$pdf->Cell(10, 5, utf8_decode('Estomag'), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode('Piernas'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('Total'), 1, 1, 'C');

$pdf->SetXY(10, 113);
$pdf->Cell(5.5, 10, utf8_decode('1'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 113);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 118);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 1

//empieza 2
$r = $resultado[1];
$temperatura2 = $r['temperatura'];
$turno2 = $r['turno'];
$total =$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 124);
$pdf->Cell(5.5, 10, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');

$pdf->SetXY(29.5, 124);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 129);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 2

//empieza 3
$r = $resultado[2];
$temperatura3 = $r['temperatura'];
$turno3 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';} 
$pdf->SetXY(10, 135);
$pdf->Cell(5.5, 10, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 135);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 140);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 3

//empieza 4
$r = $resultado[3];
$temperatura4 = $r['temperatura'];
$turno4 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 146);
$pdf->Cell(5.5, 10, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 146);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 151);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 4

//empieza 5
$r = $resultado[4];
$temperatura5 = $r['temperatura'];
$turno5 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 157);
$pdf->Cell(5.5, 10, utf8_decode('5'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 157);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 162);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 5

//termina 6
$r = $resultado[5];
$temperatura6 = $r['temperatura'];
$turno6 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 168);
$pdf->Cell(5.5, 10, utf8_decode('6'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 168);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 173);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 6

//termina 7
$r = $resultado[6];
$temperatura2 = $r['temperatura'];
$turno7 = $r['turno'];$temperatura2 = $r['temperatura'];
$turno7 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 179);
$pdf->Cell(5.5, 10, utf8_decode('7'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 179);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 184);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 7

//termina 8
$r = $resultado[7];
$temperatura8 = $r['temperatura'];
$turno8 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 190);
$pdf->Cell(5.5, 10, utf8_decode('8'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 190);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 195);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 8

//empieza 9
$r = $resultado[8];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 201);
$pdf->Cell(5.5, 10, utf8_decode('9'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 201);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 206);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 9

//empieza 10
$r = $resultado[9];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 212);
$pdf->Cell(5.5, 10, utf8_decode('10'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 212);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 217);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 10

//empieza 11
$r = $resultado[10];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 223);
$pdf->Cell(5.5, 10, utf8_decode('11'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 223);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 228);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 11

//empieza 12
$r = $resultado[11];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 234);
$pdf->Cell(5.5, 10, utf8_decode('12'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 234);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 239);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 12

//empieza 13
$r = $resultado[12];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 245);
$pdf->Cell(5.5, 10, utf8_decode('13'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 245);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 250);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 13

//empieza 14
$r = $resultado[13];
$temperatura9 = $r['temperatura'];
$turno9 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(10, 256);
$pdf->Cell(5.5, 10, utf8_decode('14'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(29.5, 256);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(29.5, 261);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 14

//empieza 15
$r = $resultado[14];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 102);
$pdf->Cell(5.5, 10, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->SetXY(96, 102);$pdf->Cell(38, 5, utf8_decode('Peso'), 1, 0, 'C');
$pdf->SetXY(96, 107);$pdf->Cell(10, 5, utf8_decode('Estomag'), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode('Piernas'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('Total'), 1, 1, 'C');

$pdf->SetXY(76.5, 113);
$pdf->Cell(5.5, 10, utf8_decode('15'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 113);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 118);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 10

//empieza 16
$r = $resultado[15];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 124);
$pdf->Cell(5.5, 10, utf8_decode('16'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 124);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 129);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 16

//empieza 17
$r = $resultado[16];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 135);
$pdf->Cell(5.5, 10, utf8_decode('17'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 135);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 140);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 17

//empieza 18
$r = $resultado[17];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 146);
$pdf->Cell(5.5, 10, utf8_decode('18'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 146);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 151);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 18

//empieza 19
$r = $resultado[18];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 157);
$pdf->Cell(5.5, 10, utf8_decode('19'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 157);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 162);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 19

//empieza 20
$r = $resultado[19];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 168);
$pdf->Cell(5.5, 10, utf8_decode('20'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 168);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 173);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 20

//empieza 21
$r = $resultado[20];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 179);
$pdf->Cell(5.5, 10, utf8_decode('21'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 179);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 184);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 21

//empieza 22
$r = $resultado[21];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 190);
$pdf->Cell(5.5, 10, utf8_decode('22'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 190);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 195);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 22

//empieza 23
$r = $resultado[22];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 201);
$pdf->Cell(5.5, 10, utf8_decode('23'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 201);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 206);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 23

//empieza 24
$r = $resultado[23];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 212);
$pdf->Cell(5.5, 10, utf8_decode('24'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 212);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 217);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 24

//empieza 25
$r = $resultado[24];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 223);
$pdf->Cell(5.5, 10, utf8_decode('25'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 223);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 228);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 25

//empieza 26
$r = $resultado[25];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 234);
$pdf->Cell(5.5, 10, utf8_decode('26'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 234);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 239);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 26

//empieza 27
$r = $resultado[26];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 245);
$pdf->Cell(5.5, 10, utf8_decode('27'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 245);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 250);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 27

//empieza 28
$r = $resultado[27];
$temperatura10 = $r['temperatura'];
$turno10 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(76.5, 256);
$pdf->Cell(5.5, 10, utf8_decode('28'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(96, 256);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(96, 261);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 28

//empieza 29
$r = $resultado[28];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 102);
$pdf->Cell(5.5, 10, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->SetXY(162, 102);$pdf->Cell(38, 5, utf8_decode('Peso'), 1, 0, 'C');
$pdf->SetXY(162, 107);$pdf->Cell(10, 5, utf8_decode('Estomag'), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode('Piernas'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('Total'), 1, 1, 'C');

//empieza 29
$pdf->SetXY(142.5, 113);
$pdf->Cell(5.5, 10, utf8_decode('29'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 113);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 118);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 29

//empieza 30
$r = $resultado[29];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 124);
$pdf->Cell(5.5, 10, utf8_decode('30'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 124);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 129);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 30

//empieza 31
$r = $resultado[30];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 135);
$pdf->Cell(5.5, 10, utf8_decode('31'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 135);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 140);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 31

//empieza 32
$r = $resultado[31];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 146);
$pdf->Cell(5.5, 10, utf8_decode('32'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 146);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 151);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 32

//empieza 33
$r = $resultado[32];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 157);
$pdf->Cell(5.5, 10, utf8_decode('33'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 157);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 162);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 33

//empieza 34
$r = $resultado[33];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 168);
$pdf->Cell(5.5, 10, utf8_decode('34'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 168);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 173);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 34

//empieza 35
$r = $resultado[34];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 179);
$pdf->Cell(5.5, 10, utf8_decode('35'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 179);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 184);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 35

//empieza 36
$r = $resultado[35];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 190);
$pdf->Cell(5.5, 10, utf8_decode('36'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 190);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 195);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 36

//empieza 37
$r = $resultado[36];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 201);
$pdf->Cell(5.5, 10, utf8_decode('37'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 201);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 206);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 37

//empieza 38
$r = $resultado[37];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 212);
$pdf->Cell(5.5, 10, utf8_decode('38'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 212);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 217);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 38

//empieza 39
$r = $resultado[38];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 223);
$pdf->Cell(5.5, 10, utf8_decode('39'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 223);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 228);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 39

//empieza 40
$r = $resultado[39];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 234);
$pdf->Cell(5.5, 10, utf8_decode('40'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 234);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 239);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 40

//empieza 41
$r = $resultado[40];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 245);
$pdf->Cell(5.5, 10, utf8_decode('41'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 245);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 250);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 41

//empieza 42
$r = $resultado[41];
$temperatura28 = $r['temperatura'];
$turno28 = $r['turno'];
$total=$r['estomago1'] + $r['estomago2'] + $r['piernas1'] + $r['piernas2'];
if($total == 0){$total='';}
$pdf->SetXY(142.5, 256);
$pdf->Cell(5.5, 10, utf8_decode('42'), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['turno']), 1, 0, 'C');
$pdf->Cell(7, 10, utf8_decode($r['temperatura']), 1, 0, 'C');
$pdf->SetXY(162, 256);$pdf->Cell(10, 5, utf8_decode($r['estomago1']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas1']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($total), 1, 1, 'C');
$pdf->SetXY(162, 261);$pdf->Cell(10, 5, utf8_decode($r['estomago2']), 1, 0, 'C');$pdf->Cell(10, 5, utf8_decode($r['piernas2']), 1, 0, 'C');

$subTotalEstomago = $r['estomago1']+$r['estomago2'];
$subtotalPierna = $r['piernas1']+$r['piernas2'];

$porcentajeEstomago = ($subTotalEstomago/$total)*100;
$porcentajePierna= ($subtotalPierna/$total)*100;

$totalPiernas = $totalPiernas+$subtotalPierna;
$totalEstomagos = $totalEstomagos+$subTotalEstomago;

$granTotal = $granTotal+$total;

$pdf->Cell(9, 5, number_format(round($porcentajeEstomago),0)."%", 1, 0, 'C');
$pdf->Cell(9, 5, number_format(round($porcentajePierna),0)."%", 1, 0, 'C');
$pdf->SetFont('Arial', '', 6);
//termina 42

$pdf->ln(5);
//$r['observaciones'];

$porcentajeTotalEstomago = ($totalEstomagos/$granTotal)*100;
$porcentajeTotalPierna= ($totalPiernas/$granTotal)*100;

$pdf->Cell(35, 5, utf8_decode('Total Estomagos: '.number_format($totalEstomagos, 2, ',', '.').' Kg.'), 1, 0, '');
$pdf->Cell(31, 5, utf8_decode('% Estomagos:'.number_format(round($porcentajeTotalEstomago),0).'%'), 1, 0, '');
$pdf->Cell(31, 5, utf8_decode('Total Piernas: '.number_format($totalPiernas, 2, ',', '.').' Kg.'), 1, 0, '');
$pdf->Cell(28, 5, utf8_decode('% Piernas:'.number_format(round($porcentajeTotalPierna),0).'%'), 1, 0, '');
$pdf->Cell(31, 5, utf8_decode('Total Kilos: '.$granTotal.' Kg.'), 1, 0, '');
$pdf->Cell(34, 5, utf8_decode('Peso Promedio Canal: '.number_format(round($granTotal/$canales)).' Kg.'), 1, 1, '');
$pdf->Cell(190, 5, utf8_decode('Observaciones: '.$rs2['observaciones']), 1, 1, '');

$pdf->Cell(25, 10, utf8_decode('Hora Inicial: '.$hora_i), 1, 0,'');
$pdf->Cell(25, 10, utf8_decode('Hora Final: '.$hora_f), 1, 0, '');
$pdf->Cell(25, 10, utf8_decode('Tiempo Total: '.$tiempoTranscurrido), 1, 0, '');
$pdf->Cell(15, 10,'Canales: '.$canales, 0, 0, '');
//$pdf->Cell(65, 10, utf8_decode('Firma Responsable:____________________________'), 0, 1, '');

if($rs2['responsable'] != 12345678){
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $firma = '../assets/img/firmas/'.$rs2['responsable'].'.jpg';
        $pdf->Image($firma, $x+15, $y+1, 30, 10);
        $pdf->ln(5);
        $pdf->Cell(90, 5, '', 0, 0, '');
        $pdf->Cell(50, 5, utf8_decode('Firma Responsable:_______________________'), 0, 0, '');
        $pdf->Cell(60, 5, utf8_decode('Verificado por:____________________________'), 0, 1, '');
}
ob_end_clean();
$pdf->Output('formato_recepcion.pdf', 'D'); // D
?>