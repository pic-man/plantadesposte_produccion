<?php 
//$link=mysqli_connect("localhost", "u336881418_lab_planta", "erb4^l?3K", "u336881418_lab_planta");
$link=mysqli_connect("localhost", "root", "anitalavalatina", "plantadesposte");
if (mysqli_connect_errno()) {
      printf("Fallo la conexion: %s\n", mysqli_connect_error());exit();
}