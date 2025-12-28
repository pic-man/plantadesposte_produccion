<?php
require('fpdf.php');

class PDF extends FPDF
{
//Cargar los datos
function LoadData($file)
{
    //Leer las líneas del fichero
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

//Tabla simple
function BasicTable($header,$data)
{
    foreach($header as $col)
    //Cabecera
        $this->Cell(40,7,$col,1);
    $this->Ln();
    //Datos
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}



}

$pdf=new PDF();
//Títulos de las columnas
$header=array('País','Capital','Superficie (km2)','Pobl. (en miles)');
//Carga de datos
$data=$pdf->LoadData('paises.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);

$pdf->Output();
?> 