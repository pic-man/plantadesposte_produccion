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
$proveedor = $_POST['proveedor'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

//inicia nuevo
$listaItems = listaItemsPorProveedor($proveedor);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

if (!empty($busqueda)) {
    
    $listaItems = listaItemsPorProveedorPaginada($proveedor,$inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaItems);
} else {
    $totalFiltro = $totalData;
}

$data = array();
$cont=0;
while ($row = mysqli_fetch_array($listaItems)) {
    $cont++;

    $subdata = array();

    $contCell = $cont;
    $item = $row[0];

    if ($row[12] == "Marinado") {
        $nombreItem = $row[1] . " - MARINADO";
    } else {
        $nombreItem = $row[1];
    }

    $lote = $row[2];
    $temperatura = $row[3];
    $unidades = $row[4];
    $cajas = $row[5];

    if ($row[11] == "POLLO") {
        $peso = $row[9] . "Kg. <br>(". $row[6] ." - ". ($row[5] * 2) .")";
    } else {
        $peso = $row[9] . "Kg. <br>(". $row[6] ." - ". ($row[5] * 1.8) .")";
    }

    $horaRegistro = fsalida($row[7]);

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT
            ipc.item AS item,
            p.descripcion AS nombreItem,
            ipc.lote AS lote,
            ipc.temperatura,
            ipc.unidades,
            ipc.cajas,
            ipc.peso,
            ipc.peso_real,
            ipc.registro,
            p.tipo AS tipo,
            ipc.proceso
        FROM item_proveedor_cambios ipc 
        INNER JOIN plantilla p ON p.item = ipc.item
        WHERE ipc.id_item_proveedor = '$row[8]' 
        ORDER BY ipc.idCambio DESC";

        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                if ($rowCambios["proceso"] == "Marinado") {
                    $rowCambios["nombreItem"] .= ' - MARINADO';
                }

                if ($rowCambios["tipo"] == "POLLO") {
                    $pesoCambios = $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * 2) .")";
                } else {
                    $pesoCambios = $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * 1.8) .")";
                }

                $item .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["item"] ."</span>";
                $nombreItem .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["nombreItem"] ."</span>";
                $lote .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["lote"] ."</span>";
                $temperatura .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["temperatura"] ."</span>";
                $unidades .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["unidades"] ."</span>";
                $cajas .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["cajas"] ."</span>";
                $peso .= "<br>" . "<span style='color: #DAA520'>". $pesoCambios ."</span>";
                $horaRegistro .= "<br><br>" . "<span style='color: #DAA520'>". fsalida($rowCambios["registro"]) ."</span>";
            }
        }
    }

    $subdata[] = $cont;
    $subdata[] = $item;
    $subdata[] = $nombreItem;
    $subdata[] = $lote;
    $subdata[] = $temperatura;
    $subdata[] = $unidades;
    $subdata[] = $cajas;
    $subdata[] = $peso;
    $subdata[] = $horaRegistro;

    $estadoBtn = '<div class="d-flex justify-content-center gap-1">';

    if(($row[10] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn .= '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[8].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>';
        $estadoBtn .= '<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    }

    $estadoBtn .= '</div>';

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
