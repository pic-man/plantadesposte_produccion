<?php
include "../config.php";

$guia = $_POST["guia"];

$sql = "SELECT 
    i.id,
    p.descripcion AS item,
    i.lote,
    i.cantidad,
    i.status
FROM impresiones_items_despresado i 
INNER JOIN
    plantilla p ON i.item = p.item
WHERE guia = '$guia'";

$rs_operation = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operation);
$TotalFilter = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $sub_array = array();

    $sub_array[] = $row["item"];
    $sub_array[] = $row["lote"];
    $sub_array[] = $row["cantidad"];
    $sub_array[] = $row["status"];

    $acciones = "<div class='d-flex justify-content-center'>
        <i onclick='eliminarEtiqueta($row[id])' class='bi bi-trash-fill fs-4 text-danger' style='cursor: pointer;'></i>
    </div>";

    $sub_array[] = $acciones;
    $data[] = $sub_array;
}

$output = array(
    "recordsTotal" => $TotalData,
    "recordsFiltered" => $TotalFilter,
    "data" => $data
);

echo json_encode($output);