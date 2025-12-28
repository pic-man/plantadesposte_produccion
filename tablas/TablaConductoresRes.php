<?php

require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'cedula',
    1   =>  'nombres',
    2   =>  'telefono',
    3   =>  'status',
    4   =>  'id',
);

include('../config.php');
$sql = "SELECT `cedula`, `nombres`, `telefono`, `status`, `id` FROM `conductores_recepcion`";
$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " WHERE (cedula LIKE '%$busqueda%'";
    $sql .= " OR nombres LIKE '%$busqueda%'";
    $sql .= " OR telefono LIKE '%$busqueda%'";
    $sql .= " OR status LIKE '%$busqueda%')";
    
    $query = mysqli_query($link, $sql);
    $totalFilter = mysqli_num_rows($query); 
}

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";
$query = mysqli_query($link, $sql);

$data = array();

while ($row = mysqli_fetch_array($query)) {
    $subdata = array();

    if ($row[3] == 1) {
        $row[3] = "ACTIVO";
    } else {
        $row[3] = "INACTIVO";
    }
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . utf8_encode($row[1]) . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    
    $estadoBtn = '<a style="line-height: 1;" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarItems(\''.$row[4].'\')"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';

    $estadoBtn .= '<a style="line-height: 1;" onclick="eliminarItem(\''.$row[4].'\')"><i class="bi bi-trash-fill fs-2 me-2 text-danger"></i></a>';
    
    $subdata[] = $estadoBtn;

    $data[] = $subdata;
}

$json_data = array(
    "draw"              => intval($request['draw']),
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>