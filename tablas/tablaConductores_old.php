<?php
//session_start();
error_reporting(0);
require_once('../modelo/funciones.php');
function fsalida($cad2){
   $uno = substr($cad2, 11, 5);
   return $uno;
}
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaItems = listaConductoresCompleta($inicio,$fin,$busqueda);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaItems)) {
    $cont++;
    $btns = '';
    $subdata = array();
    $subdata[] = $cont;
    $subdata[] = "<center>".$row[0]."</center>";
    $subdata[] = "<center>".$row[1]."</center>";
    $subdata[] = "<center>".$row[2]."</center>";
    $subdata[] = "<center>".$row[3]."</center>";
    
        /* $estadoLabel = '<span class="label label-danger" rel="tooltip" data-placement="bottom" title="Inactivo">x</span>'; */
    $estadoBtn = '<center><a class="btn btn-primary" style="z-index: 0;color:#fff" data-target="#modalNuevoProveedor" data-toggle="modal" onclick="buscarConductor(\''.$row[4].'\')">M</a>&nbsp;&nbsp;</center>';

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
