<?php
include('../config.php');

$semana = $_POST["semana"] ? $_POST["semana"] : 1;

$sql = "SELECT sede, cantidad, especie FROM programacioncanales WHERE semana = '$semana'";

$query = mysqli_query($link, $sql);

$cantidad = array(
    "BOGOTA CHIA" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "BOGOTA LA 80" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "GUADUALES" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "FLORALIA" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "FLORESTA" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "PALMIRA" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "CENTRO SUR" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "CIUDAD JARDIN" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "PLAZA NORTE" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "LA 39" => [
        "RES" => "",
        "CERDO" => ""
    ],
    "LA 5A" => [
        "RES" => "",
        "CERDO" => ""
    ]
);

while ($row = mysqli_fetch_assoc($query)) {
    $cantidad[$row["sede"]][$row["especie"]] = $row["cantidad"];   
}

$sedes = ["La 5a", "La 39", "Plaza Norte", "Ciudad Jardin", "Centro Sur", "Palmira", "Floresta", "Floralia", "Guaduales", "Bogota La 80", "Bogota Chia"];

$sedesA = ["LA 5A", "LA 39", "PLAZA NORTE", "CIUDAD JARDIN", "CENTRO SUR", "PALMIRA", "FLORESTA", "FLORALIA", "GUADUALES", "BOGOTA LA 80", "BOGOTA CHIA"];

$totalData = count($cantidad);
$totalFilter = $totalData;

$data = array();

for ($i=0; $i < count($cantidad); $i++) { 

    $subdata = array();

    $subdata[] = "<center>" . $sedes[$i] . "</center>";
    $subdata[] = "<center>" . $cantidad[$sedesA[$i]]["RES"] . "</center>";
    $subdata[] = "<center>" . $cantidad[$sedesA[$i]]["CERDO"] . "</center>";
    $subdata[] = "<center>" . "<input class='form-control' type='number' name='".$sedesA[$i]."' tipo='RES' placeholder='Ingrese la res'>" . "</center>";
    $subdata[] = "<center>" . "<input class='form-control' type='number' name='".$sedesA[$i]."' tipo='CERDO' placeholder='Ingrese el cerdo'>" . "</center>";

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>