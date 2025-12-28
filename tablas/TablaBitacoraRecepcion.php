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

// Función para obtener datos de recepción de pollo
function obtenerDatosRecepcionPollo($link, $lote) {
    $data = [];
    $totalKilos = 0;
    $totalUnidades = 0;
    
    // Consulta optimizada con JOIN para evitar N+1 queries
    $sql = "SELECT 
        rp.id_recepcion AS guia, 
        rp.fecharec AS fecha,
        rp.tipo AS tipo_pollo,
        COALESCE(SUM(rpp.unidades), 0) AS unidades,
        COALESCE(SUM(REPLACE(REPLACE(rpp.peso_real, '.', ''), ',', '.')), 0) AS kilos
    FROM recepcionpollo rp
    LEFT JOIN recepcion_pesos_pollo rpp ON rpp.proveedor = rp.id_recepcion
    WHERE rp.lote_planta = ?";
    
    $params = [$lote];
    
    $sql .= " GROUP BY rp.id_recepcion, rp.fecharec, rp.tipo ORDER BY rp.fecharec DESC";
    
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $kilos = floatval($row["kilos"]);
            $unidades = intval($row["unidades"]);
            
            $data[] = [
                $lote,
                $row["fecha"],
                $row["guia"],
                number_format($unidades, 0, ".", ""),
                $row["tipo_pollo"],
                number_format($kilos, 2, ".", "")
            ];
            
            $totalKilos += $kilos;
            $totalUnidades += $unidades;
        }
        mysqli_stmt_close($stmt);
    }
    
    return ['data' => $data, 'totalKilos' => $totalKilos, 'totalUnidades' => $totalUnidades];
}

// Obtener datos
$datosRecepcion = obtenerDatosRecepcionPollo($link, $lote);

// Calcular totales
$TotalKilos = $datosRecepcion['totalKilos'];
$TotalUnidades = $datosRecepcion['totalUnidades'];

// Obtener total de registros
$TotalData = count($datosRecepcion['data']);
$TotalFiltered = $TotalData;

// Agregar fila de totales
/* $datosRecepcion['data'][] = [
    "Total",
    "<b style='visibility: hidden;'>zzz</b>",
    "",
    number_format($TotalUnidades, 0, ".", ""),
    "",
    number_format($TotalKilos, 2, ".", "")
]; */

// Preparar respuesta JSON
$json_data = [
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $datosRecepcion['data'],
    "TotalKilos" => number_format($TotalKilos, 2, ".", ""),
    "TotalUnidades" => number_format($TotalUnidades, 0, ".", "")
];

echo json_encode($json_data);