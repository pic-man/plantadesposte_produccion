<?php
session_start();
error_reporting(0);
require_once('../modelo/funciones.php');
include('../config.php');

$request = $_REQUEST;
$inicio = $request['start'] ?? 0;
$fin = $request['length'] ?? 10;
$busqueda = $request['search']['value'] ?? '';
/* $draw = isset($request['draw']) ? intval($request['draw']) : 1; */

// Obtener total de registros ANTES de la paginación
$sql_count = "SELECT COUNT(*) as total FROM guiaspollo g
              INNER JOIN responsables r ON g.responsable = r.cedula
              INNER JOIN destinos d ON g.destino = d.id
              INNER JOIN conductores c ON g.conductor = c.cedula";

/* if (!empty($busqueda)) {
    $busqueda_escaped = mysqli_real_escape_string($link, $busqueda);
    $sql_count .= " WHERE (
        g.fechaexp LIKE '%$busqueda_escaped%'
        OR d.sede LIKE '%$busqueda_escaped%'
        OR c.nombres LIKE '%$busqueda_escaped%'
        OR r.nombres LIKE '%$busqueda_escaped%'
        OR g.codigog LIKE '%$busqueda_escaped%'
        OR g.tipo LIKE '%$busqueda_escaped%'
        OR g.id_guia LIKE '%$busqueda_escaped%'
        OR g.placa LIKE '%$busqueda_escaped%'
    )";
}
 */
$result_count = mysqli_query($link, $sql_count) or die("error al ejecutar la consulta 1: " . mysqli_error($link));
$totalRecords = mysqli_fetch_assoc($result_count)['total'];

// Obtener datos principales con paginación
$listaProveedores = listaProveedoresPPaginada($inicio, $fin, $busqueda);

if (!$listaProveedores) {
    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

$totalData = mysqli_num_rows($listaProveedores);

// Obtener todos los IDs de proveedores para consultas optimizadas
$proveedores = [];
while ($row = mysqli_fetch_array($listaProveedores)) {
    $proveedores[] = $row[7]; // id_guia
}

if (empty($proveedores)) {
    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($totalRecords), // Usar totalRecords en lugar de totalData
        "data" => []
    ]);
    exit;
}

$proveedores_str = implode(',', $proveedores);

/* echo $proveedores_str . "<br>";
exit; */

// 1. Obtener datos de item_proveedorpollo agrupados por proveedor e item
$sql_items = "SELECT 
    proveedor,
    item,
    SUM(unidades) as total_unidades,
    SUM(peso) as total_peso,
    SUM(cajas) as total_cajas,
    GROUP_CONCAT(DISTINCT lote SEPARATOR ', ') as lotes
FROM item_proveedorpollo 
WHERE proveedor IN ($proveedores_str) 
GROUP BY proveedor, item";

$result_items = mysqli_query($link, $sql_items) or die("error al ejecutar la consulta 2: " . mysqli_error($link));
$items_data = [];
while ($row = mysqli_fetch_assoc($result_items)) {
    $items_data[$row['proveedor']][$row['item']] = $row;
}

// 2. Obtener el último id_guia para verificar anulaciones
$sql_last_guia = "SELECT id_guia FROM guiaspollo ORDER BY id_guia DESC LIMIT 1";
$result_last_guia = mysqli_query($link, $sql_last_guia) or die("error al ejecutar la consulta 3: " . mysqli_error($link));
$last_guia = mysqli_fetch_row($result_last_guia)[0];

// 3. Verificar proveedores anulados
$sql_anulados = "SELECT DISTINCT proveedor FROM item_proveedorpollo WHERE proveedor IN ($proveedores_str)";
$result_anulados = mysqli_query($link, $sql_anulados) or die("error al ejecutar la consulta 4: " . mysqli_error($link));
$proveedores_activos = [];
while ($row = mysqli_fetch_assoc($result_anulados)) {
    $proveedores_activos[] = $row['proveedor'];
}

// Re-ejecutar la consulta principal para construir los datos
$listaProveedores = listaProveedoresPPaginada($inicio, $fin, $busqueda);
$data = array();

while ($row = mysqli_fetch_array($listaProveedores)) {
    $proveedor_id = $row[7];
    $subdata = array();

    // Inicializar variables
    $upcc = 0; // pollo completo campesino
    $upcb = 0; // pollo completo blanco
    $upec = 0; // pollo entero campesino
    $upeb = 0; // pollo entero blanco
    $upap = 0; // pollo apanado
    $upas = 0; // pollo asado
    $lotes = "";
    $tipo_pollo = "CRUDO";

    // Procesar datos de items si existen
    if (isset($items_data[$proveedor_id])) {
        $items = $items_data[$proveedor_id];
        
        // Pollo completo blanco (059762, 059760, 059761)
        if (isset($items['059762']) && isset($items['059760']) && isset($items['059761'])) {
            $unidades = $items['059762']['total_unidades'] / 2;
            $unidades2 = $items['059760']['total_unidades'];
            $unidades3 = $items['059761']['total_unidades'] / 2;
            
            if (($unidades == $unidades2) && ($unidades2 == $unidades3)) {
                $upcb = $unidades;
            }
        }

        // Pollo completo campesino (059758, 059756, 059757)
        if (isset($items['059758']) && isset($items['059756']) && isset($items['059757'])) {
            $unidades4 = $items['059758']['total_unidades'] / 2;
            $unidades5 = $items['059756']['total_unidades'];
            $unidades6 = $items['059757']['total_unidades'] / 2;
            
            if (($unidades4 == $unidades5) && ($unidades5 == $unidades6)) {
                $upcc = $unidades4;
            }
        }

        // Pollo apanado (050514, 050515, 050516, 050517)
        if (isset($items['050514']) && isset($items['050515']) && isset($items['050516']) && isset($items['050517'])) {
            $unidades7 = $items['050514']['total_unidades'] / 2;
            $unidades8 = $items['050515']['total_unidades'] / 2;
            $unidades9 = $items['050516']['total_unidades'] / 2;
            $unidades10 = $items['050517']['total_unidades'] / 2;
            
            if (($unidades7 == $unidades8) && ($unidades8 == $unidades9) && ($unidades9 == $unidades10)) {
                $upap = $unidades7;
            }
        }

        // Pollo entero campesino (059755)
        if (isset($items['059755'])) {
            $upec = $items['059755']['total_unidades'];
        }

        // Pollo entero blanco (059759)
        if (isset($items['059759'])) {
            $upeb = $items['059759']['total_unidades'];
        }

        // Pollo asado (074700)
        if (isset($items['074700'])) {
            $upas = $items['074700']['total_unidades'];
        }

        // Obtener lotes
        $lotes_array = [];
        foreach ($items as $item) {
            if (!empty($item['lotes'])) {
                $lotes_array[] = $item['lotes'];
            }
        }
        $lotes = implode(', ', array_unique($lotes_array));
    }

    // Calcular total de unidades
    $ut = intval($upcc) + intval($upcb) + intval($upec) + intval($upeb) + intval($upap) + intval($upas);

    // Determinar tipo de pollo
    if ((intval($upap) == 0) && (intval($upas) == 0)) {
        $tipo_pollo = "CRUDO";
    } else {
        $tipo_pollo = "ASADERO";
    }

    if ($_SESSION["usuario"] == "1106781852" && $tipo_pollo != "ASADERO") {
        continue;
    }

    // Verificar si está anulado
    if ($proveedor_id != $last_guia && !in_array($proveedor_id, $proveedores_activos)) {
        $row[4] = "ANULADO";
        $row[5] = "";
    }

    // Calcular peso promedio para administrador
    $pesoPromedio = "0 kg";
    if ($_SESSION['usuario'] == 'ADMINISTRADOR' && isset($items_data[$proveedor_id])) {
        $total_peso = 0;
        $total_cajas = 0;
        foreach ($items_data[$proveedor_id] as $item) {
            $total_peso += $item['total_peso'];
            $total_cajas += $item['total_cajas'];
        }
        
        if ($total_cajas > 0 && $total_peso > 0) {
            $pesoPromedio = number_format($total_peso / $total_cajas, 2, ".", ",") . " kg";
        }
    }

    if ($_SESSION["tipo"] == 0) {
        $sqlCambios = "SELECT COUNT(idCambio) AS totalCambios FROM item_proveedorpollo_cambios WHERE proveedor = '$row[7]'";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);
        $rowCambios = mysqli_fetch_assoc($rs_operacionCambios);
        $totalCambios = $rowCambios["totalCambios"];
    } else {
        $totalCambios = 0;
    }

    // Construir las columnas de datos
    $subdata[] = '<center>' . $row[7] . '<br>' . $row[6] . ' - ' . $tipo_pollo . '</center>';

    if ($_SESSION['usuario'] == 'ADMINISTRADOR') {
        $peso_class = (floatval(str_replace(',', '', $pesoPromedio)) > 25) ? 'style="color: red;"' : '';
        $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\'' . $row[7] . '\',\'' . $row[8] . '\',\'' . $row[10] . '\',\'' . $_SESSION['tipo'] . '\'); buscarItems(\'' . $row[7] . '\',\'' . $row[6] . '\', \''. $tipo_pollo .'\', \''. $totalCambios .'\');" data-bs-toggle="modal">' . $row[1] . ' <br> ' . (($ut != $row[11]) ? "<p style='color: red;padding: 0px;margin-bottom:0px; display: inline-block;'>" . $ut . '/' . $row[11] . "</p> - <a title='Peso promedio Canastilla' " . $peso_class . ">" . $pesoPromedio . "</a>" : $ut . '/' . $row[11] . " - <a title='Peso promedio Canastilla' " . $peso_class . ">" . $pesoPromedio . "</a>") . ' <br> <span data-bs-toggle="tooltip" data-bs-placement="top" title="' . $lotes . '">Lotes</span></a></center>';
    } else {
        $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\'' . $row[7] . '\',\'' . $row[8] . '\',\'' . $row[10] . '\',\'' . $_SESSION['tipo'] . '\'); buscarItems(\'' . $row[7] . '\',\'' . $row[6] . '\', \''. $tipo_pollo .'\', \''. $totalCambios .'\');" data-bs-toggle="modal">' . $row[1] . ' <br> ' . (($ut != $row[11]) ? "<p style='color: red;padding: 0px;margin-bottom:0px'>" . $ut . '/' . $row[11] . "</p>" : $ut . '/' . $row[11]) . ' <br> <span data-bs-toggle="tooltip" data-bs-placement="top" title="' . $lotes . '">Lotes</span></a></center>';
    }

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\'' . $row[7] . '\',\'' . $row[8] . '\',\'' . $row[10] . '\',\'' . $_SESSION['tipo'] . '\'); buscarItems(\'' . $row[7] . '\',\'' . $row[6] . '\', \''. $tipo_pollo .'\', \''. $totalCambios .'\');" data-bs-toggle="modal">' . $row[9] . ' <br> ' . $row[3] . '</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\'' . $row[7] . '\',\'' . $row[8] . '\',\'' . $row[10] . '\',\'' . $_SESSION['tipo'] . '\'); buscarItems(\'' . $row[7] . '\',\'' . $row[6] . '\', \''. $tipo_pollo .'\', \''. $totalCambios .'\');" data-bs-toggle="modal">' . $row[4] . ' <br> ' . $row[5] . '</a></center>';

    // Construir botones de acción
    $estadoBtn = "";
    if ($row[4] != "ANULADO") {
        if ($_SESSION['tipo'] == 0) {
            $estadoBtn = '<center>
            <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\'' . $row[7] . '\',\'' . $row[6] . '\')"><i class="bi bi-pencil-square fs-2 text-warning me-2"></i></a>';

            $estadoBtn .= '<a style="z-index: 0;color:#000" href="controlador/preliminarpollopdf.php?id=' . $row[7] . '" target="_blank"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';

            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\'' . $row[7] . '\')" href="controlador/imprimirpollopdf.php?id=' . $row[7] . '" target="_blank"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';

            if ($row[10] == '1') {
                $estadoBtn .= '<a style="z-index: 0;color: red" onclick="bloquearEdicion(\'' . $row[7] . '\')" ><i class="bi bi-unlock fs-2 style="color:#ff0000;"></i></a>';
            } else {
                $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="desbloquearEdicion(\'' . $row[7] . '\')"><i class="bi-lock fs-2 style="color:#00a45f;"></i></a>';
            }

            if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
                $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success"></i>';
            } else {
                $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success" style="visibility: hidden"></i>';
            }

            $estadoBtn .= '</center>';
        } else {
            $estadoBtn = '<center>';
            if ($row[10] == '1') {
                $estadoBtn = '<a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\'' . $row[7] . '\',\'' . $row[6] . '\')"><i class="bi bi-pencil-square fs-2 text-warning me-2"></i></a>';
            } else {
                $estadoBtn .= '<i class="bi bi-printer fs-2 me-2" style="visibility: hidden;"></i>';
            }

            $estadoBtn .= '<a style="z-index: 0;color:#000" href="controlador/preliminarpollopdf.php?id=' . $row[7] . '" target="_blank"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';

            $estadoBtn .= '<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\'' . $row[7] . '\')" href="controlador/imprimirpollopdf.php?id=' . $row[7] . '" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a>';

            $estadoBtn .= '</center>';
        }
    }

    $subdata[] = $estadoBtn;
    $data[] = $subdata;
}

$json_data = array(
    "draw"            => intval($draw),
    "recordsTotal"    => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords), // Usar totalRecords para que coincida con el total real
    "data"            => $data
);
echo json_encode($json_data);
?>