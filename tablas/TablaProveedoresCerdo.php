<?php
include('../config.php');

$sql = "SELECT 
    id,
    nit,
    siglas,
    sede,
    direccion, 
    municipio, 
    status
FROM proveedor_cerdo";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center>" . $row[1] . "<br>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";
    $subdata[] = "<center>" . $row[5] . "</center>";
    $subdata[] = "<center>" . $row[6] . "</center>";
    
    $estadoBtn = '<center><a style="margin-left: 5px;z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarProveedor(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i></center>';
    $subdata[] = $estadoBtn;

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
