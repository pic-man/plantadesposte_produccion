<?php
include("../config.php");

$sql = "SELECT * FROM bolsas ORDER BY id DESC";
$rs_operation = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subdata = array();

    $subdata[] = $row["id"];
    $subdata[] = $row["descripcion"];
    $subdata[] = $row["capacidad"];
    $subdata[] = $row["status"];

    $acciones = '<i onclick="buscarBolsa(\''. $row['id'] .'\')" class="bi bi-pencil-square fs-3 text-warning" style="cursor: pointer;" title="Editar"></i>';
    $acciones .= '<i onclick="eliminarBolsa(\''. $row['id'] .'\')" class="bi bi-trash fs-3 text-danger" style="cursor: pointer;" title="Eliminar"></i>';

    $subdata[] = $acciones;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);
