<?php
session_start();
//error_reporting(0);
require_once('../modelo/funciones.php');
require_once("../config.php");
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
$listaItems = listaItemsPorProveedorP2($proveedor);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

if (!empty($busqueda)) {
    
    $listaItems = listaItemsPorProveedorPPaginada($proveedor,$inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaItems);
} else {
    $totalFiltro = $totalData;
}

$data = array();
$cont = 1;
while ($row = mysqli_fetch_array($listaItems)) {
    $item = $row[0];
    $descripcion = $row[1];
    $temperatura = $row[2];
    $und = $row[3];
    $canastillas = $row[4];
    $peso = $row[8] ."Kg. <br>(". $row[5] ." - ". ($row[4] * floatval($row[12])) ." - ". ($row[11] * 1.8) .")";
    $horaRegistro = fsalida($row[6]);

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT
            rppc.item AS item,
            p.descripcion AS nombreItem,
            rppc.temperatura,
            rppc.unidades,
            rppc.cajas,
            rppc.peso_real,
            rppc.peso,
            rppc.pesocanastilla,
            rppc.base,
            rppc.registro
        FROM recepcion_pesos_pollo_cambios rppc
        INNER JOIN plantilla p ON p.item = rppc.item
        WHERE rppc.id_item_proveedor = '$row[7]' 
        ORDER BY rppc.idCambio DESC";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            /* $cont2 = $cont; */

            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                /* $subData = array(); */

                $item .= "<br><br><span style='color: #DAA520'>". $rowCambios["item"] ."</span>";
                $descripcion .= "<br><br><span style='color: #DAA520'>". $rowCambios["nombreItem"] ."</span>";
                $temperatura .= "<br><br><span style='color: #DAA520'>". $rowCambios["temperatura"] ."</span>";
                $und .= "<br><br><span style='color: #DAA520'>". $rowCambios["unidades"] ."</span>";
                $canastillas .= "<br><br><span style='color: #DAA520'>". $rowCambios["cajas"] ."</span>";
                $peso .= "<br><span style='color: #DAA520'>". $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * $rowCambios["pesocanastilla"]) ." - ". ($rowCambios["base"] * 1.8) .")</span>";
                $horaRegistro .= "<br><br><span style='color: #DAA520'>". fsalida($rowCambios["registro"]) ."</span>";

                /* $data[] = $subData; */
            }
        }
    }

    $subdata = array();

    $subdata[] = $cont;
    $subdata[] = $item;
    $subdata[] = $descripcion;
    $subdata[] = $temperatura;
    $subdata[] = $und;
    $subdata[] = $canastillas;
    $subdata[] = $peso;
    $subdata[] = $horaRegistro;
    
    if(($row[9] == '1')||($_SESSION['tipo'] == 0)){
        $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[7].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[7].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    }

    if($row[13]!=''){
        $estadoBtn .= '<a style="z-index:0;color:#ff8a00;margin: 5px;" onclick="mostrarObservacion(\''.$row[13].'\')"><i class="bi bi-exclamation-diamond fs-2"></i></a>';
    }
    if($row[14]!=''){
        $estadoBtn .= '<a style="z-index:0;color:#0023bc;margin:5px;" id="abrirNovedades" onclick="mostrarFoto(\''.$row[14].'\')"><i class="bi bi-image fs-2"></i></i></a>';
    }

    $subdata[] = $estadoBtn;

    $data[] = $subdata;
    $cont++;
}

$sql = "SELECT 
    SUM(temperatura) AS temperatura,
    SUM(unidades) AS unidades,
    SUM(cajas) AS cajas,
    SUM(peso) AS peso,
    SUM(peso_real) AS peso_real,
    SUM(base) AS base,
    SUM(cajas * pesocanastilla) AS peso_cajas
FROM plantilla,recepcion_pesos_pollo 
WHERE plantilla.item=recepcion_pesos_pollo.item AND recepcion_pesos_pollo.proveedor='" . $proveedor . "'
ORDER BY id_item_proveedor desc";

/*$sql = "SELECT 
    SUM(temperatura) AS temperatura,
    SUM(unidades) AS unidades,
    SUM(cajas) AS cajas,
    SUM(peso) AS peso,
    SUM(peso_real) AS peso_real,
    SUM(base) AS base,
    SUM(cajas * pesocanastilla) AS peso_cajas
FROM plantilla,recepcion_pesos_pollo 
WHERE plantilla.item=recepcion_pesos_pollo.item AND recepcion_pesos_pollo.proveedor='" . $proveedor . "'
ORDER BY id_item_proveedor desc";
*/

$rs_operation = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($rs_operation);

$subdata = array();
$subdata[] = "";
$subdata[] = "";
$subdata[] = "";
$subdata[] = $row['temperatura'];
$subdata[] = $row['unidades'];
$subdata[] = $row['cajas'];
$subdata[] = $row['peso_real'] . "Kg. <br>(" . $row['peso'] . " - " . ($row['peso_cajas']) . " - " . ($row['base'] * 1.8) . ")";
$subdata[] = "";
$subdata[] = "";
$data[] = $subdata;


$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);

echo json_encode($json_data);
?>
