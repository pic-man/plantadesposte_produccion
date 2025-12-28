<?php
include('../config.php');

$sql = "SELECT
    year,
    semana,
    fecha_inicio,
    fecha_fin,
    status,
    id
    FROM semana
    ORDER BY semana ASC";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";

    $acciones = '<a onclick="edit(\''.$row[5].'\')" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>';
    
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