<?php
function formatear($cad){
    include("config.php");
    $cad=htmlentities($cad);
    $cad=mysqli_real_escape_string($link,$cad);
    $cad=strtolower($cad);
    $cad=str_replace("delete","",$cad);
    $cad=str_replace("/"," ",$cad);
    $cad=str_replace("*"," ",$cad);
    $cad=str_replace("#"," ",$cad);
    $cad=str_replace("-"," ",$cad);
    $cad=str_replace("_"," ",$cad);
    $cad=str_replace("("," ",$cad);
    $cad=str_replace(")"," ",$cad);
    $cad=str_replace("~"," ",$cad);
    $cad=str_replace("!"," ",$cad);
    $cad=str_replace("@"," ",$cad);
    $cad=str_replace("$"," ",$cad);
    $cad=str_replace("%"," ",$cad);
    $cad=str_replace("^"," ",$cad);
    $cad=str_replace("&"," ",$cad);
    $cad=str_replace("+"," ",$cad);
    $cad=str_replace("="," ",$cad);
    $cad=str_replace("'"," ",$cad);
    $cad=str_replace("union","",$cad);
    $cad=str_replace("where","",$cad);
    $cad=str_replace("from","",$cad);
    $cad=str_replace("á","a",$cad);
    $cad=str_replace("é","e",$cad);
    $cad=str_replace("í","i",$cad);
    $cad=str_replace("ó","o",$cad);
    $cad=str_replace("ú","u",$cad);
    $cad=str_replace("Á","A",$cad);
    $cad=str_replace("É","E",$cad);
    $cad=str_replace("Í","I",$cad);
    $cad=str_replace("Ó","O",$cad);
    $cad=str_replace("Ú","U",$cad);
    $cad=str_replace("ñ","n",$cad);
    $cad=str_replace("Ñ","N",$cad);
    $cad=strtoupper($cad);
    $cad=trim($cad);
return $cad;}
?>