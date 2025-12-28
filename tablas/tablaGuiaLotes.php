<?php
include('../config.php');
/* include('../modelo/funciones.php');
session_start(); */

$guia = isset($_POST['guia']) ? $_POST['guia']: 1; 

$sql = "SELECT 
    id,
    lote
    FROM lotes_guia
    WHERE recepcion_guia = '$guia'
    ORDER BY id DESC";

    // colocar fecha_registro

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$cont = 1;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $subdata[] = "<center>" . $cont . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";

    $acciones = '<a onclick="borrarLote(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-3 text-danger" title="Eliminar"></i></a>';

    $cont++;
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