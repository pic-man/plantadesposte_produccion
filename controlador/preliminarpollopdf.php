<?php
session_start();
//ini_set("display_errors", false);
ini_set('display_errors', '0');
error_reporting(0);

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

$sqlg = "SELECT codigog, codigoa, fechaexp, consecutivog, responsable, destino, conductor, placa, observaciones, tipo, canales, precinto  
        FROM guiaspollo 
        WHERE id_guia = " . $id;
$c2 = mysqli_query($link, $sqlg) or die("aqui 1:".mysqli_error($link));
$rs2 = mysqli_fetch_array($c2);

$sql3 = "SELECT empresa, sede, direccion, municipio 
        FROM destinos 
        WHERE id=".$rs2['destino'];
$c3 = mysqli_query($link, $sql3) or die("aqui 2:".mysqli_error($link));
$rs3 = mysqli_fetch_array($c3);

$sql4 = "SELECT nombres, telefono 
        FROM responsables 
        WHERE cedula = '". $rs2['responsable']."'";
$c4 = mysqli_query($link, $sql4) or die("aqui 3:". mysqli_error($link));
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
$pdf->Cell(65, 5, utf8_decode('Producto'), 1, 0, 'C');
$pdf->Cell(32, 5, utf8_decode('Tipo'), 1, 0, 'C');
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
$total_peso = 0;
while ($row = mysqli_fetch_assoc($c)) {

    /* $sql2 = "SELECT lote, temperatura, sum(unidades) as cant_unidades, sum(cajas) as cant_cajas, sum(peso) as cant_pesos 
        FROM item_proveedorpollo 
        WHERE item_proveedorpollo.item = " . $row['item'] . " and item_proveedorpollo.proveedor=" . $id . "
        GROUP BY item,lote"; */

    /* $sql2 = "SELECT lote, item, temperatura, SUM(unidades) as cant_unidades, SUM(cajas) as cant_cajas, SUM(peso) as cant_pesos
        FROM item_proveedorpollo 
        WHERE item_proveedorpollo.item = " . $row['item'] . " 
        AND item_proveedorpollo.proveedor=" . $id . "
        GROUP BY item, lote";  */   

    $sql2 = "SELECT lote, 
                    SUM(unidades) as cant_unidades, 
                    SUM(peso) as cant_pesos, 
                    SUM(cajas) as cant_cajas,
                    SUM(base) as cant_bases
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
    if(($row['item'] == '050514')||($row['item'] == '050515')||($row['item'] == '050516')||($row['item'] == '050517')){
        $subtotal = round($row2['cant_pesos']-($row2['cant_cajas']*2)-(($row2['cant_bases']*1.8)/4));
        $stotal_peso = round($row2['cant_pesos']-(($row2['cant_bases']*1.8)/4));
    }else{
        $subtotal = round($row2['cant_pesos']-($row2['cant_cajas']*2)-($row2['cant_bases']*1.8));
        $stotal_peso = round($row2['cant_pesos']-($row2['cant_bases']*1.8));
    }
    $pdf->Cell(10, 4, utf8_decode($cont), 1, 0, 'C');
    $pdf->Cell(13, 4, utf8_decode($row['item']), 1, 0, 'C');
    $pdf->Cell(65, 4, utf8_decode($row['descripcion']), 1, 0, '');
    $pdf->Cell(32, 4, utf8_decode($row['categoria']), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row['lote']), 1, 0, 'C');
    $pdf->Cell(10, 4, utf8_decode($row2['temperatura']), 1, 0, 'C');
    $pdf->Cell(10, 4, utf8_decode($unidades), 1, 0, 'C');
    $pdf->Cell(20, 4, utf8_decode($row2['cant_cajas']), 1, 0, 'C');
    
    /* if($row['item']!='050514' && $row['item']!='050515' && $row['item']!='050516' && $row['item']!='050517' && $row['item']!='051513'){ */
        $peso='si';
        $pdf->Cell(10, 4, number_format($subtotal, 0, ',', '.'), 1, 1, 'C');
        //$pdf->Cell(10, 4, $row2['cant_pesos']."-".(($row2['cant_cajas']*2))."-".(($row2['cant_bases']*1.8)/4), 1, 1, 'C');
    /* }else{
        $peso='no';
        $pdf->Cell(10, 4, '', 1, 1, 'C');
    } */
    $total_unidades = $total_unidades+$row2['cant_unidades'];
    $total_cajas = $total_cajas+$row2['cant_cajas'];
    $total_peso = $total_peso+$stotal_peso;
}


if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, 'PESO NETO', 1, 0, '');
        $pdf->Cell(20, 4, $total_cajas, 1, 0, 'C');
        $pdf->Cell(10, 4, number_format(round($total_peso)-($total_cajas*2), 0, ',', '.'), 1, 1, 'C');
}

if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(55, 4, 'PESO CANASTILLAS', 1, 0, '');
        $pdf->Cell(10, 4, $total_cajas*2, 1, 1, 'C');
}

if($peso=='si'){
        $pdf->Cell(10, 4, '', 0, 0, 'C');
        $pdf->Cell(20, 4, '', 0, 0, 'C');
        $pdf->Cell(60, 4, '', 0, 0, 'C');
        $pdf->Cell(35, 4, '', 0, 0, 'C');
        $pdf->Cell(55, 4, 'PESO BRUTO', 1, 0, '');
        $pdf->Cell(10, 4, number_format(round($total_peso), 0, ',', '.'), 1, 1, 'C');
}

ob_end_clean();
$pdf->Output('formato_guia.pdf', 'D'); // D
?>