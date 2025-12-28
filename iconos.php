<?php
// Include required files
include('conectarTabla.php');
$configuracion = cargarConfig();
$request = $_REQUEST;

// Define columns
$col = array(
    0 => 'nombre_archivo',
    1 => 'vista_previa',
    2 => 'acciones'
);

// Get all SVG files from the directory
$iconsDir = 'iconos/';
$svgFiles = glob($iconsDir . '*.svg');

$totalData = count($svgFiles);
$totalFilter = $totalData;

$data = array();

// Set color based on configuration
if($configuracion[0]['colorIndicador'] == 'purple'){$color ='#673ab75e';}
elseif($configuracion[0]['colorIndicador'] == 'blue'){$color ='#2196f36b';}
elseif($configuracion[0]['colorIndicador'] == 'green'){$color ='#4caf5066';}
elseif($configuracion[0]['colorIndicador'] == 'orange'){$color ='#ff980038';}
elseif($configuracion[0]['colorIndicador'] == 'red'){$color ='#f4433661';}
elseif($configuracion[0]['colorIndicador'] == 'rose'){$color ='#00968863';}

foreach($svgFiles as $file) {
    $filename = basename($file);
    $btns = '';
    $subdata = array();
    
    // Add filename
    $subdata[] = $filename;
    
    // Add SVG preview with color from configuration
    $svgContent = file_get_contents($file);
    $subdata[] = '<div style="width: 50px; height: 50px; color:'.$color.';">' . $svgContent . '</div>';
    
    // Add action buttons
    $btns .= '<a class="btn btn-simple btn-info btn-icon like" style="z-index: 0;color:#ff9800cf" data-target="#modalTutorial" data-toggle="modal" onclick="editarIcono(\'' . $filename . '\')"><i class="fa fa-edit"></i></a>';
    $btns .= '<a class="btn btn-simple btn-info btn-icon like" style="z-index: 0;color:#f44336" onclick="eliminarIcono(\'' . $filename . '\')"><i class="fa fa-times-circle"></i></a>';
    
    $subdata[] = $btns;
    $data[] = $subdata;
}

$json_data = array(
    "draw" => intval($request['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFilter),
    "data" => $data
);

echo json_encode($json_data);
?> 