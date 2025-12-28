<?php
include('../config.php');

$idGuia = $_POST["id"] ? $_POST["id"] : "";

$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'id',
    1   =>  'tipo_pollo',
    2   =>  'kilos',
    3   =>  'cajas',
    4   =>  'cajas_base'
);

$sql = "SELECT
    id,
    tipo_pollo,
    kilos,
    cajas,
    cajas_base
    FROM pesos_pollo_despresado
    WHERE guia = '$idGuia'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (id LIKE '%$busqueda%' 
                OR tipo_pollo LIKE '%$busqueda%'  
                OR kilos LIKE '%$busqueda%' 
                OR cajas LIKE '%$busqueda%'
                OR cajas_base LIKE '%$busqueda%')";
}

$totalDataQuery = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($totalDataQuery);

// Obtener el total con los filtros aplicados
$totalFilterQuery = mysqli_query($link, $sql);
$totalFilter = mysqli_num_rows($totalFilterQuery);
/* $sql .= " ORDER BY id_item_desprese DESC";


$sql .= " LIMIT $inicio, $fin"; */

$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT $inicio, $fin";

$query = mysqli_query($link, $sql) or die(mysqli_error($link));

$totalCajas = 0;
$totalKilos = 0;
$totalCajasBases = 0;
$totalKilosNeto = 0;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $totalCajas += $row[3];
    $totalKilos += $row[2];
    $totalCajasBases += $row[4];
    $totalKilosNeto += $row[2] - (($row[3] * 2) + ($row[4] * 1.8));

    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . number_format($row[2], 2, ".", ",") . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";
    $subdata[] = "<center>" . number_format(($row[2] - (($row[3] * 2) + ($row[4] * 1.8))), 2, ".", ",") . "</center>";

    $acciones = '<a onclick="editPesosPollo(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-3 me-2 text-warning"></i></a>';

    $acciones .= '<a onclick="borrarPeso(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-3 text-danger"></i></a>';
    
    $subdata[] = $acciones;

    $data[] = $subdata;
}

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = number_format($totalKilos, 2, ".", ",");
$subdata[] = number_format($totalCajas, 0, ",", ".");
$subdata[] = number_format($totalCajasBases, 0, ",", ".");
$subdata[] = number_format($totalKilosNeto, 2, ".", ",");
$subdata[] = '';

$data[] = $subdata;

$json_data = array(
    "draw"              => intval($request['draw']),
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>