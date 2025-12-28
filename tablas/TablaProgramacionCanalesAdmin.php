<?php
include('../config.php');

$sql = "SELECT
    year,
    semana.semana,
    semana.fecha_inicio,
    semana.fecha_fin,
    COUNT(programacioncanales.id),
    semana.id
    FROM semana
    INNER JOIN
        programacioncanales ON semana.semana = programacioncanales.semana
    GROUP BY semana.id
    ORDER BY semana DESC";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $row[3] . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";

    
    $acciones = '<a data-bs-target="#ModalEspecies" data-bs-toggle="modal" onclick="AbrirModalEspecies(\''.$row[1].'\')"><i class="bi bi-plus-square text-warning fs-2 me-2"></i></a>';
    $acciones .= '<a data-bs-target="#ModalRegistroSemana" data-bs-toggle="modal" onclick="AbrirModalSemana(\''.$row[1].'\')"><i class="bi bi-search fs-2 me-2"></i></a>';
    $acciones .= '<a href="./ExcelRes.php?semana='.$row[1].'" target="_blank"><i class="bi fs-2 me-2"><img src="./assets/img/vaca.png" alt="cerdo" width="35" height="35" class="mb-1 img-fluid"></i></a>';
    $acciones .= '<a href="./ExcelCerdo.php?semana='.$row[1].'" target="_blank"><i class="bi fs-2 me-2"><img src="./assets/img/cerdo.png" alt="cerdo" width="35" height="35" class="img-fluid"></i></a>';

    $acciones .= '<a data-bs-target="#ModalIntercambioRES" data-bs-toggle="modal" onclick="AbrirModalCambioRes(\''.$row[1].'\')"><i class="bi fs-2 me-2"><img src="./assets/img/vacaCambio.png" alt="cerdo" width="35" height="35" class="mb-1 img-fluid"></i></a>';

    $acciones .= '<a data-bs-target="#ModalIntercambioCerdo" data-bs-toggle="modal" onclick="AbrirModalCambioCerdo(\''.$row[1].'\')"><i class="bi fs-2"><img src="./assets/img/cerdoCambio.png" alt="cerdo" width="35" height="35" class="img-fluid"></i></a>';
    
    $subdata[] = $acciones;

    $data[] = $subdata;
}

//<i class="bi bi-printer fs-2 text-primary"></i>

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>