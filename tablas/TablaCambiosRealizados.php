<?php
//session_start();
include('../config.php');

$datos = isset($_POST['datos']) ? $_POST['datos'] : '';

$sql = "SELECT 
    id,
    sede_origen,
    item,
    enviado
    FROM registro_cambios
    WHERE sede_destino = '$datos[sede]' AND semana = '$datos[semana]' AND item = '$datos[item]'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();
    
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    
    $acciones = '<a onclick="BorrarCambio(\''.$row[0].'\', \''. $row[3] .'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';

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
