<?php
include('../config.php');
include('../modelo/funciones.php');
session_start();

// Consulta principal optimizada
$sql = "SELECT 
    d.id,
    dt.empresa,
    dt.sede,
    d.status,
    d.fecha_registro,
    d.cantidadPollo,
    d.Temp1,
    d.Temp2,
    d.Temp3
FROM despresado d
INNER JOIN destinos dt ON d.destino = dt.id
ORDER BY d.id DESC";

$query = mysqli_query($link, $sql);
if (!$query) {
    die("Error en consulta principal: " . mysqli_error($link));
}

$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

// Si no hay datos, devolver array vacío
if ($totalData == 0) {
    $json_data = array(
        "recordsTotal"      => 0,         
        "recordsFiltered"   => 0,       
        "data"              => array()                     
    );
    echo json_encode($json_data);
    exit();
}

// Obtener todos los IDs de despresado para consultas batch
$ids_despresado = array();
$data_temp = array();
while ($row = mysqli_fetch_array($query)) {
    $ids_despresado[] = $row[0];
    $data_temp[] = $row;
}

// Consultas batch para obtener todos los datos necesarios
$ids_str = implode(',', $ids_despresado);

// 1. Obtener todos los mejora_items_despresado de una vez
$sql_mejora = "SELECT id_guia, item, lote 
               FROM mejora_items_despresado 
               WHERE id_guia IN ($ids_str) 
               AND item IN ('059758', '059757', '059756', '059762', '059760', '059761')
               ORDER BY id DESC";
$rs_mejora = mysqli_query($link, $sql_mejora);
$mejora_data = array();
while ($row = mysqli_fetch_array($rs_mejora)) {
    $mejora_data[$row[0]][] = $row;
}

// 2. Obtener todos los despresado_items de una vez
$sql_items = "SELECT guia, id_item_despresado, 
              SUM(kilos - ((cajas * 2) + (canastilla_base * 1.8))) AS kilos_netos,
              SUM(kilos) AS kilos_total, SUM(cajas) AS cajas_total
              FROM despresado_items 
              WHERE guia IN ($ids_str) 
              GROUP BY guia";
$rs_items = mysqli_query($link, $sql_items);
$items_data = array();
while ($row = mysqli_fetch_array($rs_items)) {
    $items_data[$row[0]] = array(
        'tiene_items' => true,
        'kilos_netos' => $row[2] ?: 0,
        'kilos_total' => $row[3] ?: 0,
        'cajas_total' => $row[4] ?: 0
    );
}

// 3. Obtener todos los lotes_guia_despresado de una vez
$sql_lotes = "SELECT guia, tipo 
              FROM lotes_guia_despresado 
              WHERE guia IN ($ids_str) 
              ORDER BY id DESC";
$rs_lotes = mysqli_query($link, $sql_lotes);
$lotes_data = array();
while ($row = mysqli_fetch_array($rs_lotes)) {
    if (!isset($lotes_data[$row[0]])) {
        $lotes_data[$row[0]] = $row[1];
    }
}

// 4. Obtener todos los lotes de mejora_items_despresado de una vez
$sql_lotes_mejora = "SELECT id_guia, lote 
                     FROM mejora_items_despresado 
                     WHERE id_guia IN ($ids_str) 
                     ORDER BY id DESC";
$rs_lotes_mejora = mysqli_query($link, $sql_lotes_mejora);
$lotes_mejora_data = array();
while ($row = mysqli_fetch_array($rs_lotes_mejora)) {
    if (!isset($lotes_mejora_data[$row[0]])) {
        $lotes_mejora_data[$row[0]] = $row[1];
    }
}

$data = array();

// Procesar los datos usando la información pre-cargada
foreach ($data_temp as $row) {
    $id_despresado = $row[0];
    
    $subdata = array();

    // Verificar si tiene items de mejora (mínimo 3 para CAMPO_DESPRESE)
    $tiene_mejora_suficiente = isset($mejora_data[$id_despresado]) && count($mejora_data[$id_despresado]) >= 3;
    
    // Verificar si tiene items de despresado
    $tiene_items = isset($items_data[$id_despresado]);
    
    // Obtener temperaturas
    $temps = array($row[6], $row[7], $row[8]); // Temp1, Temp2, Temp3
    
    // Obtener kilos netos
    $kilosNetos = $tiene_items ? $items_data[$id_despresado]['kilos_netos'] : 0;
    
    // Calcular peso promedio para administrador
    $peso_promedio = 0;
    if ($_SESSION["usuario"] == "ADMINISTRADOR" && $tiene_items) {
        $cajas_total = $items_data[$id_despresado]['cajas_total'];
        $kilos_total = $items_data[$id_despresado]['kilos_total'];
        
        if ($cajas_total > 0 && $kilos_total > 0) {
            $peso_promedio = $kilos_total / $cajas_total;
            $peso_promedio = number_format($peso_promedio, 2, ".", ",") . " kg";
        }
    }
    
    // Obtener tipo
    $tipo = isset($lotes_data[$id_despresado]) ? $lotes_data[$id_despresado] : '';
    if ($tipo == "CAMPO_DESPRESE") {
        $tipo = "CAMPO DESPRESADO";
    } elseif ($tipo == "BLANCO_DESPRESE") {
        $tipo = "BLANCO DESPRESADO";
    }
    
    // Obtener lote
    $lote = isset($lotes_mejora_data[$id_despresado]) ? $lotes_mejora_data[$id_despresado] : '';

    if ($_SESSION["tipo"] == 0) {
        $sqlCambiosD = "SELECT COUNT(idCambio) AS totalCambios FROM despresado_items_cambios WHERE guia = '$id_despresado'";
        $rs_operacionCambiosD = mysqli_query($link, $sqlCambiosD);
        $rowCambiosD = mysqli_fetch_assoc($rs_operacionCambiosD);
        $totalCambiosD = $rowCambiosD["totalCambios"];

        $sqlCambiosM = "SELECT COUNT(idCambio) AS totalCambios FROM mejora_items_despresado_cambios WHERE id_guia = '$id_despresado'";
        $rs_operacionCambiosM = mysqli_query($link, $sqlCambiosM);
        $rowCambiosM = mysqli_fetch_assoc($rs_operacionCambiosM);
        $totalCambiosM = $rowCambiosM["totalCambios"];

        $totalCambios = $totalCambiosD + $totalCambiosM;
    } else {
        $totalCambios = 0;
    }

    // Construir primera columna (ID)
    if ($tiene_items) {
        $subdata[] = "<center>" . $id_despresado . "</center>";
    } else {
        $subdata[] = "<center><p style='color: red; padding: 0px'>" . $id_despresado . "</p></center>";
    }

    // Construir segunda columna (Empresa, Sede, Fecha, Tipo)
    $subdata[] = "<center>".$row[1]. " - " .$row[2]. "<br>" . $row[4] . "<br>" . $tipo . "</center>";

    // Construir tercera columna (Kilos, Peso promedio, Lote)
    if ($_SESSION["usuario"] == "ADMINISTRADOR") {
        if ($peso_promedio > 25) {
            $subdata[] = "<center>" . number_format($kilosNetos, 2, ".", ",") . " kg<br><a style='color: red;' title='Peso promedio Canastilla'>" . $peso_promedio . "</a><br>" . $lote . "</center>";
        } else {
            $subdata[] = "<center>" . number_format($kilosNetos, 2, ".", ",") . " kg<br><a title='Peso promedio Canastilla'>" . $peso_promedio . "</a><br>" . $lote . "</center>";
        }
    } else {
        $subdata[] = "<center>" . number_format($kilosNetos, 2, ".", ",") . " kg<br>" . $lote . "</center>";
    }

    // Construir botones de estado
    $estadoBtn = '';
    
    if ($row[3] == 1 || $_SESSION["usuario"] == "ADMINISTRADOR") {
        // Verificar si las temperaturas están completas
        $temps_completas = !empty($temps[0]) && !empty($temps[1]) && !empty($temps[2]);
        
        if ($tiene_mejora_suficiente) {
            $color = $temps_completas ? 'text-warning' : 'text-warning';
        } else {
            $color = $temps_completas ? 'text-danger' : 'text-danger';
        }
        
        $estadoBtn = '<a onclick="abrirModalMejora(\''.$id_despresado.'\', \''.$row[1]. " - " .$row[2].'\', \''.($temps_completas ? 'true' : 'false').'\')" title="Abrir Mejora"><i class="bi bi-plus-square '.$color.' fs-2 me-2"></i></a>';
        
        $estadoBtn .= '<a data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor" onclick="buscar_guia(\''.$id_despresado.'\', \''. $totalCambios .'\')" title="Editar Guia"><i class="bi bi-pencil-square fs-2 me-2 text-warning"></i></a>';
        $estadoBtn .='<a href="./controlador/imprimirDespresadoOjo.php?id='.$id_despresado.'" target="_blank" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';
        $estadoBtn .= '<a onclick="bloquear_desprese(\''.$id_despresado.'\')" href="./controlador/imprimirDespresado.php?id='.$id_despresado.'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
        
        if ($_SESSION["usuario"] == "ADMINISTRADOR") {
            if($row[3] == 0){
                $estadoBtn .='<a style="color: black" onclick="desbloquear_desprese(\''.$id_despresado.'\')" title="Desbloquear Guia"><i class="bi bi-lock fs-2 style="color:#ff0000;"></i></a>';
            }else {
                $estadoBtn .='<a style="color: red" onclick="bloquear_desprese(\''.$id_despresado.'\')" title="Bloquear Guia"><i class="bi bi-unlock fs-2 style="color:#ff0000;"></i></a>';
            }
        }
        $estadoBtn .='<a onclick="modal_temp(\''.$id_despresado.'\')" title="Abrir Temperaturas"><i class="bi bi-thermometer-half fs-2 text-danger"></i></a>';
        $estadoBtn .= '<a onclick="AbrirModalCF(\''.$id_despresado.'\', \''. $row["empresa"]. " - " .$row["sede"] .'\', \''. $row["fecha_registro"] .'\', \''. $kilosNetos .'\')"><i class="bi bi-clipboard-data fs-2 me-2 text-success" style="cursor: pointer;"></i></a>';
    } else {
        $estadoBtn = '<i style="visibility: hidden" class="bi bi-plus-square text-warning fs-2 me-2"></i>';
        $estadoBtn .= '<i style="visibility: hidden" class="bi bi-pencil-square fs-2 me-2 text-warning"></i>';
        $estadoBtn .='<a href="./controlador/imprimirDespresadoOjo.php?id='.$id_despresado.'" target="_blank" title="Imprimir Ojo"><i class="bi bi-eye fs-2 me-2" style="color:#00a45f;"></i></a>';
        $estadoBtn .= '<a onclick="bloquear_desprese(\''.$id_despresado.'\')" href="./controlador/imprimirDespresado.php?id='.$id_despresado.'" target="_blank" title="Imprimir Guia"><i class="bi bi-printer fs-2 me-2 text-primary"></i></a>';
        $estadoBtn .='<i style="visibility: hidden" class="bi bi-thermometer-half fs-2 text-danger"></i>';
        $estadoBtn .= '<a onclick="AbrirModalCF(\''.$id_despresado.'\', \''. $row["empresa"]. " - " .$row["sede"] .'\', \''. $row["fecha_registro"] .'\', \''. $kilosNetos .'\')"><i class="bi bi-clipboard-data fs-2 me-2 text-success" style="cursor: pointer;"></i></a>';
    }

    if ($totalCambios > 0 && $_SESSION["tipo"] == 0 && $_SESSION["registrosCambios"] == 1) {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success"></i>';
    } else {
        $estadoBtn .= '<i class="bi bi-exclamation-diamond ms-1 fs-2 text-success" style="visibility: hidden;"></i>';
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