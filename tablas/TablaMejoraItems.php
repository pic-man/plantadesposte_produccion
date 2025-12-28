<?php
session_start();
include('../config.php');

$ojo = "N";

$idGuia = $_POST["id"] ? $_POST["id"] : "";

$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'mejora_items.id',
    1   =>  'mejora_items.item',
    2   =>  'plantilla.descripcion',
    3   =>  'kilos_mejora',
    4   =>  'cajas_mejora',
    5   =>  'lote'
);

$sql = "SELECT
    mejora_items.id,
    mejora_items.item,
    plantilla.descripcion,
    kilos_mejora,
    cajas_mejora,
    lote,
    cajas_mejora_base,
    mejora_items.StatusPrinter,
    mejora_items.id_guia
    FROM mejora_items
    INNER JOIN
        plantilla ON mejora_items.item = plantilla.item
    WHERE id_guia = '$idGuia'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (mejora_items.id LIKE '%$busqueda%' 
                OR mejora_items.item LIKE '%$busqueda%' 
                OR plantilla.descripcion LIKE '%$busqueda%' 
                OR kilos_mejora LIKE '%$busqueda%' 
                OR cajas_mejora LIKE '%$busqueda%' 
                OR lote LIKE '%$busqueda%')";
}

$totalDataQuery = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($totalDataQuery);

// Obtener el total con los filtros aplicados
$totalFilterQuery = mysqli_query($link, $sql);
$totalFilter = mysqli_num_rows($totalFilterQuery);
$sql .= " ORDER BY mejora_items.id DESC";



$query = mysqli_query($link, $sql) or die(mysqli_error($link));

$data = array();

$sql2 = "SELECT item, kilos, cajas, canastilla_base, status FROM desprese_items WHERE guia = '$idGuia'";
$query2 = mysqli_query($link, $sql2);

$pesoPechuga = 0;
$pesoPechugaBlanca = 0;
$pesoPernil = 0;
$pesoPernilBlanca = 0;
$pesoAla = 0;
$pesoAlaBlanca = 0;
/* $pesoPollo = 0;
$pesoPolloBlanco = 0; */

while ($pesos = mysqli_fetch_assoc($query2)) {
    if ($pesos["status"] != "1") {
        $peso = $pesos["kilos"] - (($pesos["cajas"] * 2) + ($pesos["canastilla_base"] * 1.8));
    } else {
        $peso = $pesos["kilos"];
    }
    if ($pesos["item"] == "059756") {
        $pesoPechuga += $peso;
    }
    if ($pesos["item"] == "059757") {
        $pesoPernil += $peso;
    }
    if ($pesos["item"] == "059758") {
        $pesoAla += $peso;
    }
    /* if ($pesos[0] == "059755") {
        $pesoPollo += $pesos[1];
    } */
    if ($pesos["item"] == "059762") {
        $pesoAlaBlanca += $peso;
    }
    if ($pesos["item"] == "059760") {
        $pesoPechugaBlanca += $peso;
    }
    if ($pesos["item"] == "059761") {
        $pesoPernilBlanca += $peso;
    }
    /* if ($pesos[0] == "059759") {
        $pesoPolloBlanco += $pesos[1];
    } */
}

$pesoPechuga = number_format($pesoPechuga, 2, ".", "");
$pesoPechugaBlanca = number_format($pesoPechugaBlanca, 2, ".", "");
$pesoPernil = number_format($pesoPernil, 2, ".", "");
$pesoPernilBlanca = number_format($pesoPernilBlanca, 2, ".", "");
$pesoAla = number_format($pesoAla, 2, ".", "");
$pesoAlaBlanca = number_format($pesoAlaBlanca, 2, ".", "");

$sql5 = "SELECT SUM(kilos) AS kilos, SUM(cajas) AS cajas, SUM(cajas_base) AS cajas_base FROM pesos_pollo WHERE guia = '$idGuia' AND tipo_pollo = 'ENTERO' GROUP BY tipo_pollo";
$query5 = mysqli_query($link, $sql5);
if (mysqli_num_rows($query5) > 0) {
    $pesosPollo = mysqli_fetch_assoc($query5);
    $pesoPollo = $pesosPollo["kilos"] - (($pesosPollo["cajas"] * 2) + ($pesosPollo["cajas_base"] * 1.8));
} else {
    $pesoPollo = 0;
}

$pesoPollo = number_format($pesoPollo, 2, ".", "");



$porcentajePechuga20 = $pesoPechuga * 0.20;
$porcentajePechuga30 = $pesoPechuga * 0.30;
$porcentajePechugaBlanca20 = $pesoPechugaBlanca * 0.20;
$porcentajePechugaBlanca30 = $pesoPechugaBlanca * 0.30;
$porcentajePernil20 = $pesoPernil * 0.20;
$porcentajePernil30 = $pesoPernil * 0.30;
$porcentajePernilBlanca20 = $pesoPernilBlanca * 0.20;
$porcentajePernilBlanca30 = $pesoPernilBlanca * 0.30;
$porcentajeAla15 = $pesoAla * 0.15;
$porcentajeAla22 = $pesoAla * 0.22;
$porcentajeAlaBlanca15 = $pesoAlaBlanca * 0.15;
$porcentajeAlaBlanca22 = $pesoAlaBlanca * 0.22;
$porcentajePollo17 = $pesoPollo * 0.17;
$porcentajePollo22 = $pesoPollo * 0.22;
$porcentajePolloBlanco17 = $pesoPollo * 0.17;
$porcentajePolloBlanco22 = $pesoPollo * 0.22;

$sql3 = "SELECT item, kilos_mejora, cajas_mejora, cajas_mejora_base FROM mejora_items WHERE id_guia = '$idGuia'";
$query3 = mysqli_query($link, $sql3);

$mejoraPechuga = 0;
$mejoraPechugaBlanca = 0;
$mejoraPernil = 0;
$mejoraPernilBlanca = 0;
$mejoraAla = 0;
$mejoraAlaBlanca = 0;
$mejoraPollo = 0;
$mejoraPolloBlanco = 0;

while ($pesosMejora = mysqli_fetch_assoc($query3)) {
    $pesoMejora = $pesosMejora["kilos_mejora"] - (($pesosMejora["cajas_mejora"] * 2) + ($pesosMejora["cajas_mejora_base"] * 1.8));
    if ($pesosMejora["item"] == "059756") {
        $mejoraPechuga += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059757") {
        $mejoraPernil += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059758") {
        $mejoraAla += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059755") {
        $mejoraPollo += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059762") {
        $mejoraAlaBlanca += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059760") {
        $mejoraPechugaBlanca += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059761") {
        $mejoraPernilBlanca += $pesoMejora;
    }
    if ($pesosMejora["item"] == "059759") {
        $mejoraPolloBlanco += $pesoMejora;
    }
}

$sql4 = "SELECT 
    item,
    SUM(cajas_mejora)
    FROM 
        mejora_items
    WHERE 
        id_guia = '$idGuia' AND item = '059762' OR
        id_guia = '$idGuia' AND item = '059758' OR
        id_guia = '$idGuia' AND item = '059760' OR
        id_guia = '$idGuia' AND item = '059756' OR
        id_guia = '$idGuia' AND item = '059761' OR
        id_guia = '$idGuia' AND item = '059757' OR
        id_guia = '$idGuia' AND item = '059759' OR
        id_guia = '$idGuia' AND item = '059755'
    GROUP BY item
    ";

$rs_operation4 = mysqli_query($link, $sql4);
$cajas = array();

while ($row2 = mysqli_fetch_row($rs_operation4)) {
    $cajas[$row2[0]] = $row2[1];
}

$cajasB = "fino";
$cajasC = "fino";

if (((!empty($cajas["059758"]) ? $cajas["059758"] : 1) * 2) != (!empty($cajas["059756"]) ? $cajas["059756"] : 0) || ((!empty($cajas["059758"]) ? $cajas["059758"] : 1) * 2) != (!empty($cajas["059757"]) ? $cajas["059757"] : 0)) {
    $cajasC = "";
}
if (((!empty($cajas["059762"]) ? $cajas["059762"]: 1) * 2) != (!empty($cajas["059760"]) ? $cajas["059760"] : 0) || ((!empty($cajas["059762"]) ? $cajas["059762"]: 1) * 2) != (!empty($cajas["059761"]) ? $cajas["059761"] : 0)) {
    $cajasB = "";
}

$totalCajas = 0;
$totalKilos = 0;
$totalCajasBase = 0;
$totalKilosNeto = 0;

$maxCaja = 0;

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $totalKilos += $row[3];
    $totalCajas += $row[4];
    $totalCajasBase += $row[6];
    $totalKilosNeto += $row[3] - (($row[4] * 2) + ($row[6] * 1.8));

    if ($row[4] > $maxCaja) {
        $maxCaja = $row[4];
    }

    if ($row[1] == "059756") {
        if ($mejoraPechuga < ($pesoPechuga + $porcentajePechuga20) || $mejoraPechuga > ($pesoPechuga + $porcentajePechuga30)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059757") {
        if ($mejoraPernil < ($pesoPernil + $porcentajePernil20) || $mejoraPernil > ($pesoPernil + $porcentajePernil30)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "<br>".$pesoPernil." - ".$mejoraPernil."</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059758") {
        if ($mejoraAla < ($pesoAla + $porcentajeAla15) || $mejoraAla > ($pesoAla + $porcentajeAla22)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "<br></center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059755") {
        if ($mejoraPollo < ($pesoPollo + $porcentajePollo17) || $mejoraPollo > ($pesoPollo + $porcentajePollo22)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . $row[4] . "</center>";
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059759") {
        if ($mejoraPolloBlanco < ($pesoPollo + $porcentajePolloBlanco17) || $mejoraPolloBlanco > ($pesoPollo + $porcentajePolloBlanco22)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . $row[4] . "</center>";
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059762")  {
        if ($mejoraAlaBlanca > ($pesoAlaBlanca + $porcentajeAlaBlanca22) || $mejoraAlaBlanca < ($pesoAlaBlanca + $porcentajeAlaBlanca15)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059760")  {
        if ($mejoraPechugaBlanca < ($pesoPechugaBlanca + $porcentajePechugaBlanca20) || $mejoraPechugaBlanca > ($pesoPechugaBlanca + $porcentajePechugaBlanca30)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } elseif ($row[1] == "059761")  {
        if ($mejoraPernilBlanca < ($pesoPernilBlanca + $porcentajePernilBlanca20) || $mejoraPernilBlanca > ($pesoPernilBlanca + $porcentajePernilBlanca30)) {
            $subdata[] = "<center>" . "<a style='color:red'>".$row[0]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[1]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[2]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[5]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format($row[3], 2, ".", ",")."</a>" ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".$row[6]."</a>" . "</center>";
            $subdata[] = "<center>" . "<a style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</a>" . "</center>";
            $ojo = "R";
        } else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            } else {
                $subdata[] = "<center>" . "<a style='color:red'>".$row[4]."</a>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } else {
        $subdata[] = "<center>" . $row[0] . "</center>";
        $subdata[] = "<center>" . $row[1] . "</center>";
        $subdata[] = "<center>" . $row[2] . "</center>";
        $subdata[] = "<center>" . $row[5] . "</center>";
        $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."<br><a title='Peso Promedio'" . (($row[3] / $row[4]) > 25 ? " style='color:red'" : "") . ">" . number_format(($row[3] / $row[4])) . " kg</a>". "</center>";
        $subdata[] = "<center>" . $row[4] . "</center>";
        $subdata[] = "<center>" . $row[6] . "</center>";
        $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
    }
    

    $acciones = '<a onclick="edit_mejora(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 me-1 text-warning"></i></a>';
    $acciones .= '<a onclick="borrarMejora(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 me-1 text-danger"></i></a>';
    
    /* if ($row[7] == 0) {
        $acciones .= '<a onclick="actualizarItemStatus(\''.$row[0].'\',1)" href="./controlador/imprimirItem.php?guia='.$row[8].'&item='.$row[1].'&id='.$row[0].'" target="_blank" title="Imprimir Item"><i class="bi bi-printer fs-2 text-primary"></i></a>';
    } else {
        $acciones .= '<a href="./controlador/imprimirItem.php?guia='.$row[8].'&item='.$row[1].'&id='.$row[0].'" target="_blank" title="Imprimir Item"><i class="bi bi-printer fs-2 text-secondary"></i></a>';
    } */

    
    $subdata[] = $acciones;

    $data[] = $subdata;

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT
            mic.id AS idItem,
            mic.item AS item,
            p.descripcion AS nombreItem,
            mic.lote AS lote,
            mic.kilos_mejora AS kilos_mejora,
            mic.cajas_mejora AS cajas_mejora,
            mic.cajas_mejora_base AS cajas_mejora_base,
            mic.kilos_mejora - ((mic.cajas_mejora * 2) + (mic.cajas_mejora_base * 1.8)) AS kilosNeto
        FROM mejora_items_cambios mic
        INNER JOIN plantilla p ON p.item = mic.item
        WHERE mic.id = '$row[0]'
        ORDER BY mic.idCambio DESC";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                $subData = array();

                $subData[] = "<span style='color: #DAA520'>". $rowCambios["idItem"] ."</span>";
                $subData[] = "<span style='color: #DAA520'>". $rowCambios["item"] ."</span>";
                $subData[] = "<span style='color: #DAA520'>". $rowCambios["nombreItem"] ."</span>";
                $subData[] = "<span style='color: #DAA520'>". $rowCambios["lote"] ."</span>";
                $subData[] = "<span style='color: #DAA520'>". number_format($rowCambios["kilos_mejora"], 2, ".", ",") ."</span>";
                $subData[] = "<span style='color: #DAA520'>". number_format($rowCambios["cajas_mejora"], 0, ".", ",") ."</span>";
                $subData[] = "<span style='color: #DAA520'>". number_format($rowCambios["cajas_mejora_base"], 0, ".", ",") ."</span>";
                $subData[] = "<span style='color: #DAA520'>". number_format($rowCambios["kilosNeto"], 2, ".", ",") ."</span>";
                $subData[] = "";

                $data[] = $subData;
            }
        }
    }
}

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = number_format($totalKilos, 2, ".", ",");
$subdata[] = number_format($totalCajas, 0, ",", ".");
$subdata[] = number_format($totalCajasBase, 0, ",", ".");
$subdata[] = number_format($totalKilosNeto, 2, ".", ",");
$subdata[] = '';

$data[] = $subdata;

$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data,
    "ojo"              => $ojo,
    "maxCaja"          => $maxCaja
);
echo json_encode($json_data);
?>