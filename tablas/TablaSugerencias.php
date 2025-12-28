<?php
//session_start();
include('../config.php');

$datos = isset($_POST['datos']) ? $_POST['datos'] : '';

/* $sql = "SELECT 
    items_canal.sede,
    plantilla.descripcion,
    sobrante - SUM(registro_cambios.enviado) AS sobrante
FROM items_canal
INNER JOIN 
    programacioncanales ON items_canal.canal = programacioncanales.id
INNER JOIN
    plantilla ON items_canal.item = plantilla.item
LEFT JOIN
    registro_cambios ON items_canal.item = registro_cambios.item AND items_canal.sede = registro_cambios.sede_origen
WHERE 
    items_canal.item = '$datos[item]' AND 
    sobrante > 0 AND 
    programacioncanales.semana = '$datos[semana]'
"; */

$sql = "SELECT 
    items_canal.sede,
    plantilla.descripcion,
    items_canal.sobrante - COALESCE(SUM(registro_cambios.enviado), 0) AS sobrante_calculado
FROM items_canal
INNER JOIN 
    programacioncanales ON items_canal.canal = programacioncanales.id
INNER JOIN 
    plantilla ON items_canal.item = plantilla.item
LEFT JOIN 
    registro_cambios ON items_canal.item = registro_cambios.item AND items_canal.sede = registro_cambios.sede_origen AND registro_cambios.semana = '$datos[semana]'
WHERE 
    items_canal.item = '$datos[item]' AND 
    programacioncanales.semana = '$datos[semana]'
GROUP BY 
    items_canal.sede, 
    plantilla.descripcion, 
    items_canal.sobrante
HAVING 
    sobrante_calculado > 0
";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $sql2 = "SELECT SUM(enviado) FROM registro_cambios WHERE sede_origen = '$row[0]' AND item = '$datos[item]' AND semana = '$datos[semana]'";
    $result2 = mysqli_query($link, $sql2);
    $enviado = mysqli_fetch_row($result2);
    
    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . ($row[2]/*  - ($enviado[0] ?? 0) */) . "</center>";
    $subdata[] = "<center><input type='number' class='form-control' name='".$row[0]."' disponible='".($row[2]/*  - ($enviado[0] ?? 0) */)."' value='" . ($row[2]/*  - ($enviado[0] ?? 0) */) . "'></center>";
    
    $acciones = '<a onclick="Enviar_a_Cambio(\''.$row[0].'\', \''.$datos["item"].'\', \''.$datos["sede"].'\', \''.$datos["semana"].'\', \''.$datos["necesidad"].'\', \''.($row[2]/*  - ($enviado[0] ?? 0) */).'\', \''.$datos["especie"].'\')"><i class="bi bi-send fs-2 text-info"></i></a>';

    $subdata[] = $acciones;

    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
