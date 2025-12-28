<?php
include('config.php');
$sql = ("select * from recepcion_pesos where id_recepcion in (select id_recepcion from recepcion where tipo='RES')");
$rs_operacion = mysqli_query($link, $sql);
while($c = mysqli_fetch_array($rs_operacion)){
    echo $c['turno']." - e1:".$c['estomago1']." - e2:".$c['estomago2']." - p1:".$c['piernas1']." - p2:".$c['piernas2']."\n"; 
    if($c['estomago1']!=0 and $c['estomago2']!=0 and $c['piernas1']!=0 and $c['piernas2']!=0){
        $sql = ("update recepcion_pesos set status='LISTO' where id_recepcion_pesos =  ".$c['id_recepcion_pesos']."");
        mysqli_query($link, $sql);
    }else{
        $sql = ("update recepcion_pesos set status='INCOMPLETO' where id_recepcion_pesos =  ".$c['id_recepcion_pesos']."");
        mysqli_query($link, $sql);
    }
}