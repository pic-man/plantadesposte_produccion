<?php
session_start();
include('../config.php');
/* error_reporting(0); */

$sql = "SELECT 
    r.id_recepcion, 
    r.fecharec, 
    p.sede AS proveedor, 
    r.canales, 
    r.consecutivog, 
    p.polloporcanastillas AS polloxcanastilla,
    r.status AS status,
    r.especie,
    r.tipo,
    r.destino,
    r.lote_planta
FROM recepcionpollo r
INNER JOIN proveedorpollo p ON r.beneficio = p.id    
ORDER BY r.id_recepcion DESC";

$query = mysqli_query($link, $sql);
if (!$query) {
    die("Error en consulta principal: " . mysqli_error($link));
}

$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$ids_recepcion = array();
$data_temp = array();
while ($row = mysqli_fetch_array($query)) {
    $ids_recepcion[] = $row[0];
    $data_temp[] = $row;
}

if (empty($ids_recepcion)) {
    $json_data = array(
        "recordsTotal"      => 0,         
        "recordsFiltered"   => 0,       
        "data"              => array()                     
    );
    echo json_encode($json_data);
    exit();
}

$ids_str = implode(',', $ids_recepcion);

$sql_pesos = "SELECT proveedor, SUM(unidades) as canales, SUM(peso) as peso_real, SUM(cajas) as cajas 
               FROM recepcion_pesos_pollo 
               WHERE proveedor IN ($ids_str) 
               GROUP BY proveedor";
$rs_pesos = mysqli_query($link, $sql_pesos);
$pesos_data = array();
while ($row = mysqli_fetch_array($rs_pesos)) {
    $pesos_data[$row[0]] = array(
        'canales' => $row[1] ?: 0,
        'peso_real' => $row[2] ?: 0,
        'cajas' => $row[3] ?: 0
    );
}

$sql_ultimo = "SELECT id_recepcion FROM recepcionpollo ORDER BY id_recepcion DESC LIMIT 1";
$rs_ultimo = mysqli_query($link, $sql_ultimo);
$ultimo_id = mysqli_fetch_row($rs_ultimo)[0];

$sql_lotes = "SELECT recepcion_guia, lote FROM lotes_guia WHERE recepcion_guia IN ($ids_str)";
$rs_lotes = mysqli_query($link, $sql_lotes);
$lotes_data = array();
while ($row = mysqli_fetch_array($rs_lotes)) {
    $lotes_data[$row[0]][] = $row[1];
}

$sql_fotos = "SELECT proveedor, foto FROM recepcion_pesos_pollo WHERE proveedor IN ($ids_str) AND foto != ''";
$rs_fotos = mysqli_query($link, $sql_fotos);
$fotos_data = array();
while ($row = mysqli_fetch_array($rs_fotos)) {
    $fotos_data[$row[0]] = true;
}

$sql_obs = "SELECT proveedor, observacion FROM recepcion_pesos_pollo WHERE proveedor IN ($ids_str) AND observacion != ''";
$rs_obs = mysqli_query($link, $sql_obs);
$obs_data = array();
while ($row = mysqli_fetch_array($rs_obs)) {
    $obs_data[$row[0]] = true;
}

$sql_prov_check = "SELECT proveedor FROM recepcion_pesos_pollo WHERE proveedor IN ($ids_str)";
$rs_prov_check = mysqli_query($link, $sql_prov_check);
$prov_check_data = array();
while ($row = mysqli_fetch_array($rs_prov_check)) {
    $prov_check_data[$row[0]] = true;
}

$data = array();

foreach ($data_temp as $row) {
    $id_recepcion = $row[0];
    
    $peso_info = isset($pesos_data[$id_recepcion]) ? $pesos_data[$id_recepcion] : array('canales' => 0, 'peso_real' => 0, 'cajas' => 0);
    $canales = $peso_info['canales'];
    
    $estadoBtn = '';
    $slash = "/";
    
    if ($id_recepcion != $ultimo_id && !isset($prov_check_data[$id_recepcion])) {
        $canales = "";
        $slash = "";
        $row[3] = "ANULADO";
        $row[4] = "";
    }
    
    $tiene_lotes = isset($lotes_data[$id_recepcion]) || $id_recepcion < 791;
    
    if ($row[8] == "BLANCO_DESPRESE") {
        $tipo = "BLANCO DESPRESE";
    } elseif ($row[8] == "CAMPO_DESPRESE") {
        $tipo = "CAMPO DESPRESE";
    } else {
        $tipo = $row[8];
    }
    

    if ($row[8] == 'BLANCO_DESPRESE' || $row[8] == 'CAMPO_DESPRESE') {
        if ($canales == 0 || $canales == '') {
            $peso_promedio = 0;
        } else {
            $peso_promedio = $peso_info['peso_real'] / $row[3];
        }
        $peso_promedio = number_format($peso_promedio, 2, '.', ',') . " kg";
    } else {
        if ($canales == 0 || $canales == '') {
            $peso_promedio = 0;
        } else {
            $peso_promedio = $peso_info['peso_real'] / $canales;
        }
        $peso_promedio = number_format($peso_promedio, 2, '.', ',') . " kg";
    }

    if ($_SESSION["tipo"] == 0) {
        $sqlCambios = "SELECT COUNT(idCambio) AS totalCambios FROM recepcion_pesos_pollo_cambios WHERE proveedor = '$id_recepcion'";
        $rs_operacionCambios = mysqli_query($link, $sqlCambios);
        $rowCambios = mysqli_fetch_assoc($rs_operacionCambios);
        $totalCambios = $rowCambios["totalCambios"];
    } else {
        $totalCambios = 0;
    }
    
    $subdata = array();
    $subdata[] = $id_recepcion;    
    $subdata[] = '<center>' . $id_recepcion .'<br>'.$row[7].'-'.$tipo. '</center>';

    if ($tiene_lotes) {
        $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\',\''.$totalCambios.'\');" data-bs-toggle="modal">'.$row[1]. "<br> ".$row[10].'</a></center>';
        $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\',\''.$totalCambios.'\');" data-bs-toggle="modal">'.$row[2].'<br>'.$row[9].'</a></center>';
        
        if ($row[8] == 'BLANCO_DESPRESE' || $row[8] == 'CAMPO_DESPRESE') {
            if ($peso_promedio > 2.5) {
                $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\', \''.$totalCambios.'\');" data-bs-toggle="modal">'.$canales.$slash.$row[3].'<br><a style="color: red;" title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            } else {
                $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\', \''.$totalCambios.'\');" data-bs-toggle="modal">'.$canales.$slash.$row[3].'<br><a title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            }
        } else {
            if ($peso_promedio > 2.5) {
                $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\', \''.$totalCambios.'\');" data-bs-toggle="modal">'.$canales.$slash.$row[3].'<br><a style="color: red;" title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            } else {
                $subdata[] = '<center><a style="z-index: 0;color:#000" data-bs-target="#modalCriterios" onclick="abrirModal(\''.$id_recepcion.'\',\''.$row[2].'\',\''.$row[6].'\',\''.$row[5].'\',\''.$row[8].'\'); buscarItems(\''.$id_recepcion.'\',\''.$row[8].'\', \''.$totalCambios.'\');" data-bs-toggle="modal">'.$canales.$slash.$row[3].'<br><a title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            }
        }
    } else {
        $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$row[1].'</a></center>';
        $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$row[2].'<br>'.$row[9].'</a></center>';
        
        if ($row[8] == 'BLANCO_DESPRESE' || $row[8] == 'CAMPO_DESPRESE') {
            if ($peso_promedio > 25) {
                $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$canales.$slash.$row[3].'<br><a style="color: red;" title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            } else {
                $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$canales.$slash.$row[3].'<br><a title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            }
        } else {
            if ($peso_promedio > 2.5) {
                $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$canales.$slash.$row[3].'<br><a style="color: red;" title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            } else {
                $subdata[] = '<center><a style="z-index: 0;color:#000" onclick="MostrarAlerta()">'.$canales.$slash.$row[3].'<br><a title="Peso promedio Pollo">'.$peso_promedio.'</a></a></center>';
            }
        }
    }
    
    if($_SESSION['tipo'] == 0){
        $estadoBtn = '<a style="z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarGuia(\''.$id_recepcion.'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i>';

        if ($row[8] == 'BLANCO_DESPRESE' || $row[8] == 'CAMPO_DESPRESE') {
            $estadoBtn .= '<a style="z-index: 0;color:#000" data-bs-target="#modalFechas" data-bs-toggle="modal" title="Imprimir Guias"><i class="bi bi-printer fs-2  me-3 text-primary"></i></a>';
        }else {
            if($canales == $row[3]){
                $estadoBtn .= '<a style="z-index: 0;color:#000" data-bs-target="#modalFechas" data-bs-toggle="modal" title="Imprimir Guias"><i class="bi bi-printer fs-2  me-3 text-primary"></i></a>';
            }else{
                $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-3 text-warning" style="visibility: hidden;""></i>';
            }
        }
    }else{
        $estadoBtn = '<a style="z-index: 0;color:#fff" data-bs-target="#modalNuevoProveedor" data-bs-toggle="modal" onclick="buscarGuia(\''.$id_recepcion.'\')"><i class="bi bi-pencil-square fs-2 me-3 text-warning"></i>';
        
        if ($row[8] == 'BLANCO_DESPRESE' || $row[8] == 'CAMPO_DESPRESE') {
            $estadoBtn .= '<a style="z-index: 0;color:#000" data-bs-target="#modalFechas" data-bs-toggle="modal" title="Imprimir Formato"><i class="bi bi-printer fs-2  me-3 text-primary"></i></a>';
        } else {
            if($canales == $row[3]){
                $estadoBtn .= '<a style="z-index: 0;color:#000" data-bs-target="#modalFechas" data-bs-toggle="modal" title="Imprimir Formato"><i class="bi bi-printer fs-2  me-3 text-primary"></i></a>';
            }else{
                $estadoBtn .= '<i class="bi bi-pencil-square fs-2 me-3 text-warning" style="visibility: hidden;""></i>';
            }
        }
    }
    
    if (isset($fotos_data[$id_recepcion])) {
        $estadoBtn .= '<a style="z-index: 0;color: rgb(0, 35, 188); "><i class="bi bi-image fs-3 me-2"></i></a>';
    }
    
    if (isset($obs_data[$id_recepcion])) {
        $estadoBtn .= '<a style="z-index: 0;color:#ff8a00;margin: 5px;" ><i class="bi bi-exclamation-diamond fs-2"></i></a>';
    }

    if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success"></i>';
    } else {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success" style="visibility: hidden"></i>';
    }
    
    $subdata[] = $estadoBtn;
    $data[] = $subdata;
}

$json_data = array(
    "recordsTotal"      => intval($totalData),         
    "recordsFiltered"   => intval($totalFilter),       
    "data"              => $data                     
);
echo json_encode($json_data);
?>
