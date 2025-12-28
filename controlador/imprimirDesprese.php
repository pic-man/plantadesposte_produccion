<?php
session_start();
//ini_set("display_errors", false);
$idGuia = $_GET["id"] ? $_GET["id"]: "1";
date_default_timezone_set("America/Bogota");
$fecha_actual = date("Y-m-d");
include("../config.php");

ini_set('display_errors', '0');
error_reporting(0);

$sql = "SELECT 
    desprese.id,
    destino,
    nro_pollo_desprese,
    peso_pollo_desprese,
    nro_pollo_enteros,
    peso_pollo_enteros,
    proveedorpollo.sede AS proveedor_pollo,
    lote_pollo,
    fecha_venci_pollo,
    proveedor_hielo.sede AS proveedor_hielo,
    lote_hielo,
    fecha_venci_hielo,
    proveedor_empaque.sede AS proveedor_empaque,
    lote_empaque,
    fecha_venci_empaque,
    fabricante_empaque.sede AS fabricante_empaque,
    proveedor_salmuera.sede AS proveedor_salmuera   ,
    lote_salmuera,
    fecha_venci_salmuera,
    responsable,
    responsables.nombres AS responsable_desprese,
    fecha_desprese,
    Temp1,
    Temp2,
    Temp3,
    Hora1,
    Hora2,
    Hora3,
    fecha_beneficio,
    tipo_pollo,
    concentracion,
    fecha_registro,
    proveedor_pollo_entero,
    lote_pollo_entero,
    fecha_beneficio_entero,
    fecha_venci_pollo_entero,
    hora_inicio,
    hora_final
    FROM desprese 
    LEFT JOIN 
        proveedorpollo ON desprese.proveedor_pollo = proveedorpollo.id 
    LEFT JOIN
        proveedor_hielo ON desprese.proveedor_hielo = proveedor_hielo.id
    LEFT JOIN
        proveedor_empaque ON desprese.proveedor_empaque = proveedor_empaque.id
    LEFT JOIN
        proveedor_salmuera ON desprese.proveedor_salmuera = proveedor_salmuera.id
    LEFT JOIN
        responsables ON desprese.responsable = responsables.cedula
    LEFT JOIN
        fabricante_empaque ON desprese.fabricante_empaque = fabricante_empaque.id
    WHERE desprese.id = '$idGuia'";
$operation = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($operation);

$sql2 = "SELECT empresa,sede FROM destinos WHERE id = " . $row['destino'];
$operation2 = mysqli_query($link, $sql2);
$empresa = mysqli_fetch_row($operation2);

$sql3 = "SELECT item, kilos, cajas, canastilla_base, status FROM desprese_items WHERE guia = '$idGuia'";
$operation3 = mysqli_query($link, $sql3);

$canastillasBase = 0;

$items = array(
    "059758" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "045401" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "042788" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "042789" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059756" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059757" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059755" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059762" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059760" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059761" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059759" =>[
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ]
);

while($row3 = mysqli_fetch_row($operation3)){
    if ($row3[0] == "059758") {
        $items["059758"]["cajas"] += $row3[2];
        $items["059758"]["kg"] += $row3[1];
        $items["059758"]["cajasBase"] += $row3[3];
        $items["059758"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "045401") {
        $items["045401"]["cajas"] += $row3[2];
        $items["045401"]["kg"] += $row3[1];
        $items["045401"]["cajasBase"] += $row3[3];
        $items["045401"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "042788") {
        $items["042788"]["cajas"] += $row3[2];
        $items["042788"]["kg"] += $row3[1];
        $items["042788"]["cajasBase"] += $row3[3];
        $items["042788"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "042789") {
        $items["042789"]["cajas"] += $row3[2];
        $items["042789"]["kg"] += $row3[1];
        $items["042789"]["cajasBase"] += $row3[3];
        $items["042789"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059756") {
        $items["059756"]["cajas"] += $row3[2];
        $items["059756"]["kg"] += $row3[1];
        $items["059756"]["cajasBase"] += $row3[3];
        $items["059756"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059757") {
        $items["059757"]["cajas"] += $row3[2];
        $items["059757"]["kg"] += $row3[1];
        $items["059757"]["cajasBase"] += $row3[3];
        $items["059757"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059755") {
        $items["059755"]["cajas"] += $row3[2];
        $items["059755"]["kg"] += $row3[1];
        $items["059755"]["cajasBase"] += $row3[3];
        $items["059755"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059762") {
        $items["059762"]["cajas"] += $row3[2];
        $items["059762"]["kg"] += $row3[1];
        $items["059762"]["cajasBase"] += $row3[3];
        $items["059762"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059760") {
        $items["059760"]["cajas"] += $row3[2];
        $items["059760"]["kg"] += $row3[1];
        $items["059760"]["cajasBase"] += $row3[3];
        $items["059760"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059761") {
        $items["059761"]["cajas"] += $row3[2];
        $items["059761"]["kg"] += $row3[1];
        $items["059761"]["cajasBase"] += $row3[3];
        $items["059761"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }elseif ($row3[0] == "059759") {
        $items["059759"]["cajas"] += $row3[2];
        $items["059759"]["kg"] += $row3[1];
        $items["059759"]["cajasBase"] += $row3[3];
        $items["059759"]["kgNeto"] += $row3[4] == 0 ? $row3[1] - (($row3[3] * 1.8) + ($row3[2] * 2)) : $row3[1];
    }
    $canastillasBase += $row3[3];
}

$sql10 = "SELECT lote, SUM(kilos), tipo_pollo FROM pesos_pollo WHERE guia = '$idGuia' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
$rs_operation10 = mysqli_query($link, $sql10);
$DataLote = mysqli_fetch_row($rs_operation10);

$lote = $DataLote[0];
$tipo_pollo = $DataLote[2];

$sql11 = "SELECT lg.tipo, lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$lote' AND guia = '$idGuia'";
$rs_operation11 = mysqli_query($link, $sql11);
$DataTipo = mysqli_fetch_row($rs_operation11);

$tipo = $DataTipo[0];
$fechaBeneficio = $DataTipo[1];
$proveedor = $DataTipo[2];
$fechaVencimiento = $DataTipo[3];

if ($tipo_pollo == "DESPRESE") {
    $fechaBeneficioDesprese = $fechaBeneficio;
    $sql12 = "SELECT lote, SUM(kilos) FROM pesos_pollo WHERE guia = '$idGuia' AND tipo_pollo = 'ENTERO' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
    $rs_operation12 = mysqli_query($link, $sql12);
    if (mysqli_num_rows($rs_operation12) > 0) {
        $loteEntero = mysqli_fetch_row($rs_operation12)[0];
        $sql13 = "SELECT lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$loteEntero' AND guia = '$idGuia'";
        $rs_operation13 = mysqli_query($link, $sql13);
        $DataEntero = mysqli_fetch_row($rs_operation13);
        $fechaBeneficioEntero = $DataEntero[0];
    } else {
        $fechaBeneficioEntero = "";
    }
} else {
    $fechaBeneficioEntero = $fechaBeneficio;

    $sql14 = "SELECT lote, SUM(kilos) FROM pesos_pollo WHERE guia = '$idGuia' AND tipo_pollo = 'DESPRESE' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
    $rs_operation14 = mysqli_query($link, $sql14);
    if (mysqli_num_rows($rs_operation14) > 0) {
        $loteDesprese = mysqli_fetch_row($rs_operation14)[0];
        $sql15 = "SELECT lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$loteDesprese' AND guia = '$idGuia'";
        $rs_operation15 = mysqli_query($link, $sql15);
        $DataDesprese = mysqli_fetch_row($rs_operation15);
        $fechaBeneficioDesprese = $DataDesprese[0];
    } else {
        $fechaBeneficioDesprese = "";
    }
}

$sql7 = "SELECT tipo_pollo, SUM(kilos), SUM(cajas), SUM(cajas_base), SUM(nro_pollo) FROM pesos_pollo WHERE guia = '$idGuia' GROUP BY tipo_pollo";
$operation7 = mysqli_query($link, $sql7);

$nroPolloDesprese = 0;
$nroPolloEntero = 0;
$pesoInicialDesprese = 0;
$pesoInicialEntero = 0;

while ($row7 = mysqli_fetch_row($operation7)) {
    if ($row7[0] == "ENTERO") {
        $nroPolloEntero = $row7[4];
        $pesoInicialEntero = $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        if ($tipo == "BLANCO") {
            $items["059759"]["cajas"] += $row7[2];
            $items["059759"]["kg"] += $row7[1];
            $items["059759"]["cajasBase"] += $row7[3];
            $items["059759"]["kgNeto"] += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        }else {
            $items["059755"]["cajas"] += $row7[2];
            $items["059755"]["kg"] += $row7[1];
            $items["059755"]["cajasBase"] += $row7[3];
            $items["059755"]["kgNeto"] += $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
        }
    }else {
        $nroPolloDesprese = $row7[4];
        $pesoInicialDesprese = $row7[1] - (($row7[3] * 1.8) + ($row7[2] * 2));
    }
}

$sql4 = "SELECT item,kilos_mejora,cajas_mejora, cajas_mejora_base FROM mejora_items WHERE id_guia = '$idGuia'";
$operation4 = mysqli_query($link, $sql4);
$itemsM = array(
    "059758" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "045401" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "042788" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "042789" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059756" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059757" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059755" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059762" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059760" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059761" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ],
    "059759" => [
        "cajas" => 0,
        "kg" => 0,
        "cajasBase" => 0,
        "kgNeto" => 0
    ]
);

while ($row2 = mysqli_fetch_row($operation4)) {
    if ($row2[0] == "059758") {
        $itemsM["059758"]["cajas"] += $row2[2];
        $itemsM["059758"]["kg"] += $row2[1];
        $itemsM["059758"]["cajasBase"] += $row2[3];
        $itemsM["059758"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "045401") {
        $itemsM["045401"]["cajas"] += $row2[2];
        $itemsM["045401"]["kg"] += $row2[1];
        $itemsM["045401"]["cajasBase"] += $row2[3];
        $itemsM["045401"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "042788") {
        $itemsM["042788"]["cajas"] += $row2[2];
        $itemsM["042788"]["kg"] += $row2[1];
        $itemsM["042788"]["cajasBase"] += $row2[3];
        $itemsM["042788"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "042789") {
        $itemsM["042789"]["cajas"] += $row2[2];
        $itemsM["042789"]["kg"] += $row2[1];
        $itemsM["042789"]["cajasBase"] += $row2[3];
        $itemsM["042789"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059756") {
        $itemsM["059756"]["cajas"] += $row2[2];
        $itemsM["059756"]["kg"] += $row2[1];
        $itemsM["059756"]["cajasBase"] += $row2[3];
        $itemsM["059756"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059757") {
        $itemsM["059757"]["cajas"] += $row2[2];
        $itemsM["059757"]["kg"] += $row2[1];
        $itemsM["059757"]["cajasBase"] += $row2[3];
        $itemsM["059757"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059755") {
        $itemsM["059755"]["cajas"] += $row2[2];
        $itemsM["059755"]["kg"] += $row2[1];
        $itemsM["059755"]["cajasBase"] += $row2[3];
        $itemsM["059755"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059762") {
        $itemsM["059762"]["cajas"] += $row2[2];
        $itemsM["059762"]["kg"] += $row2[1];
        $itemsM["059762"]["cajasBase"] += $row2[3];
        $itemsM["059762"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059760") {
        $itemsM["059760"]["cajas"] += $row2[2];
        $itemsM["059760"]["kg"] += $row2[1];
        $itemsM["059760"]["cajasBase"] += $row2[3];
        $itemsM["059760"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059761") {
        $itemsM["059761"]["cajas"] += $row2[2];
        $itemsM["059761"]["kg"] += $row2[1];
        $itemsM["059761"]["cajasBase"] += $row2[3];
        $itemsM["059761"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }elseif ($row2[0] == "059759") {
        $itemsM["059759"]["cajas"] += $row2[2];
        $itemsM["059759"]["kg"] += $row2[1];
        $itemsM["059759"]["cajasBase"] += $row2[3];
        $itemsM["059759"]["kgNeto"] += $row2[1] - (($row2[3] * 1.8) + ($row2[2] * 2));
    }
}

$totalBlanco = $itemsM["059762"]["kg"] + $itemsM["059760"]["kg"] + $itemsM["059761"]["kg"];
$totalCampo = $itemsM["059758"]["kg"] + $itemsM["059756"]["kg"] + $itemsM["059757"]["kg"];

$porcentajeAla = $itemsM["059758"]["kg"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajeAlaBlanca = $itemsM["059762"]["kg"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajePechugaBlanca = $itemsM["059760"]["kg"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajePernilBlanca = $itemsM["059761"]["kg"] / ($totalBlanco > 0 ? $totalBlanco : 1) * 100;
$porcentajeAla = $itemsM["059758"]["kg"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajePechuga = $itemsM["059756"]["kg"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;
$porcentajePernil = $itemsM["059757"]["kg"] / ($totalCampo > 0 ? $totalCampo : 1) * 100;

$sql5 = "SELECT 
    responsables.nombres,
    responsable2
    FROM desprese
    INNER JOIN
        responsables ON desprese.responsable2 = responsables.cedula 
    WHERE desprese.id = '$idGuia'";
$operation5 = mysqli_query($link, $sql5);
$responsable = mysqli_fetch_row($operation5);

$sql6 = "SELECT lote, item FROM mejora_items WHERE id_guia = '$idGuia' GROUP BY item";
$operation6 = mysqli_query($link, $sql6);

$lotes = array();

while ($row6 = mysqli_fetch_row($operation6)) {
    $lotes[$row6[1]] = $row6[0];
}

/* $lote = "";

if (empty($lotes["059758"]) && empty($lotes["059762"])) {
    if (empty($lotes["059759"])) {
        $lote = $lotes["059755"];
    } else {
        $lote = $lotes["059759"];
    }
}else {
    if (empty($lotes["059762"])) {
        $lote = $lotes["059758"];
    }else {
        $lote = $lotes["059762"];
    }
} */

$nrPollosD = "";

if ($row['nro_pollo_desprese'] == "") {
    $nrPollos = $row['nro_pollo_enteros'];
}else {
    $nrPollos = $row['nro_pollo_desprese'];
}

$unidadesTotalDes = 0;
$unidadesTotalDesP = 0;

$PesoTotalDes = $items["059762"]["kg"] + $items["059760"]["kg"] + $items["059761"]["kg"] + $items["059758"]["kg"] + $items["059756"]["kg"] + $items["059757"]["kg"];
$PesoTotalDesP = $items["059762"]["kg"] + $items["059760"]["kg"] + $items["059761"]["kg"] + $items["059759"]["kg"] + $items["059758"]["kg"] + $items["059756"]["kg"] + $items["059757"]["kg"] + $items["059755"]["kg"];
$cajasBaseTotalDes = $items["059762"]["cajasBase"] + $items["059760"]["cajasBase"] + $items["059761"]["cajasBase"] + $items["059758"]["cajasBase"] + $items["059756"]["cajasBase"] + $items["059757"]["cajasBase"];
$cajasBaseTotalDesP = $items["059762"]["cajasBase"] + $items["059760"]["cajasBase"] + $items["059761"]["cajasBase"] + $items["059759"]["cajasBase"] + $items["059758"]["cajasBase"] + $items["059756"]["cajasBase"] + $items["059757"]["cajasBase"] + $items["059755"]["cajasBase"];
$CajaTotalDes = $items["059762"]["cajas"] + $items["059760"]["cajas"] + $items["059761"]["cajas"] + $items["059758"]["cajas"] + $items["059756"]["cajas"] + $items["059757"]["cajas"];
$CajaTotalDesP = $items["059762"]["cajas"] + $items["059760"]["cajas"] + $items["059761"]["cajas"] + $items["059759"]["cajas"] + $items["059758"]["cajas"] + $items["059756"]["cajas"] + $items["059757"]["cajas"] + $items["059755"]["cajas"];
$kilosNetoDes = $items["059762"]["kgNeto"] + $items["059760"]["kgNeto"] + $items["059761"]["kgNeto"] + $items["059758"]["kgNeto"] + $items["059756"]["kgNeto"] + $items["059757"]["kgNeto"];
$kilosNetoDesP = $items["059762"]["kgNeto"] + $items["059760"]["kgNeto"] + $items["059761"]["kgNeto"] + $items["059759"]["kgNeto"] + $items["059758"]["kgNeto"] + $items["059756"]["kgNeto"] + $items["059757"]["kgNeto"] + $items["059755"]["kgNeto"];
$PesoTotalMej = $itemsM["059762"]["kg"] + $itemsM["059760"]["kg"] + $itemsM["059761"]["kg"] + $itemsM["059758"]["kg"] + $itemsM["059756"]["kg"] + $itemsM["059757"]["kg"];
$PesoTotalMejP = $itemsM["059762"]["kg"] + $itemsM["059760"]["kg"] + $itemsM["059761"]["kg"] + $itemsM["059759"]["kg"] + $itemsM["059758"]["kg"] + $itemsM["059756"]["kg"] + $itemsM["059757"]["kg"] + $itemsM["059755"]["kg"];
$cajasBaseTotalMej = $itemsM["059762"]["cajasBase"] + $itemsM["059760"]["cajasBase"] + $itemsM["059761"]["cajasBase"] + $itemsM["059758"]["cajasBase"] + $itemsM["059756"]["cajasBase"] + $itemsM["059757"]["cajasBase"];
$cajasBaseTotalMejP = $itemsM["059762"]["cajasBase"] + $itemsM["059760"]["cajasBase"] + $itemsM["059761"]["cajasBase"] + $itemsM["059759"]["cajasBase"] + $itemsM["059758"]["cajasBase"] + $itemsM["059756"]["cajasBase"] + $itemsM["059757"]["cajasBase"] + $itemsM["059755"]["cajasBase"];
$CajaTotalMej = $itemsM["059762"]["cajas"] + $itemsM["059760"]["cajas"] + $itemsM["059761"]["cajas"] + $itemsM["059758"]["cajas"] + $itemsM["059756"]["cajas"] + $itemsM["059757"]["cajas"];
$CajaTotalMejP = $itemsM["059762"]["cajas"] + $itemsM["059760"]["cajas"] + $itemsM["059761"]["cajas"] + $itemsM["059759"]["cajas"] + $itemsM["059758"]["cajas"] + $itemsM["059756"]["cajas"] + $itemsM["059757"]["cajas"] + $itemsM["059755"]["cajas"];
$kilosNetoMej = $itemsM["059762"]["kgNeto"] + $itemsM["059760"]["kgNeto"] + $itemsM["059761"]["kgNeto"] + $itemsM["059758"]["kgNeto"] + $itemsM["059756"]["kgNeto"] + $itemsM["059757"]["kgNeto"];
$kilosNetoMejP = $itemsM["059762"]["kgNeto"] + $itemsM["059760"]["kgNeto"] + $itemsM["059761"]["kgNeto"] + $itemsM["059759"]["kgNeto"] + $itemsM["059758"]["kgNeto"] + $itemsM["059756"]["kgNeto"] + $itemsM["059757"]["kgNeto"] + $itemsM["059755"]["kgNeto"];

$sql8 = "SELECT sede FROM proveedorpollo WHERE id = " . $row["proveedor_pollo_entero"];
$rs_operation8 = mysqli_query($link, $sql8);
$proveedorPolloEntero = mysqli_fetch_row($rs_operation8);

function calcular_tiempo($horaInicio, $horaFin) {
    list($hIni, $mIni) = explode(':', $horaInicio);
    list($hFin, $mFin) = explode(':', $horaFin);
    
    $segundosInicio = $hIni * 3600 + $mIni * 60;
    $segundosFin = $hFin * 3600 + $mFin * 60;
    
    $diferencia = $segundosFin - $segundosInicio;
    
    if ($diferencia < 0) {
        $diferencia += 24 * 3600;
    }

    $horas = floor($diferencia / 3600);
    $minutos = floor(($diferencia % 3600) / 60);
    
    return sprintf("%02d:%02d", $horas, $minutos);
}

$hora = $_SESSION["fechaInicio"];
$horaActual = date("G:i");

if ($row['hora_inicio'] != "" && $row['hora_final'] != "") {
    $hora = $row['hora_inicio'];
    $horaActual = $row['hora_final'];
}else {
    $sql9 = "UPDATE desprese SET hora_inicio = '" . $hora . "', hora_final = '" . $horaActual . "' WHERE id = " . $row['id'];
    mysqli_query($link, $sql9);
}

$sql10 = "SELECT lote, SUM(kilos), tipo_pollo FROM pesos_pollo WHERE guia = '$idGuia' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
$rs_operation10 = mysqli_query($link, $sql10);
$DataLote = mysqli_fetch_row($rs_operation10);

$lote = $DataLote[0];
$tipo_pollo = $DataLote[2];

$sql11 = "SELECT lg.tipo, lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$lote' AND guia = '$idGuia'";
$rs_operation11 = mysqli_query($link, $sql11);
$DataTipo = mysqli_fetch_row($rs_operation11);

$tipo = $DataTipo[0];
$fechaBeneficio = $DataTipo[1];
$proveedor = $DataTipo[2];
$fechaVencimiento = $DataTipo[3];

if ($tipo_pollo == "DESPRESE") {
    $fechaBeneficioDesprese = $fechaBeneficio;
    $sql12 = "SELECT lote, SUM(kilos) FROM pesos_pollo WHERE guia = '$idGuia' AND tipo_pollo = 'ENTERO' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
    $rs_operation12 = mysqli_query($link, $sql12);
    if (mysqli_num_rows($rs_operation12) > 0) {
        $loteEntero = mysqli_fetch_row($rs_operation12)[0];
        $sql13 = "SELECT lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$loteEntero' AND guia = '$idGuia'";
        $rs_operation13 = mysqli_query($link, $sql13);
        $DataEntero = mysqli_fetch_row($rs_operation13);
        $fechaBeneficioEntero = $DataEntero[0];
    } else {
        $fechaBeneficioEntero = "";
    }
} else {
    $fechaBeneficioEntero = $fechaBeneficio;

    $sql14 = "SELECT lote, SUM(kilos) FROM pesos_pollo WHERE guia = '$idGuia' AND tipo_pollo = 'DESPRESE' GROUP BY lote ORDER BY kilos DESC LIMIT 1";
    $rs_operation14 = mysqli_query($link, $sql14);
    if (mysqli_num_rows($rs_operation14) > 0) {
        $loteDesprese = mysqli_fetch_row($rs_operation14)[0];
        $sql15 = "SELECT lg.fecha_beneficio, p.sede, lg.fecha_vencimiento FROM lotes_guias_desprese lg INNER JOIN proveedorpollo p ON lg.proveedor = p.id WHERE lote = '$loteDesprese' AND guia = '$idGuia'";
        $rs_operation15 = mysqli_query($link, $sql15);
        $DataDesprese = mysqli_fetch_row($rs_operation15);
        $fechaBeneficioDesprese = $DataDesprese[0];
    } else {
        $fechaBeneficioDesprese = "";
    }
}

$sql16 = "SELECT 
    pp.sede AS proveedor, 
    lgd.lote AS lote, 
    lgd.fecha_vencimiento AS fecha_vencimiento, 
    pep.tipo_pollo AS tipo_pollo,
    lgd.fecha_beneficio AS fecha_beneficio
FROM lotes_guias_desprese lgd 
INNER JOIN proveedorpollo pp ON lgd.proveedor = pp.id 
INNER JOIN pesos_pollo pep ON lgd.lote = pep.lote AND pep.guia = '$idGuia'
WHERE lgd.guia = '$idGuia'
GROUP BY pep.tipo_pollo, lgd.lote
ORDER BY proveedor ASC";

$rs_operation16 = mysqli_query($link, $sql16);

$sqlConcentracion = "SELECT concentracion FROM salmuera WHERE id = '$row[concentracion]'";
$rs_operationConcentracion = mysqli_query($link, $sqlConcentracion);
$concentracion = mysqli_fetch_row($rs_operationConcentracion)[0];

require('pdf/fpdf.php');
class PDF extends FPDF{}

$pdf = new PDF();

$pdf->AddPage('P', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->SetXY($pdf->GetX(), $pdf->GetY());
$pdf->Cell(35, 15, "", 1, 0, 'C');
$logo = "../assets/img/logo-mercamio.jpg";
$pdf->Image($logo, 12, 13, 31.5, 9.7);
$pdf->ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetXY($pdf->GetX() + 35, $pdf->GetY() - 10);
$pdf->multiCell(124, 5, utf8_decode("FORMATO DE DESPRESE Y MEJORAMIENTO"), 1, "C");
$pdf->SetXY($pdf->GetX() + 159, $pdf->GetY() - 5);
$pdf->Cell(31, 5, "     PAGINA " . $pdf->PageNo() . " de {nb}", 1, 1, 'C');
$pdf->Cell(35, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(31, 5, utf8_decode('AREA'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('FECHA DE CREACIÓN'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('FECHA DE REVISION'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('VERSIÓN'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('CODIGO'), 1, 0, 'C');
$pdf->ln();
$pdf->Cell(35, 5, '', 0, 0, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(31, 5, utf8_decode('PRODUCCIÓN'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('30/07/2024'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('30/07/2025'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('2'), 1, 0, 'C');
$pdf->Cell(31, 5, utf8_decode('FTO-PDA-12'), 1, 0, 'C');

$pdf->ln(8);
/* $pdf->SetXY($pdf->GetX() + 8, $pdf->GetY()); */
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(14, 5, utf8_decode("Empresa: "), 0, 0, '');
$pdf->Cell(24, 5, utf8_decode(utf8_decode($empresa[0])), "B", 0, 'C');
$pdf->Cell(6, 5, "", 0, 0, 'C');
$pdf->Cell(9, 5, utf8_decode("Sede: "), 0, 0, '');
$pdf->Cell(28, 5, utf8_decode(substr($empresa[1], 0, 16)), "B", 0, 'C');
$pdf->Cell(6, 5, "", 0, 0, 'C');
$pdf->Cell(16, 5, utf8_decode("Tipo Pollo: "), 0, 0, '');
$pdf->Cell(17, 5, utf8_decode($tipo), "B", 0, 'C');
$pdf->Cell(6, 5, "", 0, 0, 'C');
$pdf->Cell(7.5, 5, utf8_decode("Nro: "), 0, 0, '');
$pdf->Cell(11, 5, str_pad(utf8_decode($row['id']), 4, '0', STR_PAD_LEFT), "B", 0, 'C');
$pdf->Cell(6, 5, "", 0, 0, 'C');
$pdf->Cell(22.5, 5, utf8_decode("Fecha Proceso: "), 0, 0, '');
$pdf->Cell(17, 5, utf8_decode($row['fecha_registro']), "B", 0, 'C');
$pdf->Ln(8);
/* $pdf->Cell(22, 5, utf8_decode("Lote Planta: "), 0, 0, '');
$pdf->Cell(30, 5, utf8_decode($lote), "B", 0, 'C'); 
$pdf->Cell(2, 5, "", 0, 0, 'C');*/
/* $pdf->SetXY($pdf->GetX() + 21, $pdf->GetY()); */
$pdf->Cell(30, 5, utf8_decode("Nro Pollos Desprese: "), 0, 0, '');
$pdf->Cell(14, 5, utf8_decode($nroPolloDesprese), "B", 0, 'C');
$pdf->Cell(6.2, 5, "", 0, 0, 'C');
$pdf->Cell(31.5, 5, utf8_decode("Peso Inicial Desprese: "), 0, 0, '');
$pdf->Cell(14, 5, number_format(utf8_decode($pesoInicialDesprese), 2, ".", ","), "B", 0, 'C');
$pdf->Cell(6.2, 5, "", 0, 0, 'C');
$pdf->Cell(26.5, 5, utf8_decode("Nro Pollos Entero: "), 0, 0, '');
$pdf->Cell(14, 5, utf8_decode($nroPolloEntero), "B", 0, 'C');
$pdf->Cell(6.1, 5, "", 0, 0, 'C');
$pdf->Cell(27.5, 5, utf8_decode("Peso Inicial Entero: "), 0, 0, '');
$pdf->Cell(14, 5, number_format(utf8_decode($pesoInicialEntero), 2, ".", ","), "B", 0, 'C');
$pdf->Ln(10);
$pdf->Cell(49, 8, "", 1, 0, 'C');
$pdf->Cell(84.4, 4, utf8_decode("Peso por Desprese"), 1, 0, 'C');
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(51.6, 4, utf8_decode("Peso Producto con Proceso"), 1, 1, 'C');
$pdf->Cell(49, 8, "", 0, 0, 'C');
$pdf->Cell(84.4, 4, utf8_decode("TOTAL"), 1, 0, 'C');
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(51.6, 4, utf8_decode("TOTAL PROCESADO"), 1, 1, 'C');
$pdf->Cell(49, 8, utf8_decode("Item       Descripción"), 1, 0, '');
$pdf->Cell(20, 8, utf8_decode("Lote Planta"), 1, 0, 'C');
$pdf->Cell(12.8, 8, utf8_decode("Unids"), 1, 0, 'C');
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Kilos Brutos"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 94.7, $pdf->GetY() - 7);
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Cajas Base"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 107.6, $pdf->GetY() - 7);
$pdf->Cell(12.9, 8, utf8_decode("Cajas"), 1, 0, 'C');
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Kilos Netos"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 133.4, $pdf->GetY() - 7);
$pdf->Cell(5, 4, "", 0, 0, 'C');
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Kilos Brutos"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 151.3, $pdf->GetY() - 7);
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Cajas Base"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 164.2, $pdf->GetY() - 7);
$pdf->Cell(12.9, 8, utf8_decode("Cajas"), 1, 0, 'C');
$pdf->Cell(12.9, 8, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12.9, $pdf->GetY() + 1);
$pdf->MultiCell(12.9, 3, utf8_decode("Kilos Netos"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 190, $pdf->GetY() - 4);
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 8);
if ($items["059762"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059762", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("ALAS BLANCA MERCAMIO MARINADAS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059762"]) ? "" : $lotes["059762"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese;$unidadesTotalDesP += $nroPolloDesprese;
    $pdf->Cell(12.9, 10, number_format($items["059762"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059762"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059762"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059762"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059762"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059762"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059762"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059762"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059760"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059760", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PECHUGA BLANCA MERCAMIO MARINADA"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059760"]) ? "" : $lotes["059760"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese;$unidadesTotalDesP += $nroPolloDesprese;
    $pdf->Cell(12.9, 10, number_format($items["059760"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059760"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059760"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059760"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059760"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059760"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059760"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059760"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059761"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059761", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PERNIL BLANCO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059761"]) ? "" : $lotes["059761"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese * 2, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese * 2;$unidadesTotalDesP += $nroPolloDesprese * 2;
    $pdf->Cell(12.9, 10, number_format($items["059761"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059761"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059761"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059761"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059761"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059761"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059761"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059761"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059759"]["kg"]) {
    $pdf->Cell(11, 10, "059759", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("POLLO BLANCO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059759"]) ? "" : $lotes["059759"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloEntero, 1, 0, 'C');
    $unidadesTotalDesP += $nroPolloEntero;
    $pdf->Cell(12.9, 10, number_format($items["059759"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059759"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059759"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059759"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059759"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059759"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059759"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059759"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059758"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059758", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("ALAS POLLO CAMPO MP MARINADAS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059758"]) ? "" : $lotes["059758"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese;$unidadesTotalDesP += $nroPolloDesprese;
    $pdf->Cell(12.9, 10, number_format($items["059758"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059758"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059758"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059758"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059758"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059758"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059758"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059758"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059756"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059756", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PECHUGA CAMPO MERCAMIO MARINADA"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059756"]) ? "" : $lotes["059756"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese;$unidadesTotalDesP += $nroPolloDesprese;
    $pdf->Cell(12.9, 10, number_format($items["059756"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059756"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059756"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059756"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059756"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059756"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059756"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059756"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059757"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059757", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("PERNIL CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059757"]) ? "" : $lotes["059757"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloDesprese * 2, 1, 0, 'C');
    $unidadesTotalDes += $nroPolloDesprese * 2;$unidadesTotalDesP += $nroPolloDesprese * 2;
    $pdf->Cell(12.9, 10, number_format($items["059757"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059757"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059757"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059757"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059757"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059757"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059757"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059757"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["059755"]["kg"] != 0) {
    $pdf->Cell(11, 10, "059755", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("POLLO CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["059755"]) ? "" : $lotes["059755"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, $nroPolloEntero, 1, 0, 'C');
    $unidadesTotalDesP += $nroPolloEntero;
    $pdf->Cell(12.9, 10, number_format($items["059755"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059755"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["059755"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["059755"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059755"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059755"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["059755"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["059755"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["042788"]["kg"] != 0) {
    $pdf->Cell(11, 10, "042788", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("C/MUSLO CAMPO MERCAMIO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["042788"]) ? "" : $lotes["042788"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, "", 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["042788"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["042788"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["042788"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["042788"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["042788"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["042788"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["042788"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["042788"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["042789"]["kg"] != 0) {
    $pdf->Cell(11, 10, "042789", 1, 0, 'C');
    $pdf->Cell(38, 10, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 2);
    $pdf->MultiCell(38, 3, utf8_decode("MUSLO CAMPO MERCAMIO MARINADOS"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 4, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 10, empty($lotes["042789"]) ? "" : $lotes["042789"], 1, 0, 'C');
    $pdf->Cell(12.8, 10, "", 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["042789"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["042789"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $items["042789"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($items["042789"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["042789"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["042789"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, $itemsM["042789"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 10, number_format($itemsM["042789"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
if ($items["045401"]["kg"]) {
    $pdf->Ln(2);
    $pdf->Cell(11, 14, "045401", 1, 0, 'C');
    $pdf->Cell(38, 14, "", 1, 0, 'C');
    $pdf->SetXY($pdf->GetX() - 38, $pdf->GetY() + 1);
    $pdf->MultiCell(38, 3, utf8_decode("BOMBONES POLLO CAMPO MARINADO"), 0, 'C');
    $pdf->SetXY($pdf->GetX() + 11, $pdf->GetY() + 1);
    $pdf->Cell(38, 5, "LOTE:_______________", 0, 0, '');
    $pdf->SetXY($pdf->GetX() - 45, $pdf->GetY() - 8);
    $pdf->Cell(45, 5, "", 0, 0, 'C');
    $pdf->Cell(20, 14, empty($lotes["045401"]) ? "" : $lotes["045401"], 1, 0, 'C');
    $pdf->Cell(12.8, 14, "", 1, 0, 'C');
    $pdf->Cell(12.9, 14, number_format($items["045401"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 14, $items["045401"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 14, $items["045401"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 14, number_format($items["045401"]["kgNeto"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(5, 4, "", 0, 0, 'C');
    $pdf->Cell(12.9, 14, number_format($itemsM["045401"]["kg"], 2, ".", ","), 1, 0, 'C');
    $pdf->Cell(12.9, 14, $itemsM["045401"]["cajasBase"], 1, 0, 'C');
    $pdf->Cell(12.9, 14, $itemsM["045401"]["cajas"], 1, 0, 'C');
    $pdf->Cell(12.9, 14, number_format($itemsM["045401"]["kgNeto"], 2, ".", ","), 1, 1, 'C');
}
$pdf->Ln(2);
$pdf->Cell(190, 14, "", 1, 1, 'C');
$pdf->SetXY($pdf->GetX(), $pdf->GetY() - 14);
$pdf->Cell(47, 7, "SUBTOTAL POLLO DESPRESADO", 0, 0, 'C');
$pdf->Cell(22, 7, "", 0, 0, 'C');
$pdf->Cell(12.8, 7, $unidadesTotalDes, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($PesoTotalDes, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(12.9, 7, $cajasBaseTotalDes, 0, 0, 'C');
$pdf->Cell(12.9, 7, $CajaTotalDes, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($kilosNetoDes, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(5, 7, "", 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($PesoTotalMej, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(12.9, 7, $cajasBaseTotalMej, 0, 0, 'C');
$pdf->Cell(12.9, 7, $CajaTotalMej, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($kilosNetoMej, 2, ".", ","), 0, 1, 'C');
$pdf->Cell(21, 7, "TOTAL BRUTO", 0, 0, 'C');
$pdf->Cell(48, 7, "", 0, 0, 'C');
$pdf->Cell(12.8, 7, $unidadesTotalDesP, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($PesoTotalDesP, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(12.9, 7, $cajasBaseTotalDesP, 0, 0, 'C');
$pdf->Cell(12.9, 7, $CajaTotalDesP, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($kilosNetoDesP, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(5, 7, "", 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($PesoTotalMejP, 2, ".", ","), 0, 0, 'C');
$pdf->Cell(12.9, 7, $cajasBaseTotalMejP, 0, 0, 'C');
$pdf->Cell(12.9, 7, $CajaTotalMejP, 0, 0, 'C');
$pdf->Cell(12.9, 7, number_format($kilosNetoMejP, 2, ".", ","), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(45, 5, utf8_decode("Emisión de la guia") , 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22.5, 5, utf8_decode("Hora Inicial") , 1, 0, 'C');
$pdf->Cell(22.5, 5, $hora , 1, 1, 'C');
$pdf->Cell(22.5, 5, utf8_decode("Hora Final") , 1, 0, 'C');
$pdf->Cell(22.5, 5, $horaActual , 1, 1, 'C');
$pdf->Cell(22.5, 5, utf8_decode("Tiempo Total") , 1, 0, 'C');
$pdf->Cell(22.5, 5, calcular_tiempo($hora, $horaActual) , 1, 1, 'C');

$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY() - 20);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(23, 5, "Item", 1, 0, 'C');
$pdf->Cell(49, 5, "Proveedor", 1, 0, 'C');
$pdf->Cell(25, 5, "Lote", 1, 0, 'C');
$pdf->Cell(25, 5, "Fecha Benefecio", 1, 0, 'C');
$pdf->Cell(18, 5, "Fecha Vcto", 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);

while ($row16 = mysqli_fetch_assoc($rs_operation16)) {
    $pdf->SetXY($pdf->GetX() + 50, $pdf->GetY());
    if ($row16["tipo_pollo"] == "DESPRESE") {
        $pdf->Cell(23, 5, "Pollo Desprese", 1, 0, 'L');
    } else {
        $pdf->Cell(23, 5, "Pollo Entero", 1, 0, 'L');
    }
    
    $pdf->Cell(49, 5, substr(utf8_decode($row16["proveedor"]), 0, 27)  , 1, 0, 'L');
    $pdf->Cell(25, 5, utf8_decode($row16["lote"]), 1, 0, 'C');
    if ($row13["fecha_beneficio"] == "0000-00-00") {
        $row13["fecha_beneficio"] = "";
    }
    $pdf->Cell(25, 5, $row16["fecha_beneficio"], 1, 0, 'C');
    if ($row16["fecha_vencimiento"] == '0000-00-00') {
        $row16["fecha_vencimiento"] = "";
    }
    $pdf->Cell(18, 5, utf8_decode($row16["fecha_vencimiento"]), 1, 1, 'C');
}


$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY() + 5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(23, 5, "Item", 1, 0, 'C');
$pdf->Cell(74, 5, "Proveedor", 1, 0, 'C');
$pdf->Cell(25, 5, "Lote", 1, 0, 'C');
$pdf->Cell(18, 5, "Fecha Vcto", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(23, 5, "Hielo", 1, 0, 'L');
$pdf->Cell(74, 5, substr(utf8_decode($row['proveedor_hielo']), 0, 27) , 1, 0, 'L');
$pdf->Cell(25, 5, utf8_decode($row['lote_hielo']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($row['fecha_venci_hielo']), 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY());
$pdf->Cell(23, 5, "Empaque", 1, 0, 'L');
$pdf->Cell(74, 5, substr(utf8_decode($row['proveedor_empaque']), 0, 27), 1, 0, 'L');
$pdf->Cell(25, 5, utf8_decode($row['lote_empaque']), 1, 0, 'C');
$pdf->Cell(18, 5, 'N/A', 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY());
$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(23, 5, "Fabricante Empaque", 1, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(74, 5, substr(utf8_decode($row['fabricante_empaque']), 0, 27) , 1, 0, 'L');
$pdf->Cell(25, 5, "N/A", 1, 0, 'C');
$pdf->Cell(18, 5, "N/A", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 50, $pdf->GetY());
$pdf->Cell(23, 5, "Salmuera", 1, 0, 'L');
$pdf->Cell(74, 5, substr(utf8_decode($row['proveedor_salmuera']), 0, 27) , 1, 0, 'L');
$pdf->Cell(25, 5, utf8_decode($row['lote_salmuera']), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode($row['fecha_venci_salmuera']), 1, 1, 'C');

$pdf->SetXY($pdf->GetX() + 146, $pdf->GetY());
$pdf->Cell(43, 5, "Concentracion Salmuera: " . $concentracion . "%", 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(80, 5, "PRODUCTO QUE NO LLEVA PROCESO", 0, 1, 'C');
$pdf->Cell(32, 5, "", 1, 0, 'C');
$pdf->Cell(48, 5, utf8_decode("TOTAL"), 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 5, "Item", 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode("Descripción"), 1, 0, 'C');
$pdf->Cell(16, 5, utf8_decode("Unids"), 1, 0, 'C');
$pdf->Cell(16, 5, utf8_decode("Kilos"), 1, 0, 'C');
$pdf->Cell(16, 5, utf8_decode("Cajas"), 1, 1, 'C');
$pdf->Cell(12, 5, "059758", 1, 0, 'C');
$pdf->Cell(20, 5,utf8_decode("COSTILLAR"), 1, 0, 'C');
$pdf->Cell(16, 5, "", 1, 0, 'C');
$pdf->Cell(16, 5, "", 1, 0, 'C');
$pdf->Cell(16, 5, "", 1, 1, 'C');
$pdf->Cell(12, 13, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12, $pdf->GetY());
$pdf->Cell(12, 6.5, "059758", 0, 0, 'C');
$pdf->SetXY($pdf->GetX() - 12, $pdf->GetY() + 6.5);
$pdf->Cell(12, 6.5, "059758", 0, 0, 'C');
$pdf->SetXY($pdf->GetX(), $pdf->GetY() - 6.5);
$pdf->Cell(20, 13, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 20, $pdf->GetY());
$pdf->MultiCell(20, 3.25, "Costillar Ala Mercamio", 0, 'C');
$pdf->SetXY($pdf->GetX() + 32, $pdf->GetY() - 6.5);
$pdf->Cell(16, 6.5, "", 1, 0, 'C');
$pdf->Cell(16, 6.5, "", 1, 0, 'C');
$pdf->Cell(16, 6.5, "", 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 12, $pdf->GetY());
$pdf->Cell(20, 6.5, "Punta de Ala", 0, 0, 'C');
$pdf->Cell(16, 6.5, "", 1, 0, 'C');
$pdf->Cell(16, 6.5, "", 1, 0, 'C');
$pdf->Cell(16, 6.5, "", 1, 1, 'C');


$pdf->SetXY($pdf->GetX() + 86, $pdf->GetY() - 28);
$pdf->Cell(19, 10, "", 1, 0, 'C');
$pdf->SetXY($pdf->GetX() - 19, $pdf->GetY());
$pdf->MultiCell(19, 5, utf8_decode("Temperatura Salmuera"), 0, 'C');
$pdf->SetXY($pdf->GetX() + 105, $pdf->GetY() - 10);
$pdf->Cell(10, 5, "Hora", 1, 0, 'C');
$pdf->Cell(15, 5, $row["Hora1"], 1, 0, 'C');
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->Cell(10, 5, "Hora", 1, 0, 'C');
$pdf->Cell(15, 5, $row["Hora2"], 1, 0, 'C');
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->Cell(10, 5, "Hora", 1, 0, 'C');
$pdf->Cell(15, 5, $row["Hora3"], 1, 1, 'C');
$pdf->SetXY($pdf->GetX() + 105, $pdf->GetY());
$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(10, 5, utf8_decode("Temp °C"), 1, 0, 'C');
if (!empty($row["Temp1"])) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, utf8_decode($row["Temp1"] . "°"), 1, 0, 'C');
}else {
    $pdf->Cell(15, 5, "", 1, 0, 'C');
}
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(10, 5, utf8_decode("Temp °C"), 1, 0, 'C');
if (!empty($row["Temp2"])) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, utf8_decode($row["Temp2"] . "°"), 1, 0, 'C');
}else {
    $pdf->Cell(15, 5, "", 1, 0, 'C');
}
$pdf->Cell(5, 5, "", 0, 0, 'C');
$pdf->SetFont('Arial', '', 6.5);
$pdf->Cell(10, 5, utf8_decode("Temp °C"), 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 5, utf8_decode($row["Temp3"] . "°"), 1, 1, 'C');

// 190
$pdf->SetXY($pdf->GetX() + 38.25, $pdf->GetY() + 25);
$firma = "../assets/img/firmas/".$row["responsable"].".jpg";
if(file_exists($firma)){
    // 14.25
    $pdf->Image($firma, $pdf->GetX(), $pdf->GetY(), 31.5, 9.7);
}else {
    $pdf->Cell(31.5, 9.7, "", 0, 0, 'C');
}
$pdf->SetXY($pdf->GetX() + 84, $pdf->GetY());
$firma = "../assets/img/firmas/".$responsable[1].".jpg";
if(file_exists($firma)){
    $pdf->Image($firma, $pdf->GetX(), $pdf->GetY(), 31.5, 9.7);    
} else {
    $pdf->Cell(31.5, 9.7, "", 0, 0, 'C');
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY($pdf->GetX() + 24, $pdf->GetY());
$pdf->Cell(60, 5, utf8_decode($row["responsable_desprese"]), "T", 0, 'C');
$pdf->SetXY($pdf->GetX() + 24, $pdf->GetY());
$pdf->Cell(60, 5, utf8_decode($responsable[0]), "T", 1, 'C');
$pdf->SetXY($pdf->GetX() + 24, $pdf->GetY());
$pdf->Cell(60, 5, "Responsable Pesos Iniciales y Finales de Desprese" , 0, 0, 'C');
$pdf->SetXY($pdf->GetX() + 24, $pdf->GetY());
$pdf->Cell(60, 5, "Responsable Pesos Producto Marinado" , 0, 0, 'C');

ob_end_clean();
$pdf->Output('formato_desprese.pdf', 'I'); // 'D' para descargar, 'I' para mostrar en el navegador
?>