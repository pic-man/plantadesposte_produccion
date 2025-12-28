<?php
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$proveedor = $_POST['proveedor'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'item',
    1   =>  'descripcion',
    2   =>  'temperatura',
    3   =>  'unidades',
    4   =>  'cajas',
    5   =>  'peso',
    6   =>  'registro',
    7   =>  'id',
);

include('../config.php');
$sql = "SELECT plantilla.item, descripcion, temperatura, unidades, cajas, peso, registro, id_item_proveedor, peso_real,
                 item_proveedorpollo.status, tipo, base 
          FROM plantilla,recepcion_pesos_pollo 
          WHERE plantilla.item=recepcion_pesos_pollo.item AND recepcion_pesos_pollo.proveedor='" . $proveedor . "'";
$query = mysqli_query($link, $sql);



$totalData = mysqli_num_rows($query);

$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (plantilla.item LIKE '%$busqueda%'";
    $sql .= " OR descripcion LIKE '%$busqueda%'";
    $sql .= " OR temperatura LIKE '%$busqueda%'";
    $sql .= " OR unidades LIKE '%$busqueda%'";
    $sql .= " OR cajas LIKE '%$busqueda%'";
    $sql .= " OR peso LIKE '%$busqueda%'";
    $sql .= " OR registro LIKE '%$busqueda%'";
    $sql .= " OR peso_real LIKE '%$busqueda%')";
    
    $query = mysqli_query($link, $sql);
    $totalFilter = mysqli_num_rows($query); 
}

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";
$query = mysqli_query($link, $sql);

$data = array();
while ($row = mysqli_fetch_array($listaItems)) {
    $cont++;
    $btns = '';
    $subdata = array();
    $subdata[] = $cont;
    $subdata[] = $row[0];
    $subdata[] = $row[1];
    $subdata[] = $row[2];
    $subdata[] = $row[3];
    $subdata[] = $row[4];
    $subdata[] = $row[5];
    $subdata[] = $row[8]."Kg. <br>(".$row[5]."-".($row[4]*2)."-".(($row[11]*1.8)).")";
    
    $subdata[] = fsalida($row[7]);
    
    if(($row[10] == '1')||($_SESSION['tipo'] == 0)){
        if(($row[0] == '050514')||($row[0] == '050515')||($row[0] == '050516')||($row[0] == '050517')){
            $estadoBtn = '<i class="bi bi-pencil-square fs-2 text-warning" style="visibility: hidden;"></i>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
        }else{
            $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[8].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
        }
        
    }   
    $subdata[] = $estadoBtn;

    $data[] = $subdata;
}

$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);

echo json_encode($json_data);
?>
