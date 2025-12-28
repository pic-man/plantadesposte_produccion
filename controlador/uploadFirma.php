<?php

$uploadDir = '../assets/img/firmas/';
$response = ['status' => 'error', 'message' => ''];

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}else{
    chmod($uploadDir, 0777);
}

if (!empty($_FILES['photos'])) {
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['photos']['name'][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            // Llamar a la función agregarFoto para guardar en la base de datos
            $datosFoto = ['file_name' => $fileName];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al mover el archivo';
            break;
        }
    }
}else{
    $response['message'] = 'no pase de files';
}

echo json_encode($response);