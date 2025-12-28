<?php
session_start();

//ini_set("display_errors", false);
ini_set('display_errors', '0');
error_reporting(0);

function fsalida($cad2)
{
    $uno = substr($cad2, 8, 2);
    $dos = substr($cad2, 5, 2);
    $tres = substr($cad2, 2, 2);
    return $uno . '-' . $dos . '-' . $tres;
}

function fsalidaconsecutivo($cad, $cad2)
{
    $uno = "213DM";
    $dos = str_pad($cad, 6, '0', STR_PAD_LEFT);
    $tres = substr($cad2, 2, 2);
    return $uno . "-" . $dos . "-" . $tres;
}

$fechainicial = (isset($_REQUEST['fechainicial'])) ? $_REQUEST['fechainicial'] : "";
$fechafinal = (isset($_REQUEST['fechafinal'])) ? $_REQUEST['fechafinal'] : "";
$fecha_actual = date("Y-m-d");
include("../config.php");

/* $sqlg = "SELECT * 
        FROM recepcionpollo 
        "; */
$filtroFecha = "";
if (!empty($fechainicial) && !empty($fechafinal)) {
    $filtroFecha = "WHERE DATE(fecharec) BETWEEN '$fechainicial' AND '$fechafinal'";
} elseif (!empty($fechaInicial)) {
    $filtroFecha = "WHERE DATE(fecharec) >= '$fechainicial'";
} elseif (!empty($fechaFinal)) {
    $filtroFecha = "WHERE DATE(fecharec) <= '$fechafinal'";
}

$sqlg = "SELECT * FROM recepcionpollo $filtroFecha";

$c = mysqli_query($link, $sqlg) or die("aqui 1 " . mysqli_error($link));

/* $sql3 = "SELECT empresa, sede, direccion, municipio 
         FROM destinos 
         WHERE id='" . $rs2['destino'] . "'";
$c3 = mysqli_query($link, $sql3) or die("aqui 2 " . mysqli_error($link));
$rs3 = mysqli_fetch_array($c3);

$sql30 = "SELECT sede, municipio 
          FROM beneficio 
          WHERE id='" . $rs2['beneficio'] . "'";
$c30 = mysqli_query($link, $sql30) or die("aqui 2 " . mysqli_error($link));
$rs30 = mysqli_fetch_array($c30);

$sql4 = "SELECT nombres, telefono 
         FROM responsables 
         WHERE cedula='" . $rs2['responsable'] . "'";
$c4 = mysqli_query($link, $sql4) or die("aqui 3 " . mysqli_error($link));
$rs4 = mysqli_fetch_array($c4);

$sql40 = "SELECT nombres 
          FROM conductores_recepcion 
          WHERE cedula='" . $rs2['conductor'] . "'";
$c40 = mysqli_query($link, $sql40) or die("aqui 3 " . mysqli_error($link));
$rs40 = mysqli_fetch_array($c40); */

/* $sql = "SELECT distinct plantilla.item, descripcion, lote
        FROM plantilla, item_proveedor 
        WHERE plantilla.item = item_proveedor.item 
        AND item_proveedor.proveedor = " . $id . "
        ORDER BY registro DESC"; */
/* $sql = "SELECT DISTINCT p.item,ip.lote, p.descripcion 
        FROM plantilla p
        JOIN item_proveedor ip ON p.item = ip.item
        WHERE ip.proveedor = " . $id . "
        ORDER BY p.descripcion ASC";
$c = mysqli_query($link, $sql) or die("aqui 1:" . mysqli_error($link)); */

/* $sql5 = "SELECT *
        FROM recepcion_pesos 
        WHERE id_recepcion = " . $id . "
        ORDER BY turno ASC";
$c5 = mysqli_query($link, $sql5) or die("aqui 1:" . mysqli_error($link)); */
//$rs5 = mysqli_fetch_array($c5);

/* $resultado = [];

while ($row = mysqli_fetch_assoc($c5)) {
    $resultado[] = $row;
} */
/* $canales = count($resultado);

$horaInicio = $resultado[0];
$horaFin = $resultado[$canales - 1];

$inicio = date('H:i', strtotime($horaInicio['registro']));
$fin = date('H:i', strtotime($horaFin['registro']));

$inicioDateTime = new DateTime($inicio);
$finDateTime = new DateTime($fin);
$diferencia = $inicioDateTime->diff($finDateTime); */

/* $tiempoTranscurrido = $diferencia->format('%H:%I');

$sql6 = "SELECT nombres, telefono 
        FROM conductores 
        WHERE cedula=" . $rs2['conductor'];
$c6 = mysqli_query($link, $sql6) or die(mysqli_error($link));
$rs6 = mysqli_fetch_array($c6); */

$hora = explode(":", $_SESSION["fechaInicio"]);
$horaActual = date("G:i");
$horaActual = explode(":", $horaActual);

require('pdf/fpdf.php');
class PDF extends FPDF {}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Letter');

$pdf->AddFont('DejaVu', '', 'DejaVuSans.php', true);

$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);

$logo = '../assets/img/logo-mercamio.jpg';
$pdf->Image($logo, 10, 7, 50, 15);
$pdf->ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY($pdf->GetX() + 60, $pdf->GetY() - 12);
$pdf->multiCell(190, 6, utf8_decode('FORMATO RECEPCIÓN POLLO                                                        '), 1, 0, 'C');
$pdf->SetXY($pdf->GetX() + 250, $pdf->GetY() - 12);
$pdf->Cell(30, 6, '', 0, 1, 'C');
$pdf->SetXY($pdf->GetX() + 250, $pdf->GetY());
$pdf->Cell(30, 6, utf8_decode('Página 1 de 1'), 1, 1, 'C');
$pdf->Cell(60, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(40, 5, utf8_decode('AREA'), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode('FECHA DE CREACIÓN'), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode('FECHA DE REVISIÓN'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('VERSIÓN'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('CÓDIGO'), 1, 1, 'C');
$pdf->Cell(60, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(40, 5, utf8_decode('Producción'), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode('7/11/2023'), 1, 0, 'C');
$pdf->Cell(70, 5, utf8_decode('7/11/2024'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('3'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('FTO-PDA-06'), 1, 1, 'C');
$pdf->ln(35.5);

$encabezado = '../assets/img/encabezado.jpg';
$pdf->Image($encabezado, 9.3, 25, 282, 35);
$i = 0;
while ($row = mysqli_fetch_array($c)) {
    $sql7 = "SELECT proveedor FROM recepcion_pesos_pollo WHERE proveedor =".$row["id_recepcion"];
    if (mysqli_num_rows(mysqli_query($link, $sql7)) == 0) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(280.4, 6,utf8_decode("ANULADO"), 1, 0, 'C');
        $pdf->Ln();
    }else{
        $sqlg2 = "SELECT lote,sum(CAST(REPLACE(REPLACE(peso_real, '.', ''), ',', '.') AS DECIMAL(10,2))) as peso, sum(unidades) as unidades, temperatura
        FROM recepcion_pesos_pollo 
        WHERE proveedor=" . $row['id_recepcion'] . "";
        $c2 = mysqli_query($link, $sqlg2) or die("aqui 10 " . mysqli_error($link));

        $sqlg3 = "SELECT sede as proveedor 
                FROM proveedorpollo 
                WHERE id=" . $row['beneficio'] . "";
        $c3 = mysqli_query($link, $sqlg3) or die("aqui 10 " . mysqli_error($link));
        $row3 = mysqli_fetch_array($c3);

        $row['guiat'] != '' ? $checkmark3 = chr(51) : $checkmark3 = chr(53);

        $row['ccoh1'] == 1 ? $checkmark1 = chr(51) : $checkmark1 = chr(53);
        $row['ccoh2'] == 1 ? $checkmark2 = chr(51) : $checkmark2 = chr(53);

        /* $rs['cph3'] == 1 ? $checkmark3 = chr(51) : $checkmark3 = chr(53);
        $rs['cph4'] == 1 ? $checkmark4 = chr(51) : $checkmark4 = chr(53);
        $rs['cph5'] == 1 ? $checkmark5 = chr(51) : $checkmark5 = chr(53);
        */

        $sql8 = "SELECT lote FROM lotes_guia WHERE recepcion_guia = ".$row["id_recepcion"];
        $rs_operation8 = mysqli_query($link, $sql8) or die("aqui 10 " . mysqli_error($link));
        $numLotes = mysqli_num_rows($rs_operation8);
        $altura = 0;
        $piezas = 0;

        if ($row["tipo"] == 'BLANCO_DESPRESE' || $row["tipo"] == 'CAMPO_DESPRESE') {
            $items = [
                "059762" => "ALAS BLANCA",
                "059760" => "PECHUGA BLANCA",
                "059761" => "PERNIL BLANCO",
                "059758" => "ALAS CAMPO",
                "059756" => "PECHUGA CAMPO",
                "059757" => "PERNIL CAMPO"
            ];

            $sql9 = "SELECT 
                rec.item, 
                SUM(CAST(REPLACE(REPLACE(peso_real, '.', ''), ',', '.') AS DECIMAL(10,2))) as peso_total,
                rec.temperatura
            FROM recepcion_pesos_pollo rec
            INNER JOIN 
                plantilla p ON p.item = rec.item
            WHERE proveedor = '$row[id_recepcion]'
            GROUP BY item
            ORDER BY p.descripcion ASC";
            $rs_operation9 = mysqli_query($link, $sql9) or die("aqui 10 " . mysqli_error($link));
            $row9 = [];
            while ($row11 = mysqli_fetch_assoc($rs_operation9)) {
                $row9[] = array(
                    "item" => $row11["item"], 
                    "peso_total" => $row11["peso_total"], 
                    "temperatura" => $row11["temperatura"]
                );
            }
            if (mysqli_num_rows($rs_operation9) > 0) {
                $piezas = mysqli_num_rows($rs_operation9);
                if ($piezas == 3) {
                    if ($numLotes == 1) {
                        $numLotes += 2;
                    } elseif ($numLotes == 2) {
                        $numLotes += 1;
                    }
                }
            }
        }

        if ($numLotes > 2) {
            $altura = 2.5;
            $numLotes = $numLotes - 2;
        }

        if ($row2 = mysqli_fetch_array($c2)) {
        $i += 1;
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(12.9, 5 + ($altura * $numLotes), "", 1, 0, 'T');
        $pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + ($altura * $numLotes) / 2);
        $pdf->Cell(12.9, 2.5, fsalida($row['fecharec']), 0, 0, 'C');
        $pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 2.5);
        $pdf->Cell(12.9, 2.5, substr($row["lote_planta"], 0, 10), 0, 0, 'C');
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - (2.5 + $altura * ($numLotes - ($numLotes / 2))));
        $pdf->Cell(8.9, 5 + ($altura * $numLotes), $row['id_recepcion'], 1, 0, 'C');
        if ($piezas > 0) {
            $pdf->Cell(20.7, 5 + ($altura * $numLotes), "", 1, 0, 'C');
            $pdf->SetXY($pdf->GetX() - 20.7, $pdf->GetY());
            if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 1.25);
            }
            for ($i=0; $i < $piezas; $i++) { 
                $pdf->Cell(20.7, 2.5, $items[$row9[$i]["item"]], 0, 0, 'L');
                if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                    $pdf->SetXY($pdf->GetX() - 20.7, $pdf->GetY() + 3.75);
                } else {
                    $pdf->SetXY($pdf->GetX() - 20.7, $pdf->GetY() + 2.5);
                }
            }
            if ($piezas == 3) {
                $pdf->SetXY($pdf->GetX() + 20.7, $pdf->GetY() - 7.5);
            }else {
                $pdf->SetXY($pdf->GetX() + 20.7, $pdf->GetY() - 5);
            }
        } else {
            $pdf->Cell(20.7, 5 + ($altura * $numLotes), $row['especie'].' '.$row['tipo'], 1, 0, 'C');
        }
        $pdf->Cell(18.5, 5 + ($altura * $numLotes), substr($row3['proveedor'], 0, 12), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(14.8, 5 + ($altura * $numLotes), "", 1, 0, 'C');
        if (mysqli_num_rows($rs_operation8) == 0) {
            $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY() + 1.25);
            $pdf->Cell(14.8, 2.5, $row2["lote"], 0, 0, 'C');
            $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 1.25);
        }else {
            if (mysqli_num_rows($rs_operation8) == 1) {
                $lote = mysqli_fetch_row($rs_operation8);
                $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY() + 1.25);
                $pdf->Cell(14.8, 2.5, $lote[0], 0, 0, 'C');
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 1.25);
            } elseif (mysqli_num_rows($rs_operation8) == 2 && $piezas == 3) {
                $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY() + 1.25);
                while ($lote = mysqli_fetch_row($rs_operation8)) {
                    $pdf->Cell(14.8, 2.5, $lote[0], 0, 0, 'C');
                    $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY() + 2.5);
                }
                $pdf->SetXY($pdf->GetX() + 14.8, $pdf->GetY() - 6.25);
            } else {
                $cont = 0;
                while ($lotes = mysqli_fetch_row($rs_operation8)) {
                    if ($cont == 0) {
                        $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY());
                    }else {
                        $pdf->SetXY($pdf->GetX() - 14.8, $pdf->GetY() + 2.5);
                    }
                    $pdf->Cell(14.8, 2.5, substr($lotes[0], 0, 10), 0, 0, 'C');
                    $cont++;
                }
                if (mysqli_num_rows($rs_operation8) == 2) {
                    $pdf->SetXY($pdf->GetX(), $pdf->GetY() - (2.5 * 1));
                }else {
                    if ($numLotes > 0) {
                        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - ($altura * ($numLotes + 1)));
                    }
                }
            }
        }
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(17, 5 + ($altura * $numLotes), fsalida($row['fechasac']), 1, 0, 'C');

        if ($piezas > 0) {
            $pdf->Cell(9.9, 5 + ($altura * $numLotes), "", 1, 0, 'C');
            $pdf->SetXY($pdf->GetX() - 9.9, $pdf->GetY());
            if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 1.25);
            }
            for ($i=0; $i < $piezas; $i++) { 
                $pdf->Cell(9.9, 2.5, number_format($row9[$i]["peso_total"], 1, ',', '.'), 0, 0, 'C');
                if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                    $pdf->SetXY($pdf->GetX() - 9.9, $pdf->GetY() + 3.75);
                } else {
                    $pdf->SetXY($pdf->GetX() - 9.9, $pdf->GetY() + 2.5);
                }
            }
            if ($piezas == 3) {
                $pdf->SetXY($pdf->GetX() + 9.9, $pdf->GetY() - 7.5);
            }else {
                $pdf->SetXY($pdf->GetX() + 9.9, $pdf->GetY() - 5);
            }
        } else {
            $pdf->Cell(9.9, 5 + ($altura * $numLotes), number_format($row2['peso'], 1, ',', '.'), 1, 0, 'C');
        }

        if ($piezas > 0) {
            $pdf->Cell(13.5, 5 + ($altura * $numLotes), "", 1, 0, 'C');
            $pdf->SetXY($pdf->GetX() - 13.5, $pdf->GetY());
            if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 1.25);
            }
            for ($i=0; $i < $piezas; $i++) {
                if ($row9[$i]["item"] == "059761" || $row9[$i]["item"] == "059757") {
                    $pdf->Cell(13.5, 2.5, $row["canales"] * 2, 0, 0, 'C');
                } else {
                    $pdf->Cell(13.5, 2.5, $row["canales"], 0, 0, 'C');
                }
                if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                    $pdf->SetXY($pdf->GetX() - 13.5, $pdf->GetY() + 3.75);
                } else {
                    $pdf->SetXY($pdf->GetX() - 13.5, $pdf->GetY() + 2.5);
                }
            }
            if ($piezas == 3) {
                $pdf->SetXY($pdf->GetX() + 13.5, $pdf->GetY() - 7.5);
            }else {
                $pdf->SetXY($pdf->GetX() + 13.5, $pdf->GetY() - 5);
            }
        } else {
            $pdf->Cell(13.5, 5 + ($altura * $numLotes), $row2['unidades'], 1, 0, 'C');
        }

        $pdf->Cell(7, 5 + ($altura * $numLotes), $row['chv1'], 1, 0, 'C');
        $pdf->SetFont('ZapfDingbats', '', 8);
        $pdf->Cell(6.9, 5 + ($altura * $numLotes), $checkmark3, 1, 0, 'C');
        $pdf->SetFont('Arial', '', 6);

        if ($piezas > 0) {
            $pdf->Cell(6.8, 5 + ($altura * $numLotes), "", 1, 0, 'C');
            $pdf->SetXY($pdf->GetX() - 6.8, $pdf->GetY());
            if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 1.25);
            }
            for ($i=0; $i < $piezas; $i++) { 
                $pdf->Cell(6.8, 2.5, $row9[$i]["temperatura"], 0, 0, 'C');
                if ($piezas == 1 && mysqli_num_rows($rs_operation8) < 3) {
                    $pdf->SetXY($pdf->GetX() - 6.8, $pdf->GetY() + 3.75);
                } else {
                    $pdf->SetXY($pdf->GetX() - 6.8, $pdf->GetY() + 2.5);
                }
            }
            if ($piezas == 3) {
                $pdf->SetXY($pdf->GetX() + 6.8, $pdf->GetY() - 7.5);
            }else {
                $pdf->SetXY($pdf->GetX() + 6.8, $pdf->GetY() - 5);
            }
        } else {
            $pdf->Cell(6.8, 5 + ($altura * $numLotes), $row2['temperatura'], 1, 0, 'C');
        }

        $pdf->SetFont('ZapfDingbats', '', 8);
        $pdf->Cell(6.9, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7.8, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(6.8, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(9.5, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(4.6, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(5.9, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(5.8, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(5.4, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(8.2, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(6.1, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7.6, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7.3, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7.3, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(7.2, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(19, 5 + ($altura * $numLotes), $checkmark1, 1, 0, 'C');
        $pdf->Cell(21.1, 5 + ($altura * $numLotes), '', 1, 0, 'C');

        if (mysqli_num_rows($rs_operation8) <= 2) {
            $y = $pdf->GetY() - 0.8;
        }else{
            $y = $pdf->GetY() - 0.8 + (($altura - 1.4) * $numLotes);
        }
        

        $x = 270;
        $firma = '../assets/img/firmas/'.$row['responsable'].'.jpg';
        $pdf->Image($firma, $x, $y + 1, 15, 5);
        $pdf->ln();

        }
    }

}


ob_end_clean();
$pdf->Output('formato_recepcion.pdf', 'D');
?>