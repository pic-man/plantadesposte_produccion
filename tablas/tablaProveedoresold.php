<?php
session_start();
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaProveedores = listaProveedoresCompleta();
$totalData = mysqli_num_rows($listaProveedores);

if (!empty($busqueda)) {
    $listaProveedores = listaProveedoresPaginada($inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaPrecios);
} else {
    $totalFiltro = $totalData;
}

$listaProveedores = listaProveedoresPaginada($inicio, $fin, $busqueda); 
$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaProveedores)) {
    $cont++;
    $btns = '';
    $subdata = array();
    $subdata[] = '<center>'.$row[7].'<br>'.$row[6].'</center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\');" data-bs-toggle="modal">'.$row[1].'<br>'.$row[8].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\');" data-bs-toggle="modal">'.$row[9].' <br> '.$row[3].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\');" data-bs-toggle="modal">'.$row[4].' <br> '.$row[5].'</a></center>';
    
    if(($row[10] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[7].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>
        <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
    }else{
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" class="btn btn-primary" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank">Imprimir</a></center>';
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