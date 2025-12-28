<?php
session_start();
include('../config.php');

$sql = "SELECT 
    id,
    semana,
    fecha_inicio,
    fecha_fin,
    especie,
    cantidad,
    status
    FROM programacioncanales";

if ($_SESSION['usuario'] != "ADMINISTRADOR" && $_SESSION['usuario'] != "16757007") {
    $sql .= " WHERE sede = '".$_SESSION['usuario']."' AND status = '1'";
}

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[0] . "</a></center>";
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[1] . "</a></center>";
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[2] . "</a></center>";
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[3] . "</a></center>";
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[4] . "</a></center>";
    $subdata[] = "<center><a data-bs-toggle='modal' data-bs-target='#modalNuevoProveedor' onclick='abrirModal(\"".$row[0]."\")'>" . $row[5] . "</a></center>";

    $acciones = '<a onclick="edit_canal(\''.$row[0].'\')" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>';

    if ($_SESSION['usuario'] == "ADMINISTRADOR") {
        if($row[6] == '1'){
            $acciones .='<a style="color: red" onclick="bloquearCanal(\''.$row[0].'\')"><i class="bi bi-unlock fs-2 ms-2 style="color:#ff0000;"></i></a></center>';
        }else{
            $acciones .='<a style="color:#000" onclick="desbloquearCanal(\''.$row[0].'\')"><i class="bi-lock fs-2 ms-2 style="color:#00a45f;"></i></a></center>';
        }
    }

    $subdata[] = $acciones;

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
