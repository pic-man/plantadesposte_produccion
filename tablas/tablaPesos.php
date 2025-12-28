<?php
session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
include('../config.php');
function fsalida($cad2){
   $uno = substr($cad2, 11, 5);
   return $uno;
}
$request = $_REQUEST;
$recepcion = $_POST['recepcion'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$listaItems = listaPesosPorRecepcion($recepcion);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

if (!empty($busqueda)) {
    $listaItems = listaPesosPorRecepcionPaginada($recepcion, $inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaItems);
} else {
    $totalFiltro = $totalData;
}

$listaItems = listaPesosPorRecepcionPaginada($recepcion, $inicio, $fin, $busqueda); 

$data = array();
$cont =-1;

while ($row = mysqli_fetch_array($listaItems)) {
    $sql = "SELECT canales FROM recepcion WHERE id_recepcion = " . $row[1];
    $rs_operacion = mysqli_query($link, $sql);
    $c = mysqli_fetch_array($rs_operacion);
    
    $cont++;

    $total = $row[3] + $row[4] + $row[5] + $row[6];

    if ($row[3]=='0' or $row[4]=='0' or $row[5]=='0' or $row[6]=='0') {
        $color = "#ff0000";
    } else {
        $color = "#000000";
    }

    $subdata = array();

    $numeral = "<font color='". $color ."'>". intval($totalData-$cont) ."</font>";
    $turno = "<font color='". $color ."'>".$row[2]."</font>";
    $eepp = "<font color='". $color ."'>".$row[3] . " - " . $row[4] . " - " . $row[5] . " - " . $row[6]."</font>";
    $totalEepp = "<font color='". $color ."'>".$total."</font>";
    $temperatura = "<font color='". $color ."'>".$row[10]."</font>";
    $status = "<font color='". $color ."'>".$row[8]."</font>";
    
    $fechas = array();

    if (!empty($row[9]) && $row[9] != '0000-00-00 00:00:00') $fechas[] = $row[9];
    if (!empty($row[13]) && $row[13] != '0000-00-00 00:00:00') $fechas[] = $row[13];
    if (!empty($row[14]) && $row[14] != '0000-00-00 00:00:00') $fechas[] = $row[14];
    if (!empty($row[15]) && $row[15] != '0000-00-00 00:00:00') $fechas[] = $row[15];

    $hora_menor = '';
    
    if (!empty($fechas)) {
        sort($fechas);
        $fecha_menor = $fechas[0];
        $partes = explode(' ', $fecha_menor);
        $hora_menor = (count($partes) > 1) ? $partes[1] : $partes[0];
        if (strlen($hora_menor) > 5) {
            $hora_menor = substr($hora_menor, 0, 5);
        }
    }

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT * FROM recepcion_pesos_cambios WHERE id_recepcion_pesos = '$row[0]' ORDER BY idCambio DESC";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            /* $cont2 = intval($totalData - $cont); */

            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                $totalCambios = $rowCambios["estomago1"] + $rowCambios["estomago2"] + $rowCambios["piernas1"] + $rowCambios["piernas2"];

                /* $subData = array(); */

                /* 
                    $subData[] = "<span class='text-warning'>". $cont2 ."</span>";
                    $subData[] = "<span class='text-warning'>". $rowCambios["turno"] ."</span>";
                    $subData[] = "<span class='text-warning'>". $rowCambios["estomago1"]. " - " .$rowCambios["estomago2"]. " - " .$rowCambios["piernas1"]. " - " .$rowCambios["piernas2"] ."</span>";
                    $subData[] = "<span class='text-warning'>". $totalCambios ."</span>";
                    $subData[] = "<span class='text-warning'>". $rowCambios["temperatura"] ."</span>";
                    $subData[] = "<span class='text-warning'>". $rowCambios["status"] ."</span>";
                    $subData[] = "";
                */

                $turno .= "<br>" . "<span class='text-warning'>". $rowCambios["turno"] ."</span>";
                $eepp .= "<br>" . "<span class='text-warning'>". $rowCambios["estomago1"]. " - " .$rowCambios["estomago2"]. " - " .$rowCambios["piernas1"]. " - " .$rowCambios["piernas2"] ."</span>";
                $totalEepp .= "<br>" . "<span class='text-warning'>". $totalCambios ."</span>";
                $temperatura .= "<br>" . "<span class='text-warning'>". $rowCambios["temperatura"] ."</span>";
                $status .= "<br>" . "<span class='text-warning'>". $rowCambios["status"] ."</span>";

                /* $data[] = $subData; */
            }
        }
    }
    
    $subdata[] = $numeral;
    $subdata[] = $turno;
    $subdata[] = $eepp;
    $subdata[] = $totalEepp;
    $subdata[] = $temperatura;
    $subdata[] = $status;     
    $subdata[] = date('g:i a', strtotime($hora_menor));
    $acciones = '<div class="d-flex justify-content-center align-items-start gap-2">';
    
    if(($row[8] == '1')||($_SESSION['tipo'] == 0)){
        $acciones .= '<a style="cursor: pointer;" onclick="buscarCriterio(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a><a style="cursor: pointer;" onclick="eliminarItem(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    }
    
    if($row[11]!=''){
        $acciones .= '<a style="cursor: pointer;" onclick="mostrarObservacion(\''.$row[11].'\')"><i class="bi bi-exclamation-diamond fs-2"></i></a>';
    }
    if($row[12]!=''){
        $acciones .= '<a style="cursor: pointer;" id="abrirNovedades" onclick="mostrarFoto(\''.$row[12].'\')"><i class="bi bi-image fs-2"></i></i></a>';
    }

    $acciones .= '</div>';
    
    $subdata[] = $acciones;

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
