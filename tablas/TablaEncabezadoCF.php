<?php
include "../config.php";

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

if ($guia == "") {
    echo json_encode(array(
        "recordsTotal"      => intval(0),         
        "recordsFiltered"   => intval(0),       
        "data"              => 0                     
    ));
    exit;
}

$sqlTipo = "SELECT lgd.tipo AS tipo FROM lotes_guias_desprese lgd INNER JOIN pesos_pollo pp ON lgd.guia = pp.guia AND lgd.lote = pp.lote WHERE lgd.guia = '$guia' GROUP BY lgd.lote ORDER BY pp.kilos DESC LIMIT 1";
$rs_operationTipo = mysqli_query($link, $sqlTipo);
$rowTipo = mysqli_fetch_assoc($rs_operationTipo);

if ($rowTipo == null) {
    echo json_encode(array(
        "recordsTotal"      => intval(0),         
        "recordsFiltered"   => intval(0),       
        "data"              => 0                     
    ));
    exit;
}

$tipo = $rowTipo["tipo"];

if ($tipo == "CAMPO") {
    $item = "059763";
    $itemName = "POLLO CAMPO MERCAMIO NATURAL";
} else {
    $item = "";
    $itemName = "POLLO CAMPO MERCAMIO NATURAL";
}

$sql = "SELECT 
    CASE WHEN status = 1 THEN
        SUM(kilos)
    ELSE
        SUM(kilos) - (SUM(cajas) * 2 + SUM(canastilla_base) * 1.8)
    END AS kilosNetos
FROM desprese_items WHERE guia = '$guia'";
$rs_operation = mysqli_query($link, $sql);
$rowKilosNetos = mysqli_fetch_assoc($rs_operation);
$totalKilosNetos = $rowKilosNetos["kilosNetos"];

$sqlEntero = "SELECT SUM(kilos) - (SUM(cajas) * 2 + SUM(cajas_base) * 1.8) AS kilosNetos FROM pesos_pollo WHERE guia = '$guia' AND tipo_pollo = 'ENTERO'	";
$rs_operationEntero = mysqli_query($link, $sqlEntero);
$rowEntero = mysqli_fetch_assoc($rs_operationEntero);
$totalKilosNetos += $rowEntero["kilosNetos"];


$sqlDatos = "SELECT * FROM datos_cf WHERE guia = '$guia'";
$rs_operationDatos = mysqli_query($link, $sqlDatos);
$datosCF = mysqli_fetch_assoc($rs_operationDatos);

$sqlDL = "SELECT * FROM datosliquidacion";
$rs_operationDL = mysqli_query($link, $sqlDL);
$rowDL = mysqli_fetch_assoc($rs_operationDL);

if ($rowDL == null) {
    $rowDL["costoPromedioSalmuera"] = 0;
    $rowDL["porcentajeSalmuera"] = 0;
}

if ($datosCF == null) {
    $datosCF["costo_factura"] = 0;
    $datosCF["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"];
    $datosCF["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"];
} else {
    $datosCF["costo_factura"] == 0 ? $datosCF["costo_factura"] = 0 : $datosCF["costo_factura"];
    $datosCF["costo_promedio_salmuera"] == 0 ? $datosCF["costo_promedio_salmuera"] = $rowDL["costoPromedioSalmuera"] : $datosCF["costo_promedio_salmuera"];
    $datosCF["porcentaje_salmuera"] == 0 ? $datosCF["porcentaje_salmuera"] = $rowDL["porcentajeSalmuera"] : $datosCF["porcentaje_salmuera"];
}

$data = array();
$subData = array();

$subData[] = $item;
$subData[] = $itemName;
$subData[] = number_format($totalKilosNetos, 2, ".", ",");
$subData[] = number_format($datosCF["costo_factura"] / $totalKilosNetos, 2, ".", ",");
$subData[] = "<a onclick='InsertDatosCF(\"".$guia."\", \"costoFactura\")' style='cursor: pointer;'>". number_format($datosCF["costo_factura"], 2, ".", ",") ."<i class='bi bi-pencil ms-1'></i></a>";
$subData[] = "<a onclick='InsertDatosCF(\"".$guia."\", \"costoPromedio\")' style='cursor: pointer;'>". number_format($datosCF["costo_promedio_salmuera"], 2, ".", ",") ."<i class='bi bi-pencil ms-1'></i></a>";
$subData[] = "<a onclick='InsertDatosCF(\"".$guia."\", \"porcentSalmuera\")' style='cursor: pointer;'>". $datosCF["porcentaje_salmuera"] . "%<i class='bi bi-pencil ms-1'></i></a>";

$data[] = $subData;

$json_data = [
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => $data
];

echo json_encode($json_data);