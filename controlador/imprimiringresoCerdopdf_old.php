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

$sqlg = "SELECT fecharec, remision, destino, beneficio, canales, consecutivog, fechasac, ica, guiat, certificadoc, responsable, conductor, placa, 
                cph1, cph2, cph3, cph4, cph5, chv1, chv2, chv3, chv4, ccoh1, ccoh2, ccoh3, ccoh4, ccoh5, ccoh6, ccoh7, ccoh8, ccoh9, ccoh10, tipo 
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
          FROM conductores 
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
$pdf->Cell(20, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('FTO-PDM-04'), 1, 1, 'C');
$pdf->ln(3);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(150, 5, utf8_decode('INFORMACIÓN RECEPCIÓN'), 1, 0, 'C');
$pdf->Cell(40, 5, utf8_decode('LOTE PLANTA DM'), 1, 1, 'C');

$pdf->Cell(42, 5, utf8_decode('Fecha Recepción'), 1, 0, 'C');
$pdf->Cell(40, 5, utf8_decode('Nro Remisión'), 1, 0, 'C');
$pdf->Cell(68, 5, utf8_decode('Sede Destino'), 1, 0, 'C');
$pdf->Cell(40, 10, utf8_decode(''), 1, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->ln(-5);
$pdf->Cell(42, 5, utf8_decode($rs2['fecharec']), 1, 0, 'C');
$pdf->Cell(40, 5, utf8_decode($rs2['remision']), 1, 0, 'C');
$pdf->Cell(68, 5, utf8_decode($rs3['empresa']." - ".$rs3['sede']), 1, 1, 'C');
$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('INFORMACIÓN PLANTA DE BENEFICIO'), 1, 1, 'C');

$pdf->Cell(63, 5, utf8_decode('Nombre o Razón Social'), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode('Departamento'), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode('Municipio'), 1, 1, 'C');
$pdf->Cell(63, 5, utf8_decode($rs30['sede']), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode("VALLE"), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode($rs30['municipio']), 1, 1, 'C');
$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('INFORMACIÓN PRODUCTO'), 1, 1, 'C');

$pdf->Cell(63, 5, utf8_decode('No Canales'), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode('Lote'), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode('Fecha Sacrificio'), 1, 1, 'C');
$pdf->Cell(63, 5, utf8_decode($rs2['canales']), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode($rs2['consecutivog']), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode($rs2['fechasac']), 1, 1, 'C');
$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('INFORMACIÓN GUÍAS DE TRANSPORTE Y CERTIFCADO DE CALIDAD'), 1, 1, 'C');

$pdf->Cell(63, 5, utf8_decode('No Guia de ICA'), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode('No Guia de Transporte de Carne en Canal'), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode('Certificado de Calidad'), 1, 1, 'C');
$pdf->Cell(63, 5, utf8_decode($rs2['ica']), 1, 0, 'C');
$pdf->Cell(64, 5, utf8_decode($rs2['guiat']), 1, 0, 'C');
$pdf->Cell(63, 5, utf8_decode($rs2['certificadoc']), 1, 1, 'C');
$pdf->ln(2);

$pdf->Cell(190, 5, utf8_decode('INFORMACIÓN CONDUCTOR Y VEHÍCULO DE TRANSPORTE'), 1, 1, 'C');

$pdf->Cell(120, 5, utf8_decode('Nombres y Apellidos'), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode('Placa Vehiculo'), 1, 1, 'C');
$pdf->Cell(120, 5, utf8_decode($rs40['nombres']), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode($rs2['placa']), 1, 1, 'C');
$pdf->ln(2);

$pdf->Cell(190, 5, utf8_decode('CUMPLIMIENTO PRÁCTICAS HIGIÉNICAS PERSONAL MANIPULADOR'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(38, 5, utf8_decode('Usa cofia y tapabocas'), 1, 0, 'C');
$pdf->Cell(38, 5, utf8_decode('Capa limpia y en buen estado'), 1, 0, 'C');
$pdf->Cell(38, 5, utf8_decode('Botas limpias y en buen estado'), 1, 0, 'C');
$pdf->Cell(38, 5, utf8_decode('Uñas cortas limpias y sin esmalte'), 1, 0, 'C');
$pdf->Cell(38, 5, utf8_decode('No usa accesorios'), 1, 1, 'C');

$rs2['cph1'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['cph2'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['cph3'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['cph4'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['cph5'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 1, 'C');
$pdf->ln(2);

$pdf->Cell(190, 5, utf8_decode('CUMPLIMIENTO CONDICIONES HIGIÉNICO SANITARIAS DEL VEHÍCULO DE TRANSPORTE'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(38, 5, utf8_decode('Temperatura Unidad de Transporte'), 1, 0, 'C');
$pdf->Cell(38, 5, utf8_decode('¿Vehículo limpio y ausente de plagas?'), 1, 0, 'C');
$pdf->Cell(62, 5, utf8_decode('¿Se transportan sustancias químicas junto con las canales?'), 1, 0, 'C');
$pdf->Cell(52, 5, utf8_decode('¿Se transporta canales de una sola especie?'), 1, 1, 'C');

$pdf->Cell(38, 5, utf8_decode($rs2['chv1']."°"), 1, 0, 'C');
$rs2['chv2'] == 1 ? $pdf->Cell(38, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(38, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['chv3'] == 1 ? $pdf->Cell(62, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(62, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['chv4'] == 1 ? $pdf->Cell(52, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(52, 5, utf8_decode('NO'), 1, 1, 'C');
$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('CUMPLIMIENTO CARACTERISTICAS ORGANOLÉPTICAS E HIGIÉNICO SANITARIAS DE LAS CANALES'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 6);

$pdf->Cell(12, 5, utf8_decode('Olor'), 1, 0, 'C');
$pdf->Cell(12, 5, utf8_decode('Color'), 1, 0, 'C');
$pdf->Cell(12, 5, utf8_decode('Sin Pelo'), 1, 0, 'C');
$pdf->Cell(22, 5, utf8_decode('Sin contenido Rumial'), 1, 0, 'C');
$pdf->Cell(21, 5, utf8_decode('Sin Materia Fecal'), 1, 0, 'C');
$pdf->Cell(21, 5, utf8_decode('Sin Medula Espinal'), 1, 0, 'C');
$pdf->Cell(21, 5, utf8_decode('Sin Nuches'), 1, 0, 'C');
$pdf->Cell(21, 5, utf8_decode('Sin Garrapatas'), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode('Sin Coagulos ni Hematomas'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('Sin Leche'), 1, 1, 'C');

$rs2['ccoh1'] == 1 ? $pdf->Cell(12, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(12, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh2'] == 1 ? $pdf->Cell(12, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(12, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh3'] == 1 ? $pdf->Cell(12, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(12, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh4'] == 1 ? $pdf->Cell(22, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(22, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh5'] == 1 ? $pdf->Cell(21, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(21, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh6'] == 1 ? $pdf->Cell(21, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(21, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh7'] == 1 ? $pdf->Cell(21, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(21, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh8'] == 1 ? $pdf->Cell(21, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(21, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh9'] == 1 ? $pdf->Cell(30, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(30, 5, utf8_decode('NO'), 1, 0, 'C');
$rs2['ccoh10'] == 1 ? $pdf->Cell(18, 5, utf8_decode('SI'), 1, 0, 'C'):$pdf->Cell(18, 5, utf8_decode('NO'), 1, 1, 'C');
$pdf->ln(7);

$pdf->Cell(190, 5, utf8_decode('PESOS CANALES'), 1, 1, 'C');

$granTotal = 0;

$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode('Peso Canal'), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode('Peso Canal'), 1, 1, 'C');

// 1
$r = $resultado[0];
$peso1 = $r['estomago1'];
$turno1 = $r['turno'];
$temperatura1 = $r['temperatura'];
// 10
$r = $resultado[9];
$peso10 = $r['estomago1'];
$turno10 = $r['turno'];
$temperatura10 = $r['temperatura'];
// 19
$r = $resultado[18];
$peso19 = $r['estomago1'];
$turno19 = $r['turno'];
$temperatura19 = $r['temperatura'];
// 28
$r = $resultado[27];
$peso28 = $r['estomago1'];
$turno28 = $r['turno'];
$temperatura28 = $r['temperatura'];

$granTotal = $granTotal+$peso1+$peso10+$peso19+$peso28;

$pdf->Cell(7.5, 5, utf8_decode('1'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno1), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso1), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('10'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno10), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso10), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('19'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno19), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso19), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('28'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno28), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso28), 1, 1, 'C');
//termina 1

// 2
$r = $resultado[1];
$peso2 = $r['estomago1'];
$turno2 = $r['turno'];
$temperatura2 = $r['temperatura'];
// 11
$r = $resultado[10];
$peso11 = $r['estomago1'];
$turno11 = $r['turno'];
$temperatura11 = $r['temperatura'];
// 20
$r = $resultado[19];
$peso20 = $r['estomago1'];
$turno20 = $r['turno'];
$temperatura20 = $r['temperatura'];
// 29
$r = $resultado[28];
$peso29 = $r['estomago1'];
$turno29 = $r['turno'];
$temperatura29 = $r['temperatura'];

$granTotal = $granTotal+$peso2+$peso11+$peso20+$peso29;

$pdf->Cell(7.5, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno2), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso2), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('11'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno11), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso11), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('20'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno20), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso20), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('29'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno29), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso29), 1, 1, 'C');
//termina 2

// 3
$r = $resultado[2];
$peso3 = $r['estomago1'];
$turno3 = $r['turno'];
$temperatura3 = $r['temperatura'];
// 12
$r = $resultado[11];
$peso12 = $r['estomago1'];
$turno12 = $r['turno'];
$temperatura12 = $r['temperatura'];
// 21
$r = $resultado[20];
$peso21 = $r['estomago1'];
$turno21 = $r['turno'];
$temperatura21 = $r['temperatura'];
// 30
$r = $resultado[29];
$peso30 = $r['estomago1'];
$turno30 = $r['turno'];
$temperatura30 = $r['temperatura'];

$granTotal = $granTotal+$peso3+$peso12+$peso21+$peso30;

$pdf->Cell(7.5, 5, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno3), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso3), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('12'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno12), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso12), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('21'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno21), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso21), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('30'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno30), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso30), 1, 1, 'C');
//termina 3

// 4
$r = $resultado[3];
$peso4 = $r['estomago1'];
$turno4 = $r['turno'];
$temperatura4 = $r['temperatura'];
// 13
$r = $resultado[12];
$peso13 = $r['estomago1'];
$turno13 = $r['turno'];
$temperatura13 = $r['temperatura'];
// 22
$r = $resultado[21];
$peso22 = $r['estomago1'];
$turno22 = $r['turno'];
$temperatura22 = $r['temperatura'];
// 31
$r = $resultado[30];
$peso31 = $r['estomago1'];
$turno31 = $r['turno'];
$temperatura31 = $r['temperatura'];

$granTotal = $granTotal+$peso4+$peso13+$peso22+$peso31;

$pdf->Cell(7.5, 5, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno4), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso4), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('13'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno13), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso13), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('22'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno22), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso22), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('31'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno31), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso31), 1, 1, 'C');
//termina 4

// 5
$r = $resultado[4];
$peso5 = $r['estomago1'];
$turno5 = $r['turno'];
$temperatura5 = $r['temperatura'];
// 14
$r = $resultado[13];
$peso14 = $r['estomago1'];
$turno14 = $r['turno'];
$temperatura14 = $r['temperatura'];
// 23
$r = $resultado[22];
$peso23 = $r['estomago1'];
$turno23 = $r['turno'];
$temperatura23 = $r['temperatura'];
// 32
$r = $resultado[31];
$peso32 = $r['estomago1'];
$turno32 = $r['turno'];
$temperatura32 = $r['temperatura'];

$granTotal = $granTotal+$peso5+$peso14+$peso23+$peso32;

$pdf->Cell(7.5, 5, utf8_decode('5'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno5), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso5), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('14'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno14), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso14), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('23'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno23), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso23), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('32'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno32), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso32), 1, 1, 'C');
//termina 5

// 6
$r = $resultado[5];
$peso6 = $r['estomago1'];
$turno6 = $r['turno'];
$temperatura6 = $r['temperatura'];
// 15
$r = $resultado[14];
$peso15 = $r['estomago1'];
$turno15 = $r['turno'];
$temperatura15 = $r['temperatura'];
// 24
$r = $resultado[23];
$peso24 = $r['estomago1'];
$turno24 = $r['turno'];
$temperatura24 = $r['temperatura'];
// 33
$r = $resultado[32];
$peso33 = $r['estomago1'];
$turno33 = $r['turno'];
$temperatura33 = $r['temperatura'];

$granTotal = $granTotal+$peso6+$peso15+$peso24+$peso33;

$pdf->Cell(7.5, 5, utf8_decode('6'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno6), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso6), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('15'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno15), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso15), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('24'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno24), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso24), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('33'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno33), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso33), 1, 1, 'C');
//termina 6

// 7
$r = $resultado[6];
$peso7 = $r['estomago1'];
$turno7 = $r['turno'];
$temperatura7 = $r['temperatura'];
// 16
$r = $resultado[15];
$peso16 = $r['estomago1'];
$turno16 = $r['turno'];
$temperatura16 = $r['temperatura'];
// 25
$r = $resultado[24];
$peso25 = $r['estomago1'];
$turno25 = $r['turno'];
$temperatura25 = $r['temperatura'];
// 34
$r = $resultado[33];
$peso34 = $r['estomago1'];
$turno34 = $r['turno'];
$temperatura34 = $r['temperatura'];

$granTotal = $granTotal+$peso7+$peso16+$peso25+$peso34;

$pdf->Cell(7.5, 5, utf8_decode('7'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno7), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso7), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('16'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno16), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso16), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('25'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno25), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso25), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('34'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno34), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso34), 1, 1, 'C');
//termina 7

// 8
$r = $resultado[7];
$peso8 = $r['estomago1'];
$turno8 = $r['turno'];
$temperatura8 = $r['temperatura'];
// 17
$r = $resultado[16];
$peso17 = $r['estomago1'];
$turno17 = $r['turno'];
$temperatura17 = $r['temperatura'];
// 26
$r = $resultado[25];
$peso26 = $r['estomago1'];
$turno26 = $r['turno'];
$temperatura26 = $r['temperatura'];
// 35
$r = $resultado[34];
$peso35 = $r['estomago1'];
$turno35 = $r['turno'];
$temperatura35 = $r['temperatura'];

$granTotal = $granTotal+$peso8+$peso17+$peso26+$peso35;

$pdf->Cell(7.5, 5, utf8_decode('8'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno8), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso8), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('17'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno17), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso17), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('26'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno26), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso26), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('35'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno35), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso35), 1, 1, 'C');
//termina 8

// 9
$r = $resultado[8];
$peso9 = $r['estomago1'];
$turno9 = $r['turno'];
$temperatura9 = $r['temperatura'];
// 18
$r = $resultado[17];
$peso18 = $r['estomago1'];
$turno18 = $r['turno'];
$temperatura18 = $r['temperatura'];
// 27
$r = $resultado[26];
$peso27 = $r['estomago1'];
$turno27 = $r['turno'];
$temperatura27 = $r['temperatura'];
// 36
$r = $resultado[35];
$peso36 = $r['estomago1'];
$turno36 = $r['turno'];
$temperatura36 = $r['temperatura'];

$granTotal = $granTotal+$peso9+$peso18+$peso27+$peso36;

$pdf->Cell(7.5, 5, utf8_decode('9'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno9), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso9), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('18'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno18), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso18), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('27'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno27), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso27), 1, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('36'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno36), 1, 0, 'C');
$pdf->Cell(30, 5, utf8_decode($peso36), 1, 1, 'C');
//termina 9

$pdf->ln(3);

$pdf->Cell(40, 5, utf8_decode('Total Kilos: '.$granTotal.' Kg.'), 1, 1, '');

$pdf->ln(3);

$pdf->Cell(190, 5, utf8_decode('TEMPERATURA CANALES'), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode('Temperatura Canal'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode('Temperatura Canal'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode('Temperatura Canal'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Turno'), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode('Temperatura Canal'), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('1'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno1), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura1."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('10'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno10), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura10."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('19'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno19), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura19."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('28'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno28), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura28."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno2), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura2."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('11'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno11), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura11."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('20'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno20), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura20."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('29'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno29), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura29."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno3), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura3."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('12'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno12), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura12."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('21'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno21), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura21."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('30'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno30), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura30."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('4'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno4), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura4."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('13'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno13), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura13."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('22'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno22), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura22."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('31'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno31), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura31."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('5'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno5), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura5."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('14'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno14), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura14."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('23'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno23), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura23."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('32'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno32), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura32."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('6'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno6), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura6."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('15'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno15), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura15."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('24'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno24), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura24."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('33'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno33), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura33."°"), 1, 1, 'C');

$pdf->Cell(7.5, 5, utf8_decode('7'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno7), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura7."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('16'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno16), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura16."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('25'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno25), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura25."°"), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode('34'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode($turno34), 1, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode($temperatura34."°"), 1, 1, 'C');

/*$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, utf8_decode('IDENTIFICACIÓN DEL ESTABLECIMIENTO DE PROCEDENCIA'), 1, 1, 'C');
$pdf->Cell(64, 5, utf8_decode('Razón Social'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(126, 5, utf8_decode('MERCAMIO SA PLANTA DE DESPOSTE MIXTO'), 1, 1, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Dirección'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(126, 5, utf8_decode('CALLE 33B # 17C-87'), 1, 1, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Departamento'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 5, utf8_decode('VALLE'), 1, 0, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Municipio'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(32, 5, utf8_decode('CALI'), 1, 1, '');
$pdf->ln(3);
       
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, utf8_decode('IDENTIFICACIÓN DEL ESTABLECIMIENTO DE DESTINO'), 1, 1, 'C');
$pdf->Cell(64, 5, utf8_decode('Razón Social'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(126, 5, utf8_decode($rs3['empresa']." - ".$rs3['sede']), 1, 1, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Direccion'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(78, 5, utf8_decode($rs3['direccion']), 1, 0, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(24, 5, utf8_decode('Especie'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(24, 5, utf8_decode($rs2['tipo']), 1, 1, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Departamento'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 5, utf8_decode('VALLE'), 1, 0, '');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(64, 5, utf8_decode('Municipio'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(32, 5, utf8_decode($rs3['municipio']), 1, 1, '');
$pdf->ln(3);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, utf8_decode('DESCRIPCIÓN DE LOS PRODUCTOS TRANSPORTADOS'), 1, 1, 'C');
$pdf->Cell(10, 5, utf8_decode('#'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Item'), 1, 0, 'C');
$pdf->Cell(60, 5, utf8_decode('Producto'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Lote'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Unds'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Canastillas'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Peso'), 1, 1, 'C');

$cont = 0;
$pdf->SetFont('Arial', '', 9);
$registro = $row['item'];

$total_unidades = 0;
$total_cajas = 0;
$total_peso = 0;
while ($row = mysqli_fetch_assoc($c)) {

    $sql2 = "SELECT lote, temperatura, sum(unidades) as cant_unidades, sum(cajas) as cant_cajas, sum(peso) as cant_pesos 
        FROM item_proveedor 
        WHERE item_proveedor.item = " . $row['item'] . " and item_proveedor.proveedor=" . $id . "";

    $c2 = mysqli_query($link, $sql2) or die(mysqli_error($link));    
    $row2 = mysqli_fetch_assoc($c2);

    $unidades=$row2['cant_unidades'];
    if($unidades == 0){
       $unidades = '-';
    }    
    
    $cont++;
    $subtotal = round($row2['cant_pesos']-($row2['cant_cajas']*1.8));
    $pdf->Cell(10, 4, utf8_decode($cont), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row['item']), 1, 0, 'C');
    $pdf->Cell(60, 4, utf8_decode($row['descripcion']), 1, 0, '');
    $pdf->Cell(20, 4, utf8_decode($row['lote']), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row2['temperatura']), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($unidades), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row2['cant_cajas']), 1, 0, 'C');
    $pdf->Cell(20, 4, number_format($subtotal, 0, ',', '.'), 1, 1, 'C');
    $total_unidades = $total_unidades+$row2['cant_unidades'];
    $total_cajas = $total_cajas+$row2['cant_cajas'];
    $total_peso = $total_peso+$row2['cant_pesos'];
}

$pdf->Cell(10, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(60, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(40, 4, 'PESO NETO', 1, 0, '');
$pdf->Cell(20, 4, $total_cajas, 1, 0, 'C');
$pdf->Cell(20, 4, (round($total_peso)-($total_cajas*1.8), 0, ',', '.'), 1, 1, 'C');

$pdf->Cell(10, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(60, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(60, 4, 'PESO CANASTILLAS', 1, 0, '');
$pdf->Cell(20, 4, $total_cajas*1.8, 1, 1, 'C');

$pdf->Cell(10, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(60, 4, '', 0, 0, 'C');
$pdf->Cell(20, 4, '', 0, 0, 'C');
$pdf->Cell(60, 4, 'PESO BRUTO', 1, 0, '');
$pdf->Cell(20, 4, number_format(round($total_peso), 0, ',', '.'), 1, 1, 'C'); */

/* $sql3 = "SELECT max(registro) as hora FROM item_proveedor WHERE proveedor = " . $id . "";
$c3 = mysqli_query($link, $sql3) or die("es aqui:".mysqli_error($link));    
$row3 = mysqli_fetch_assoc($c3);
$hora = fsalida($row3['hora']); */
/* $horai = fsalida($rs5['inicio']);
$horaf = fsalida($rs5['fin']);
$pdf->ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 5, utf8_decode('IDENTIFICACIÓN DEL CONDUCTOR Y VEHICULO DE TRANSPORTE'), 1, 1, 'C');
$pdf->Cell(54, 5, utf8_decode('Nombre del Conductor'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($rs6['nombres']), 1, 0, '');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Placa Vehiculo'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 5, utf8_decode($rs2['placa']), 1, 1, '');
$pdf->ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 5, utf8_decode('OBSERVACIONES'), 1, 1, 'C');
$pdf->MultiCell(190, 12, utf8_decode(''), 1, 1, 'C');
$pdf->ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 5, utf8_decode('RESPONSABLE DE EMISIÓN DE LA GUIA'), 1, 1, 'C');
$pdf->Cell(54, 5, utf8_decode('Nombres y Apellidos'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode(utf8_decode($rs4['nombres'])), 1, 0, '');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Hora inicial'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 5, utf8_decode($horaf), 1, 1, '');
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(54, 5, utf8_decode('Cedula Responsable'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($rs2['responsable']), 1, 0, '');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Hora final'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 5, utf8_decode($horai), 1, 1, '');
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(54, 5, utf8_decode('Teléfono de contacto'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($rs4['telefono']), 1, 0, '');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Tiempo Total'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);

$fecha1 = new DateTime($rs5['fin']);
$fecha2 = new DateTime($rs5['inicio']);
$diferencia = $fecha1->diff($fecha2);

$horas = $diferencia->h;
$minutos = $diferencia->i;
$segundos = $diferencia->s;
$dias = $diferencia->d;
if ($dias > 0) {
    $horas += $dias * 24;
}
$total = $horas.":".$minutos.":".$segundos;

$pdf->Cell(32, 5, utf8_decode($total), 1, 1, '');

$pdf->ln(5);

$pdf->Cell(190, 5, utf8_decode('FIRMA:____________________________'), 0, 1, '');
 */
$pdf->Output('formato_recepcion.pdf', 'D');
