<?php
include "../config.php";

$sql = "SELECT lote_planta, COUNT(id_recepcion) AS cantidad FROM recepcionpollo WHERE lote_planta <> '' GROUP BY lote_planta ORDER BY id_recepcion ASC";
$rs_operation = mysqli_query($link, $sql);

$TotalData = mysqli_num_rows($rs_operation);

$data = array();

while ($row = mysqli_fetch_array($rs_operation)) {
    if (isset($data[$row["lote_planta"]])) {
        $data[$row["lote_planta"]][3] += $row["cantidad"];
    } else {
        $acciones = '<div class="d-flex justify-content-center gap-2">
            <i onclick="verLote(\'' . $row["lote_planta"] . '\')" class="bi bi-search fs-3" style="cursor: pointer;" title="Ver Lote"></i>
        </div>';
        $data[$row["lote_planta"]] = [$row["lote_planta"], "", "", $row["cantidad"], $acciones];
    }
}

$sql = "SELECT lote, guia FROM lotes_guias_desprese WHERE lote <> '' GROUP BY lote, guia ORDER BY guia ASC";
$rs_operation = mysqli_query($link, $sql);

$TotalData += mysqli_num_rows($rs_operation);

$guiasDesprese = [];
$lotesGuias = [];

while ($row = mysqli_fetch_array($rs_operation)) {
    if (isset($data[$row["lote"]])) {
        $data[$row["lote"]][3] += 1;
        if ($data[$row["lote"]][1] == "") {
            $data[$row["lote"]][1] = $row["guia"];
        } else {
            $data[$row["lote"]][1] = $data[$row["lote"]][1] . ', ' . $row["guia"];
        }
        $guiasDesprese[] = $row["guia"];
        $lotesGuias[$row["guia"]] = $row["lote"];
    } else {
        $acciones = '<div class="d-flex justify-content-center gap-2">
            <i onclick="verLote(\'' . $row["lote"] . '\')" class="bi bi-search fs-3" style="cursor: pointer;" title="Ver Lote"></i>
        </div>';
        $data[$row["lote"]] = [$row["lote"], $row["guia"], "", 1, $acciones];
        $guiasDesprese[] = $row["guia"];
        $lotesGuias[$row["guia"]] = $row["lote"];
    }
}

$sql = "SELECT lote, guia FROM lotes_guia_despresado WHERE lote <> '' GROUP BY lote, guia ORDER BY guia ASC";
$rs_operation = mysqli_query($link, $sql);

$TotalData += mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$guiasDespresado = [];
$lotesGuiasDespresado = [];

while ($row = mysqli_fetch_array($rs_operation)) {
    if (isset($data[$row["lote"]])) {
        $data[$row["lote"]][3] += 1;
        if ($data[$row["lote"]][1] == "") {
            $data[$row["lote"]][1] = $row["guia"];
        } else {
            $data[$row["lote"]][1] = $data[$row["lote"]][1] . ', ' . $row["guia"];
        }
        $guiasDespresado[] = $row["guia"];
        $lotesGuiasDespresado[$row["guia"]] = $row["lote"];
    } else {
        $acciones = '<div class="d-flex justify-content-center gap-2">
            <i onclick="verLote(\'' . $row["lote"] . '\')" class="bi bi-search fs-3" style="cursor: pointer;" title="Ver Lote"></i>
        </div>';
        $data[$row["lote"]] = [$row["lote"], $row["guia"], "", 1, $acciones];
        $guiasDespresado[] = $row["guia"];
        $lotesGuiasDespresado[$row["guia"]] = $row["lote"];
    }
}

$guiasDesprese = implode("', '", $guiasDesprese);
$guiasDespresado = implode("', '", $guiasDespresado);

$sql = "SELECT id_guia, tipoGuia, guiaDesprese FROM guiaspollo WHERE tipoGuia = 'DESPRESE' AND guiaDesprese IN ('$guiasDesprese') OR tipoGuia = 'DESPRESADO' AND guiaDesprese IN ('$guiasDespresado') GROUP BY id_guia ORDER BY id_guia ASC";
$rs_operation = mysqli_query($link, $sql);

$TotalData += mysqli_num_rows($rs_operation);

while ($row = mysqli_fetch_array($rs_operation)) {
    if ($row["tipoGuia"] == "DESPRESE") {
        if ($data[$lotesGuias[$row["guiaDesprese"]]][2] == "") {
            $data[$lotesGuias[$row["guiaDesprese"]]][2] = $row["id_guia"];
        } else {
            $data[$lotesGuias[$row["guiaDesprese"]]][2] = $data[$lotesGuias[$row["guiaDesprese"]]][2] . ', ' . $row["id_guia"];
        }
        $data[$lotesGuias[$row["guiaDesprese"]]][3] += 1;
    } else {
        if ($data[$lotesGuiasDespresado[$row["guiaDesprese"]]][2] == "") {
            $data[$lotesGuiasDespresado[$row["guiaDesprese"]]][2] = $row["id_guia"];
        } else {
            $data[$lotesGuiasDespresado[$row["guiaDesprese"]]][2] = $data[$lotesGuiasDespresado[$row["guiaDesprese"]]][2] . ', ' . $row["id_guia"];
        }
        $data[$lotesGuiasDespresado[$row["guiaDesprese"]]][3] += 1;
    }
}

$data = array_values($data);

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);
