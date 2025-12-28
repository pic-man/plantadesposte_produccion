<?php
//session_start();
include('../config.php');

$semana = isset($_POST['semana']) ? $_POST['semana'] : '';

$sql = "SELECT 
    items_canal.sede,
    item, 
    nececidad, 
    sobrante,
    items_canal.id AS id_item
    FROM items_canal 
    INNER JOIN
        programacioncanales ON items_canal.canal = programacioncanales.id
    WHERE programacioncanales.semana = '$semana' AND programacioncanales.especie = 'CERDO'
    ORDER BY items_canal.sede ASC
";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$items = array(
    "BOGOTA CHIA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "BOGOTA LA 80" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "GUADUALES" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "FLORALIA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "FLORESTA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "PALMIRA" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "CENTRO SUR" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "CIUDAD JARDIN" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "PLAZA NORTE" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "LA 39" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ],
    "LA 5A" => [
        "073612" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073613" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073614" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073615" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073616" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009251" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073559" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005800" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "009270" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "005819" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "007844" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "040959" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "033700" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ]
    ]
);

while ($row = mysqli_fetch_assoc($query)) {
    if ($row["nececidad"] != "") {
        $items[$row["sede"]][$row["item"]]["Nececidad"] = $row["nececidad"];
        $items[$row["sede"]][$row["item"]]["id_item"] = $row["id_item"];
    }else {
        $items[$row["sede"]][$row["item"]]["Sobrante"] = $row["sobrante"];
    }
    
}

$sedesA = ["LA 5A", "LA 39", "PLAZA NORTE", "CIUDAD JARDIN", "CENTRO SUR", "PALMIRA", "FLORESTA", "FLORALIA", "GUADUALES", "BOGOTA LA 80", "BOGOTA CHIA"];

$NameItems = ["BRAZO CERDO", "COSTILLA CERDO", "LOMO CERDO", "PERNIL CERDO", "TOCINETA", "ESPINAZO", "HUESO BLANCO CERDO", "EMPELLLA CERDO", "OREJA CERDO", "PAPADA", "PEZUNA", "TOCINO", "CABEZA SIN OREJA"];

$NroItems = ["073612", "073613", "073614", "073615", "073616", "009251", "073559", "005800", "009270", "005819", "007844", "040959", "033700"];

$sql2 = "SELECT sede_origen, item, enviado, sede_destino FROM registro_cambios WHERE semana = '$semana' AND especie = 'CERDO'";
$result2 = mysqli_query($link, $sql2);

while ($row2 = mysqli_fetch_row($result2)) {
    $items[$row2[0]][$row2[1]]["Sobrante"] -= $row2[2];
    $items[$row2[3]][$row2[1]]["Nececidad"] -= $row2[2];
}

$data = array();

for ($i=0; $i < 13; $i++) { 
    $subdata = array();

    $subdata[] = $NameItems[$i];

    if ($items[$sedesA[0]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[0]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[0]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[1]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[1]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[1]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[2]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[2]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[2]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[3]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[3]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[3]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[4]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[4]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[4]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[5]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[5]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[5]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[6]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[6]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[6]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[7]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[7]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[7]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[8]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[8]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[8]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[9]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[9]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[9]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[10]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[10]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[10]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px; color: red;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."CERDO"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    }

    $subdata[] = "false";

    $data[] = $subdata;
}

$sql3 = "SELECT sede_origen, sede_destino FROM registro_cambios WHERE semana = '$semana' AND especie = 'CERDO' GROUP BY sede_origen, sede_destino";
$rs_operation3 = mysqli_query($link, $sql3);

if (mysqli_num_rows($rs_operation3) > 0) {
    $subData = array();
    $subData[] = "<center style='margin-top:11px' >Excels</center>";
    $rows = [];
    while ($row3 = mysqli_fetch_assoc($rs_operation3)) {
        $rows[] = $row3;
    }
    for ($i = 0; $i < 11; $i++) {
        $encontrado = false;
        foreach ($rows as $row3) {
            if ($row3['sede_origen'] == $sedesA[$i] || $row3['sede_destino'] == $sedesA[$i]) {
                $encontrado = true;
                break;
            }
        }
        $subData[] = $encontrado ? '<a href="./ExcelRegistrosCERDO.php?semana='.$semana.'&sede='.$sedesA[$i].'" target="_blank"><i class="bi fs-2"><img src="./assets/img/descarga.png" alt="excel" width="35" height="35"></i></a>' : "";
    }
    $subData[] = "true";
    $data[] = $subData;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
