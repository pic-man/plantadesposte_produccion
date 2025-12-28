<?php
include("../config.php");

$sqlSemana = "SELECT semana FROM semana WHERE status = 'ACTIVO'";
$querySemana = mysqli_query($link, $sqlSemana);
$rowSemana = mysqli_fetch_assoc($querySemana);
$semana = $_POST["semana"] ?? $rowSemana["semana"];

$sqlSedes = "SELECT cedula FROM responsables WHERE tipo = '3'";
$rs_operation = mysqli_query($link, $sqlSedes);

$TotalData = mysqli_num_rows($rs_operation);
$TotalFiltered = $TotalData;

$dataCanalesSemana = array();

$totalCerdo = 0;
$totalRes = 0;

while ($rowSedes = mysqli_fetch_assoc($rs_operation)) {
    $sede = strtoupper($rowSedes["cedula"]);
    $dataCanalesSemana[$sede]["Nombre"] = $rowSedes["cedula"];

    $sqlCanales = "SELECT 
        SUM(CASE WHEN especie = 'CERDO' THEN cantidad ELSE 0 END) AS totalCerdo,
        SUM(CASE WHEN especie = 'RES' THEN cantidad ELSE 0 END) AS totalRes
    FROM programacioncanales WHERE sede = '$sede' AND semana = '$semana'";
    $queryCanales = mysqli_query($link, $sqlCanales);
    $rowCanales = mysqli_fetch_assoc($queryCanales);

    $dataCanalesSemana[$sede]["TotalCerdo"] = $rowCanales["totalCerdo"] ?? 0;
    $dataCanalesSemana[$sede]["TotalRes"] = $rowCanales["totalRes"] ?? 0;

    $totalCerdo += $rowCanales["totalCerdo"];
    $totalRes += $rowCanales["totalRes"];
}

$data = array();
$diasSemana = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
$totalDias = ["Lunes" => 0, "Martes" => 0, "Miercoles" => 0, "Jueves" => 0, "Viernes" => 0, "Sabado" => 0];

foreach ($dataCanalesSemana as $canales) {
    $sede = strtoupper($canales["Nombre"]);

    $subdata = array();

    $subdata[] = $canales["TotalCerdo"];
    $subdata[] = round($canales["TotalCerdo"] / ($totalCerdo == 0 ? 1 : $totalCerdo) * 100, 2) . "%";
    $subdata[] = $canales["TotalRes"];
    $subdata[] = round($canales["TotalRes"] / ($totalRes == 0 ? 1 : $totalRes) * 100, 2) . "%";
    $subdata[] = $canales["Nombre"];

    $totalCerdoDia = 0;
    $totalResDia = 0;

    for ($i=0; $i < count($diasSemana); $i++) { 
        $diaSemana = $diasSemana[$i];
        $especie = $i % 2 == 0 ? "CERDO" : "RES";
        $maximo = $especie == "CERDO" ? $canales["TotalCerdo"] : $canales["TotalRes"];

        $sqlProduccionDia = "SELECT 
            id,
            cantidad 
        FROM produccion_dia_canales 
        WHERE 
            sede = '$sede' AND 
            semana = '$semana' AND 
            dia = '$diaSemana' AND 
            especie = '$especie'
        ";

        $rs_operation = mysqli_query($link, $sqlProduccionDia);

        $sqlProduccionDiaTotal = "SELECT 
            SUM(cantidad) AS total
        FROM produccion_dia_canales 
        WHERE sede = '$sede' AND semana = '$semana' AND especie = '$especie'GROUP BY especie";

        $rs_operationTotal = mysqli_query($link, $sqlProduccionDiaTotal);
        $rowProduccionDiaTotal = mysqli_fetch_assoc($rs_operationTotal);
        $totalProduccionDia = $rowProduccionDiaTotal["total"] ?? 0;
        $maximo -= $totalProduccionDia;

        if (mysqli_num_rows($rs_operation) > 0) {
            $rowProduccionDia = mysqli_fetch_assoc($rs_operation);
            $cantidad = $rowProduccionDia["cantidad"];
            $especie == "CERDO" ? $totalCerdoDia += $cantidad : $totalResDia += $cantidad;
            $maximo += $cantidad;
            $subdata[] = "<span style='cursor: pointer;' onclick='editarProduccionDia(\"". $rowProduccionDia["id"] ."\", \"". $cantidad ."\", \"". $maximo ."\")'>" . $cantidad . "</span>";
        } else {
            $cantidad = 0;
            $subdata[] = "<span style='cursor: pointer;' onclick='AgregarProduccionDia(\"". $sede ."\", \"". $semana ."\", \"". $diaSemana ."\", \"". $especie ."\", \"". $maximo ."\")'>" . $cantidad . "</span>";
        }

        $totalDias[$diaSemana] += $cantidad;
    }

    $subdata[] = "";
    $subdata[] = round($totalCerdoDia / ($canales["TotalCerdo"] == 0 ? 1 : $canales["TotalCerdo"]) * 100, 2) . "%";
    $subdata[] = round($totalResDia / ($canales["TotalRes"] == 0 ? 1 : $canales["TotalRes"]) * 100, 2) . "%";

    $data[] = $subdata;
}

$subdata = array();
$subdata[] = "<span class='text-danger'>" . $totalCerdo . "</span>";
$subdata[] = round($totalCerdo / ($totalCerdo == 0 ? 1 : $totalCerdo) * 100, 2) . "%";
$subdata[] = "<span class='text-danger'>" . $totalRes . "</span>";
$subdata[] = round($totalRes / ($totalRes == 0 ? 1 : $totalRes) * 100, 2) . "%";
$subdata[] = "Ingreso * Dia";
$subdata[] = "<span class='text-danger'>" . $totalDias["Lunes"] . "</span>";
$subdata[] = "<span class='text-danger'>" . $totalDias["Martes"] . "</span>";
$subdata[] = "<span class='text-danger'>" . $totalDias["Miercoles"] . "</span>";
$subdata[] = "<span class='text-danger'>" . $totalDias["Jueves"] . "</span>";
$subdata[] = "<span class='text-danger'>" . $totalDias["Viernes"] . "</span>";
$subdata[] = "<span class='text-danger'>" . $totalDias["Sabado"] . "</span>";
$subdata[] = "";
$subdata[] = "";
$subdata[] = "";
$data[] = $subdata;

$json_data = array(
    "recordsTotal" => $TotalData,
    "recordsFiltered" => $TotalFiltered,
    "data" => $data,
    "semana" => $semana,
    "semanaPlanta" => $semana + 30
);

echo json_encode($json_data);