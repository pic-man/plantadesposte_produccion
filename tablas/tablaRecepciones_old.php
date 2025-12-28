<?php
session_start();
error_reporting(0);
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaProveedores = listaRecepcionesCompleta();
$totalData = mysqli_num_rows($listaProveedores);

if (!empty($busqueda)) {
    $listaProveedores = listaRecepcionesPaginada($inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaPrecios);
} else {
    $totalFiltro = $totalData;
}

$listaProveedores = listaRecepcionesPaginada($inicio, $fin, $busqueda); 
$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaProveedores)) {
    $cont++;
    $btns = '';
    $subdata = array();
    $subdata[] = '<center>'.$row[0].'<br>'.$row[8].'</center>';
if($row[8]=='RES'){
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[1].'<br>'.$row[2].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[3].'<br>'.$row[7].'</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItems(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[5].'<br>'.$row[6].'</a></center>';
    
    if(($row[9] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>
        <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
    }else{
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
    }
}else{
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriteriosC" onclick="abrirModalC(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItemsC(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[1].'<br>'.$row[2].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriteriosC" onclick="abrirModalC(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItemsC(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[3].'<br>'.$row[7].'</a></center>';

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriteriosC" onclick="abrirModalC(\''.$row[0].'\',\''.$row[9].'\',\''.$_SESSION['tipo'].'\'); buscarItemsC(\''.$row[0].'\');" data-bs-toggle="modal">'.$row[5].'<br>'.$row[6].'</a></center>';
}
if(($row[9] == '1')||($_SESSION['tipo'] == 0)){
    $estadoBtn = '<center>
    <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>
    <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
}else{
    $estadoBtn = '<center>
    <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[0].'\')" href="controlador/imprimiringresopdf.php?id='.$row[0].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
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
