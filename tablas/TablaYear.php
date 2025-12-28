<?php
include('../config.php');

$sql = "SELECT
    id,
    year,
    status
    FROM year
    ORDER BY year DESC";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";   

    $acciones = '<a onclick="editYear(\''.$row[0].'\')" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';
    
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