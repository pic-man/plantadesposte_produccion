<?php
include('../config.php');

$sql = "SELECT * FROM salmuera";
$rs_operation = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subdata = array();

    $subdata[] = $row["id"];
    $subdata[] = $row["concentracion"];

    $ingredientes = $row["ingredientes"];
    $max_length = 50;
    if (mb_strlen($ingredientes) > $max_length) {
        $ingredientes_mostrar = mb_substr($ingredientes, 0, $max_length) . "...";
    } else {
        $ingredientes_mostrar = $ingredientes;
    }
    
    $subdata[] = '<span style="color: var(--bs-body-color);" title="' . htmlspecialchars($ingredientes, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($ingredientes_mostrar, ENT_QUOTES, 'UTF-8') . '</span>';
    $subdata[] = $row["status"];

    $acciones = '
    <div class="d-flex justify-content-center gap-2">
        <i onclick="TraerSalmuera(\''. $row["id"] .'\')" class="bi bi-pencil-square text-warning fs-4" style="cursor: pointer;"></i>
        <i onclick="EliminarSalmuera(\''. $row["id"] .'\')" class="bi bi-trash-fill text-danger fs-4" style="cursor: pointer;"></i>
    </div>';

    $subdata[] = $acciones;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);