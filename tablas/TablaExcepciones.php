<?php
include "../config.php";

$sql = "SELECT 
    e.id,
    d.empresa AS empresa,
    d.sede AS sede,
    p.descripcion AS item,
    e.proceso AS proceso
FROM excepciones e
INNER JOIN destinos d ON e.destino = d.id
INNER JOIN plantilla p ON e.item = p.item";

$rs_operacion = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operacion);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operacion)) {
    $subdata = array();

    $subdata[] = $row["id"];
    $subdata[] = $row["empresa"] . " - " . $row["sede"];
    $subdata[] = $row["item"];
    $subdata[] = $row["proceso"];

    $acciones = '<div class="d-flex justify-content-center gap-1">';
    $acciones .= '<i onclick="TraerExcepcion(\''. $row["id"] . '\')" class="bi bi-pencil-square fs-3 text-warning" style="cursor: pointer;"></i>';
    $acciones .= '<i onclick="EliminarExcepcion(\''. $row["id"] . '\')" class="bi bi-trash-fill fs-3 text-danger" style="cursor: pointer;"></i>';
    $acciones .= '</div>';

    $subdata[] = $acciones;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);