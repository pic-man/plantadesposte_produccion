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
    WHERE programacioncanales.semana = '$semana' AND programacioncanales.especie = 'RES'
";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$items = array(
    "BOGOTA CHIA" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "BOGOTA LA 80" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "GUADUALES" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "FLORALIA" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "FLORESTA" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "PALMIRA" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "CENTRO SUR" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "CIUDAD JARDIN" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "PLAZA NORTE" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "LA 39" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
    ],
    "LA 5A" => [
        "073617" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073618" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073619" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073620" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073624" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073625" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073626" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073627" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073628" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073631" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "006411" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "037508" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073633" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073621" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073622" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073623" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073629" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
        "073630" => [
            "Nececidad" => "",
            "Sobrante" => "",
            "id_item" => ""
        ],
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

$NameItems = ["AMPOLLETA NORMAL", "BOLA NEGRA NORMAL", "CADERITA NORMAL", "COSTILLA NORMAL", "MORRILLO*KILO", "MUCHACHO", "PECHO*KILO", "PEPINO*KILO", "PULPA NORMAL", "SOBACO*KILO", "HUESO COGOTE", "HUESO PROMOCION", "LOMO VICHE", "ESPALDILLA", "LOMO CARACHA", "LOMO REDONDO", "PUNTA ANCA", "PUNTA FALDA"];

$NroItems = ["073617", "073618", "073619", "073620", "073624", "073625", "073626", "073627", "073628", "073631", "006411", "037508", "073633", "073621", "073622", "073623", "073629", "073630"];

$sql2 = "SELECT sede_origen, item, enviado, sede_destino FROM registro_cambios WHERE semana = '$semana' AND especie = 'RES'";
$result2 = mysqli_query($link, $sql2);

while ($row2 = mysqli_fetch_row($result2)) {
    $items[$row2[0]][$row2[1]]["Sobrante"] -= $row2[2];
    $items[$row2[3]][$row2[1]]["Nececidad"] -= $row2[2];
}

$data = array();

for ($i=0; $i < 18; $i++) { 
    $subdata = array();

    $subdata[] = $NameItems[$i];
    if ($items[$sedesA[0]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[0]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[0]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    }else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[0]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[0]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[0]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[0]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[0]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[1]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[1]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[1]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[1]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[1]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[1]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[1]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[1]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[2]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[2]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[2]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[2]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[2]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[2]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[2]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[2]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[3]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[3]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[3]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[3]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[3]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[3]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[3]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[3]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[4]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[4]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[4]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[4]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[4]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[4]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[4]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[4]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[5]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[5]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[5]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[5]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[5]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[5]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[5]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[5]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[6]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[6]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[6]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[6]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[6]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[6]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[6]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[6]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[7]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[7]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[7]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[7]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[7]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[7]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[7]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[7]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[8]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[8]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[8]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[8]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[8]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[8]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[8]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[8]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[9]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[9]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[9]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[9]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[9]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[9]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[9]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[9]][$NroItems[$i]]["Sobrante"] . "</a>";
    }
    if ($items[$sedesA[10]][$NroItems[$i]]["Nececidad"] == 0 && $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[10]][$NroItems[$i]]["Nececidad"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } elseif ($items[$sedesA[10]][$NroItems[$i]]["Sobrante"] == 0) {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    } else {
        $subdata[] = "<a style='margin-right: 66px;color: red' onclick='ModalIntercambio(\"".$items[$sedesA[10]][$NroItems[$i]]["id_item"]."\",\"".$sedesA[10]."\",\"".$NroItems[$i]."\",\"".$NameItems[$i]."\",\"".$items[$sedesA[10]][$NroItems[$i]]["Nececidad"]."\",\"".$semana."\",\""."RES"."\")'>" . $items[$sedesA[10]][$NroItems[$i]]["Nececidad"] . "</a>" . "<a style='margin-right: 5px; color: green;'>" . $items[$sedesA[10]][$NroItems[$i]]["Sobrante"] . "</a>";
    }

    $subdata[] = "false";

    $data[] = $subdata;
}

$sql3 = "SELECT sede_origen, sede_destino FROM registro_cambios WHERE semana = '$semana' AND especie = 'RES' GROUP BY sede_origen, sede_destino";
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
        $subData[] = $encontrado ? '<a href="./ExcelRegistrosRES.php?semana='.$semana.'&sede='.$sedesA[$i].'" target="_blank"><i class="bi fs-2"><img src="./assets/img/descarga.png" alt="excel" width="35" height="35"></i></a>' : "";
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
