<?php
date_default_timezone_set("America/bogota");
//error_reporting (0);
include("config.php");

$semana = isset($_GET["semana"]) ? $_GET["semana"] : "";

$sql = "SELECT sede, cantidad, especie FROM programacioncanales WHERE semana = '$semana'";
$rs = mysqli_query($link, $sql);

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

while ($row = mysqli_fetch_assoc($rs)) {
    $cantidad[$row["sede"]][$row["especie"]] = $row["cantidad"];   
}

$sql2 = "SELECT 
    items_canal.sede,
    item, 
    nececidad, 
    sobrante 
    FROM items_canal 
    INNER JOIN
        programacioncanales ON items_canal.canal = programacioncanales.id
    WHERE programacioncanales.semana = '$semana' AND programacioncanales.especie = 'CERDO'
";
$rs2 = mysqli_query($link, $sql2);

$items = array(
    "BOGOTA CHIA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "BOGOTA LA 80" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "GUADUALES" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "FLORALIA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "FLORESTA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "PALMIRA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "CENTRO SUR" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "CIUDAD JARDIN" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "PLAZA NORTE" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "LA 39" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ],
    "LA 5A" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => ""
        ]
    ]
);

while ($row = mysqli_fetch_assoc($rs2)) {
    if ($row["nececidad"] != "") {
        $items[$row["sede"]][$row["item"]]["Nececidad"] = $row["nececidad"];
    }else {
        $items[$row["sede"]][$row["item"]]["Sobrante"] = $row["sobrante"];
    }
    
}

$sedes = ["La 5a", "La 39", "Plaza Norte", "Ciudad Jardin", "Centro Sur", "Palmira", "Floresta", "Floralia", "Guaduales", "Bogota La 80", "Bogota Chia"];

$sedesA = ["LA 5A", "LA 39", "PLAZA NORTE", "CIUDAD JARDIN", "CENTRO SUR", "PALMIRA", "FLORESTA", "FLORALIA", "GUADUALES", "BOGOTA LA 80", "BOGOTA CHIA"];

$NameItems = ["BRAZO CERDO", "COSTILLA CERDO", "LOMO CERDO", "PERNIL CERDO", "TOCINETA", "ESPINAZO", "HUESO BLANCO CERDO", "EMPELLLA CERDO", "OREJA CERDO", "PAPADA", "PEZUNA", "TOCINO", "CABEZA SIN OREJA"];

$NroItems = ["073612", "073613", "073614", "073615", "073616", "009251", "073559", "005800", "009270", "005819", "007844", "040959", "033700"];

$sql3 = "SELECT sede, res, cerdo FROM programacion_final WHERE semana = '$semana'";
$rs_operation3 = mysqli_query($link, $sql3);

$final = array(
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

while ($row3 = mysqli_fetch_row($rs_operation3)) {
    $final[$row3[0]]["RES"] = $row3[1];
    $final[$row3[0]]["CERDO"] = $row3[2];
}

?>
<html lang="es">
<head>
</head>
<body>
    <?php 
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=prueba.xls");
        header("Content-Transfer-Encoding: binary ");
    ?>
    <table>
        <tr>
            <th style="width:100px">
                <font color="#000000" size="3">Sede</font>
            </th>
            <th style="width:80px">
                <font color="#000000" size="3"><b>#Canales Res</b></font>
            </th>
            <th style="width:80px">
                <font color="#000000" size="3"><b>#Canales Cerdo</b></font>
            </th>
            <th style="width:115px">
                <font color="#000000" size="3"><b>Programacion Final Res</b></font>
            </th>
            <th style="width:115px">
                <font color="#000000" size="3"><b>Programacion Final Cerdo</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:150px">
                <font color="#000000" size="3"><b>Items</b></font>
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>LA 5A</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width: 100px;">
                <font color="#000000" size="3"><b>LA 39</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>PLAZA NORTE</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>CIUDAD JARDIN</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>CENTRO SUR</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>PALMIRA</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>FLORESTA</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>FLORALIA</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>GUADUALES</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>BOGOTA LA 80</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:100px">
                <font color="#000000" size="3"><b>BOGOTA CHIA</b></font>
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
        </tr>
        <tr>
            <th style="width:100px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <th style="width:80px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <th style="width:80px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <th style="width:115px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <th style="width:115px">
                &nbsp;
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <th style="width:150px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3">NECESIDAD</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">SOBRANTE</font>
            </td>
        </tr>
        <?php for ($i=0; $i < 11; $i++): ?>
        <tr>
            <td style="width:100px">
                <font color="#000000" size="3"><?= $sedes[$i] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $cantidad[$sedesA[$i]]["RES"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $cantidad[$sedesA[$i]]["CERDO"] ?></font>
            </td>
            <td style="text-align: center;width:115px">
                <font color="#000000" size="3"><?= $final[$sedesA[$i]]["RES"] ?></font>
            </td>
            <td style="text-align: center;width:115px">
                <font color="#000000" size="3"><?= $final[$sedesA[$i]]["CERDO"] ?></font>
            </td>
            <th style="width:80px">
                &nbsp;
            </th>
            <td style="text-align:center; border: 1px; width:150px" bgcolor="#FFFF00">
                <font color="#000000" size="3"><?= $NameItems[$i] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
        </tr>
        <?php endfor; ?>
        <?php for ($i=11; $i < 13; $i++): ?>
        <tr>
            <td style="width:100px">
                <font color="#000000" size="3">&nbsp;</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">&nbsp;</font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3">&nbsp;</font>
            </td>
            <th style="width:115px">
                <font color="#000000" size="3">&nbsp;</font>
            </th>
            <th style="width:115px">
                &nbsp;
            </th>
            <th style="width:80px">
                &nbsp;
            </th>
            <td style="text-align:center; border: 1px; width:150px" bgcolor="#FFFF00">
                <font color="#000000" size="3"><?= $NameItems[$i] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:100px">
                <font color="#000000" size="3"><?= $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] ?></font>
            </td>
            <td style="text-align: center;width:80px">
                <font color="#000000" size="3"><?= $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] ?></font>
            </td>
        </tr>
        <?php endfor; ?>
    </table>
