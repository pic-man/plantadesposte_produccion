<?php
session_start();

if (isset($_POST['accion']) && $_POST['accion'] == 'resetearOjo') {
    $_SESSION["ojo"] = "N";
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
?> 