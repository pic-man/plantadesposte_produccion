<?php
session_start();
include('../config.php');

$semana = isset($_POST['semana']) ? $_POST['semana'] : '';

$sql = "SELECT 
    id,
    sede,
    semana,
    fecha_inicio,
    fecha_fin,
    especie,
    cantidad,
    status
    FROM programacioncanales WHERE semana = '$semana'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";
    $subdata[] = "<center>" . $row[5] . "</center>";
    $subdata[] = "<center>" . $row[6] . "</center>";

    $acciones = '<a onclick="abrirModal(\''.$row[0].'\')"><i class="bi bi-search fs-2"></i></a>';

    /* if ($_SESSION['usuario'] == "ADMINISTRADOR" || $_SESSION['usuario'] == "16757007") {
        if($row[6] == '1'){
            $acciones .='<a style="color: red" onclick="bloquearCanal(\''.$row[0].'\')"><i class="bi bi-unlock fs-2 ms-2 style="color:#ff0000;"></i></a></center>';
        }else{
            $acciones .='<a style="color:#000" onclick="desbloquearCanal(\''.$row[0].'\')"><i class="bi-lock fs-2 ms-2 style="color:#00a45f;"></i></a></center>';
        }
    } */

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
