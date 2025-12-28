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

$data = [
    [
        "043874",
        "COSTILLAR",
        "0",
        "",
        "0",
        "0.00%",
        "",
        "",
        "0",
        "0",
        "",
        ""
    ],
    [
        "043874",
        "COSTILLAR ALA MP",
        "0",
        "",
        "0",
        "0.00%",
        "",
        "",
        "0",
        "0",
        "",
        ""
    ],
    [
        "045588",
        "PUNTA DE ALA",
        "0",
        "",
        "0",
        "0.00%",
        "",
        "",
        "0",
        "0",
        "",
        ""
    ],
    [
        "",
        "SubTotal Pollo Sin Proceso",
        "0",
        "",
        "0",
        "0.00%",
        "",
        "",
        "0",
        "0",
        "",
        ""
    ],
];

$json_data = [
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
];

echo json_encode($json_data);