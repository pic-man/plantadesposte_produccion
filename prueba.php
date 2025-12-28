<?php
session_start();
include('config.php');
/* error_reporting(0); */

$sedesA = ["LA 5A", "LA 39", "PLAZA NORTE", "CIUDAD JARDIN", "CENTRO SUR", "PALMIRA", "FLORESTA", "FLORALIA", "GUADUALES", "BOGOTA LA 80", "BOGOTA CHIA"];
$NroItems = ["073617", "073618", "073619", "073620", "073624", "073625", "073626", "073627", "073628", "073631", "006411", "037508", "073633", "073621", "073622", "073623", "073629", "073630"];

for ($i=0; $i < count($sedesA); $i++) { 
    $sede = $sedesA[$i];
    for ($j=0; $j < count($NroItems); $j++) { 
        $item = $NroItems[$j];
        $accion = mt_rand(0, 1);
        $cantidad = rand(20, 100);
        
        if ($accion == 0) {
            $sql = "INSERT INTO items_canal(sede, canal, item, nececidad) VALUES ('$sede', '1', '$item', '$cantidad')";
            mysqli_query($link, $sql);
        } else {
            $sql = "INSERT INTO items_canal(sede, canal, item, sobrante) VALUES ('$sede', '1', '$item', '$cantidad')";
            mysqli_query($link, $sql);
        }
    }
}