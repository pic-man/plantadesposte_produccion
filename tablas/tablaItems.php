<?php
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'item',
    1   =>  'codigo',
    2   =>  'descripcion',
    3   =>  'tipo',
    4   =>  'categoria',
    5   =>  'categoriadestino',
    6   =>  'status',
    7   =>  'id',
);

include('../config.php');
$sql = "SELECT `item`, `codigo`, `descripcion`, `tipo`, `categoria`, `categoriadestino`, `status`, `id` FROM `plantilla`";
$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " WHERE (item LIKE '%$busqueda%'";
    $sql .= " OR codigo LIKE '%$busqueda%'";
    $sql .= " OR descripcion LIKE '%$busqueda%'";
    $sql .= " OR tipo LIKE '%$busqueda%'";
    $sql .= " OR status LIKE '%$busqueda%'";
    $sql .= " OR categoria LIKE '%$busqueda%'";
    $sql .= " OR categoriadestino LIKE '%$busqueda%'";
    $sql .= " OR tipo LIKE '%$busqueda%')";
    
    $query = mysqli_query($link, $sql);
    $totalFilter = mysqli_num_rows($query); 
}

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";
$query = mysqli_query($link, $sql);

$data = array();

while ($row = mysqli_fetch_array($query)) {
    $subdata = array();
    
    $subcategoria = ($row[5] != 0) ? $row[5] : '';
    
    $subdata[] = "<center>" . $row[0] . "<br>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "<br>" . $row[4] . "</center>";
    $subdata[] = "<center>" . $subcategoria . "<br>" . $row[6] . "</center>";
    
    $estadoBtn = '<center><a style="z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarItems(\''.$row[7].'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i></center>';
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
