<?php
include('../config.php');
include('../modelo/funciones.php');
session_start();

// Consulta principal optimizada con JOINs
$sql = "SELECT 
    d.id AS guia,
    d2.empresa AS empresa,
    d2.sede AS sede,
    d.nro_pollo_desprese,
    d.peso_pollo_desprese,
    d.nro_pollo_enteros,
    d.peso_pollo_enteros,
    d.status AS status,
    d.fecha_registro AS fecha_registro,
    d.Temp1,
    d.Temp2,
    d.Temp3
FROM desprese d
INNER JOIN destinos d2 ON d.destino = d2.id
ORDER BY d.id DESC";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

// Obtener todos los IDs de guías para consultas optimizadas
$guias = [];
while ($row = mysqli_fetch_assoc($query)) {
    $guias[] = $row['guia'];
}

if (empty($guias)) {
    echo json_encode([
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

// Consultas optimizadas para obtener datos relacionados
$guias_str = implode(',', $guias);

// 1. Obtener datos de pesos_pollo agrupados por guía y tipo
$sql_pesos = "SELECT 
    guia,
    tipo_pollo,
    SUM(kilos) as total_kilos,
    SUM(nro_pollo) as total_nro_pollo,
    SUM(cajas) as total_cajas,
    SUM(cajas_base) as total_cajas_base,
    GROUP_CONCAT(DISTINCT lote ORDER BY kilos DESC SEPARATOR ', ') as lotes
FROM pesos_pollo 
WHERE guia IN ($guias_str) 
GROUP BY guia, tipo_pollo";

$result_pesos = mysqli_query($link, $sql_pesos);
$pesos_data = [];
while ($row = mysqli_fetch_assoc($result_pesos)) {
    $pesos_data[$row['guia']][$row['tipo_pollo']] = $row;
}

// 2. Obtener datos de mejora_items
$sql_mejora = "SELECT 
    id_guia,
    COUNT(DISTINCT item) as total_items,
    GROUP_CONCAT(DISTINCT lote ORDER BY id DESC SEPARATOR ', ') as lotes_mejora
FROM mejora_items 
WHERE id_guia IN ($guias_str) 
GROUP BY id_guia";

$result_mejora = mysqli_query($link, $sql_mejora);
$mejora_data = [];
while ($row = mysqli_fetch_assoc($result_mejora)) {
    $mejora_data[$row['id_guia']] = $row;
}

// 3. Obtener datos de desprese_items
$sql_desprese_items = "SELECT 
    guia,
    COUNT(*) as total_items,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as items_completados
FROM desprese_items 
WHERE guia IN ($guias_str) 
GROUP BY guia";

$result_desprese_items = mysqli_query($link, $sql_desprese_items);
$desprese_items_data = [];
while ($row = mysqli_fetch_assoc($result_desprese_items)) {
    $desprese_items_data[$row['guia']] = $row;
}

// 4. Obtener datos de lotes_guias_desprese
$sql_lotes_guias = "SELECT 
    guia,
    tipo
FROM lotes_guias_desprese 
WHERE guia IN ($guias_str) 
ORDER BY id DESC";

$result_lotes_guias = mysqli_query($link, $sql_lotes_guias);
$lotes_guias_data = [];
while ($row = mysqli_fetch_assoc($result_lotes_guias)) {
    $lotes_guias_data[$row['guia']] = $row['tipo'];
}

// 5. Obtener lotes específicos de mejora
$sql_lotes_especificos = "SELECT 
    id_guia,
    item,
    lote
FROM mejora_items 
WHERE id_guia IN ($guias_str) 
AND (item IN ('059757', '059756', '059758', '059762', '059760', '059761', '059755', '059759'))
ORDER BY id DESC";

$result_lotes_especificos = mysqli_query($link, $sql_lotes_especificos);
$lotes_especificos_data = [];
while ($row = mysqli_fetch_assoc($result_lotes_especificos)) {
    $lotes_especificos_data[$row['id_guia']][$row['item']] = $row['lote'];
}

// Re-ejecutar la consulta principal para construir los datos
$query = mysqli_query($link, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $guia = $row['guia'];
    $subdata = [];

    // Procesar datos de pesos_pollo
    $polloEntero = [0, 0, 0]; // [kilos, nro_pollo, cajas]
    $polloDesprese = [0, 0]; // [kilos, nro_pollo]
    
    if (isset($pesos_data[$guia]['ENTERO'])) {
        $entero = $pesos_data[$guia]['ENTERO'];
        $polloEntero[0] = floatval($entero['total_kilos'] - (($entero['total_cajas'] * 2) + ($entero['total_cajas_base'] * 1.8)));
        $polloEntero[1] = intval($entero['total_nro_pollo']);
        $polloEntero[2] = intval($entero['total_cajas']);
    }
    
    if (isset($pesos_data[$guia]['DESPRESE'])) {
        $desprese = $pesos_data[$guia]['DESPRESE'];
        $polloDesprese[0] = floatval($desprese['total_kilos'] - (($desprese['total_cajas'] * 2) + ($desprese['total_cajas_base'] * 1.8)));
        $polloDesprese[1] = intval($desprese['total_nro_pollo']);
    }

    // Obtener datos relacionados
    $mejora_info = isset($mejora_data[$guia]) ? $mejora_data[$guia] : ['total_items' => 0, 'lotes_mejora' => ''];
    $desprese_items_info = isset($desprese_items_data[$guia]) ? $desprese_items_data[$guia] : ['total_items' => 0, 'items_completados' => 0];
    $tipo = isset($lotes_guias_data[$guia]) ? $lotes_guias_data[$guia] : '';
    
    // Lotes específicos
    $loteDesprese = '';
    $loteEntero = '';
    if (isset($lotes_especificos_data[$guia])) {
        foreach (['059757', '059756', '059758', '059762', '059760', '059761'] as $item) {
            if (isset($lotes_especificos_data[$guia][$item])) {
                $loteDesprese = $lotes_especificos_data[$guia][$item];
                break;
            }
        }
        foreach (['059755', '059759'] as $item) {
            if (isset($lotes_especificos_data[$guia][$item])) {
                $loteEntero = $lotes_especificos_data[$guia][$item];
                break;
            }
        }
    }

    // Lotes de pesos_pollo
    $lotesPlantaEnt = isset($pesos_data[$guia]['ENTERO']['lotes']) ? $pesos_data[$guia]['ENTERO']['lotes'] : '';
    $lotesPlantaDes = isset($pesos_data[$guia]['DESPRESE']['lotes']) ? $pesos_data[$guia]['DESPRESE']['lotes'] : '';

    // Verificar si hay datos de desprese
    $tieneDesprese = isset($pesos_data[$guia]['DESPRESE']);
    $tieneEntero = isset($pesos_data[$guia]['ENTERO']);
    $tieneItems = $desprese_items_info['total_items'] > 0;
    $status = $desprese_items_info['items_completados'] > 0 ? 1 : 0;

    if ($_SESSION["tipo"] == 0) {
        $sqlCambiosD = "SELECT COUNT(idCambio) AS totalCambios FROM desprese_items_cambios WHERE guia = '$guia'";
        $rs_operacionCambiosD = mysqli_query($link, $sqlCambiosD);
        $rowCambiosD = mysqli_fetch_assoc($rs_operacionCambiosD);
        $totalCambiosD = $rowCambiosD["totalCambios"];

        $sqlCambiosM = "SELECT COUNT(idCambio) AS totalCambios FROM mejora_items_cambios WHERE id_guia = '$guia'";
        $rs_operacionCambiosM = mysqli_query($link, $sqlCambiosM);
        $rowCambiosM = mysqli_fetch_assoc($rs_operacionCambiosM);
        $totalCambiosM = $rowCambiosM["totalCambios"];

        $sqlCambiosP = "SELECT COUNT(idCambio) AS totalCambios FROM pesos_pollo_cambios WHERE guia = '$guia'";
        $rs_operacionCambiosP = mysqli_query($link, $sqlCambiosP);
        $rowCambiosP = mysqli_fetch_assoc($rs_operacionCambiosP);
        $totalCambiosP = $rowCambiosP["totalCambios"];

        $totalCambios = $totalCambiosD + $totalCambiosM + $totalCambiosP;
    } else {
        $totalCambios = 0;
    }

    // Construir la primera columna (Guía)
    if ($tieneDesprese) {
        if ($tieneItems) {
            if ($status == 1) {
                $subdata[] = "<center><p style='color: blue; padding: 0px'>" . $guia . "</p></center>";
            } else {
                $subdata[] = "<center>" . $guia . "</center>";
            }
        } else {
            $subdata[] = "<center><p style='color: red; padding: 0px'>" . $guia . "</p></center>";
        }
    } else {
        if ($tieneEntero) {
            if ($status == 1) {
                $subdata[] = "<center><p style='color: blue; padding: 0px'>" . $guia . "</p></center>";
            } else {
                $subdata[] = "<center>" . $guia . "</center>";
            }
        } else {
            $subdata[] = "<center><p style='color: red; padding: 0px'>" . $guia . "</p></center>";
        }
    }

    // Construir la segunda columna (Empresa y detalles)
    if ($row['status'] == 1 || $_SESSION["usuario"] == "ADMINISTRADOR") {
        $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios ."\")'>".$row["empresa"]. " - " .$row["sede"]. "<br>" . $row["fecha_registro"] . " (" . $tipo . ")<br>Ent: " . $lotesPlantaEnt . " <br> Des: " . $lotesPlantaDes . "</a></center>";
        
        // Calcular peso promedio para administrador
        if ($_SESSION["usuario"] == "ADMINISTRADOR") {
            $peso_promedio = 0;
            if ($status == 1) {
                $total_kilos = isset($pesos_data[$guia]['ENTERO']['total_kilos']) ? $pesos_data[$guia]['ENTERO']['total_kilos'] : 0;
                $total_cajas = isset($pesos_data[$guia]['ENTERO']['total_cajas']) ? $pesos_data[$guia]['ENTERO']['total_cajas'] : 0;
            } else {
                $total_kilos = isset($pesos_data[$guia]['DESPRESE']['total_kilos']) ? $pesos_data[$guia]['DESPRESE']['total_kilos'] : 0;
                $total_cajas = isset($pesos_data[$guia]['DESPRESE']['total_cajas']) ? $pesos_data[$guia]['DESPRESE']['total_cajas'] : 0;
            }
            
            if ($total_cajas > 0 && $total_kilos > 0) {
                $peso_promedio = $total_kilos / $total_cajas;
                $peso_promedio = number_format($peso_promedio, 2, ".", ",") . " kg";
            }
            
            $pesoPromedioEntero = 0;
            if ($polloEntero[2] > 0) {
                $pesoPromedioEntero = $polloEntero[0] / $polloEntero[2];
                $pesoPromedioEntero = number_format($pesoPromedioEntero, 2, ".", ",") . " kg";
            }
            
            // Columnas de pollo desprese y entero para administrador
            if ($peso_promedio > 25) {
                $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloDesprese[1], 0, ".", ",") . "<br>" . number_format($polloDesprese[0], 2, ".", ",") . " kg<br><a style='color: red;' title='Peso promedio Canastilla'>" . $peso_promedio . "</a><br>" . $loteDesprese . "</a></center>";
            } else {
                $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloDesprese[1], 0, ".", ",") . "<br>" . number_format($polloDesprese[0], 2, ".", ",") . " kg<br><a title='Peso promedio Canastilla'>" . $peso_promedio . "</a><br>" . $loteDesprese . "</a></center>";
            }
            
            if ($pesoPromedioEntero > 25) {
                $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloEntero[1], 0, ".", ",") . "<br>" . number_format($polloEntero[0], 2, ".", ",") . " kg<br><a style='color: red;' title='Peso promedio Canastilla'>" . $pesoPromedioEntero . "</a><br>" . $loteEntero . "</a></center>";
            } else {
                $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloEntero[1], 0, ".", ",") . "<br>" . number_format($polloEntero[0], 2, ".", ",") . " kg<br><a title='Peso promedio Canastilla'>" . $pesoPromedioEntero . "</a><br>" . $loteEntero . "</a></center>";
            }
        } else {
            $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloDesprese[1], 0, ".", ",") . "<br>" . number_format($polloDesprese[0], 2, ".", ",") . " kg<br>" . $loteDesprese . "</a></center>";
            $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$guia."\", \"".$row["empresa"]. " - " .$row["sede"]."\", \"". $totalCambios."\")'>" . number_format($polloEntero[1], 0, ".", ",") . "<br>" . number_format($polloEntero[0], 2, ".", ",") . " kg<br>" . $loteEntero . "</a></center>";
        }
    } else {
        $subdata[] = "<center>".$row["empresa"]. " - " .$row["sede"]. "<br>" . $row["fecha_registro"] . " (" . $tipo . ")<br>Ent: " . $lotesPlantaEnt . " <br> Des: " . $lotesPlantaDes . "</center>";
        $subdata[] = "<center>" . number_format($polloDesprese[1], 0, ".", ",") . "<br>" . number_format($polloDesprese[0], 2, ".", ",") . " kg<br>" . $loteDesprese . "</center>";
        $subdata[] = "<center>" . number_format($polloEntero[1], 0, ".", ",") . "<br>" . number_format($polloEntero[0], 2, ".", ",") . " kg<br>" . $loteEntero . "</center>";
    }
    
    // Construir botones de acción
    $estadoBtn = '';
    
    if ($row['status'] == 1 || $_SESSION["usuario"] == "ADMINISTRADOR") {
        // Lógica para botón de mejora
        $temps_completas = !empty($row['Temp1']) && !empty($row['Temp2']) && !empty($row['Temp3']);
        
        if ($tieneDesprese && $tieneEntero) {
            if ($mejora_info['total_items'] >= 4) {
                $color = $temps_completas ? 'text-warning' : 'text-warning';
            } else {
                $color = $temps_completas ? 'text-danger' : 'text-danger';
            }
        } elseif ($tieneDesprese) {
            if ($mejora_info['total_items'] >= 3) {
                $color = $temps_completas ? 'text-warning' : 'text-warning';
            } else {
                $color = $temps_completas ? 'text-danger' : 'text-danger';
            }
        } else {
            if ($mejora_info['total_items'] >= 1) {
                $color = $temps_completas ? 'text-warning' : 'text-warning';
            } else {
                $color = $temps_completas ? 'text-danger' : 'text-danger';
            }
        }
        
        $estadoBtn = '<a onclick="abrirModalMejora(\''.$guia.'\', \''.$row["empresa"]. " - " .$row["sede"].'\', \''.($temps_completas ? 'true' : 'false').'\', \''. $totalCambios .'\')" title="Abrir Mejora"><i class="bi bi-plus-square '.$color.' fs-2 me-2"></i></a>';
    } else {
        $estadoBtn = '<i style="visibility: hidden" class="bi bi-plus-square text-warning fs-2 me-2"></i>';
    }
    
    // Botones adicionales
    if ($row['status'] == 1 || $_SESSION["usuario"] == "ADMINISTRADOR") {
        $estadoBtn .= '<a data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscar_guia(\''.$guia.'\', \''. $totalCambios .'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';
        $estadoBtn .='<a href="./controlador/imprimirDespreseOjo.php?id='.$guia.'" target="_blank" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;" ></i></a>';
        $estadoBtn .= '<a onclick="bloquear_desprese(\''.$guia.'\')" href="./controlador/imprimirDesprese.php?id='.$guia.'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
        $estadoBtn .= '<a onclick="AbrirModalCF(\''.$guia.'\', \''. $row["empresa"]. " - " .$row["sede"] .'\', \''. $row["fecha_registro"] .'\', '. json_encode($polloDesprese) .', '. json_encode($polloEntero) .')"><i class="bi bi-clipboard-data fs-2 me-2 text-success" style="cursor: pointer;"></i></a>';
        
        if ($_SESSION["usuario"] == "ADMINISTRADOR") {
            if($row['status'] == 0){
                $estadoBtn .='<a style="color: black" onclick="desbloquear_desprese(\''.$guia.'\')" title="Desbloquear Guia"><i class="bi bi-lock fs-2 style="color:#ff0000;"></i></a>';
            }else {
                $estadoBtn .='<a style="color: red" onclick="bloquear_desprese(\''.$guia.'\')" title="Bloquear Guia"><i class="bi bi-unlock fs-2 style="color:#ff0000;"></i></a>';
            }
        }
        $estadoBtn .='<a onclick="modal_temp(\''.$guia.'\')" title="Abrir Temperaturas"><i class="bi bi-thermometer-half fs-2 text-danger"></i></a>';
    }else {
        $estadoBtn .= '<i style="visibility: hidden" class="bi bi-pencil-square fs-2 me-2 text-warning"></i>';
        $estadoBtn .='<a href="./controlador/imprimirDespreseOjo.php?id='.$guia.'" target="_blank" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';
        $estadoBtn .= '<a onclick="bloquear_desprese(\''.$guia.'\')" href="./controlador/imprimirDesprese.php?id='.$guia.'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
        $estadoBtn .= '<a onclick="AbrirModalCF(\''.$guia.'\')"><i class="bi bi-clipboard-data fs-2 me-2 text-success"></i></a>';
        $estadoBtn .='<i style="visibility: hidden" class="bi bi-thermometer-half fs-2 text-danger"></i>';
    }

    if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success"></i>';
    } else {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success" style="visibility: hidden;"></i>';
    }
    
    $subdata[] = $estadoBtn;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>