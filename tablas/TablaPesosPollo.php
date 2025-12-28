<?php
include('../config.php');
session_start();

$idGuia = $_POST["id"] ? $_POST["id"] : "";

$request = $_REQUEST;
$inicio = $request['start'];
$fin = $request['length'];
$busqueda = $request['search']['value'];

$col = array(
    0   =>  'id',
    1   =>  'tipo_pollo',
    2   =>  'nro_pollo',
    3   =>  'kilos',
    4   =>  'cajas',
    5   =>  'cajas_base',
    6   =>  'lote'
);

$sql = "SELECT
    id,
    tipo_pollo,
    nro_pollo,
    kilos,
    cajas,
    cajas_base,
    lote
    FROM pesos_pollo
    WHERE guia = '$idGuia'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (id LIKE '%$busqueda%' 
                OR tipo_pollo LIKE '%$busqueda%' 
                OR nro_pollo LIKE '%$busqueda%' 
                OR kilos LIKE '%$busqueda%' 
                OR cajas LIKE '%$busqueda%'
                OR cajas_base LIKE '%$busqueda%'
                OR lote LIKE '%$busqueda%')";
}

$totalDataQuery = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($totalDataQuery);

// Obtener el total con los filtros aplicados
$totalFilterQuery = mysqli_query($link, $sql);
$totalFilter = mysqli_num_rows($totalFilterQuery);
/* $sql .= " ORDER BY id_item_desprese DESC";


$sql .= " LIMIT $inicio, $fin"; */

$sql .= " ORDER BY id DESC LIMIT $inicio, $fin";

$query = mysqli_query($link, $sql) or die(mysqli_error($link));

$totalNroPollos = 0;
$totalCajas = 0;
$totalKilos = 0;
$totalCajasBases = 0;
$totalKilosNeto = 0;

$data = array();

while ($row = mysqli_fetch_row($query)) {
    $lote = $row[6];

    if ($lote == "") {
        if ($row[1] == "ENTERO") {
            $lotePedir = "lote_pollo_entero";
        } else {
            $lotePedir = "lote_pollo";
        }
        $sqlLote = "SELECT $lotePedir FROM desprese WHERE id = '$idGuia'";
        $queryLote = mysqli_query($link, $sqlLote);
        $rowLote = mysqli_fetch_assoc($queryLote);
        $lote = $rowLote[$lotePedir];
    }

    $subdata = array();

    $totalNroPollos += $row[2];
    $totalCajas += $row[4];
    $totalKilos += $row[3];
    $totalCajasBases += $row[5];
    $totalKilosNeto += $row[3] - (($row[4] * 2) + ($row[5] * 1.8));

    $id = $row[0];
    $descripcion = $row[1];
    $loteRow = $lote;
    $nroPollos = $row[2];
    $kilos = number_format($row[3], 2, ".", ",");
    $cajas = $row[4];
    $cajasBase = $row[5];
    $kilosNetos = number_format(($row[3] - (($row[4] * 2) + ($row[5] * 1.8))), 2, ".", ",");

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT
            ppc.id AS idItem,
            ppc.tipo_pollo AS tipoPollo,
            ppc.lote AS lote,
            ppc.nro_pollo AS nroPollos,
            ppc.kilos AS kilos,
            ppc.cajas AS cajas,
            ppc.cajas_base AS cajasBase,
            ppc.kilos - ((ppc.cajas * 2) + (ppc.cajas_base * 1.8)) AS kilosNeto
        FROM pesos_pollo_cambios ppc
        WHERE ppc.id = '$row[0]'
        ORDER BY ppc.idCambio DESC";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                $loteCambios = $rowCambios["lote"];

                if ($loteCambios == "") {
                    if ($rowCambios["tipoPollo"] == "ENTERO") {
                        $lotePedir = "lote_pollo_entero";
                    } else {
                        $lotePedir = "lote_pollo";
                    }

                    $sqlLote = "SELECT $lotePedir FROM desprese WHERE id = '$idGuia'";
                    $queryLote = mysqli_query($link, $sqlLote);
                    $rowLote = mysqli_fetch_assoc($queryLote);

                    $loteCambios = $rowLote[$lotePedir];
                }

                $id .= "<br><span style='color: #DAA520'>". $rowCambios["idItem"] ."</span>";
                $descripcion .= "<br><span style='color: #DAA520'>". $rowCambios["tipoPollo"] ."</span>";
                $loteRow .= "<br><span style='color: #DAA520'>". $loteCambios ."</span>";
                $nroPollos .= "<br><span style='color: #DAA520'>". $rowCambios["nroPollos"] ."</span>";
                $kilos .= "<br><span style='color: #DAA520'>". number_format($rowCambios["kilos"], 2, ".", ",") ."</span>";
                $cajas .= "<br><span style='color: #DAA520'>". $rowCambios["cajas"] ."</span>";
                $cajasBase .= "<br><span style='color: #DAA520'>". $rowCambios["cajasBase"] ."</span>";
                $kilosNetos .= "<br><span style='color: #DAA520'>". number_format($rowCambios["kilosNeto"], 2, ".", ",") ."</span>";
            }
        }
    }

    $subdata[] = $id;
    $subdata[] = $descripcion;
    $subdata[] = $loteRow;
    $subdata[] = $nroPollos;
    $subdata[] = $kilos;
    $subdata[] = $cajas;
    $subdata[] = $cajasBase;
    $subdata[] = $kilosNetos;

    $acciones = '<a onclick="editPesosPollo(\''.$row[0].'\')"><i class="bi bi-pencil-square fs-4 me-2 text-warning"></i></a>';
    $acciones .= '<a onclick="borrarPeso(\''.$row[0].'\')"><i class="bi bi-trash-fill fs-4 text-danger"></i></a>';
    
    $subdata[] = $acciones;
    $data[] = $subdata;
}

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = number_format($totalNroPollos, 0, ",", ".");
$subdata[] = number_format($totalKilos, 2, ".", ",");
$subdata[] = number_format($totalCajas, 0, ",", ".");
$subdata[] = number_format($totalCajasBases, 0, ",", ".");
$subdata[] = number_format($totalKilosNeto, 2, ".", ",");
$subdata[] = '';

$data[] = $subdata;

$json_data = array(
    "draw"              => intval($request['draw']),
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>