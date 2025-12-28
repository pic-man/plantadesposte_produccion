<?php
include('../config.php');

$idGuia = $_POST["id"] ? $_POST["id"] : "";

$sql = "SELECT status FROM desprese_items WHERE guia = '$idGuia' LIMIT 1";
$query = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($query);

echo json_encode(['status' => $row['status'] ?? 0]);
?> 