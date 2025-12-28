<?php
session_start();
ini_set("display_errors", false);
ini_set('display_errors', '0');
error_reporting(0);
date_default_timezone_set("America/bogota");
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

$sqlg = "SELECT id_recepcion, fecharec, remision, destino, beneficio, canales, consecutivog, fechasac, lotep, ica, guiat, certificadoc, responsable, conductor, placa, cph1, cph2, cph3, cph4, cph5, chv1, chv2, chv3, chv4, ccoh1, ccoh2, ccoh3, ccoh4, ccoh5, ccoh6, ccoh7, ccoh8, ccoh9, ccoh10, tipo, observaciones, hora_inicial, hora_final, proveedor_cerdo.sede
FROM recepcion 
     LEFT JOIN proveedor_cerdo ON proveedor_cerdo.id = recepcion.proveedor_cerdo
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
$pdf->multiCell(100, 6, utf8_decode('FORMATO RECEPCIÓN CANALES PORCINAS            '), 1, 0, 'C');
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

$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(18, 5, utf8_decode('No Canales:'), 1, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(13, 5, utf8_decode($rs2['canales']), 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(23, 5, utf8_decode('Lote Beneficio:'), 1, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(25, 5, utf8_decode($rs2['lotep']), 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(25, 5, utf8_decode('Fecha Beneficio:'), 1, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(20, 5, utf8_decode($rs2['fechasac']), 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(18, 5, utf8_decode('Proveedor:'), 1, 0, '');
$pdf->SetFont('Arial', '', 8);$pdf->Cell(48, 5, utf8_decode($rs2['sede']), 1, 1, '');

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
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(34, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(18, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(16, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(19, 5,'', 1, 0, '');
$pdf->SetFont('Arial', 'B', 8);$pdf->Cell(21, 5,'', 1, 1, '');
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
$pdf->Cell(23, 5, utf8_decode('Contenido Estomacal:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark4 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18, 5, utf8_decode('Sin Materia Fecal:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark5 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 5, utf8_decode('Sin coágulos ni hematomas:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark6 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(14, 5, utf8_decode('Sin Riñones:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark7 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(12, 5, utf8_decode('Sin Leche:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark8 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(15, 5, utf8_decode('Sin Fracturas:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark9 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(16, 5, utf8_decode('Sin Dermatitis:'), 0, 0, '');
$pdf->SetFont('ZapfDingbats', '', 7);
$pdf->Cell(4, 5,  $checkmark10 , 0, 0, '');

$pdf->SetFont('Arial', 'B', 6);
$pdf->ln(7);

$pdf->Cell(190, 5, utf8_decode('PESOS CANALES'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$granTotal = 0;
//empieza 1
$r = $resultado[0];

$linea = 0;
        
/* for ($i = 0; $i < 10; $i++) {
    if ($i == 0 || ($i % 15) == 0) {
        $pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
        $pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
        $pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode('Peso Canal'), 1, 0, 'C');  
        if ($linea != 0) {
            $pdf->SetXY($pdf->GetX() + $linea, $pdf->GetY());
        }
    }
    $r = $resultado[$i];
    $peso = $r['estomago1'];
    $turno = $r['turno'];
    $temperatura = $r['temperatura'];
    $pdf->Cell(7.5, 5, utf8_decode($i + 1), 1, 0, 'C');
    $pdf->Cell(10, 5, utf8_decode($turno), 1, 0, 'C');
    $pdf->Cell(10, 5, utf8_decode($temperatura), 1, 0, 'C');
    $pdf->Cell(20, 5, utf8_decode($peso), 1, 0, 'C');
    if (($i + 1) % 15 == 0) {
        $pdf->SetXY($pdf->GetX() + 47.5, $pdf->GetY() - 80);
        $linea += 47.5;
    } else {
        $pdf->SetXY($pdf->GetX() + $linea, $pdf->GetY());
    }
} */

$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso Canal'), 1, 1, 'C');

// 1
$r = $resultado[0];
$peso1 = $r['estomago1'];
$turno1 = $r['turno'];
$temperatura1 = $r['temperatura'];
// 16
$r = $resultado[25];
$peso26 = $r['estomago1'];
$turno26 = $r['turno'];
$temperatura26 = $r['temperatura'];
// 31
$r = $resultado[50];
$peso51 = $r['estomago1'];
$turno51 = $r['turno'];
$temperatura51 = $r['temperatura'];
// 46
$r = $resultado[75];
$peso76 = $r['estomago1'];
$turno76 = $r['turno'];
$temperatura76 = $r['temperatura'];

$granTotal = $granTotal+$peso1+$peso26+$peso51+$peso76;

$pdf->Cell(7.5, 5, utf8_decode('1'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno1), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura1), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso1), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('26'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno26), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura26), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso26), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('51'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno51), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura51), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso51), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('76'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno76), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura76), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso76), 1, 1, 'C');
//termina 1

// 2
$r = $resultado[1];
$peso2 = $r['estomago1'];
$turno2 = $r['turno'];
$temperatura2 = $r['temperatura'];
// 17
$r = $resultado[26];
$peso27 = $r['estomago1'];
$turno27 = $r['turno'];
$temperatura27 = $r['temperatura'];
// 32
$r = $resultado[51];
$peso52 = $r['estomago1'];
$turno52 = $r['turno'];
$temperatura52 = $r['temperatura'];
// 47
$r = $resultado[76];
$peso77 = $r['estomago1'];
$turno77 = $r['turno'];
$temperatura77 = $r['temperatura'];

$granTotal = $granTotal+$peso2+$peso27+$peso52+$peso77;

$pdf->Cell(7.5, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno2), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura2), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso2), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('27'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno27), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura27), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso27), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('52'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno52), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura52), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso52), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('77'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno77), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura77), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso77), 1, 1, 'C');
//termina 2

// 3
$r = $resultado[2];
$peso3 = $r['estomago1'];
$turno3 = $r['turno'];
$temperatura3 = $r['temperatura'];
// 18
$r = $resultado[27];
$peso28 = $r['estomago1'];
$turno28 = $r['turno'];
$temperatura28 = $r['temperatura'];
// 33
$r = $resultado[52];
$peso53 = $r['estomago1'];
$turno53 = $r['turno'];
$temperatura53 = $r['temperatura'];
// 48
$r = $resultado[77];
$peso78 = $r['estomago1'];
$turno78 = $r['turno'];
$temperatura78 = $r['temperatura'];

$granTotal = $granTotal+$peso3+$peso28+$peso53+$peso78;

$pdf->Cell(7.5, 5, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno3), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura3), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso3), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('28'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno28), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura28), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso28), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('53'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno53), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura53), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso53), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('78'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno78), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura78), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso78), 1, 1, 'C');
//termina 3

// 4
$r = $resultado[3];
$peso4 = $r['estomago1'];
$turno4 = $r['turno'];
$temperatura4 = $r['temperatura'];
// 19
$r = $resultado[28];
$peso29 = $r['estomago1'];
$turno29 = $r['turno'];
$temperatura29 = $r['temperatura'];
// 34
$r = $resultado[53];
$peso54 = $r['estomago1'];
$turno54 = $r['turno'];
$temperatura54 = $r['temperatura'];
// 49
$r = $resultado[78];
$peso79 = $r['estomago1'];
$turno79 = $r['turno'];
$temperatura79 = $r['temperatura'];

$granTotal = $granTotal+$peso4+$peso29+$peso54+$peso79;

$pdf->Cell(7.5, 5, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno4), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura4), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso4), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('29'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno29), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura29), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso29), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('54'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno54), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura54), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso54), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('79'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno79), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura79), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso79), 1, 1, 'C');
//termina 4

// 5
$r = $resultado[4];
$peso5 = $r['estomago1'];
$turno5 = $r['turno'];
$temperatura5 = $r['temperatura'];
// 20
$r = $resultado[29];
$peso30 = $r['estomago1'];
$turno30 = $r['turno'];
$temperatura30 = $r['temperatura'];
// 35
$r = $resultado[54];
$peso55 = $r['estomago1'];
$turno55 = $r['turno'];
$temperatura55 = $r['temperatura'];
// 50
$r = $resultado[79];
$peso80 = $r['estomago1'];
$turno80 = $r['turno'];
$temperatura80 = $r['temperatura'];

$granTotal = $granTotal+$peso5+$peso30+$peso55+$peso80;

$pdf->Cell(7.5, 5, utf8_decode('5'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno5), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura5), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso5), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('30'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno30), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura30), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso30), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('55'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno55), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura55), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso55), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('80'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno80), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura80), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso80), 1, 1, 'C');
//termina 5

// 6
$r = $resultado[5];
$peso6 = $r['estomago1'];
$turno6 = $r['turno'];
$temperatura6 = $r['temperatura'];
// 21
$r = $resultado[30];
$peso31 = $r['estomago1'];
$turno31 = $r['turno'];
$temperatura31 = $r['temperatura'];
// 36
$r = $resultado[55];
$peso56 = $r['estomago1'];
$turno56 = $r['turno'];
$temperatura56 = $r['temperatura'];
// 51
$r = $resultado[80];
$peso81 = $r['estomago1'];
$turno81 = $r['turno'];
$temperatura81 = $r['temperatura'];

$granTotal = $granTotal+$peso6+$peso31+$peso56+$peso81;

$pdf->Cell(7.5, 5, utf8_decode('6'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno6), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura6), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso6), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('31'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno31), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura31), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso31), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('56'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno56), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura56), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso56), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('81'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno81), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura81), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso81), 1, 1, 'C');
//termina 6

// 7
$r = $resultado[6];
$peso7 = $r['estomago1'];
$turno7 = $r['turno'];
$temperatura7 = $r['temperatura'];
// 22
$r = $resultado[31];
$peso32 = $r['estomago1'];
$turno32 = $r['turno'];
$temperatura32 = $r['temperatura'];
// 37
$r = $resultado[56];
$peso57 = $r['estomago1'];
$turno57 = $r['turno'];
$temperatura57 = $r['temperatura'];
// 52
$r = $resultado[81];
$peso82 = $r['estomago1'];
$turno82 = $r['turno'];
$temperatura82 = $r['temperatura'];

$granTotal = $granTotal+$peso7+$peso32+$peso57+$peso82;

$pdf->Cell(7.5, 5, utf8_decode('7'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno7), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura7), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso7), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('32'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno32), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura32), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso32), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('57'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno57), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura57), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso57), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('82'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno82), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura82), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso82), 1, 1, 'C');
//termina 7

// 8
$r = $resultado[7];
$peso8 = $r['estomago1'];
$turno8 = $r['turno'];
$temperatura8 = $r['temperatura'];
// 23
$r = $resultado[32];
$peso33 = $r['estomago1'];
$turno33 = $r['turno'];
$temperatura33 = $r['temperatura'];
// 38
$r = $resultado[57];
$peso58 = $r['estomago1'];
$turno58 = $r['turno'];
$temperatura58 = $r['temperatura'];
// 53
$r = $resultado[82];
$peso83 = $r['estomago1'];
$turno83 = $r['turno'];
$temperatura83 = $r['temperatura'];

$granTotal = $granTotal+$peso8+$peso33+$peso58+$peso83;

$pdf->Cell(7.5, 5, utf8_decode('8'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno8), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura8), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso8), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('33'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno33), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura33), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso33), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('58'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno58), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura58), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso58), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('83'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno83), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura83), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso83), 1, 1, 'C');
//termina 8

// 9
$r = $resultado[8];
$peso9 = $r['estomago1'];
$turno9 = $r['turno'];
$temperatura9 = $r['temperatura'];
// 24
$r = $resultado[33];
$peso34 = $r['estomago1'];
$turno34 = $r['turno'];
$temperatura34 = $r['temperatura'];
// 39
$r = $resultado[58];
$peso59 = $r['estomago1'];
$turno59 = $r['turno'];
$temperatura59 = $r['temperatura'];
// 54
$r = $resultado[83];
$peso84 = $r['estomago1'];
$turno84 = $r['turno'];
$temperatura84 = $r['temperatura'];

$granTotal = $granTotal+$peso9+$peso34+$peso59+$peso84;

$pdf->Cell(7.5, 5, utf8_decode('9'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno9), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura9), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso9), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('34'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno34), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura34), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso34), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('59'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno59), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura59), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso59), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('84'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno84), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura84), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso84), 1, 1, 'C');
//termina 9

// 10
$r = $resultado[9];
$peso10 = $r['estomago1'];
$turno10 = $r['turno'];
$temperatura10 = $r['temperatura'];
// 25
$r = $resultado[34];
$peso35 = $r['estomago1'];
$turno35 = $r['turno'];
$temperatura35 = $r['temperatura'];
// 40
$r = $resultado[59];
$peso60 = $r['estomago1'];
$turno60 = $r['turno'];
$temperatura60 = $r['temperatura'];
// 55
$r = $resultado[84];
$peso85 = $r['estomago1'];
$turno85 = $r['turno'];
$temperatura85 = $r['temperatura'];

$granTotal = $granTotal+$peso10+$peso35+$peso60+$peso85;

$pdf->Cell(7.5, 5, utf8_decode('10'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno10), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura10), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso10), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('35'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno35), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura35), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso35), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('60'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno60), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura60), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso60), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('85'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno85), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura85), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso85), 1, 1, 'C');
//termina 10

// 11
$r = $resultado[10];
$peso11 = $r['estomago1'];
$turno11 = $r['turno'];
$temperatura11 = $r['temperatura'];
// 26
$r = $resultado[35];
$peso36 = $r['estomago1'];
$turno36 = $r['turno'];
$temperatura36 = $r['temperatura'];
// 41
$r = $resultado[60];
$peso61 = $r['estomago1'];
$turno61 = $r['turno'];
$temperatura61 = $r['temperatura'];
// 56
$r = $resultado[85];
$peso86 = $r['estomago1'];
$turno86 = $r['turno'];
$temperatura86 = $r['temperatura'];

$granTotal = $granTotal+$peso11+$peso36+$peso61+$peso86;

$pdf->Cell(7.5, 5, utf8_decode('11'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno11), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura11), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso11), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('36'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno36), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura36), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso36), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('61'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno61), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura61), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso61), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('86'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno86), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura86), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso86), 1, 1, 'C');
//termina 11

// 12
$r = $resultado[11];
$peso12 = $r['estomago1'];
$turno12 = $r['turno'];
$temperatura12 = $r['temperatura'];
// 27
$r = $resultado[36];
$peso37 = $r['estomago1'];
$turno37 = $r['turno'];
$temperatura37 = $r['temperatura'];
// 42
$r = $resultado[61];
$peso62 = $r['estomago1'];
$turno62 = $r['turno'];
$temperatura62 = $r['temperatura'];
// 57
$r = $resultado[86];
$peso87 = $r['estomago1'];
$turno87 = $r['turno'];
$temperatura87 = $r['temperatura'];

$granTotal = $granTotal+$peso12+$peso37+$peso62+$peso87;

$pdf->Cell(7.5, 5, utf8_decode('12'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno12), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura12), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso12), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('37'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno37), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura37), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso37), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('62'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno62), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura62), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso62), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('87'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno87), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura87), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso87), 1, 1, 'C');
//termina 12

// 13
$r = $resultado[12];
$peso13 = $r['estomago1'];
$turno13 = $r['turno'];
$temperatura13 = $r['temperatura'];
// 28
$r = $resultado[37];
$peso38 = $r['estomago1'];
$turno38 = $r['turno'];
$temperatura38 = $r['temperatura'];
// 43
$r = $resultado[62];
$peso63 = $r['estomago1'];
$turno63 = $r['turno'];
$temperatura63 = $r['temperatura'];
// 58
$r = $resultado[87];
$peso88 = $r['estomago1'];
$turno88 = $r['turno'];
$temperatura88 = $r['temperatura'];

$granTotal = $granTotal+$peso13+$peso38+$peso63+$peso88;

$pdf->Cell(7.5, 5, utf8_decode('13'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno13), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura13), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso13), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('38'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno38), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura38), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso38), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('63'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno63), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura63), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso63), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('88'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno88), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura88), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso88), 1, 1, 'C');
//termina 13

// 14
$r = $resultado[13];
$peso14 = $r['estomago1'];
$turno14 = $r['turno'];
$temperatura14 = $r['temperatura'];
// 29
$r = $resultado[38];
$peso39 = $r['estomago1'];
$turno39 = $r['turno'];
$temperatura39 = $r['temperatura'];
// 44
$r = $resultado[63];
$peso64 = $r['estomago1'];
$turno64 = $r['turno'];
$temperatura64 = $r['temperatura'];
// 59
$r = $resultado[88];
$peso89 = $r['estomago1'];
$turno89 = $r['turno'];
$temperatura89 = $r['temperatura'];

$granTotal = $granTotal+$peso14+$peso39+$peso64+$peso89;

$pdf->Cell(7.5, 5, utf8_decode('14'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno14), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura14), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso14), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('39'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno39), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura39), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso39), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('64'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno64), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura64), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso64), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('89'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno89), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura89), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso89), 1, 1, 'C');
//termina 14

// 15
$r = $resultado[14];
$peso15 = $r['estomago1'];
$turno15 = $r['turno'];
$temperatura15 = $r['temperatura'];
// 30
$r = $resultado[39];
$peso40 = $r['estomago1'];
$turno40 = $r['turno'];
$temperatura40 = $r['temperatura'];
// 45
$r = $resultado[64];
$peso65 = $r['estomago1'];
$turno65 = $r['turno'];
$temperatura65 = $r['temperatura'];
// 60
$r = $resultado[89];
$peso90 = $r['estomago1'];
$turno90 = $r['turno'];
$temperatura90 = $r['temperatura'];

$granTotal = $granTotal+$peso15+$peso40+$peso65+$peso90;

$pdf->Cell(7.5, 5, utf8_decode('15'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno15), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura15), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso15), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('40'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno40), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura40), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso40), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('65'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno65), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura65), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso65), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('90'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno90), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura90), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso90), 1, 1, 'C');


// 61
$r = $resultado[15];
$peso16 = $r['estomago1'];
$turno16 = $r['turno'];
$temperatura16 = $r['temperatura'];
// 71
$r = $resultado[40];
$peso41 = $r['estomago1'];
$turno41 = $r['turno'];
$temperatura41 = $r['temperatura'];
// 81
$r = $resultado[65];
$peso66 = $r['estomago1'];
$turno66 = $r['turno'];
$temperatura66 = $r['temperatura'];
// 91
$r = $resultado[90];
$peso91 = $r['estomago1'];
$turno91 = $r['turno'];
$temperatura91 = $r['temperatura'];

$granTotal = $granTotal+$peso16+$peso41+$peso66+$peso91;

$pdf->Cell(7.5, 5, utf8_decode('16'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno16), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura16), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso16), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('41'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno41), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura41), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso41), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('66'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno66), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura66), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso66), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('91'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno91), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura91), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso91), 1, 1, 'C');

$r = $resultado[16];
$peso17 = $r['estomago1'];
$turno17 = $r['turno'];
$temperatura17 = $r['temperatura'];
// 72
$r = $resultado[41];
$peso42 = $r['estomago1'];
$turno42 = $r['turno'];
$temperatura42 = $r['temperatura'];
// 82
$r = $resultado[66];
$peso67 = $r['estomago1'];
$turno67 = $r['turno'];
$temperatura67 = $r['temperatura'];
// 92
$r = $resultado[91];
$peso92 = $r['estomago1'];
$turno92 = $r['turno'];
$temperatura92 = $r['temperatura'];

$granTotal = $granTotal+$peso17+$peso42+$peso67+$peso92;

$pdf->Cell(7.5, 5, utf8_decode('17'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno17), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura17), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso17), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('42'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno42), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura42), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso42), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('67'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno67), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura67), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso67), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('92'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno92), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura92), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso92), 1, 1, 'C');

$r = $resultado[17];
$peso18 = $r['estomago1'];
$turno18 = $r['turno'];
$temperatura18 = $r['temperatura'];
// 73
$r = $resultado[42];
$peso43 = $r['estomago1'];
$turno43 = $r['turno'];
$temperatura43 = $r['temperatura'];
// 83
$r = $resultado[67];
$peso68 = $r['estomago1'];
$turno68 = $r['turno'];
$temperatura68 = $r['temperatura'];
// 93
$r = $resultado[92];
$peso93 = $r['estomago1'];
$turno93 = $r['turno'];
$temperatura93 = $r['temperatura'];

$granTotal = $granTotal+$peso18+$peso43+$peso68+$peso93;

$pdf->Cell(7.5, 5, utf8_decode('18'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno18), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura18), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso18), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('43'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno43), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura43), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso43), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('68'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno68), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura68), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso68), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('93'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno93), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura93), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso93), 1, 1, 'C');

$r = $resultado[18];
$peso19 = $r['estomago1'];
$turno19 = $r['turno'];
$temperatura19 = $r['temperatura'];
// 74
$r = $resultado[43];
$peso44 = $r['estomago1'];
$turno44 = $r['turno'];
$temperatura44 = $r['temperatura'];
// 84
$r = $resultado[68];
$peso69 = $r['estomago1'];
$turno69 = $r['turno'];
$temperatura69 = $r['temperatura'];
// 94
$r = $resultado[93];
$peso94 = $r['estomago1'];
$turno94 = $r['turno'];
$temperatura94 = $r['temperatura'];

$granTotal = $granTotal+$peso19+$peso44+$peso69+$peso94;

$pdf->Cell(7.5, 5, utf8_decode('19'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno19), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura19), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso19), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('44'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno44), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura44), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso44), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('69'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno69), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura69), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso69), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('94'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno94), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura94), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso94), 1, 1, 'C');

$r = $resultado[19];
$peso20 = $r['estomago1'];
$turno20 = $r['turno'];
$temperatura20 = $r['temperatura'];
// 75
$r = $resultado[44];
$peso45 = $r['estomago1'];
$turno45 = $r['turno'];
$temperatura45 = $r['temperatura'];
// 85
$r = $resultado[69];
$peso70 = $r['estomago1'];
$turno70 = $r['turno'];
$temperatura70 = $r['temperatura'];
// 60
$r = $resultado[94];
$peso95 = $r['estomago1'];
$turno95 = $r['turno'];
$temperatura95 = $r['temperatura'];

$granTotal = $granTotal+$peso20+$peso45+$peso70+$peso95;

$pdf->Cell(7.5, 5, utf8_decode('20'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno20), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura20), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso20), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('45'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno45), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura45), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso45), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('70'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno70), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura70), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso70), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('95'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno95), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura95), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso95), 1, 1, 'C');

$r = $resultado[20];
$peso21 = $r['estomago1'];
$turno21 = $r['turno'];
$temperatura21 = $r['temperatura'];
// 71
$r = $resultado[45];
$peso46 = $r['estomago1'];
$turno46 = $r['turno'];
$temperatura46 = $r['temperatura'];
// 45
$r = $resultado[70];
$peso71 = $r['estomago1'];
$turno71 = $r['turno'];
$temperatura71 = $r['temperatura'];
// 60
$r = $resultado[95];
$peso96 = $r['estomago1'];
$turno96 = $r['turno'];
$temperatura96 = $r['temperatura'];

$granTotal = $granTotal+$peso21+$peso46+$peso71+$peso96;

$pdf->Cell(7.5, 5, utf8_decode('21'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno21), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura21), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso21), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('46'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno46), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura46), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso46), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('71'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno71), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura71), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso71), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('96'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno96), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura96), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso96), 1, 1, 'C');

$r = $resultado[21];
$peso22 = $r['estomago1'];
$turno22 = $r['turno'];
$temperatura22 = $r['temperatura'];
// 30
$r = $resultado[46];
$peso47 = $r['estomago1'];
$turno47 = $r['turno'];
$temperatura47 = $r['temperatura'];
// 45
$r = $resultado[71];
$peso72 = $r['estomago1'];
$turno72 = $r['turno'];
$temperatura72 = $r['temperatura'];
// 60
$r = $resultado[96];
$peso97 = $r['estomago1'];
$turno97 = $r['turno'];
$temperatura97 = $r['temperatura'];

$granTotal = $granTotal+$peso22+$peso47+$peso72+$peso97;

$pdf->Cell(7.5, 5, utf8_decode('22'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno22), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura22), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso22), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('47'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno47), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura47), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso47), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('72'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno72), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura72), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso72), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('97'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno97), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura97), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso97), 1, 1, 'C');

$r = $resultado[22];
$peso23 = $r['estomago1'];
$turno23 = $r['turno'];
$temperatura23 = $r['temperatura'];
// 30
$r = $resultado[47];
$peso48 = $r['estomago1'];
$turno48 = $r['turno'];
$temperatura48 = $r['temperatura'];
// 45
$r = $resultado[72];
$peso73 = $r['estomago1'];
$turno73 = $r['turno'];
$temperatura73 = $r['temperatura'];
// 60
$r = $resultado[97];
$peso98 = $r['estomago1'];
$turno98 = $r['turno'];
$temperatura98 = $r['temperatura'];

$granTotal = $granTotal+$peso23+$peso48+$peso73+$peso98;

$pdf->Cell(7.5, 5, utf8_decode('23'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno23), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura23), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso23), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('48'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno48), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura48), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso48), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('73'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno73), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura73), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso73), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('98'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno98), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura98), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso98), 1, 1, 'C');

$r = $resultado[23];
$peso24 = $r['estomago1'];
$turno24 = $r['turno'];
$temperatura24 = $r['temperatura'];
// 30
$r = $resultado[48];
$peso49 = $r['estomago1'];
$turno49 = $r['turno'];
$temperatura49 = $r['temperatura'];
// 45
$r = $resultado[73];
$peso74 = $r['estomago1'];
$turno74 = $r['turno'];
$temperatura74 = $r['temperatura'];
// 60
$r = $resultado[98];
$peso99 = $r['estomago1'];
$turno99 = $r['turno'];
$temperatura99 = $r['temperatura'];

$granTotal = $granTotal+$peso24+$peso49+$peso74+$peso99;

$pdf->Cell(7.5, 5, utf8_decode('24'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno24), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura24), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso24), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('49'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno49), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura49), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso49), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('74'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno74), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura74), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso74), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('99'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno99), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura99), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso99), 1, 1, 'C');

$r = $resultado[24];
$peso25 = $r['estomago1'];
$turno25 = $r['turno'];
$temperatura25 = $r['temperatura'];
// 30
$r = $resultado[49];
$peso50 = $r['estomago1'];
$turno50 = $r['turno'];
$temperatura50 = $r['temperatura'];
// 45
$r = $resultado[74];
$peso75 = $r['estomago1'];
$turno75 = $r['turno'];
$temperatura75 = $r['temperatura'];
// 60
$r = $resultado[99];
$peso100 = $r['estomago1'];
$turno100 = $r['turno'];
$temperatura100 = $r['temperatura'];

$granTotal = $granTotal+$peso25+$peso50+$peso75+$peso100;

$pdf->Cell(7.5, 5, utf8_decode('25'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno25), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura25), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso25), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('50'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno50), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura50), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso50), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('75'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno75), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura75), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso75), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('100'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno100), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($temperatura100), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode($peso100), 1, 1, 'C');

$pdf->ln(5);

$pdf->Cell(31, 5, utf8_decode('Total Kilos: '.$granTotal.' Kg.'), 1, 0, '');
$pdf->Cell(34, 5, utf8_decode('Peso Promedio Canal: '.number_format(round($granTotal/$canales)).' Kg.'), 1, 1, '');
$pdf->ln(5);
$pdf->Cell(190, 5, utf8_decode('Observaciones: '.$rs2['observaciones']), 0, 1, '');

$pdf->Cell(25, 10, utf8_decode('Hora Inicial: '.$hora_i), 0, 0,'');
$pdf->Cell(25, 10, utf8_decode('Hora Final: '.$hora_f), 0, 0, '');
$pdf->Cell(25, 10, utf8_decode('Tiempo Total: '.$tiempoTranscurrido), 0, 0, '');
$pdf->Cell(15, 10,'Canales: '.$canales, 0, 0, '');

//$pdf->Cell(65, 10, utf8_decode('Firma Responsable:____________________________'), 0, 1, '');

/* if($rs2['responsable'] != 12345678){
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $firma = '../assets/img/firmas/'.$rs2['responsable'].'.jpg';
        $pdf->Image($firma, $x+25, $y+1, 30, 10);
        $pdf->ln(5);
        $pdf->Cell(130, 5, '', 0, 0, '');
        $pdf->Cell(60, 5, utf8_decode('Firma Responsable:____________________________'), 0, 1, '');
} */

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
$pdf->Output('formato_recepcion.pdf', 'D');
?>