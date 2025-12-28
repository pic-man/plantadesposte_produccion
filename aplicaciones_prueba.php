<?php 

// Include required files
include('conectarTabla.php');

// Define constants for column mapping
define('COLUMNS', [
    'nombre_aplicacion',
    'icono_aplicacio',
    'url_aplicacion',
    'siglas_aplicacion',
    'id_aplicaciones'
]);

// Get configuration
$configuracion = cargarConfig();
if (!$configuracion) {
    die(json_encode(['error' => 'Configuration not found']));
}

// Sanitize input
$empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_STRING);
$request = filter_input_array(INPUT_GET, [
    'draw' => FILTER_VALIDATE_INT,
    'start' => FILTER_VALIDATE_INT,
    'length' => FILTER_VALIDATE_INT,
    'search' => [
        'value' => FILTER_SANITIZE_STRING,
        'regex' => FILTER_SANITIZE_STRING
    ]
]);

// Define color mapping
$colorMap = [
    'purple' => '#673ab75e',
    'blue' => '#2196f36b',
    'green' => '#4caf5066',
    'orange' => '#ff980038',
    'red' => '#f4433661',
    'rose' => '#00968863'
];

$color = $colorMap[$configuracion[0]['colorIndicador']] ?? '#673ab75e';

// Prepare and execute query
$sql = "SELECT id_aplicaciones, nombre_aplicacion, icono_aplicacio, url_aplicacion, siglas_aplicacion 
        FROM aplicaciones_prueba";

try {
    $query = mysqli_query($con, $sql);
    if (!$query) {
        throw new Exception(mysqli_error($con));
    }
    
    $totalData = mysqli_num_rows($query);
    $totalFilter = $totalData;
    
    $data = [];
    while ($row = mysqli_fetch_array($query)) {
        $subdata = [];
        $subdata[] = $row[0] . $configuracion[0]['colorIndicador'];
        $subdata[] = htmlspecialchars($row[1]);
        $subdata[] = '<i class="' . htmlspecialchars($row[2]) . '" style="font-size: 21px;color:' . $color . ';"></i>';
        
        // Generate action buttons
        $btns = '';
        $btns .= '<a class="btn btn-simple btn-info btn-icon like" style="z-index: 0;color:#ff9800cf" 
                 data-target="#modalTutorial" data-toggle="modal" 
                 onclick="buscarAdmApp(' . (int)$row[0] . ')">
                 <i class="fa fa-edit"></i></a>';
        $btns .= '<a class="btn btn-simple btn-info btn-icon like" style="z-index: 0;color:#f44336" 
                 onclick="eliminarAdmApp(' . (int)$row[0] . ')">
                 <i class="fa fa-times-circle"></i></a>';
        
        $subdata[] = $btns;
        $data[] = $subdata;
    }
    
    // Prepare response
    $json_data = [
        "draw" => intval($request['draw']),
        "recordsTotal" => $totalData,
        "recordsFiltered" => $totalFilter,
        "data" => $data
    ];
    
    echo json_encode($json_data);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 