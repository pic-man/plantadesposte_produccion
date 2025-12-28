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
$tipo_pollo = $_POST['tipo_pollo'];
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

//inicia nuevo
$listaItems = listaItemsPorProveedorP($proveedor);
$totalData = mysqli_num_rows($listaItems);
$totalFiltro = $totalData;

if (!empty($busqueda)) {
    
    $listaItems = listaItemsPorProveedorPPaginada($proveedor,$inicio, $fin, $busqueda);
    $totalFiltro = mysqli_num_rows($listaItems);
} else {
    $totalFiltro = $totalData;
}

if ($tipo_pollo == "CRUDO") {
    $sql = "SELECT guiaDesprese FROM guiaspollo WHERE id_guia = ".$proveedor;
    $rs_operation = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($rs_operation);
    $guiaDesprese = $row['guiaDesprese'];

    $sqlCajas = "SELECT item, SUM(cajas_mejora) AS cajas_mejora, SUM(kilos_mejora - (cajas_mejora * 2 + cajas_mejora_base * 1.8)) AS kilos_mejora FROM mejora_items WHERE id_guia = '$guiaDesprese' GROUP BY item";
    $rs_operationCajas = mysqli_query($link, $sqlCajas);

    $alasDesprese = 0;
    $pechugaDesprese = 0;
    $pernilDesprese = 0;
    $polloDesprese = 0;

    $alasDespreseKilos = 0;
    $pechugaDespreseKilos = 0;
    $pernilDespreseKilos = 0;
    $polloDespreseKilos = 0;

    $itemsAlas = ["059758", "059762"];
    $itemsPechuga = ["059756", "059760"];
    $itemsPernil = ["059757", "059761"];
    $itemsPollo = ["059755", "059759"];

    // Función auxiliar para obtener el tipo de item
    function getItemType($item, $itemsAlas, $itemsPechuga, $itemsPernil, $itemsPollo) {
        if (in_array($item, $itemsAlas)) return 'alas';
        if (in_array($item, $itemsPechuga)) return 'pechuga';
        if (in_array($item, $itemsPernil)) return 'pernil';
        if (in_array($item, $itemsPollo)) return 'pollo';
        return null;
    }

    while ($rowCajas = mysqli_fetch_array($rs_operationCajas)) {
        $itemType = getItemType($rowCajas["item"], $itemsAlas, $itemsPechuga, $itemsPernil, $itemsPollo);
        if ($itemType) {
            ${$itemType . 'Desprese'} += $rowCajas["cajas_mejora"];
            ${$itemType . 'DespreseKilos'} += $rowCajas["kilos_mejora"];
        }
    }

    $sqlCajasDP = "SELECT 
        item, 
        SUM(cajas) AS cajas, 
        SUM(peso_real) AS kilos 
    FROM item_proveedorpollo 
    WHERE proveedor = '$proveedor' 
    GROUP BY item";
    $rs_operationCajasDP = mysqli_query($link, $sqlCajasDP);

    $alasDP = 0;
    $pechugaDP = 0;
    $pernilDP = 0;
    $polloDP = 0;

    $alasDPKilos = 0;
    $pechugaDPKilos = 0;
    $pernilDPKilos = 0;
    $polloDPKilos = 0;

    while ($rowCajasDP = mysqli_fetch_array($rs_operationCajasDP)) {
        $itemType = getItemType($rowCajasDP["item"], $itemsAlas, $itemsPechuga, $itemsPernil, $itemsPollo);
        if ($itemType) {
            ${$itemType . 'DP'} += $rowCajasDP["cajas"];
            ${$itemType . 'DPKilos'} += $rowCajasDP["kilos"];
        }
    }
}

$data = array();
$cont = 1;
while ($row = mysqli_fetch_array($listaItems)) {
    if ($tipo_pollo == "CRUDO") {
        $itemType = getItemType($row[0], $itemsAlas, $itemsPechuga, $itemsPernil, $itemsPollo);
        $variableDP = ${$itemType . 'DP'};
        $variableDesprese = ${$itemType . 'Desprese'};

        $variableDPKilos = number_format(${$itemType . 'DPKilos'}, 2, '.', ',');
        $variableDespreseKilos = number_format(${$itemType . 'DespreseKilos'}, 2, '.', ',');
    }

    $item = $row[0];
    $descripcion = $row[1];
    $lote = $row[2];
    $temperatura = $row[3];
    $unidades = $row[4];

    if ($tipo_pollo == "CRUDO") {
        if ($variableDP == $variableDesprese) {
            $canastillas = "<span class='m-0' title='Cajas Desprese: ".$variableDesprese." / Cajas DP: ".$variableDP."'>". $row[5] ."</span>";
        } else {
            $canastillas = "<span class='m-0' style='color: red;' title='Cajas Desprese: ".$variableDesprese." / Cajas DP: ".$variableDP."'>".$row[5]."</span>";
        }
    } else {
        $canastillas = $row[5];
    }

    if (($row[0] == '050514') || ($row[0] == '050515') || ($row[0] == '050516') || ($row[0] == '050517')){
        if ($tipo_pollo == "CRUDO") {
            $peso = "<span class='m-0' title='Kilos Desprese: ".$variableDespreseKilos." / Kilos DP: ".$variableDPKilos."'>". $row[9]."Kg. <br> (". $row[6] ." - ". ($row[5] * 1.8) ." - ". (($row[12] * 1.8) / 4) .")</span>";
        } else {
            $peso = $row[9]."Kg. <br>(".$row[6]."-".($row[5]*1.8)."-".(($row[12]*1.8)/4).")";
        }
    }else{
        if(($row[0] == '074700')){
            if ($tipo_pollo == "CRUDO") {
                $peso = "<span class='m-0' title='Kilos Desprese: ".$variableDespreseKilos." / Kilos DP: ".$variableDPKilos."'>". $row[9]."Kg. <br> (". $row[6] ." - ". $row[5] * 1.8 ." - ". $row[12] * 1.8 .")</span>";
            } else {
                $peso = $row[9]."Kg. <br>(".$row[6]."-".($row[5]*1.8)."-".(($row[12]*1.8)).")";
            }
        }else{
            if ($tipo_pollo == "CRUDO") {
                $peso = "<span class='m-0' title='Kilos Desprese: ".$variableDespreseKilos." / Kilos DP: ".$variableDPKilos."'>". $row[9]."Kg. <br> (". $row[6] ." - ". $row[5] * 2 ." - ". $row[12] * 1.8 .")</span>";
            } else {
                $peso = $row[9]."Kg. <br>(".$row[6]."-".($row[5]*2)."-".(($row[12]*1.8)).")";
            }
        }
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
            ipc.peso_real,
            ipc.peso,
            ipc.base,
            ipc.registro
        FROM item_proveedorpollo_cambios ipc 
        INNER JOIN plantilla p ON p.item = ipc.item
        WHERE ipc.id_item_proveedor = '$row[8]' 
        ORDER BY ipc.idCambio DESC";

        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                if (($rowCambios["item"] == '050514') || ($rowCambios["item"] == '050515') || ($rowCambios["item"] == '050516') || ($rowCambios["item"] == '050517')){
                    $pesoCambios = $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * 1.8) ." - ". (($rowCambios["base"] * 1.8) / 4) .")";
                } else {
                    if ($rowCambios["item"] == '074700') {
                        $pesoCambios = $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * 1.8) ." - ". (($rowCambios["base"] * 1.8)) .")";
                    } else {
                        $pesoCambios = $rowCambios["peso_real"] ."Kg. <br>(". $rowCambios["peso"] ." - ". ($rowCambios["cajas"] * 2) ." - ". (($rowCambios["base"] * 1.8)) .")";
                    }
                }

                $item .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["item"] ."</span>";
                $descripcion .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["nombreItem"] ."</span>";
                $lote .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["lote"] ."</span>";
                $temperatura .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["temperatura"] ."</span>";
                $unidades .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["unidades"] ."</span>";
                $canastillas .= "<br><br>" . "<span style='color: #DAA520'>". $rowCambios["cajas"] ."</span>";
                $peso .= "<br>" . "<span style='color: #DAA520'>". $pesoCambios ."</span>";
                $horaRegistro .= "<br><br>" . "<span style='color: #DAA520'>". fsalida($rowCambios["registro"]) ."</span>";
            }
        }
    }
    
    $subdata = array();

    $subdata[] = $cont;
    $subdata[] = $item;
    $subdata[] = $descripcion;
    $subdata[] = $lote;
    $subdata[] = $temperatura;
    $subdata[] = $unidades;
    $subdata[] = $canastillas;
    $subdata[] = $peso;
    $subdata[] = $horaRegistro;
    
    if(($row[10] == '1')||($_SESSION['tipo'] == 0)){
        /* if(($row[0] == '050514')||($row[0] == '050515')||($row[0] == '050516')||($row[0] == '050517')){
            $estadoBtn = '<i class="bi bi-pencil-square fs-2 text-warning" style="visibility: hidden;"></i>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
        }else{
            $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[8].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
        } */
        $estadoBtn = '<a style="z-index: 0;color:#fff" onclick="buscarCriterio(\''.$row[8].'\')"><i class="bi bi-pencil-square fs-2 text-warning"></i></a>&nbsp;&nbsp;<a style="z-index: 0;color:#fff" onclick="eliminarItem(\''.$row[8].'\')"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
        
    }

    $subdata[] = $estadoBtn;
    $data[] = $subdata;
    $cont++;
}

$json_data = array(
    "draw"            => intval($request['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltro),
    "data"            => $data
);

echo json_encode($json_data);
?>
