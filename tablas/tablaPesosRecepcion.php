<?php
session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
function fsalida($cad2){
   $uno = substr($cad2, 11, 5);
   return $uno;
}
$request = $_REQUEST;
$recepcion = $_POST['recepcion'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaItems = listaPesosPorRecepcionInv($recepcion);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

/* $listaProveedores = listaRecepcionesCompleta();
$totalData = mysqli_num_rows($listaProveedores);*/

if (!empty($busqueda)) {
    $listaItems = listaPesosPorRecepcionPaginadaInv($recepcion, $inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaItems);
} else {
    $totalFiltro = $totalData;
}

$listaItems = listaPesosPorRecepcionPaginadaInv($recepcion, $inicio, $fin, $busqueda); 

$data = array();
$cont=0;

while ($row = mysqli_fetch_array($listaItems)) {
    $estadoBtn ="";
    include('../config.php');
    $sql = ("select canales from recepcion where id_recepcion =".$row[1]);
    $rs_operacion = mysqli_query($link, $sql);
    $c = mysqli_fetch_array($rs_operacion);
    
    $cont++;
    $btns = '';
    $total = $row[3] + $row[4] + $row[5] + $row[6];
    if($row[3]=='0' or $row[4]=='0' or $row[5]=='0' or $row[6]=='0'){
        $color = "#ff0000";
    }else{
        $color = "#000000";
    }
    $subdata = array();
    $subdata[] = "<font color='".$color."'>".$cont."</font>";
    $subdata[] = "<font color='".$color."'>".$row[2]."</font>";
    if($row[13]== '0'){
        $subdata[] = "<font color='".$color."'>-</font>";
        $subdata[] = "<font color='".$color."'>-</font>";
        
        /* $subdata[] = "<input type='number' class='form-control' name='pesoInv' min='1' max='1000' autocomplete='off' id='pesoInv' placeholder='Ingrese Peso' onblur='calcularDiferencia({$row[0]}, this.value, {$total})'>"; */
        
        $subdata[] = "<input type='number' class='form-control' name='pesoInv' min='1' max='1000' autocomplete='off' id='pesoInv_{$cont}' placeholder='Ingrese Peso' onkeydown='if(event.keyCode == 13) { calcularDiferencia({$row[0]}, this.value, {$total}); focusNextInput({$cont}); return false; }'>";
        
        $subdata[] = "<font color='".$color."'>-</font>";
        $subdata[] = "<font color='".$color."'>-</font>";
    }else{
        $pesodif = $row[14]*1000;
        $subdata[] = "<font color='".$color."'>".$row[3]."     -     ".$row[4]."     -     ".$row[5]."     -     ".$row[6]."</font>";
        $subdata[] = "<font color='".$color."'>".$total."</font>";
        $subdata[] = "<font color='".$color."'>".$row[13]."</font>";
        $subdata[] = "<font color='".$color."'>".number_format((($pesodif) / 1000), 2, '.', '')." Kg.</font>";
        $subdata[] = "<font color='".$color."'>".$row[15]."%</font>";
        $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    }
    
    //$subdata[] = "<font color='".$color."'>".fsalida($row[9])."</font>";
    
    if($row[13]!= '0'){
        
    }
    
    $subdata[] = $estadoBtn;
    $data[] = $subdata;
}

$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);

echo json_encode($json_data);
?>
