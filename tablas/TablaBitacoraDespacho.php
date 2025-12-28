<?php
include "../config.php";

$lote = isset($_POST["lote"]) ? $_POST["lote"] : "";

$sql = "SELECT guia FROM lotes_guias_desprese WHERE lote = '$lote'";
$rs_operation = mysqli_query($link, $sql);

$guiasDesprese = [];

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $guiasDesprese[] = $row["guia"];
}

$sql = "SELECT guia FROM lotes_guia_despresado WHERE lote = '$lote'";
$rs_operation = mysqli_query($link, $sql);

$guiasDespresado = [];

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $guiasDespresado[] = $row["guia"];
}

$guiasDesprese = implode("', '", $guiasDesprese);
$guiasDespresado = implode("', '", $guiasDespresado);

/* echo $guiasDesprese . " - ";
echo $guiasDespresado;
exit; */

$sql = "SELECT 
    gp.tipoGuia, 
    gp.guiaDesprese, 
    gp.fechaexp, 
    gp.id_guia, 
    SUM(ipp.unidades) AS nroPollos,
    SUM(ipp.peso_real) AS kilos
FROM guiaspollo gp
INNER JOIN item_proveedorpollo ipp ON ipp.proveedor = gp.id_guia
WHERE 
    gp.tipoGuia = 'DESPRESE' AND gp.guiaDesprese IN ('$guiasDesprese') AND gp.guiaDesprese != 0 OR 
    gp.tipoGuia = 'DESPRESADO' AND gp.guiaDesprese IN ('$guiasDespresado') AND gp.guiaDesprese != 0 
GROUP BY gp.id_guia 
ORDER BY gp.id_guia ASC";

$rs_operation = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = [];

$totalUnidades = 0;
$totalKilos = 0;

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subdata = [];

    $subdata[] = $row["tipoGuia"];
    $subdata[] = $row["guiaDesprese"];
    $subdata[] = $row["fechaexp"];
    $subdata[] = $row["id_guia"];
    $subdata[] = $row["nroPollos"];
    $subdata[] = number_format($row["kilos"], 2, ".", "");

    $data[] = $subdata;

    $totalKilos += $row["kilos"];
    $totalUnidades += $row["nroPollos"];
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data,
    "TotalKilos" => number_format($totalKilos, 2, ".", ""),
    "TotalUnidades" => number_format($totalUnidades, 0, ".", "")
);

echo json_encode($json_data);
