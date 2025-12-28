<?php
include("../config.php");

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

$sql = "SELECT 
    lgd.lote,
    lgd.fecha_beneficio,
    lgd.fecha_vencimiento,
    pp.sede AS proveedor,
    lgd.tipo
FROM lotes_guia_despresado lgd
INNER JOIN 
    proveedorpollo pp ON pp.id = lgd.proveedor
WHERE lgd.guia = '$guia'
";

$query = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($query);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($query)) {
    $subdata = array();

    $subdata[] = $row["tipo"];
    $subdata[] = $row["lote"];
    $subdata[] = $row["proveedor"];
    $subdata[] = $row["fecha_beneficio"];
    $subdata[] = $row["fecha_vencimiento"];

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);

