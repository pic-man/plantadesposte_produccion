<?php
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'id_recepcion',
    1   =>  'fecharec',
    2   =>  'beneficio',
    3   =>  'destino',
    4   =>  'canales',
    5   =>  'consecutivog'
);

include('../config.php');
$sql = "SELECT `id_recepcion`, 
               `fecharec`, 
                proveedorpollo.sede as proveedor, 
                canales, 
               `consecutivog`, 
                proveedorpollo.polloporcanastillas as polloxcanastilla,
               `recepcionpollo`.`status`
        FROM   `recepcionpollo`
        INNER JOIN proveedorpollo ON recepcionpollo.beneficio = proveedorpollo.id    
        ";
$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " WHERE (id_recepcion LIKE '%$busqueda%'";
    $sql .= " OR fecharec LIKE '%$busqueda%'";
    $sql .= " OR beneficio LIKE '%$busqueda%'";
    $sql .= " OR destino LIKE '%$busqueda%'";
    $sql .= " OR canales LIKE '%$busqueda%'";
    $sql .= " OR consecutivog LIKE '%$busqueda%')";
    
    $query = mysqli_query($link, $sql);
    $totalFilter = mysqli_num_rows($query); 
}

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";
$query = mysqli_query($link, $sql);

$data = array();

while ($row = mysqli_fetch_array($query)) {
    $subdata = array();
        
    $subdata[] = "<center>" . $row[0] . "</center>";
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[4].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[1].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[4].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[2].'</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[4].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[3].'<br>'.$row[4].'</a></center>';
        
    $estadoBtn = '<center><a style="z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarItems(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i></center>';
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
