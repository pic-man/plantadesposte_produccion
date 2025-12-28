<?php

include("../config.php");

$sql = "SELECT empresa,sede,direccion,municipio,status,id FROM destinos";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {
    $subdata = array();     
    
    $subdata[] = "<center>".$row[0]."</center>";

    $subdata[] = "<center>".$row[1]."</center>";

    $subdata[] = "<center>".$row[2]."</center>";

    $subdata[] = "<center>".$row[3]."</center>";

    $subdata[] = "<center>".$row[4]."</center>";

    $acciones = '<center><a style="z-index: 0;color:#fff" data-bs-toggle="modal" data-bs-target="#modalDestinos" onclick="editar_destino(\''.$row[5].'\')"><i class="bi bi-pencil-square fs-3 me-2 text-warning"></i></a></center>';

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