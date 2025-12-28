<?php
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'nit',
    1   =>  'razon',
    2   =>  'direccion',
    3   =>  'municipio',
    4   =>  'polloporcanastillas',
    5   =>  'status',
    6   =>  'id',
    7   =>  'siglas'
);

include('../config.php');
$sql = "SELECT `nit`, `sede`, `direccion`, `municipio`, polloporcanastillas, `status`, `id`,`siglas` 
FROM `proveedorpollo`";
$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " WHERE (nit LIKE '%$busqueda%'";
    $sql .= " OR sede LIKE '%$busqueda%'";
    $sql .= " OR direccion LIKE '%$busqueda%'";
    $sql .= " OR municipio LIKE '%$busqueda%'";
    $sql .= " OR polloporcanastillas LIKE '%$busqueda%'";
    $sql .= " OR siglas LIKE '%$busqueda%'";
    $sql .= " OR status LIKE '%$busqueda%')";
    
    $query = mysqli_query($link, $sql);
    $totalFilter = mysqli_num_rows($query); 
}

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";
$query = mysqli_query($link, $sql);

$data = array();

while ($row = mysqli_fetch_array($query)) {
    $subdata = array();
    
    $subcategoria = ($row[5] != 0) ? $row[5] : '';
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";
    $subdata[] = "<center>" . $row[7] . "</center>";
    $subdata[] = "<center>" . $row[5] . "</center>";
    
    $estadoBtn = '<center><a style="z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarItems(\''.$row[6].'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i></center>';
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
