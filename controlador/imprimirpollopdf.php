<?php
// Codigo modificado 2025-10-16 de la linea 237-247. Ojo con este codigo

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
        $uno = "262 DA";
        $dos = str_pad($cad, 6, '0', STR_PAD_LEFT);
        $tres = substr($cad2, 2, 2);
        return $uno."-".$dos."-".$tres;
     }

$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : "";
$fecha_actual = date("Y-m-d");
include("../config.php");

$sqlg = "SELECT codigog, codigoa, fechaexp, consecutivog, responsable, destino, conductor, placa, observaciones, tipo, canales, precinto, hora_inicial,hora_final  
        FROM guiaspollo 
        WHERE id_guia = " . $id;
$c2 = mysqli_query($link, $sqlg) or die("aqui 1:".mysqli_error($link));
$rs2 = mysqli_fetch_array($c2); 

if($rs2['hora_final']=='00:00:00'){
        $hora = date("H:i:s");
        $sql_hora = "select hora_recepcion_carne as hora_inicial from hora_temporal";
        $c_hora = mysqli_query($link, $sql_hora) or die("aqui 2:".mysqli_error($link));
        $rs_hora = mysqli_fetch_array($c_hora);
     
        $sql = "UPDATE guiaspollo set 
                   hora_inicial = '" . $rs_hora['hora_inicial'] . "',
                   hora_final = '" . $hora . "'
                WHERE id_guia = " . $id;
     
         $rs_operacion = mysqli_query($link, $sql) or die("aqui 3:".mysqli_error($link));
     
         $hora_i = $rs_hora['hora_inicial'];
         $hora_f = $hora;
     }else{
         $hora_i = $rs2['hora_inicial'];
         $hora_f = $rs2['hora_final'];
     }

$sql3 = "SELECT empresa, sede, direccion, municipio 
        FROM destinos 
        WHERE id=".$rs2['destino'];
$c3 = mysqli_query($link, $sql3) or die("aqui 4:".mysqli_error($link));
$rs3 = mysqli_fetch_array($c3);

$sql4 = "SELECT nombres, telefono 
        FROM responsables 
        WHERE cedula = '".$rs2['responsable']."'";
$c4 = mysqli_query($link, $sql4) or die("aqui 5:".mysqli_error($link));
$rs4 = mysqli_fetch_array($c4);

/* $sql = "SELECT DISTINCT p.item,ip.lote, p.descripcion, p.categoria
        FROM plantilla p
        JOIN item_proveedorpollo ip ON p.item = ip.item
        WHERE ip.proveedor = ".$id."
        ORDER BY p.descripcion ASC"; */        

$sql = "SELECT DISTINCT p.item, ip.lote, p.descripcion, p.categoria
        FROM plantilla p
        JOIN item_proveedorpollo ip ON p.item = ip.item
        WHERE ip.proveedor = ".$id."
        ORDER BY p.descripcion ASC";

$c = mysqli_query($link, $sql) or die("aqui 1:".mysqli_error($link));

$sql5 = "SELECT max(registro) as inicio,min(registro) as fin
        FROM plantilla, item_proveedorpollo 
        WHERE plantilla.item = item_proveedorpollo.item 
        AND item_proveedorpollo.proveedor = " . $id . "
        ORDER BY registro DESC";
$c5 = mysqli_query($link, $sql5) or die("aqui 1:".mysqli_error($link));
$rs5 = mysqli_fetch_array($c5);

$sql6 = "SELECT nombres, telefono 
        FROM conductores 
        WHERE cedula=".$rs2['conductor'];
$c6 = mysqli_query($link, $sql6) or die(mysqli_error($link));
$rs6 = mysqli_fetch_array($c6);

require('pdf/fpdf.php');
class PDF extends FPDF
{
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');
//$pdf->Ln(2);
$logo = '../assets/img/logo-mercamio.jpg';
$pdf->Image($logo, 10, 10, 50, 15);
$pdf->ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY($pdf->GetX() + 60, $pdf->GetY() - 12);
$pdf->multiCell(100, 6, utf8_decode('GUÍA DE TRANSPORTE Y DESTINO DE CARNE   Y PRODUCTOS CÁRNICOS COMESTIBLES     '), 1, 0, 'C');
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
$pdf->Cell(20, 5, utf8_decode('Calidad'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('7/11/2023'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('7/11/2024'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('FTO-PDA-10'), 1, 1, 'C');
$pdf->ln(3);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(33, 5, utf8_decode('Fecha de Exp.'), 1, 0, 'C');
$pdf->Cell(54, 5, utf8_decode('Código Autorización Planta'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('Consecutivo Guía'), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode('Precinto'), 1, 0, 'C');
$pdf->Cell(33, 5, utf8_decode('Nro. Pollos'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(33, 5, utf8_decode($rs2['fechaexp']), 1, 0, 'C');
$pdf->Cell(54, 5, utf8_decode('262 DA'), 1, 0, 'C');
$pdf->Cell(35, 5, fsalidaconsecutivo($id,$rs2['fechaexp']), 1, 0, 'C');
$pdf->Cell(35, 5, utf8_decode($rs2['precinto']), 1, 0, 'C');
$pdf->Cell(33, 5, utf8_decode($rs2['canales']), 1, 1, 'C');
$pdf->ln(3);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, utf8_decode('IDENTIFICACIÓN DEL ESTABLECIMIENTO DE PROCEDENCIA'), 1, 1, 'C');
$pdf->Cell(64, 5, utf8_decode('Razón Social'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(126, 5, utf8_decode('MERCAMIO SA PLANTA DE DESPRESE Y ACONDICIONAMIENTO DE AVES'), 1, 1, '');
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
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(190, 5, utf8_decode('DESCRIPCIÓN DE LOS PRODUCTOS TRANSPORTADOS'), 1, 1, 'C');
$pdf->Cell(10, 5, utf8_decode('#'), 1, 0, 'C');
$pdf->Cell(13, 5, utf8_decode('Item'), 1, 0, 'C');
$pdf->Cell(60, 5, utf8_decode('Producto'), 1, 0, 'C');
$pdf->Cell(37, 5, utf8_decode('Tipo'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Lote'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Temp.'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Unds'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Canastillas'), 1, 0, 'C');
$pdf->Cell(10, 5, utf8_decode('Peso'), 1, 1, 'C');

$cont = 0;
$pdf->SetFont('Arial', '', 7);
$registro = $row['item'];

$total_unidades = 0;
$total_cajas = 0;
$total_bases = 0;
$total_peso = 0;
while ($row = mysqli_fetch_assoc($c)) {

    $sql2 = "SELECT lote, 
                    SUM(unidades) as cant_unidades, 
                    SUM(peso) as cant_pesos, 
                    SUM(cajas) as cant_cajas,
                    SUM(base) as cant_bases,
                    temperatura
        FROM item_proveedorpollo 
        WHERE item_proveedorpollo.item = '" . $row['item'] . "' 
        AND item_proveedorpollo.proveedor = ".$id."
        AND item_proveedorpollo.lote = '" . $row['lote'] . "' 
        GROUP BY item, lote";    

    $c2 = mysqli_query($link, $sql2) or die(mysqli_error($link));    
    $row2 = mysqli_fetch_assoc($c2);

    $unidades=$row2['cant_unidades'];
    if($unidades == 0){
       $unidades = '-';
    }    
    
    $cont++;
    if(($row['item'] == '050514')||($row['item'] == '050515')||($row['item'] == '050516')||($row['item'] == '050517')||($row['item'] == '074700')){
        $subtotal = round($row2['cant_pesos']-($row2['cant_cajas']*1.8)-($row2['cant_bases']*1.8));
        //$subtotal = round($row2['cant_pesos']);
        //$stotal_peso = round($row2['cant_pesos']);
    }else{
        $subtotal = round($row2['cant_pesos']-($row2['cant_cajas']*2)-($row2['cant_bases']*1.8));
        //$subtotal = round($row2['cant_pesos']);
        //$stotal_peso = round($row2['cant_pesos']);
    }
    $pdf->Cell(10, 4, utf8_decode($cont), 1, 0, 'C');
    $pdf->Cell(13, 4, utf8_decode($row['item']), 1, 0, 'C');
    $pdf->Cell(60, 4, utf8_decode($row['descripcion']), 1, 0, '');
    $pdf->Cell(37, 4, utf8_decode($row['categoria']), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row['lote']), 1, 0, 'C');
    $pdf->Cell(10, 4, utf8_decode($row2['temperatura']), 1, 0, 'C');

    // Codigo comentado 2025-10-16 
    /* if ($row["item"] == "059757" || $row["item"] == "059761") {
        $pdf->Cell(10, 4, $rs2['canales'] * 2, 1, 0, 'C');
    } elseif ($row["item"] == "059758" || $row["item"] == "059762" || $row["item"] == "059756" || $row["item" == "059760"]) {
        $pdf->Cell(10, 4, $rs2['canales'], 1, 0, 'C');
    } else {
        $pdf->Cell(10, 4, utf8_decode($unidades), 1, 0, 'C');
    } */

    // Se dejo este codigo del codigo comentado 2025-10-16
    $pdf->Cell(10, 4, utf8_decode($unidades), 1, 0, 'C');

    $pdf->Cell(20, 4, utf8_decode($row2['cant_cajas']), 1, 0, 'C');
    
    $peso='si';
    $pdf->Cell(10, 4, number_format($subtotal, 0, ',', '.'), 1, 1, 'C');
    $total_unidades = $total_unidades+$row2['cant_unidades'];
    $total_cajas = $total_cajas+$row2['cant_cajas'];
    $total_bases = $total_bases+$row2['cant_bases'];
    $total_peso = $total_peso+$subtotal;
}

if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, 'PESO NETO', 1, 0, '');
        $pdf->Cell(20, 4, $total_cajas, 1, 0, 'C');
        $pdf->Cell(10, 4, number_format(round($total_peso), 0, ',', '.'), 1, 1, 'C');
}

if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(55, 4, 'PESO CANASTILLAS', 1, 0, '');
        $pdf->Cell(10, 4, number_format(round($total_cajas*1.8), 0, ',', '.'), 1, 1, 'C');
}

if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(55, 4, 'PESO BRUTO', 1, 0, '');
        $pdf->Cell(10, 4, number_format(round($total_peso+($total_cajas*1.8)), 0, ',', '.'), 1, 1, 'C');
}

$horai = fsalida($rs5['inicio']);
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

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, utf8_decode('OBSERVACIONES'), 1, 1, 'C');
$pdf->MultiCell(190, 12, utf8_decode($rs2['observaciones']), 1, 1, 'C');
$pdf->ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 5, utf8_decode('RESPONSABLE DE EMISIÓN DE LA GUIA'), 1, 1, 'C');
$pdf->Cell(54, 5, utf8_decode('Nombres y Apellidos'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode(utf8_decode($rs4['nombres'])), 1, 0, '');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Hora inicial'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 5, utf8_decode($hora_i), 1, 1, '');
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(54, 5, utf8_decode('Cedula Responsable'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($rs2['responsable']), 1, 0, '');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Hora final'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(32, 5, utf8_decode($hora_f), 1, 1, '');
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(54, 5, utf8_decode('Teléfono de contacto'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, utf8_decode($rs4['telefono']), 1, 0, '');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 5, utf8_decode('Tiempo Total'), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);

$inicio = date('H:i', strtotime($hora_i));
$fin = date('H:i', strtotime($hora_f));

$inicioDateTime = new DateTime($inicio);
$finDateTime = new DateTime($fin);
$diferencia = $inicioDateTime->diff($finDateTime);

$tiempoTranscurrido = $diferencia->format('%H:%I');

$pdf->Cell(32, 5, utf8_decode($tiempoTranscurrido), 1, 1, '');

$pdf->ln(5);

$x = $pdf->GetX();
$y = $pdf->GetY();

if($rs2['responsable'] != 12345678){
        $firma = '../assets/img/firmas/'.$rs2['responsable'].'.jpg';
        $pdf->Image($firma, $x+15, $y-4, 30, 10);
        $pdf->Cell(190, 5, utf8_decode('FIRMA:____________________________'), 0, 1, '');
}
$pdf->Output('formato_guia.pdf', 'D');
