<?php
include("../config.php");

$guia = isset($_POST["guia"]) ? $_POST["guia"] : "";

$sql = "SELECT 
    lgd.id,
    lgd.lote,
    lgd.fecha_beneficio,
    lgd.fecha_vencimiento,
    lgd.kilos_alas,
    lgd.kilos_pechuga,
    lgd.kilos_pernil,
    lgd.tipo,
    lgd.unidades,
    pp.sede AS proveedor,
    lgd.kilos_pollo
FROM lotes_guia_despresado lgd
INNER JOIN 
    proveedorpollo pp ON pp.id = lgd.proveedor
WHERE lgd.guia = '$guia'
GROUP BY lgd.lote
";
$query = mysqli_query($link, $sql);
$TotalData = mysqli_num_rows($query);
$TotalFiltered = $TotalData;

$data = array();

while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT guia FROM lotes_guia_despresado WHERE lote = '$row[lote]' ORDER BY guia DESC LIMIT 1";
    $rs_operation2 = mysqli_query($link, $sql2);
    $ultimaGuia = mysqli_fetch_assoc($rs_operation2)["guia"];

    $sql3 = "SELECT item, kilos, cajas, canastilla_base FROM despresado_items WHERE guia = '$guia' AND lote = '$row[lote]'";
    $rs_operation3 = mysqli_query($link, $sql3);

    /* $kilos = floatval($row["kilos"]);
    $cajas = floatval($row["cajas"]);
    $cajas_base = floatval($row["cajas_base"]);
    $pesosPollo = $kilos - (($cajas * 2) + ($cajas_base * 1.8)); */

    if ($row["tipo"] == "BLANCO_DESPRESE" || $row["tipo"] == "CAMPO_DESPRESE") {
        if ($row["tipo"] == "BLANCO_DESPRESE") {
            $tipo = "BLANCO";
        } elseif ($row["tipo"] == "CAMPO_DESPRESE") {
            $tipo = "CAMPO";
        }
    
        $CampoName = [
            0 => "ALAS CAMPO MERCAMIO MARINADAS",
            1 => "PECHUGA CAMPO MERCAMIO MARINADA",
            2 => "PERNIL CAMPO MERCAMIO MARINADO"
        ];
    
        $BlancoName = [
            0 => "ALAS BLANCA MERCAMIO MARINADAS",
            1 => "PECHUGA BLANCA MERCAMIO MARINADA",
            2 => "PERNIL BLANCO MERCAMIO MARINADO"
        ];
    
        $kilosDisponibles = [
            0 => $row["kilos_alas"],
            1 => $row["kilos_pechuga"],
            2 => $row["kilos_pernil"]
        ];
    
        while ($item = mysqli_fetch_assoc($rs_operation3)) {
            $pesoPollo = $item["kilos"] - (($item["cajas"] * 2) + ($item["canastilla_base"] * 1.8));
            if ($item["item"] == "059758" || $item["item"] == "059762") {
                $kilosDisponibles[0] -= $pesoPollo;
            } elseif ($item["item"] == "059756" || $item["item"] == "059760") {
                $kilosDisponibles[1] -= $pesoPollo;
            } elseif ($item["item"] == "059757" || $item["item"] == "059761") {
                $kilosDisponibles[2] -= $pesoPollo;
            }
        }
    
        for ($i=0; $i < 3; $i++) {
            $subdata = array();
    
            $subdata[] = $row["tipo"];
            if ($tipo == "CAMPO") {
                $subdata[] = $CampoName[$i];
            } else {
                $subdata[] = $BlancoName[$i];
            }
            $subdata[] = $row["lote"];
            $subdata[] = $row["proveedor"];
            $subdata[] = $row["fecha_beneficio"];
            $subdata[] = $row["fecha_vencimiento"];
            $subdata[] = $row["unidades"];
            $subdata[] = number_format($kilosDisponibles[$i], 2, ".", ",");
    
            if ($guia >= $ultimaGuia) {
                $acciones = '<i class="bi bi-trash-fill fs-3 text-danger" style="cursor: pointer;" onclick="EliminarRegistro(\''. $row["id"] .'\', \''. $row["lote"] .'\', \''. $guia .'\')"></i>';
            } else {
                $acciones = '<i class="bi bi-trash-fill fs-4 text-danger" style="visibility: hidden;"></i>';
            }
    
            $subdata[] = $acciones;
            $data[] = $subdata;
        }
    } else {
        $subdata = array();

        $PollosName = [
            "CAMPO" => "POLLO CAMPO MERCAMIO MARINADO",
            "ASADERO" => "POLLO BLANCO - ASADERO",
            "BLANCO" => "POLLO BLANCO MERCAMIO MARINADO"
        ];

        while ($item = mysqli_fetch_assoc($rs_operation3)) {
            $pesoPollo = $item["kilos"] - (($item["cajas"] * 2) + ($item["canastilla_base"] * 1.8));
            $row["kilos_pollo"] -= $pesoPollo;
        }

        $subdata[] = $row["tipo"];
        $subdata[] = $PollosName[$row["tipo"]];
        $subdata[] = $row["lote"];
        $subdata[] = $row["proveedor"];
        $subdata[] = $row["fecha_beneficio"];
        $subdata[] = $row["fecha_vencimiento"];
        $subdata[] = $row["unidades"];
        $subdata[] = number_format($row["kilos_pollo"], 2, ".", ",");

        if ($guia >= $ultimaGuia) {
            $acciones = '<i class="bi bi-trash-fill fs-3 text-danger" style="cursor: pointer;" onclick="EliminarRegistro(\''. $row["id"] .'\', \''. $row["lote"] .'\', \''. $guia .'\')"></i>';
        } else {
            $acciones = '<i class="bi bi-trash-fill fs-4 text-danger" style="visibility: hidden;"></i>';
        }

        $subdata[] = $acciones;
        $data[] = $subdata;
    }
}

$json_data = array(
    "recordsTotal" => intval($TotalData),
    "recordsFiltered" => intval($TotalFiltered),
    "data" => $data
);

echo json_encode($json_data);

