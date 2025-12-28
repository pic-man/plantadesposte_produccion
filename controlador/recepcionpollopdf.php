<?php
require('pdf/fpdf.php');
ini_set('display_errors', '0');
error_reporting(0);

class PDF extends FPDF
{
    protected $angle = 0; // Define la propiedad 'angle' y la inicializa a 0

    // Rotar texto para encabezado vertical
    function RotatedText($x, $y, $txt, $angle)
    {
        // Rotar texto en el ángulo dado
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0); // Volver a la rotación original
    }

    // Añadir función para rotación
    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.3F %.3F %.3F %.3F %.3F %.3F cm 1 0 0 1 %.3F %.3F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    // Tabla con encabezados rotados
    function CreateTable($header, $data)
{
    // Ancho y altura de las celdas
    $cellWidthHorizontal = 15; // Ancho para las celdas horizontales
    $cellWidthRotated = 5;     // Ancho para las celdas rotadas
    $cellHeightRotated = 40;   // Altura ajustada de las celdas rotadas
    $cellHeight = 7;           // Altura para las celdas de datos

    // Textos de los encabezados rotados a partir de la celda 7
    $headersRotados = [
        'Temperatura unidad de frio',
        'Guia de Transporte',
        'T',
        'Olor',
        'Color',
        'Limpio',
        'En buen estado',
        'Sin olores fuertes o desagradables',
        'Limpio',
        'Ausente de plagas',
        'Ausente de olores fuertes',
        'Uso de estibas',
        'No se transportan sustancias peligrosas',
        'Usa cofia y tapabocas',
        'Dotación limpia y en buen estado',
        'Botas limpias y en buen estado',
        'Uñas cortadas y sin esmalte',
        'No usa accesorios'
    ];

    // Crear encabezados horizontales y rotados
    $this->SetFont('Arial', '', 6); // Cambiar el tamaño de fuente a 6

    for ($i = 0; $i < count($header); $i++) {
        if ($i >= 7 && $i < 7 + count($headersRotados)) {
            // Posición y texto para los encabezados rotados
            $xPos = 10 + (7 * $cellWidthHorizontal) + (($i - 7) * $cellWidthRotated);
            $yPos = 50 - $cellHeightRotated;

            $this->SetXY($xPos, $yPos);
            $this->RotatedText($xPos + 3, 48, $headersRotados[$i - 7], 90);
            $this->Rect($xPos, $yPos, $cellWidthRotated, $cellHeightRotated);
        } else {
            // Posicionar celdas horizontales
            $this->SetXY(10 + ($i * $cellWidthHorizontal), 40);

            // Celdas horizontales según el índice
            $titles = ['Fecha', 'Producto', 'Proveedor', 'Lote Prov.', 'Fecha Venc.', 'Peso (Kg.)', 'Cantidad'];
            if (isset($titles[$i])) {
                $this->Cell($cellWidthHorizontal, 10, $titles[$i], 1, 0, 'C');
            }
        }
    }

    // Crear las filas de datos
    for ($row = 0; $row < count($data); $row++) {
        for ($col = 0; $col < count($header); $col++) {
            if ($col >= 7 && $col < 7 + count($headersRotados)) {
                $this->SetXY(10 + (7 * $cellWidthHorizontal) + (($col - 7) * $cellWidthRotated), 50 + ($row * $cellHeight));
                $this->Cell($cellWidthRotated, $cellHeight, $data[$row][$col], 1, 0, 'C');
            } else {
                $this->SetXY(10 + ($col * $cellWidthHorizontal), 50 + ($row * $cellHeight));
                $this->Cell($cellWidthHorizontal, $cellHeight, $data[$row][$col], 1, 0, 'C');
            }
        }
        $this->Ln();
    }
}

}

// Crear el documento
$pdf = new PDF();
$pdf->AddPage('L', 'Letter'); // Apaisado en tamaño carta

// Encabezados de la tabla (27 columnas)
$header = [];
for ($i = 1; $i <= 27; $i++) {
    $header[] = "Header $i";
}

// Datos de la tabla (20 filas)
$data = [];
for ($i = 1; $i <= 19; $i++) {
    $row = [];
    for ($j = 1; $j <= 27; $j++) {
        //$row[] = " $i-$j";
        $row[] = " ";
    }
    $data[] = $row;
}

// Crear la tabla en el PDF
$pdf->SetFont('Arial', '', 10);
$pdf->CreateTable($header, $data);

ob_end_clean();
$pdf->Output('formato_recepcion.pdf', 'D');
?>