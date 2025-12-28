<?php
include('../config.php');
session_start();

$idGuia = $_POST["id"] ? $_POST["id"] : "";

$request = $_REQUEST;
$busqueda = $request['search']['value'];

$sql = "SELECT
    id_item_despresado,
    despresado_items.item,
    plantilla.descripcion,
    kilos,
    cajas,
    canastilla_base,
    lote
    FROM despresado_items
    INNER JOIN
        plantilla ON despresado_items.item = plantilla.item
    WHERE guia = '$idGuia'";

$query = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

if (!empty($busqueda)) {
    $sql .= " AND (id_item_despresado LIKE '%$busqueda%' 
                OR despresado_items.item LIKE '%$busqueda%' 
                OR plantilla.descripcion LIKE '%$busqueda%' 
                OR kilos LIKE '%$busqueda%' 
                OR cajas LIKE '%$busqueda%'
                OR canastilla_base LIKE '%$busqueda%'
                OR lote LIKE '%$busqueda%')";
}

$totalDataQuery = mysqli_query($link, $sql);
$totalData = mysqli_num_rows($totalDataQuery);

// Obtener el total con los filtros aplicados
$totalFilterQuery = mysqli_query($link, $sql);
$totalFilter = mysqli_num_rows($totalFilterQuery);
$sql .= " ORDER BY id_item_despresado DESC";

$query = mysqli_query($link, $sql) or die(mysqli_error($link));

$totalCajas = 0;
$totalKilos = 0;
$totalCajasBases = 0;
$totalKilosNeto = 0;

$totalKilosAlas = 0;
$totalKilosPechuga = 0;
$totalKilosPernil = 0;
$totalKilosAlasCampo = 0;
$totalKilosPechugaCampo = 0;
$totalKilosPernilCampo = 0;

$data = array();

while ($row = mysqli_fetch_row($query)) {

    $subdata = array();

    $totalCajas += $row[4];
    $totalKilos += $row[3];
    $totalCajasBases += $row[5];
    $totalKilosNeto += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));

    if ($row[1] == "059762") {
        $totalKilosAlas += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }elseif ($row[1] == "059760") {
        $totalKilosPechuga += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }elseif ($row[1] == "059761") {
        $totalKilosPernil += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }elseif ($row[1] == "059758") {
        $totalKilosAlasCampo += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }elseif ($row[1] == "059756") {
        $totalKilosPechugaCampo += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }elseif ($row[1] == "059757") {
        $totalKilosPernilCampo += $row[3] - (($row[5] * 1.8) + ($row[4] * 2));
    }

    $kilos = number_format($row[3], 2, ".", ",");
    $kilosNetos = number_format($row[3] - (($row[5] * 1.8) + ($row[4] * 2)), 2, ".", ",");

    $idItem = $row[0];
    $descripcion = $row[2];

    if ($_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $sqlCambios = "SELECT
            dic.id_item_despresado AS idItem,
            dic.item AS item,
            dic.lote AS lote,
            p.descripcion AS nombreItem,
            dic.kilos AS kilos,
            dic.cajas AS cajas,
            dic.canastilla_base AS canastillaBase,
            dic.kilos - ((dic.cajas * 2) + (dic.canastilla_base * 1.8)) AS kilosNeto
        FROM despresado_items_cambios dic
        INNER JOIN plantilla p ON p.item = dic.item
        WHERE dic.id_item_despresado = '$row[0]'
        ORDER BY dic.idCambio DESC";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);

        if (mysqli_num_rows($rs_operacionCambios) > 0) {
            while ($rowCambios = mysqli_fetch_assoc($rs_operacionCambios)) {
                $row[0] .= "<br><span style='color: #DAA520'>". $rowCambios["idItem"] ."</span>";
                $row[1] .= "<br><span style='color: #DAA520'>". $rowCambios["item"] ."</span>";
                $row[6] .= "<br><span style='color: #DAA520'>". $rowCambios["lote"] ."</span>";
                $row[2] .= "<br><span style='color: #DAA520'>". $rowCambios["nombreItem"] ."</span>";
                $kilos .= "<br><span style='color: #DAA520'>". number_format($rowCambios["kilos"], 2, ".", ",") ."</span>";
                $row[4] .= "<br><span style='color: #DAA520'>". number_format($rowCambios["cajas"], 0, ".", ",") ."</span>";
                $row[5] .= "<br><span style='color: #DAA520'>". number_format($rowCambios["canastillaBase"], 0, ".", ",") ."</span>";
                $kilosNetos .= "<br><span style='color: #DAA520'>". number_format($rowCambios["kilosNeto"], 2, ".", ",") ."</span>";
            }
        }
    }

    $subdata[] = "<center>" . $row[0] . "</center>";
    $subdata[] = "<center>" . $row[1] . "</center>";
    $subdata[] = "<center>" . $row[6] . "</center>";
    $subdata[] = "<center>" . $row[2] . "</center>";
    $subdata[] = "<center>" . $kilos . "</center>";
    $subdata[] = "<center>" . $row[4] . "</center>";
    $subdata[] = "<center>" . $row[5] . "</center>";
    $subdata[] = "<center>" . $kilosNetos . "</center>";

    $acciones = '<a onclick="editDesprese(\''.$idItem.'\',\''.$descripcion.'\')"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';

    $acciones .= '<a onclick="borrarItem(\''.$idItem.'\',\''.$descripcion.'\')" style="cursor: pointer;"><i class="bi bi-trash-fill fs-2 text-danger"></i></a>';
    
    $subdata[] = $acciones;
    $data[] = $subdata;
}

$totalKilosPorcentaje = $totalKilos - (($totalCajasBases * 1.8) + ($totalCajas * 2));

$porcentajeAlas = $totalKilosAlas / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;
$porcentajePechugas = $totalKilosPechuga / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;
$porcentajePernil = $totalKilosPernil / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;
$porcentajeAlasCampo = $totalKilosAlasCampo / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;
$porcentajePechugasCampo = $totalKilosPechugaCampo / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;
$porcentajePernilCampo = $totalKilosPernilCampo / ($totalKilosPorcentaje > 0 ? $totalKilosPorcentaje: 1) * 100;

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = number_format($totalKilos, 2, ".", ",");
$subdata[] = number_format($totalCajas, 0, ".", ",");
$subdata[] = number_format($totalCajasBases, 0, ".", ",");
$subdata[] = number_format($totalKilosNeto, 2, ".", ",");
$subdata[] = '';
$data[] = $subdata;

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = "";
$subdata[] = "";
$subdata[] = "";
$subdata[] = '';
$subdata[] = '';
$data[] = $subdata;

$subdata = [];
$subdata[] = '';
$subdata[] = '';
$subdata[] = '';
$subdata[] = 'Descripción';
$subdata[] = "Kilos Brutos";
$subdata[] = "% Participacion";
$subdata[] = "Rango";
$subdata[] = '';
$subdata[] = '';
$data[] = $subdata;

if ($totalKilosAlas > 0 || $totalKilosPechuga > 0 || $totalKilosPernil > 0) {
    if ($porcentajeAlas > 16) {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '<p style="color:red">ALAS BLANCA MERCAMIO MARINADAS<p>';
        $subdata[] = '<p style="color:red">'.number_format($totalKilosAlas, 2, ",", ".").'</p>';
        $subdata[] = '<p style="color:red">'.number_format($porcentajeAlas, 2, ",", ".") . "%</p>";
        $subdata[] = '<p style="color:red">Menor al 16%</p>';
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    }else {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = 'ALAS BLANCA MERCAMIO MARINADAS';
        $subdata[] = number_format($totalKilosAlas, 2, ",", ".");
        $subdata[] = number_format($porcentajeAlas, 2, ",", ".") . "%";
        $subdata[] = 'Menor al 16%';
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    }

    if ($porcentajePechugas < 39) {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '<p style="color:red">PECHUGA BLANCA MERCAMIO MARINADA</p>';
        $subdata[] = '<p style="color:red">'.number_format($totalKilosPechuga, 2, ",", ".")."</p>";
        $subdata[] = '<p style="color:red">'.number_format($porcentajePechugas, 2, ",", ".") . "%</p>";
        $subdata[] = "<p style='color:red'>Mayor al 39%</p>";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    } else {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = 'PECHUGA BLANCA MERCAMIO MARINADA';
        $subdata[] = number_format($totalKilosPechuga, 2, ",", ".");
        $subdata[] = number_format($porcentajePechugas, 2, ",", ".") . "%";
        $subdata[] = "Mayor al 39%";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    }
    
    $subdata = [];
    $subdata[] = '';
    $subdata[] = '';
    $subdata[] = '';
    $subdata[] = 'PERNIL BLANCO MERCAMIO MARINADO';
    $subdata[] = number_format($totalKilosPernil, 2, ",", ".");
    $subdata[] = number_format($porcentajePernil, 2, ",", ".") . "%";
    $subdata[] = "";
    $subdata[] = '';
    $subdata[] = '';
    $data[] = $subdata;
}elseif($totalKilosAlasCampo > 0 || $totalKilosPechugaCampo > 0 || $totalKilosPernilCampo > 0) {
    if ($porcentajeAlasCampo > 16) {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '<p style="color:red">ALAS CAMPO MERCAMIO MARINADAS</p>';
        $subdata[] = '<p style="color:red">'.number_format($totalKilosAlasCampo, 2, ",", ".") . "</p>";
        $subdata[] = '<p style="color:red">'.number_format($porcentajeAlasCampo, 2, ",", ".") . "%</p>";
        $subdata[] = "<p style='color:red'>Menor al 16%</p>";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    } else {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = 'ALAS CAMPO MERCAMIO MARINADAS';
        $subdata[] = number_format($totalKilosAlasCampo, 2, ",", ".");
        $subdata[] = number_format($porcentajeAlasCampo, 2, ",", ".") . "%";
        $subdata[] = "Menor al 16%";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    }

    if ($porcentajePechugasCampo < 39) {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '<p style="color: red">PECHUGA CAMPO MERCAMIO MARINADA</p>';
        $subdata[] = '<p style="color: red">'.number_format($totalKilosPechugaCampo, 2, ",", ".") . "</p>";
        $subdata[] = '<p style="color: red">'.number_format($porcentajePechugasCampo, 2, ",", ".") . "%</p>";
        $subdata[] = "<p style='color: red'>Mayor al 39%</p>";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    } else {
        $subdata = [];
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = '';
        $subdata[] = 'PECHUGA CAMPO MERCAMIO MARINADA';
        $subdata[] = number_format($totalKilosPechugaCampo, 2, ",", ".");
        $subdata[] = number_format($porcentajePechugasCampo, 2, ",", ".") . "%";
        $subdata[] = "Mayor al 39%";
        $subdata[] = '';
        $subdata[] = '';
        $data[] = $subdata;
    }

    $subdata = [];
    $subdata[] = '';
    $subdata[] = '';
    $subdata[] = '';
    $subdata[] = 'PERNIL CAMPO MERCAMIO MARINADO';
    $subdata[] = number_format($totalKilosPernilCampo, 2, ",", ".");
    $subdata[] = number_format($porcentajePernilCampo, 2, ",", ".") . "%";
    $subdata[] = "";
    $subdata[] = '';
    $subdata[] = '';
    $data[] = $subdata;
}

$json_data = array(
    "draw"              => intval($request['draw']),
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>