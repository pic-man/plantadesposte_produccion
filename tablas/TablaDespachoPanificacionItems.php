<?php
include("../config.php");
session_start();
$idGuia = isset($_POST["id"]) ? $_POST["id"] : "";

$sql = "SELECT 
    i.id,
    p.descripcion AS item,
    i.lote,
    i.unidades,
    i.canastillas,
    i.canastillas_base,
    i.fecha_venci,
    b.descripcion AS tipo_bolsa,
    i.kilos
FROM items_despachop i
INNER JOIN plantilla p ON p.item = i.item
LEFT JOIN bolsas b ON b.id = i.tipo_bolsa
WHERE guia = '$idGuia'
ORDER BY id DESC
";

$rs_operation = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subdata = array();

    $subdata[] = $row["id"];
    $subdata[] = $row["item"];
    $subdata[] = $row["lote"];
    if ($row["tipo_bolsa"] != "") {
        $subdata[] = $row["tipo_bolsa"];
    }else {
        $subdata[] = "";
    }
    $subdata[] = $row["unidades"];
    if ($row["kilos"] != "0") {
        $subdata[] = $row["kilos"];
    } else {
        $subdata[] = "";
    }
    $subdata[] = $row["canastillas"];
    $subdata[] = $row["canastillas_base"];
    if ($row["kilos"] != "0") {
        $subdata[] = "";
        $subdata[] = $row["fecha_venci"];
    } else {
        $subdata[] = $row["fecha_venci"];
        $subdata[] = "";
    }
    
    if ($_SESSION["usuario"] == "1106781852") {
        $acciones = '';
    } else {
        $acciones = '<i style="cursor: pointer;display: inline-flex;vertical-align: middle;width: 29px;height: 29px;" onclick="TraerItem(\''.$row["id"].'\')" class="bi bi-pencil-square fs-3 me-2 text-warning"></i>';
        $acciones .= '<i style="cursor: pointer;display: inline-flex;vertical-align: middle;width: 29px;height: 29px;" onclick="EliminarItem(\''.$row["id"].'\')" class="bi bi-trash-fill fs-3 text-danger"></i>';
    }
    $subdata[] = $acciones;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);
echo json_encode($json_data);

