<?php
include("../config.php");

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

$sql = "SELECT 
    lgd.lote,
    lgd.fecha_beneficio,
    lgd.fecha_vencimiento,
    lgd.kilos_disponibles,
    pp.sede AS proveedor,
    lgd.tipo,
    psp.tipo_pollo AS tipo_pollo
FROM lotes_guias_desprese lgd
INNER JOIN 
    proveedorpollo pp ON pp.id = lgd.proveedor
INNER JOIN 
    pesos_pollo psp ON psp.lote = lgd.lote AND lgd.guia = psp.guia
WHERE 
    lgd.guia = '$guia'
GROUP BY lgd.lote, psp.tipo_pollo
ORDER BY SUM(psp.kilos) DESC
";

$query = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($query);
$TotalFiltered = $TotalData;

$lotes = [];

if ($TotalData == 0) {
    $sql = "SELECT 
        d.lote_pollo, 
        d.lote_pollo_entero, 
        d.tipo_pollo, 
        pp.sede AS proveedor, 
        ppe.sede AS proveedor_pollo_entero,
        d.fecha_beneficio,
        d.fecha_beneficio_entero,
        d.fecha_venci_pollo,
        d.fecha_venci_pollo_entero,
        d.tipo_pollo,
        COALESCE(pep.tipo_pollo, 'N/A') AS tipoUso,
        COALESCE(pepe.tipo_pollo, 'N/A') AS tipoUsoPolloEntero
    FROM desprese d
    LEFT JOIN proveedorpollo pp ON pp.id = d.proveedor_pollo
    LEFT JOIN proveedorpollo ppe ON ppe.id = d.proveedor_pollo_entero
    LEFT JOIN pesos_pollo pep ON pep.lote = d.lote_pollo AND pep.guia = d.id
    LEFT JOIN pesos_pollo pepe ON pepe.lote = d.lote_pollo_entero AND pepe.guia = d.id
    WHERE d.id = '$guia'";

    $query = mysqli_query($link, $sql);
    $TotalData = mysqli_num_rows($query);
    $TotalFiltered = $TotalData;

    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        if ($row["lote_pollo"] != "") {
            $subdata = array();
            $lotes[] = $row["lote_pollo"];

            $subdata[] = $row["tipo_pollo"];
            $subdata[] = $row["tipoUso"];
            $subdata[] = $row["lote_pollo"];
            $subdata[] = $row["proveedor"];
            $subdata[] = $row["fecha_beneficio"];
            $subdata[] = $row["fecha_venci_pollo"];

            $data[] = $subdata;
        }

        if ($row["lote_pollo_entero"] != "" || $row["lote_pollo_entero"] != 0) {
            $subdata = array();
            $lotes[] = $row["lote_pollo_entero"];

            $subdata[] = $row["tipo_pollo"];
            $subdata[] = $row["tipoUsoPolloEntero"];
            $subdata[] = $row["lote_pollo_entero"];
            $subdata[] = $row["proveedor_pollo_entero"];
            $subdata[] = $row["fecha_beneficio_entero"];
            $subdata[] = $row["fecha_venci_pollo_entero"];

            $data[] = $subdata;
        }
    }
} else {
    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $subdata = array();
        $lotes[] = $row["lote"];

        $subdata[] = $row["tipo"];
        $subdata[] = $row["tipo_pollo"];
        $subdata[] = $row["lote"];
        $subdata[] = $row["proveedor"];
        $subdata[] = $row["fecha_beneficio"];
        $subdata[] = $row["fecha_vencimiento"];

        $data[] = $subdata;
    }
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data,
    "lotes" => $lotes
);

echo json_encode($json_data);

