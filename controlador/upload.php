<?php
error_reporting(0);
function agregarFoto($datosFoto) {
    /* include('../config.php');

    $sql = "INSERT INTO fotos (user_id, file_name) VALUES (
        '".$datosFoto['user_id']."',
        '".$datosFoto['file_name']."'
    )";

    $rs_operacion = mysqli_query($link, $sql);

    if (!$rs_operacion) {
        return array("status" => "error", "message" => "Error al ejecutar la consulta: " . mysqli_error($link));
    } else {
        return array("status" => "success", "id" => mysqli_insert_id($link));
    } */
}

$uploadDir = 'uploads/';
$response = ['status' => 'error', 'message' => ''];

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$userId = $_POST['user-id'];

if (!empty($_FILES['photos'])) {
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['photos']['name'][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            // Llamar a la función agregarFoto para guardar en la base de datos
            $datosFoto = ['user_id' => $userId, 'file_name' => $fileName];
            //$resultado = agregarFoto($datosFoto);
            if ($resultado['status'] === 'success') {
                $response['status'] = 'success';
                $response['message'] = 'Fotos guardadas exitosamente';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al guardar las fotos: ' . $resultado['message'];
                break;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al mover el archivo';
            break;
        }
    }
}

echo json_encode($response);
?>
