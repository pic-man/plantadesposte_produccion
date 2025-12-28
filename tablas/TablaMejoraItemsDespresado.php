<?php
include('../config.php');

$idGuia = $_POST["id"] ? $_POST["id"] : "";
$ojo = "N";

$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'mejora_items_despresado.id',
    1   =>  'mejora_items_despresado.item',
    2   =>  'plantilla.descripcion',
    3   =>  'kilos_mejora',
    4   =>  'cajas_mejora',
    5   =>  'lote'
);

$sql = "SELECT
    mejora_items_despresado.id,
    mejora_items_despresado.item,
    plantilla.descripcion,
    kilos_mejora,
    cajas_mejora,
    lote,
    cajas_mejora_base
    FROM mejora_items_despresado
    INNER JOIN
        plantilla ON mejora_items_despresado.item = plantilla.item
    WHERE id_guia = '$idGuia'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (mejora_items_despresado.id LIKE '%$busqueda%' 
                OR mejora_items_despresado.item LIKE '%$busqueda%' 
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
$sql .= " ORDER BY mejora_items_despresado.id DESC";

$query = mysqli_query($link, $sql) or die(mysqli_error($link));

$data = array();

$sql2 = "SELECT item, kilos, cajas, canastilla_base FROM despresado_items WHERE guia = '$idGuia'";
$query2 = mysqli_query($link, $sql2);

$pesoPechuga = 0;
$pesoPechugaBlanca = 0;
$pesoPernil = 0;
$pesoPernilBlanca = 0;
$pesoAla = 0;
$pesoAlaBlanca = 0;

while ($pesos = mysqli_fetch_row($query2)) {
    if ($pesos[0] == "059756") {
        $pesoPechuga += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    if ($pesos[0] == "059757") {
        $pesoPernil += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    if ($pesos[0] == "059758") {
        $pesoAla += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    if ($pesos[0] == "059762") {
        $pesoAlaBlanca += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    if ($pesos[0] == "059760") {
        $pesoPechugaBlanca += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    if ($pesos[0] == "059761") {
        $pesoPernilBlanca += $pesos[1] - (($pesos[2] * 2) + ($pesos[3] * 1.8));
    }
    /* if ($pesos[0] == "059759") {
        $pesoPolloBlanco += $pesos[1];
    } */
}

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

$sql3 = "SELECT item, kilos_mejora, cajas_mejora, cajas_mejora_base FROM mejora_items_despresado WHERE id_guia = '$idGuia'";
$query3 = mysqli_query($link, $sql3);

$mejoraPechuga = 0;
$mejoraPechugaBlanca = 0;
$mejoraPernil = 0;
$mejoraPernilBlanca = 0;
$mejoraAla = 0;
$mejoraAlaBlanca = 0;
$mejoraPollo = 0;
$mejoraPolloBlanco = 0;

while ($pesosMejora = mysqli_fetch_row($query3)) {
    if ($pesosMejora[0] == "059756") {
        $mejoraPechuga += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059757") {
        $mejoraPernil += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059758") {
        $mejoraAla += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059755") {
        $mejoraPollo += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059762") {
        $mejoraAlaBlanca += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059760") {
        $mejoraPechugaBlanca += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059761") {
        $mejoraPernilBlanca += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
    if ($pesosMejora[0] == "059759") {
        $mejoraPolloBlanco += $pesosMejora[1] - (($pesosMejora[2] * 2) + ($pesosMejora[3] * 1.8));
    }
}

$sql4 = "SELECT 
    item,
    SUM(cajas_mejora)
    FROM 
        mejora_items_despresado
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

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $totalKilos += $row[3];
    $totalCajas += $row[4];
    $totalCajasBase += $row[6];
    $totalKilosNeto += $row[3] - (($row[4] * 2) + ($row[6] * 1.8));

    if ($row[1] == "059756") {
        if ($mejoraPechuga < ($pesoPechuga + $porcentajePechuga20) || $mejoraPechuga > ($pesoPechuga + $porcentajePechuga30)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    }elseif ($row[1] == "059757") {
        if ($mejoraPernil < ($pesoPernil + $porcentajePernil20) || $mejoraPernil > ($pesoPernil + $porcentajePernil30)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    }elseif ($row[1] == "059758") {
        if ($mejoraAla < ($pesoAla + $porcentajeAla15) || $mejoraAla > ($pesoAla + $porcentajeAla22)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") ."</center>";
            if ($cajasC == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    }elseif ($row[1] == "059762")  {
        if ($mejoraAlaBlanca > ($pesoAlaBlanca + $porcentajeAlaBlanca22) || $mejoraAlaBlanca < ($pesoAlaBlanca + $porcentajeAlaBlanca15)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    }elseif ($row[1] == "059760")  {
        if ($mejoraPechugaBlanca < ($pesoPechugaBlanca + $porcentajePechugaBlanca20) || $mejoraPechugaBlanca > ($pesoPechugaBlanca + $porcentajePechugaBlanca30)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    }elseif ($row[1] == "059761")  {
        if ($mejoraPernilBlanca < ($pesoPernilBlanca + $porcentajePernilBlanca20) || $mejoraPernilBlanca > ($pesoPernilBlanca + $porcentajePernilBlanca30)) {
            $subdata[] = "<center>" . "<p style='color:red'>".$row[0]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[1]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[2]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[5]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format($row[3], 2, ".", ",")."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".$row[6]."</p>" . "</center>";
            $subdata[] = "<center>" . "<p style='color:red'>".number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",")."</p>" . "</center>";
            $ojo = "R";
        }else {
            $subdata[] = "<center>" . $row[0] . "</center>";
            $subdata[] = "<center>" . $row[1] . "</center>";
            $subdata[] = "<center>" . $row[2] . "</center>";
            $subdata[] = "<center>" . $row[5] . "</center>";
            $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
            if ($cajasB == "fino") {
                $subdata[] = "<center>" . $row[4] . "</center>";
            }else {
                $subdata[] = "<center>" . "<p style='color:red'>".$row[4]."</p>" . "</center>";
            }
            $subdata[] = "<center>" . $row[6] . "</center>";
            $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
        }
    } else {
        $subdata[] = "<center>" . $row[0] . "</center>";
        $subdata[] = "<center>" . $row[1] . "</center>";
        $subdata[] = "<center>" . $row[2] . "</center>";
        $subdata[] = "<center>" . $row[5] . "</center>";
        $subdata[] = "<center>" . number_format($row[3], 2, ".", ",") . "</center>";
        $subdata[] = "<center>" . $row[4] . "</center>";
        $subdata[] = "<center>" . $row[6] . "</center>";
        $subdata[] = "<center>" . number_format(($row[3] - (($row[4] * 2) + ($row[6] * 1.8))), 2, ".", ",") . "</center>";
    }

    $acciones = '<a onclick="edit_mejora(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';

    $acciones .= '<a onclick="borrarMejora(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    
    $subdata[] = $acciones;

    $data[] = $subdata;
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
    "ojo"               => $ojo
);
echo json_encode($json_data);
?>