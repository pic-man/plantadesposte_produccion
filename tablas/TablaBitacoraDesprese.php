<?php
include "../config.php";

// Otros parámetros con validación
$lote = isset($_POST["lote"]) ? trim($_POST["lote"]) : "";

// Validar que el lote no esté vacío
if (empty($lote)) {
    echo json_encode([
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "TotalKilos" => "0.00",
        "TotalUnidades" => "0"
    ]);
    exit;
}

// Función para obtener datos de desprese
function obtenerDatosDesprese($link, $lote) {
    $data = [];
    $totalKilos = 0;
    $totalUnidades = 0;
    
    // Consulta optimizada para desprese con JOIN
    $sql = "SELECT 
        lgd.guia,
        desp.fecha_registro,
        COALESCE(SUM(CASE WHEN pp.tipo_pollo = 'DESPRESE' THEN pp.kilos - ((pp.cajas * 2) + (pp.cajas_base * 1.8)) ELSE 0 END), 0) as kilos_desprese,
        COALESCE(SUM(CASE WHEN pp.tipo_pollo = 'DESPRESE' THEN pp.nro_pollo ELSE 0 END), 0) as unidades_desprese,
        COALESCE(SUM(CASE WHEN pp.tipo_pollo = 'ENTERO' THEN pp.kilos - ((pp.cajas * 2) + (pp.cajas_base * 1.8)) ELSE 0 END), 0) as kilos_entero,
        COALESCE(SUM(CASE WHEN pp.tipo_pollo = 'ENTERO' THEN pp.nro_pollo ELSE 0 END), 0) as unidades_entero,
        lgd.tipo AS tipoPollo
    FROM lotes_guias_desprese lgd
    INNER JOIN desprese desp ON desp.id = lgd.guia
    LEFT JOIN pesos_pollo pp ON pp.guia = lgd.guia AND pp.lote = lgd.lote
    WHERE lgd.lote = ?";
    
    $params = [$lote];
    
    $sql .= " GROUP BY lgd.guia, desp.fecha_registro ORDER BY lgd.guia ASC";
    
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar registro de DESPRESE si hay datos
            if ($row['kilos_desprese'] > 0) {
                $data[] = [
                    "Desprese",
                    $lote,
                    $row["fecha_registro"],
                    $row["guia"],
                    number_format($row["unidades_desprese"], 0, ".", ""),
                    $row["tipoPollo"],
                    "DESPRESE",
                    number_format($row["kilos_desprese"], 2, ".", "")
                ];
                $totalKilos += $row["kilos_desprese"];
                $totalUnidades += $row["unidades_desprese"];
            }
            
            // Agregar registro de ENTERO si hay datos
            if ($row['kilos_entero'] > 0) {
                $data[] = [
                    "Desprese",
                    $lote,
                    $row["fecha_registro"],
                    $row["guia"],
                    number_format($row["unidades_entero"], 0, ".", ""),
                    $row["tipoPollo"],
                    "ENTERO",
                    number_format($row["kilos_entero"], 2, ".", "")
                ];
                $totalKilos += $row["kilos_entero"];
                $totalUnidades += $row["unidades_entero"];
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    return ['data' => $data, 'totalKilos' => $totalKilos, 'totalUnidades' => $totalUnidades];
}

// Función para obtener datos de despresado
function obtenerDatosDespresado($link, $lote) {
    $data = [];
    $totalKilos = 0;
    
    // Consulta optimizada para despresado con JOIN
    $sql = "SELECT 
        lgd.guia,
        desp.fecha_registro,
        COALESCE(SUM(CASE WHEN di.item IN ('059758', '059756', '059757', '059762', '059760', '059761') 
            THEN di.kilos - ((di.cajas * 2) + (di.canastilla_base * 1.8)) ELSE 0 END), 0) as kilos_desprese,
        COALESCE(SUM(CASE WHEN di.item IN ('059755', '059759') 
            THEN di.kilos - ((di.cajas * 2) + (di.canastilla_base * 1.8)) ELSE 0 END), 0) as kilos_entero,
        lgd.tipo AS tipoPollo
    FROM lotes_guia_despresado lgd
    INNER JOIN despresado desp ON desp.id = lgd.guia
    LEFT JOIN despresado_items di ON di.guia = lgd.guia AND di.lote = lgd.lote
    WHERE lgd.lote = ?";
    
    $params = [$lote];
    
    $sql .= " GROUP BY lgd.guia, desp.fecha_registro ORDER BY lgd.guia ASC";
    
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar registro de DESPRESE si hay datos
            if ($row['kilos_desprese'] > 0) {
                $data[] = [
                    "Despresado",
                    $lote,
                    $row["fecha_registro"],
                    $row["guia"],
                    "N/A",
                    $row["tipoPollo"],
                    "DESPRESE",
                    number_format($row["kilos_desprese"], 2, ".", "")
                ];
                $totalKilos += $row["kilos_desprese"];
            }
            
            // Agregar registro de ENTERO si hay datos
            if ($row['kilos_entero'] > 0) {
                $data[] = [
                    "Despresado",
                    $lote,
                    $row["fecha_registro"],
                    $row["guia"],
                    "N/A",
                    $row["tipoPollo"],
                    "ENTERO",
                    number_format($row["kilos_entero"], 2, ".", "")
                ];
                $totalKilos += $row["kilos_entero"];
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    return ['data' => $data, 'totalKilos' => $totalKilos];
}

// Obtener datos
$datosDesprese = obtenerDatosDesprese($link, $lote);
$datosDespresado = obtenerDatosDespresado($link, $lote);

// Combinar datos
$data = array_merge($datosDesprese['data'], $datosDespresado['data']);

// Calcular totales
$TotalKilos = $datosDesprese['totalKilos'] + $datosDespresado['totalKilos'];
$TotalUnidades = $datosDesprese['totalUnidades'];

// Obtener total de registros
$TotalData = count($data);
$TotalFiltered = $TotalData;

// Agregar fila de totales
/* $data[] = [
    "Total",
    "",
    "<b style='visibility: hidden;'>zzz</b>",
    "",
    number_format($TotalUnidades, 0, ".", ""),
    "",
    "",
    number_format($TotalKilos, 2, ".", "")
]; */

// Preparar respuesta JSON
$json_data = [
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data,
    "TotalKilos" => number_format($TotalKilos, 2, ".", ""),
    "TotalUnidades" => number_format($TotalUnidades, 0, ".", "")
];

echo json_encode($json_data);