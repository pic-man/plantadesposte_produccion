<?php
session_start();
include('../config.php');

$sql = "SELECT 
    id,
    descripcion,
    status
    FROM municipios ORDER BY descripcion ASC";
    
$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";

    $acciones = '<a onclick="edit_ciudad(\''.$row[0].'\')" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';

    $acciones .= '<a onclick="borrar_ciudad(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';

    $subdata[] = $acciones;

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
