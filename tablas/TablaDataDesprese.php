<?php
include("../config.php");

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

$sql = "SELECT 
    lgd.id,
    lgd.lote,
    lgd.fecha_beneficio,
    lgd.fecha_vencimiento,
    lgd.kilos_disponibles,
    lgd.unidades_disponibles,
    pp.sede AS proveedor,
    lgd.tipo,
    COALESCE(SUM(po.kilos), 0) AS kilos,
    COALESCE(SUM(po.cajas), 0) AS cajas,
    COALESCE(SUM(po.cajas_base), 0) AS cajas_base,
    COALESCE(SUM(po.nro_pollo), 0) AS nro_pollo
FROM lotes_guias_desprese lgd
INNER JOIN 
    proveedorpollo pp ON pp.id = lgd.proveedor
LEFT JOIN
    pesos_pollo po ON po.guia = lgd.guia AND po.lote = lgd.lote
WHERE lgd.guia = '$guia'
GROUP BY lgd.lote, po.lote
";
$query = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($query);
$TotalFiltered = $TotalData;

$data = array();

if ($TotalData > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $subdata = array();
    
        $sql2 = "SELECT guia FROM lotes_guias_desprese WHERE lote = '$row[lote]' ORDER BY guia DESC LIMIT 1";
        $rs_operation2 = mysqli_query($link, $sql2);
        $ultimaGuia = mysqli_fetch_assoc($rs_operation2)["guia"];
    
        $pesosPollo = $row["kilos"] - (($row["cajas"] * 2) + ($row["cajas_base"] * 1.8));
    
        $subdata[] = $row["tipo"];
        $subdata[] = $row["lote"];
        $subdata[] = $row["proveedor"];
        $subdata[] = $row["fecha_beneficio"];
        $subdata[] = $row["fecha_vencimiento"];
        $subdata[] = $row["unidades_disponibles"] - $row["nro_pollo"];
        $subdata[] = number_format($row["kilos_disponibles"] - $pesosPollo, 2, ".", ",");
    
        if ($guia >= $ultimaGuia) {
            $acciones = '<i class="bi bi-trash-fill fs-3 text-danger" style="cursor: pointer;" onclick="EliminarRegistro(\''. $row["id"] .'\', \''. $row["lote"] .'\', \''. $guia .'\')"></i>';
        } else {
            $acciones = '<i class="bi bi-trash-fill fs-4 text-danger" style="visibility: hidden;"></i>';
        }
    
        $subdata[] = $acciones;
        $data[] = $subdata;
    }
} else {
    $sql = "SELECT 
        d.lote_pollo, 
        d.lote_pollo_entero, 
        d.tipo_pollo, 
        pp.sede AS proveedor, 
        ppe.sede AS proveedor_pollo_entero,
        d.fecha_beneficio,
        d.fecha_beneficio_entero,
        d.fecha_venci_pollo,
        d.fecha_venci_pollo_entero
    FROM desprese d
    LEFT JOIN proveedorpollo pp ON pp.id = d.proveedor_pollo
    LEFT JOIN proveedorpollo ppe ON ppe.id = d.proveedor_pollo_entero
    WHERE d.id = '$guia'";

    $query = mysqli_query($link, $sql);
    $TotalData = mysqli_num_rows($query);
    $TotalFiltered = $TotalData;

    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        if ($row["lote_pollo"] != "") {
            $subdata = array();
     
            $subdata[] = $row["tipo_pollo"];
            $subdata[] = $row["lote_pollo"];
            $subdata[] = $row["proveedor"];
            $subdata[] = $row["fecha_beneficio"];
            $subdata[] = $row["fecha_venci_pollo"];
            $subdata[] = "";
            $subdata[] = "";
            $subdata[] = "";

            $data[] = $subdata;
        }

        if ($row["lote_pollo_entero"] != "") {
            $subdata = array();

            $subdata[] = $row["tipo_pollo"];
            $subdata[] = $row["lote_pollo_entero"];
            $subdata[] = $row["proveedor_pollo_entero"];
            $subdata[] = $row["fecha_beneficio_entero"];
            $subdata[] = $row["fecha_venci_pollo_entero"];
            $subdata[] = "";
            $subdata[] = "";
            $subdata[] = "";

            $data[] = $subdata;
        }
    }
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);

