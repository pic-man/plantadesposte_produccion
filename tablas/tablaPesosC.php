<?php
session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
function fsalida($cad2){
   $uno = substr($cad2, 11, 5);
   return $uno;
}
$request = $_REQUEST;
$recepcion = $_POST['recepcion'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaItems = listaPesosPorRecepcion($recepcion);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaItems)) {
    $cont++;
    $btns = '';
    $total = $row[3] + $row[4] + $row[5] + $row[6];
    if($row[3]=='0' or $row[4]=='0' or $row[5]=='0' or $row[6]=='0'){
        $color = "#ff0000";
    }else{
        $color = "#000000";
    }
    $subdata = array();
    $subdata[] = "<font color='".$color."'>".$row[1]."</font>";
    $subdata[] = "<font color='".$color."'>".$row[2]."</font>";
    $subdata[] = "<font color='".$color."'>".$row[3]."</font>";
    $subdata[] = "<font color='".$color."'>".$row[4]."</font>";
    $subdata[] = "<font color='".$color."'>".$row[5]."</font>";
    $subdata[] = "<font color='".$color."'>".$row[6]."</font>";
    $subdata[] = "<font color='".$color."'>".$total."</font>";
    //$subdata[] = "<font color='".$color."'>".$row[10]."</font>";
    $subdata[] = "<font color='".$color."'>".fsalida($row[9])."</font>";
    
    if(($row[8] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
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
