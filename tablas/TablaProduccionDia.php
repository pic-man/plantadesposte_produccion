<?php
include("../config.php");

$sql = "SELECT 
    semana,
    SUM(CASE WHEN especie = 'CERDO' THEN cantidad ELSE 0 END) AS TotalCerdo,
    SUM(CASE WHEN especie = 'RES' THEN cantidad ELSE 0 END) AS TotalRes
FROM produccion_dia_canales
GROUP BY semana
ORDER BY semana ASC";

$rs_operation = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = array();
$cont = 1;

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subData = array();

    $subData[] = $cont;
    $subData[] = $row["semana"];
    $subData[] = $row["TotalCerdo"];
    $subData[] = $row["TotalRes"];

    $acciones = '
        <i onclick="EditarProduccionDia(\''. $row["semana"] .'\')" class="bi bi-pencil-square text-warning fs-3" style="cursor: pointer;"></i>
        <i onclick="EliminarProduccionDia(\''. $row["semana"] .'\')" class="bi bi-trash text-danger fs-3" style="cursor: pointer;"></i>
        <a href="controlador/imprimirProduccionDiaCanales.php?semana='. $row["semana"] .'"><i class="bi bi-printer text-info fs-3" style="cursor: pointer;" target="_blank"></i></a>
    ';

    $subData[] = $acciones;
    $data[] = $subData;
    $cont++;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);