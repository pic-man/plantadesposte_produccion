<?php
//session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaSedes = listaSedesConComprador();
$totalData = mysqli_num_rows($listaSedes);
$totalFiltro = $totalData;

$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaSedes)) {
    $cont++;
    $btns = '';
    $subdata = array();
    $subdata[] = $cont;
    if($row[0]=='01'){$empresa='Mercatodo';}
    elseif($row[0]=='02'){$empresa='MercaMio';}
    else{$empresa='Merkmios';}
    $subdata[] = $empresa;
    
    $subdata[] = $row[1];
    $subdata[] = $row[2];

        /* $estadoLabel = '<span class="label label-danger" rel="tooltip" data-placement="bottom" title="Inactivo">x</span>'; */
        $estadoBtn = '<a class="btn btn-primary" style="z-index: 0;color:#fff" class="btn btn-info" onclick="eliminarSede(\''.$row[1].'\')">X</a>';

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
