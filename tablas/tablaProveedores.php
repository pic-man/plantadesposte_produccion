<?php
session_start();
require_once('../modelo/funciones.php');
$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaProveedores = listaProveedoresCCompleta();
$totalData = mysqli_num_rows($listaProveedores);

if (!empty($busqueda)) {
    $listaProveedores = listaProveedoresCPaginada($inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaProveedores);
} else {
    $totalFiltro = $totalData;
}

$listaProveedores = listaProveedoresCPaginada($inicio, $fin, $busqueda); 
$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaProveedores)) {
    $cont++;
    $btns = '';
    $subdata = array();

    include('../config.php');
    $sql = "SELECT id_guia FROM guias ORDER BY id_guia DESC LIMIT 1";
    $id_guia = mysqli_fetch_row(mysqli_query($link, $sql));
    if ($row[7] != $id_guia[0]) {
        $sql5 = "SELECT proveedor FROM item_proveedor WHERE proveedor =".$row[7];
        if (mysqli_num_rows(mysqli_query($link, $sql5)) == 0) {
            $row[4] = "ANULADO";
            $row[5] = "";
        }
    }

    if ($_SESSION["usuario"] == "ADMINISTRADOR") {
        $sql13 = "SELECT SUM(peso) AS kilos, SUM(cajas) AS cajas FROM item_proveedor WHERE proveedor = '".$row[7]."' ";
        $rs_operation = mysqli_query($link, $sql13);
        $row13 = mysqli_fetch_assoc($rs_operation);
        if ($row13["cajas"] > 0 && $row13["kilos"] > 0) {
            $peso_promedio = $row13["kilos"] / $row13["cajas"];
            $peso_promedio = number_format($peso_promedio, 2, ".", ",") . " kg";
        } else {
            $peso_promedio = "0 kg";
        }
    }

    if ($_SESSION["tipo"] == 0) {
        $sqlCambios = "SELECT COUNT(idCambio) AS totalCambios FROM item_proveedor_cambios WHERE proveedor = '$row[id_guia]'";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);
        $rowCambios = mysqli_fetch_assoc($rs_operacionCambios);
        $totalCambios = $rowCambios["totalCambios"];
    } else {
        $totalCambios = 0;
    }

    $subdata[] = '<center>'.$row[7].'<br>'.$row[6].'</center>';
    
    if ($_SESSION["usuario"] == "ADMINISTRADOR") {
        if ($peso_promedio > 25) {
            $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\', \''. $row[11] .'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[1].'<br><a title="Peso Promedio" style="color: red;">'.$peso_promedio.'</a></a></center>';
        } else {
            $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\', \''. $row[11] .'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[1].'<br><a title="Peso Promedio"">'.$peso_promedio.'</a></a></center>';
        }
    } else {
        $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\', \''. $row[11] .'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[1].'</a></center>';
    }

    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\', \''. $row[11] .'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[9].' <br> '.$row[3].' <br> '.$row[8].'</a></center>';
    
    $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$row[7].'\',\''.$row[8].'\',\''.$row[10].'\',\''.$_SESSION['tipo'].'\', \''. $row[11] .'\'); buscarItems(\''.$row[7].'\',\''.$row[6].'\', \''. $totalCambios .'\');" data-bs-toggle="modal">'.$row[4].' <br> '.$row[5].'</a></center>';
    
    /* if(($row[10] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[7].'\',\''.$row[6].'\')"><i class="bi bi-pencil-square fs-2 text-warning me-2"></i></a>';
        
        $estadoBtn .='<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
    
    }else{
        $estadoBtn = '<center>
        <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank"><i class="bi bi-printer fs-2 text-primary"></i></a></center>';
    } */

    if ($row[4] != "ANULADO") {
        if($_SESSION['tipo'] == 0){
            $estadoBtn = '<center>
            <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[7].'\',\''.$row[6].'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 text-warning me-2"></i></a>';
        
            $estadoBtn .='<a style="z-index: 0;color:#000" href="controlador/preliminarpdf.php?id='.$row[7].'" target="_blank" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';
    
            $estadoBtn .='<a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
    
            if($row[10] == '1'){
                $estadoBtn .='<a style="z-index: 0;color: red" onclick="bloquearEdicion(\''.$row[7].'\')" title="Bloquear Guia"><i class="bi bi-unlock fs-2 style="color:#ff0000;"></i></a>';
            }else{
                $estadoBtn .='<a style="z-index: 0;color:#000" onclick="desbloquearEdicion(\''.$row[7].'\')" title="Desbloquear Guia"><i class="bi-lock fs-2 style="color:#00a45f;"></i></a>';
            }
        }else{
            $estadoBtn = '<center>';
            if($row[10] == '1'){
                $estadoBtn = '
            <a style="z-index: 0;color:#000" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscarGuia(\''.$row[7].'\',\''.$row[6].'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 text-warning me-2"></i></a>';
            }else{
                $estadoBtn .= '<i class="bi bi-printer fs-2 me-2" style="visibility: hidden;"></i>';
            }
    
            $estadoBtn .='<a style="z-index: 0;color:#000" href="controlador/preliminarpdf.php?id='.$row[7].'" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';
    
                $estadoBtn .= '
                <a style="z-index: 0;color:#000" onclick="bloquearEdicion(\''.$row[7].'\')" href="controlador/imprimirpdf.php?id='.$row[7].'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 text-primary"></i></a>';
                 
        }

        if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
            $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success"></i>';
        } else {
            $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success" style="visibility: hidden;"></i>';
        }

        $estadoBtn .= '</center>';
    }else {
        $estadoBtn = "";
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