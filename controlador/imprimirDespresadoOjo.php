<?php
session_start();
ini_set("display_errors", false);

$idGuia = $_GET["id"]? $_GET["id"]: "1";
date_default_timezone_set("America/Bogota");
$fecha_actual = date("Y-m-d");
include("../config.php");

$sql = "SELECT 
    despresado.id,
    destino,
    proveedorpollo.sede,
    lote_pollo,
    fecha_venci_pollo,
    proveedor_hielo.sede,
    lote_hielo,
    fecha_venci_hielo,
    proveedor_empaque.sede,
    lote_empaque,
    fecha_venci_empaque,
    fabricante_empaque,
    proveedor_salmuera.sede,
    lote_salmuera,
    fecha_venci_salmuera,
    responsable,
    responsables.nombres,
    fecha_despresado,
    Temp1,
    Temp2,
    Temp3,
    Hora1,
    Hora2,
    Hora3,
    fecha_beneficio,
    tipo_pollo,
    fecha_registro,
    despresado.cantidadPollo
    FROM despresado 
    LEFT JOIN
        proveedorpollo ON despresado.proveedor_pollo = proveedorpollo.id 
    INNER JOIN
        proveedor_hielo ON despresado.proveedor_hielo = proveedor_hielo.id
    INNER JOIN
        proveedor_empaque ON despresado.proveedor_empaque = proveedor_empaque.id
    INNER JOIN
        proveedor_salmuera ON despresado.proveedor_salmuera = proveedor_salmuera.id
    INNER JOIN
        responsables ON despresado.responsable = responsables.cedula
    WHERE despresado.id = '$idGuia'";
$operation = mysqli_query($link, $sql);
$row = mysqli_fetch_row($operation);

$sql2 = "SELECT empresa, sede FROM destinos WHERE id = " . $row[1];
$operation2 = mysqli_query($link, $sql2);
$empresa = mysqli_fetch_row($operation2);

$sql3 = "SELECT item,kilos,cajas, canastilla_base FROM despresado_items WHERE guia = '$idGuia'";
$operation3 = mysqli_query($link, $sql3);

$items = array(
    "059758" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "045401" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "042788" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "042789" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059756" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059757" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059755" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059762" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059760" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059761" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059759" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ]
);

while($row3 = mysqli_fetch_row($operation3)){
    if ($row3[0] == "059758") {
        $items["059758"]["cajas"] += $row3[2];
        $items["059758"]["kg"] += $row3[1];
        $items["059758"]["cajasBase"] += $row3[3];
        $items["059758"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "045401") {
        $items["045401"]["cajas"] += $row3[2];
        $items["045401"]["kg"] += $row3[1];
        $items["045401"]["cajasBase"] += $row3[3];
        $items["045401"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "042788") {
        $items["042788"]["cajas"] += $row3[2];
        $items["042788"]["kg"] += $row3[1];
        $items["042788"]["cajasBase"] += $row3[3];
        $items["042788"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "042789") {
        $items["042789"]["cajas"] += $row3[2];
        $items["042789"]["kg"] += $row3[1];
        $items["042789"]["cajasBase"] += $row3[3];
        $items["042789"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059756") {
        $items["059756"]["cajas"] += $row3[2];
        $items["059756"]["kg"] += $row3[1];
        $items["059756"]["cajasBase"] += $row3[3];
        $items["059756"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059757") {
        $items["059757"]["cajas"] += $row3[2];
        $items["059757"]["kg"] += $row3[1];
        $items["059757"]["cajasBase"] += $row3[3];
        $items["059757"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059755") {
        $items["059755"]["cajas"] += $row3[2];
        $items["059755"]["kg"] += $row3[1];
        $items["059755"]["cajasBase"] += $row3[3];
        $items["059755"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059762") {
        $items["059762"]["cajas"] += $row3[2];
        $items["059762"]["kg"] += $row3[1];
        $items["059762"]["cajasBase"] += $row3[3];
        $items["059762"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059760") {
        $items["059760"]["cajas"] += $row3[2];
        $items["059760"]["kg"] += $row3[1];
        $items["059760"]["cajasBase"] += $row3[3];
        $items["059760"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059761") {
        $items["059761"]["cajas"] += $row3[2];
        $items["059761"]["kg"] += $row3[1];
        $items["059761"]["cajasBase"] += $row3[3];
        $items["059761"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }elseif ($row3[0] == "059759") {
        $items["059759"]["cajas"] += $row3[2];
        $items["059759"]["kg"] += $row3[1];
        $items["059759"]["cajasBase"] += $row3[3];
        $items["059759"]["kgNetos"] += $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2));
    }
}

$sql7 = "SELECT SUM(kilos), SUM(cajas), SUM(cajas_base) FROM despresado_items WHERE guia = '$idGuia'";
$operation7 = mysqli_query($link, $sql7);

/* "" = 0; */
/* $nroPolloEntero = 0; */
$pesoInicialDespresado = 0;
/* $pesoInicialEntero = 0; */

while ($row7 = mysqli_fetch_row($operation7)) {
    var_dump($row7);
    die();
    /* if ($row7[0] == "ENTERO") {
        $nroPolloEntero += $row7[4];
        $pesoInicialEntero += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        if ($row[25] == "BLANCO") {
            $items["059759"]["cajas"] += $row7[2];
            $items["059759"]["kg"] += $row7[1];
            $items["059759"]["cajasBase"] += $row7[3];
            $items["059759"]["kgNetos"] += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        }else {
            $items["059755"]["cajas"] += $row7[2];
            $items["059755"]["kg"] += $row7[1];
            $items["059755"]["cajasBase"] += $row7[3];
            $items["059755"]["kgNetos"] += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        }
    }else { */
        /* "" += $row7[4]; */
        $pesoInicialDespresado += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
    /* } */
}

$sql4 = "SELECT item,kilos_mejora,cajas_mejora, cajas_mejora_base FROM mejora_items_despresado WHERE id_guia = '$idGuia'";
$operation4 = mysqli_query($link, $sql4);
$itemsM = array(
    "059758" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "045401" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "042788" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "042789" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059756" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059757" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059755" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059762" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059760" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059761" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ],
    "059759" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNetos" => 0,
    ]
);

while ($row2 = mysqli_fetch_row($operation4)) {
    if ($row2[0] == "059758") {
        $itemsM["059758"]["cajas"] += $row2[2];
        $itemsM["059758"]["kg"] += $row2[1];
        $itemsM["059758"]["cajasBase"] += $row2[3];
        $itemsM["059758"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "045401") {
        $itemsM["045401"]["cajas"] += $row2[2];
        $itemsM["045401"]["kg"] += $row2[1];
        $itemsM["045401"]["cajasBase"] += $row2[3];
        $itemsM["045401"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "042788") {
        $itemsM["042788"]["cajas"] += $row2[2];
        $itemsM["042788"]["kg"] += $row2[1];
        $itemsM["042788"]["cajasBase"] += $row2[3];
        $itemsM["042788"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "042789") {
        $itemsM["042789"]["cajas"] += $row2[2];
        $itemsM["042789"]["kg"] += $row2[1];
        $itemsM["042789"]["cajasBase"] += $row2[3];
        $itemsM["042789"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059756") {
        $itemsM["059756"]["cajas"] += $row2[2];
        $itemsM["059756"]["kg"] += $row2[1];
        $itemsM["059756"]["cajasBase"] += $row2[3];
        $itemsM["059756"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059757") {
        $itemsM["059757"]["cajas"] += $row2[2];
        $itemsM["059757"]["kg"] += $row2[1];
        $itemsM["059757"]["cajasBase"] += $row2[3];
        $itemsM["059757"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059755") {
        $itemsM["059755"]["cajas"] += $row2[2];
        $itemsM["059755"]["kg"] += $row2[1];
        $itemsM["059755"]["cajasBase"] += $row2[3];
        $itemsM["059755"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059762") {
        $itemsM["059762"]["cajas"] += $row2[2];
        $itemsM["059762"]["kg"] += $row2[1];
        $itemsM["059762"]["cajasBase"] += $row2[3];
        $itemsM["059762"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059760") {
        $itemsM["059760"]["cajas"] += $row2[2];
        $itemsM["059760"]["kg"] += $row2[1];
        $itemsM["059760"]["cajasBase"] += $row2[3];
        $itemsM["059760"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059761") {
        $itemsM["059761"]["cajas"] += $row2[2];
        $itemsM["059761"]["kg"] += $row2[1];
        $itemsM["059761"]["cajasBase"] += $row2[3];
        $itemsM["059761"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059759") {
        $itemsM["059759"]["cajas"] += $row2[2];
        $itemsM["059759"]["kg"] += $row2[1];
        $itemsM["059759"]["cajasBase"] += $row2[3];
        $itemsM["059759"]["kgNetos"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }
}

$totalBlanco = $items["059762"]["kgNetos"] + $items["059760"]["kgNetos"] + $items["059761"]["kgNetos"];
$totalCampo = $items["059758"]["kgNetos"] + $items["059756"]["kgNetos"] + $items["059757"]["kgNetos"];

$porcentajeAla = $items["059758"]["kgNetos"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajeAlaBlanca = $items["059762"]["kgNetos"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajePechugaBlanca = $items["059760"]["kgNetos"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajePernilBlanca = $items["059761"]["kgNetos"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajeAla = $items["059758"]["kgNetos"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajePechuga = $items["059756"]["kgNetos"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajePernil = $items["059757"]["kgNetos"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;

$mejoraAlaBlanca = ($itemsM["059762"]["kgNetos"] - $items["059762"]["kgNetos"]) / ($items["059762"]["kgNetos"] > 0 ? $items["059762"]["kgNetos"] : 1) * 100;
$mejoraPechugaBlanca = ($itemsM["059760"]["kgNetos"] - $items["059760"]["kgNetos"]) / ($items["059760"]["kgNetos"] > 0 ? $items["059760"]["kgNetos"] : 1) * 100;
$mejoraPernilBlanco = ($itemsM["059761"]["kgNetos"] - $items["059761"]["kgNetos"]) / ($items["059761"]["kgNetos"] > 0 ? $items["059761"]["kgNetos"] : 1) * 100;
$mejoraPolloBlanco = ($itemsM["059759"]["kgNetos"] - $items["059759"]["kgNetos"]) / ($items["059759"]["kgNetos"] > 0 ? $items["059759"]["kgNetos"] : 1) * 100;
$mejoraAlas = ($itemsM["059758"]["kgNetos"] - $items["059758"]["kgNetos"]) / ($items["059758"]["kgNetos"] > 0 ? $items["059758"]["kgNetos"] : 1) * 100;
$mejoraPechuga = ($itemsM["059756"]["kgNetos"] - $items["059756"]["kgNetos"]) / ($items["059756"]["kgNetos"] > 0 ? $items["059756"]["kgNetos"] : 1) * 100;
$mejoraPernil = ($itemsM["059757"]["kgNetos"] - $items["059757"]["kgNetos"]) / ($items["059757"]["kgNetos"] > 0 ? $items["059757"]["kgNetos"] : 1) * 100;
$mejoraPollo = ($itemsM["059755"]["kgNetos"] - $items["059755"]["kgNetos"]) / ($items["059755"]["kgNetos"] > 0 ? $items["059755"]["kgNetos"] : 1) * 100;

$sql5 = "SELECT 
    responsables.nombres,
    responsable2
    FROM despresado
    INNER JOIN
        responsables ON despresado.responsable2 = responsables.cedula 
    WHERE despresado.id = '$idGuia'";
$operation5 = mysqli_query($link, $sql5);
$responsable = mysqli_fetch_row($operation5);

$sql6 = "SELECT lote, item FROM mejora_items_despresado WHERE id_guia = '$idGuia' GROUP BY item";
$operation6 = mysqli_query($link, $sql6);

$lotes = array();

while ($row6 = mysqli_fetch_row($operation6)) {
    $lotes[$row6[1]] = $row6[0];
}

$unidadesTotalDes = 0;
$unidadesTotalDesP = 0;

$PesoTotalDes = $items["059762"]["kg"] + $items["059760"]["kg"] + $items["059761"]["kg"] + $items["059758"]["kg"] + $items["059756"]["kg"] + $items["059757"]["kg"];

$PesoTotalDesP = $items["059762"]["kg"] + $items["059760"]["kg"] + $items["059761"]["kg"] + $items["059759"]["kg"] + $items["059758"]["kg"] + $items["059756"]["kg"] + $items["059757"]["kg"] + $items["059755"]["kg"];

$cajasBaseTotalDes = $items["059762"]["cajasBase"] + $items["059760"]["cajasBase"] + $items["059761"]["cajasBase"] + $items["059758"]["cajasBase"] + $items["059756"]["cajasBase"] + $items["059757"]["cajasBase"];

$cajasBaseTotalDesP = $items["059762"]["cajasBase"] + $items["059760"]["cajasBase"] + $items["059761"]["cajasBase"] + $items["059759"]["cajasBase"] + $items["059758"]["cajasBase"] + $items["059756"]["cajasBase"] + $items["059757"]["cajasBase"] + $items["059755"]["cajasBase"];

$CajaTotalDes = $items["059762"]["cajas"] + $items["059760"]["cajas"] + $items["059761"]["cajas"] + $items["059758"]["cajas"] + $items["059756"]["cajas"] + $items["059757"]["cajas"];

$CajaTotalDesP = $items["059762"]["cajas"] + $items["059760"]["cajas"] + $items["059761"]["cajas"] + $items["059759"]["cajas"] + $items["059758"]["cajas"] + $items["059756"]["cajas"] + $items["059757"]["cajas"] + $items["059755"]["cajas"];

$kilosNetoDes = $items["059762"]["kgNetos"] + $items["059760"]["kgNetos"] + $items["059761"]["kgNetos"] + $items["059758"]["kgNetos"] + $items["059756"]["kgNetos"] + $items["059757"]["kgNetos"];

$kilosNetoDesP = $items["059762"]["kgNetos"] + $items["059760"]["kgNetos"] + $items["059761"]["kgNetos"] + $items["059759"]["kgNetos"] + $items["059758"]["kgNetos"] + $items["059756"]["kgNetos"] + $items["059757"]["kgNetos"] + $items["059755"]["kgNetos"];

$PesoTotalMej = $itemsM["059762"]["kg"] + $itemsM["059760"]["kg"] + $itemsM["059761"]["kg"] + $itemsM["059758"]["kg"] + $itemsM["059756"]["kg"] + $itemsM["059757"]["kg"];

$PesoTotalMejP = $itemsM["059762"]["kg"] + $itemsM["059760"]["kg"] + $itemsM["059761"]["kg"] + $itemsM["059759"]["kg"] + $itemsM["059758"]["kg"] + $itemsM["059756"]["kg"] + $itemsM["059757"]["kg"] + $itemsM["059755"]["kg"];

$cajasBaseTotalMej = $itemsM["059762"]["cajasBase"] + $itemsM["059760"]["cajasBase"] + $itemsM["059761"]["cajasBase"] + $itemsM["059758"]["cajasBase"] + $itemsM["059756"]["cajasBase"] + $itemsM["059757"]["cajasBase"];

$cajasBaseTotalMejP = $itemsM["059762"]["cajasBase"] + $itemsM["059760"]["cajasBase"] + $itemsM["059761"]["cajasBase"] + $itemsM["059759"]["cajasBase"] + $itemsM["059758"]["cajasBase"] + $itemsM["059756"]["cajasBase"] + $itemsM["059757"]["cajasBase"] + $itemsM["059755"]["cajasBase"];

$CajaTotalMej = $itemsM["059762"]["cajas"] + $itemsM["059760"]["cajas"] + $itemsM["059761"]["cajas"] + $itemsM["059758"]["cajas"] + $itemsM["059756"]["cajas"] + $itemsM["059757"]["cajas"];

$CajaTotalMejP = $itemsM["059762"]["cajas"] + $itemsM["059760"]["cajas"] + $itemsM["059761"]["cajas"] + $itemsM["059759"]["cajas"] + $itemsM["059758"]["cajas"] + $itemsM["059756"]["cajas"] + $itemsM["059757"]["cajas"] + $itemsM["059755"]["cajas"];

$kilosNetoMej = $itemsM["059762"]["kgNetos"] + $itemsM["059760"]["kgNetos"] + $itemsM["059761"]["kgNetos"] + $itemsM["059758"]["kgNetos"] + $itemsM["059756"]["kgNetos"] + $itemsM["059757"]["kgNetos"];

$kilosNetoMejP = $itemsM["059762"]["kgNetos"] + $itemsM["059760"]["kgNetos"] + $itemsM["059761"]["kgNetos"] + $itemsM["059759"]["kgNetos"] + $itemsM["059758"]["kgNetos"] + $itemsM["059756"]["kgNetos"] + $itemsM["059757"]["kgNetos"] + $itemsM["059755"]["kgNetos"];

$totalPorcentaje = ($PesoTotalMej - $PesoTotalDes) / ($PesoTotalDes > 0 ? $PesoTotalDes : 1) * 100;
$totalPorcentajeP = ($PesoTotalMejP - $PesoTotalDesP) / ($PesoTotalDesP > 0 ? $PesoTotalDesP : 1) * 100;



require('pdf/fpdf.php');
class PDF extends FPDF{}

$pdf = new PDF();

$pdf->AddPage('L', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY($pdf->GetX() + 44, $pdf->GetY());
$pdf->Cell(14, 5, utf8_decode("Empresa: "), 0, 0, '');
$pdf->Cell(26, 5, utf8_decode(utf8_decode($empresa[0])), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(9, 5, utf8_decode("Sede: "), 0, 0, '');
$pdf->Cell(26, 5, utf8_decode(substr($empresa[1], 0, 16)), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
/* $pdf->Cell(22, 5, utf8_decode("Lote Planta: "), 0, 0, '');
$pdf->Cell(30, 5, utf8_decode($lote), "B", 0, 'C'); 
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');*/
/* $pdf->Cell(29, 5, utf8_decode("No Pollos Despresado: "), 0, 0, '');
$pdf->Cell(15, 5, utf8_decode(""), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(25, 5, utf8_decode("No Pollos Entero: "), 0, 0, '');
$pdf->Cell(15, 5, utf8_decode(""), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C'); */
$pdf->Cell(16, 5, utf8_decode("Tipo Pollo: "), 0, 0, '');
$pdf->Cell(18, 5, utf8_decode($row[25]), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(8, 5, utf8_decode("Nro: "), 0, 0, '');
$pdf->Cell(12, 5, utf8_decode($row[0]), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(35, 5, utf8_decode("Peso Inicial Despresado: "), 0, 0, '');
$pdf->Cell(17, 5, number_format($kilosNetoDes, 2), "B", 0, 'C');
$pdf->Ln(10);
$pdf->SetXY($pdf->GetX() + 51.5, $pdf->GetY());
$pdf->Cell(24, 5, utf8_decode("Cantidad Pollos: "), 0, 0, '');
$pdf->Cell(23, 5, utf8_decode($row[27]), "B", 0, 'C');
$pdf->Cell(24, 5, utf8_decode("Fecha Beneficio: "), 0, 0, '');
$pdf->Cell(23, 5, utf8_decode($row[24]), "B", 0, 'C');
$pdf->Cell(2, 5, utf8_decode(""), 0, 0, 'C');
$pdf->Cell(22, 5, utf8_decode("Fecha Proceso: "), 0, 0, '');
$pdf->Cell(23, 5, utf8_decode($row[26]), "B", 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(21.75, 5, "", 0, 0, 'C');
$pdf->Cell(49, 8, "", 1, 0, 'C');
$pdf->Cell(92.5, 4, utf8_decode("Peso por Despresado"), 1, 0, 'C');
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(87, 4, utf8_decode("Peso Producto con Proceso"), 1, 1, 'C');
$pdf->Cell(21.75, 5, "", 0, 0, 'C');
$pdf->Cell(49, 8, "", 0, 0, 'C');
$pdf->Cell(92.5, 4, utf8_decode("TOTAL"), 1, 0, 'C');
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(87, 4, utf8_decode("TOTAL PROCESADO"), 1, 1, 'C');
$pdf->Cell(21.75, 5, "", 0, 0, 'C');
$pdf->Cell(49, 8, utf8_decode("Item       Descripción"), 1, 0, '');
$pdf->Cell(20, 8, utf8_decode("Lote Planta"), 1, 0, 'C');
$pdf->Cell(14.5, 8, utf8_decode("Unids"), 1, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Kilos Bruto", 0, 'C');
$pdf->SetXY($pdf->GetX() + 119.75, $pdf->GetY() - 7);
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Cajas Base", 0, 'C');
$pdf->SetXY($pdf->GetX() + 134.25, $pdf->GetY() - 7);
$pdf->Cell(14.5, 8, utf8_decode("Cajas"), 1, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Kilos Netos", 0, 'C');
$pdf->SetXY($pdf->GetX() + 163.25, $pdf->GetY() - 7);
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Kilos Bruto", 0, 'C');
$pdf->SetXY($pdf->GetX() + 182.75, $pdf->GetY() - 7);
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Cajas Base", 0, 'C');
$pdf->SetXY($pdf->GetX() + 197.25, $pdf->GetY() - 7);
$pdf->Cell(14.5, 8, utf8_decode("Cajas"), 1, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 14.5, $pdf->GetY() + 1);
$pdf->MultiCell(14.5, 3, "Kilos Neto", 0, 'C');
$pdf->SetXY($pdf->GetX() + 211.75, $pdf->GetY() - 7);
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->Cell(14.5, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 30, $pdf->GetY() + 1);
$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(16.5, 3, utf8_decode("% Participación"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 240.75, $pdf->GetY() - 6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(14.5, 3, utf8_decode("%  Mejora"), 0, 'C');
$pdf->Ln(5);
$pdf->SetXY($pdf->GetX(), $pdf->GetY() - 4);
$pdf->SetFont('Arial', '', 8);
if ($items["059762"]["kg"] != 0) {
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059762", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("ALAS BLANCA MERCAMIO MARINADAS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5,"", 0, 0, 'C');
    $pdf->Cell(20, 10,"", 1, 0, 'C');
    //$pdf->Cell(20, 10,empty($lotes["059762"]) ? "" : $lotes["059762"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $row[27], 1, 0, 'C');
    $unidadesTotalDes += "";$unidadesTotalDesP += "";
    $pdf->Cell(14.5, 10, number_format($items["059762"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059762"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059762"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059762"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059762"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059762"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059762"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059762"]["kgNetos"], 2), 1, 0, 'C');
    if ($porcentajeAlaBlanca > 16) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($porcentajeAlaBlanca, 2) . "%", 1, 0, 'C');
    } else {
        $pdf->Cell(14.5, 10, number_format($porcentajeAlaBlanca, 2) . "%", 1, 0, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
    if ($mejoraAlaBlanca < 15 || $mejoraAlaBlanca > 22) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraAlaBlanca, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraAlaBlanca, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
    
}
if ($items["059760"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059760", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PECHUGA BLANCA MERCAMIO MARINADA"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(40, 5, "" , 0, 0, 'C');
    $pdf->Cell(29, 5, "" , 0, 0, 'C');
    //$pdf->Cell(20, 10,empty($lotes["059760"]) ? "" : $lotes["059760"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $row[27], 1, 0, 'C');
    $unidadesTotalDes += "";$unidadesTotalDesP += "";
    $pdf->Cell(14.5, 10, number_format($items["059760"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059760"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059760"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059760"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059760"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059760"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059760"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059760"]["kgNetos"], 2), 1, 0, 'C');
    if ($porcentajePechugaBlanca < 39) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10,number_format($porcentajePechugaBlanca, 2) . "%", 1, 0, 'C');
    } else {
        $pdf->Cell(14.5, 10,number_format($porcentajePechugaBlanca, 2) . "%", 1, 0, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
    if ($mejoraPechugaBlanca < 20 || $mejoraPechugaBlanca > 30) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPechugaBlanca, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPechugaBlanca, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059761"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059761", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PERNIL BLANCO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, "", 1, 0, 'C');
    $pdf->Cell(14.5, 10, $row[27], 1, 0, 'C');
    //$pdf->Cell(20, 10,empty($lotes["059761"]) ? "" : $lotes["059761"], 1, 0, 'C');
    //$pdf->Cell(14.5, 10, "" * 2, 1, 0, 'C');
    $unidadesTotalDes += "" * 2;$unidadesTotalDesP += "" * 2;
    $pdf->Cell(14.5, 10, number_format($items["059761"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059761"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059761"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059761"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059761"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059761"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059761"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059761"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($porcentajePernilBlanca,2) . "%", 1, 0, 'C');
    if ($mejoraPernilBlanco < 20 || $mejoraPernilBlanco > 30) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPernilBlanco, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPernilBlanco, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059759"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059759", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("POLLO BLANCO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["059759"]) ? "" : $lotes["059759"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    $unidadesTotalDesP += $nroPolloEntero;
    $pdf->Cell(14.5, 10, number_format($items["059759"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059759"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059759"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059759"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059759"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059759"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059759"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059759"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    if ($mejoraPolloBlanco < 17 || $mejoraPolloBlanco > 22) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPolloBlanco, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPolloBlanco, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059758"]["kg"] != 0) {
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059758", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("ALAS POLLO CAMPO MP MARINADAS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["059758"]) ? "" : $lotes["059758"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    $unidadesTotalDes += "";$unidadesTotalDesP += "";
    $pdf->Cell(14.5, 10, number_format($items["059758"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059758"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059758"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059758"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059758"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059758"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059758"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059758"]["kgNetos"], 2), 1, 0, 'C');
    if ($porcentajeAla > 16) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10,number_format($porcentajeAla, 2) . "%" , 1, 0, 'C');
    } else {
        $pdf->Cell(14.5, 10,number_format($porcentajeAla, 2) . "%" , 1, 0, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
    if ($mejoraAlas < 15 || $mejoraAlas > 22) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraAlas, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraAlas, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059756"]["kg"] != 0) {
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059756", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PECHUGA CAMPO MERCAMIO MARINADA"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["059756"]) ? "" : $lotes["059756"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    $unidadesTotalDes += "";$unidadesTotalDesP += "";
    $pdf->Cell(14.5, 10, number_format($items["059756"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059756"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059756"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059756"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059756"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059756"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059756"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059756"]["kgNetos"], 2), 1, 0, 'C');
    if ($porcentajePechuga < 39) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($porcentajePechuga, 2) . "%", 1, 0, 'C');
    } else {
        $pdf->Cell(14.5, 10, number_format($porcentajePechuga, 2) . "%", 1, 0, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
    if ($mejoraPechuga < 20 || $mejoraPechuga > 30) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPechuga, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPechuga, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059757"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059757", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PERNIL CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["059757"]) ? "" : $lotes["059757"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "" * 2, 1, 0, 'C');
    $unidadesTotalDes += "" * 2;$unidadesTotalDesP += "" * 2;
    $pdf->Cell(14.5, 10, number_format($items["059757"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059757"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059757"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059757"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059757"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059757"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059757"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059757"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10,number_format($porcentajePernil, 2) . "%", 1, 0, 'C');
    if ($mejoraPernil < 20 || $mejoraPernil > 30) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPernil, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPernil, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["059755"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "059755", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("POLLO CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["059755"]) ? "" : $lotes["059755"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $nroPolloEntero, 1, 0, 'C');
    $unidadesTotalDesP += $nroPolloEntero;
    $pdf->Cell(14.5, 10, number_format($items["059755"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059755"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["059755"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["059755"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059755"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059755"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["059755"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["059755"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    if ($mejoraPollo < 17 || $mejoraPollo > 22) {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(14.5, 10, number_format($mejoraPollo, 2) . "%", 1, 1, 'C');
    }else {
        $pdf->Cell(14.5, 10, number_format($mejoraPollo, 2) . "%", 1, 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0);
}
if ($items["042788"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "042788", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("C/MUSLO CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["042788"]) ? "" : $lotes["042788"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["042788"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["042788"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["042788"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["042788"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["042788"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["042788"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["042788"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["042788"]["kgNetos"], 2), 1, 1, 'C');
}
if ($items["042789"]["kg"] != 0) {

    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 10, "042789", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("MUSLO CAMPO MERCAMIO MARINADOS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10,empty($lotes["042789"]) ? "" : $lotes["042789"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, "", 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["042789"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["042789"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $items["042789"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($items["042789"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["042789"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["042789"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, $itemsM["042789"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 10, number_format($itemsM["042789"]["kgNetos"], 2), 1, 1, 'C');
}
if ($items["045401"]["kg"] != 0) {
    $pdf->Ln(2);
    $pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
    $pdf->Cell(11, 14, "045401", 1, 0, 'C');
    $pdf->Cell(38, 14, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 1);
    $pdf->MultiCell(38, 3, utf8_decode("BOMBONES POLLO CAMPO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 32.75, $pdf->GetY() + 1);
    $pdf->Cell(38, 5, "LOTE:_______________", 0, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 49, $pdf->GetY() - 8);
    $pdf->Cell(49, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 14,empty($lotes["045401"]) ? "" : $lotes["045401"], 1, 0, 'C');
    $pdf->Cell(14.5, 14, "", 1, 0, 'C');
    $pdf->Cell(14.5, 14, number_format($items["045401"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 14, $items["045401"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 14, $items["045401"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 14, number_format($items["045401"]["kgNetos"], 2), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(14.5, 14, number_format($itemsM["045401"]["kg"], 2), 1, 0, 'C');
    $pdf->Cell(14.5, 14, $itemsM["045401"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(14.5, 14, $itemsM["045401"]["cajas"], 1, 0, 'C');
    $pdf->Cell(14.5, 14, number_format($itemsM["045401"]["kgNetos"], 2), 1, 1, 'C');
}

$pdf->Ln(2);
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->Cell(233.5, 14, "", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY() - 14);
$pdf->Cell(47, 7, "SUBTOTAL POLLO DESPRESADO", 0, 0, 'C');
$pdf->Cell(22, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, $unidadesTotalDes, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($PesoTotalDes, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, $cajasBaseTotalDes, 0, 0, 'C');
$pdf->Cell(14.5, 7, $CajaTotalDes, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($kilosNetoDes, 2), 0, 0, 'C');
$pdf->Cell(5, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($PesoTotalMej, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, $cajasBaseTotalMej, 0, 0, 'C');
$pdf->Cell(14.5, 7, $CajaTotalMej, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($kilosNetoMej, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($totalPorcentaje, 2) . "%", 0, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->Cell(21, 7, "TOTAL BRUTO", 0, 0, 'C');
$pdf->Cell(48, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, $unidadesTotalDesP, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($PesoTotalDesP, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, $cajasBaseTotalDesP, 0, 0, 'C');
$pdf->Cell(14.5, 7, $CajaTotalDesP, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($kilosNetoDesP, 2), 0, 0, 'C');
$pdf->Cell(5, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($PesoTotalMejP, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, $cajasBaseTotalMejP, 0, 0, 'C');
$pdf->Cell(14.5, 7, $CajaTotalMejP, 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($kilosNetoMejP, 2), 0, 0, 'C');
$pdf->Cell(14.5, 7, "", 0, 0, 'C');
$pdf->Cell(14.5, 7, number_format($totalPorcentajeP, 2) . "%", 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(49, 5, utf8_decode("Estándar de Mejoramiento"), 1, 0, 'C');
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->Cell(49, 5, utf8_decode("Estándar de Participacíon por Pieza"), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->SetFont("Arial", "", 9);
$pdf->Cell(20, 5, "Pechuga", 1, 0, '');
$pdf->Cell(29, 5, "20%  al  30%", 1, 0, 'C');
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->Cell(20, 5, "Pechuga", 1, 0, '');
$pdf->Cell(29, 5, "Mayor  al  39%", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->Cell(20, 5, "Pernil", 1, 0, '');
$pdf->Cell(29, 5, "20%  al  30%", 1, 0, 'C');
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->Cell(20, 5, "Ala", 1, 0, '');
$pdf->Cell(29, 5, "Menor  al  16%", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->Cell(20, 5, "Ala", 1, 0, '');
$pdf->Cell(29, 5, "15%  al  22%", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 21.75, $pdf->GetY());
$pdf->Cell(20, 5, "Pollo Entero", 1, 0, '');
$pdf->Cell(29, 5, "17%  al  22%", 1, 0, 'C');

ob_end_clean();
$pdf->Output('formato_despresado.pdf', 'I');
?>