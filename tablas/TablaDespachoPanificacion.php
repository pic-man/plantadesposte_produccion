<?php
include("../config.php");
session_start();
$cedula = $_SESSION["usuario"];

if ($cedula == "1106781852") {
    $sql = "SELECT 
        dp.id,
        dp.id_tipo,
        dp.fecha_registro,
        dp.tipo_despacho,
        d.empresa AS empresa,
        d.sede AS sede
    FROM despacho_panificacion dp
    INNER JOIN destinos d ON dp.destino = d.id
    WHERE dp.tipo_despacho = 'INSUMO PARA SEDES'
    ORDER BY dp.id DESC
    ";
} else {
    $sql = "SELECT 
        dp.id,
        dp.id_tipo,
        dp.fecha_registro,
        dp.tipo_despacho,
        d.empresa AS empresa,
        d.sede AS sede
    FROM despacho_panificacion dp
    INNER JOIN destinos d ON dp.destino = d.id
    ORDER BY dp.id DESC
    ";
}

$rs_operation = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($rs_operation)) {
    $subdata = array();

    $sql = "SELECT lote FROM items_despachop WHERE guia = ".$row["id"];
    $rs_operation2 = mysqli_query($link, $sql);
    $lotes = [];

    while ($row2 = mysqli_fetch_assoc($rs_operation2)) {
        $lotes[] = $row2["lote"];
    }

    $lotes = implode(", ", $lotes);

    //$subdata[] = $row["id"] . " / " . $row["id_tipo"];
    $subdata[] = $row["id"];
    $subdata[] = $row["id_tipo"];
    $subdata[] = "<a style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$row["id"]."\",\"".$row["tipo_despacho"]."\")'>". $row["fecha_registro"] ."<br>".$row["tipo_despacho"]."</a>";

    $subdata[] = "<a style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$row["id"]."\",\"".$row["tipo_despacho"]."\")'>". $row["empresa"] . " - " . $row["sede"] ."<br>".$lotes."</a>";

    /* if ($row["tipo_despacho"] == "HARINAS") {
        $subdata[] = "<a style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$row["id"]."\",\"".$row["tipo_despacho"]."\")'>". $unidades . " / " . $row["cantidad_harina"] ."</a>";
    } else {
        $subdata[] = "<a style='cursor: pointer;' data-bs-toggle='modal' data-bs-target='#modalItems' onclick='abrirModal(\"".$row["id"]."\",\"".$row["tipo_despacho"]."\")'>". $unidades . " / " . $row["Nro_panes"] ."</a>";
    } */
    
    $acciones = '<i style="cursor: pointer;display: inline-flex;vertical-align: middle;width: 32px;height: 32px;" onclick="TraerGuia(\''.$row["id"].'\')" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" class="bi bi-pencil-square fs-2 me-2 text-warning"></i>';

    if ($row["tipo_despacho"] == "INSUMOS PARA PRODUCCION INTERNA" || $row["tipo_despacho"] == "INSUMO PARA SEDES") {
        /* if ($unidades == $row["cantidad_harina"]) { */
            $acciones .= '<a href="./controlador/imprimirDespachoPanificacionHarina.php?id='.$row["id"].'" target="_blank"><i style="cursor: pointer;display: inline-flex;vertical-align: middle;width: 32px;height: 32px;" class="bi bi-printer fs-2 text-primary"></i></a>';
        /* } else {
            $acciones .= '<i style="display: inline-flex;vertical-align: middle;width: 32px;height: 32px;visibility: hidden;" class="bi bi-printer fs-2 text-primary"></i>';
        } */
    }else{
        /* if ($unidades == $row["Nro_panes"]) { */
            $acciones .= '<a href="./controlador/imprimirDespachoPanificacion.php?id='.$row["id"].'" target="_blank"><i style="cursor: pointer;display: inline-flex;vertical-align: middle;width: 32px;height: 32px;" class="bi bi-printer fs-2 text-primary"></i></a>';
        /* } else {
            $acciones .= '<i style="display: inline-flex;vertical-align: middle;width: 32px;height: 32px;visibility: hidden;" class="bi bi-printer fs-2 text-primary"></i>';
        } */
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

