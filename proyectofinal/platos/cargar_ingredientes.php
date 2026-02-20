<?php
require_once("../conexion/conectarBD.php");
header("Content-Type: application/json");

$query = "SELECT ing_id, ing_nombre FROM ingredientes WHERE ing_cantidad > 0 AND esta_id = 1";

$resultado = mysqli_query($connect, $query);

$ingredientes = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $ingredientes[] = $fila;
}

error_log("Datos de ingredientes cargados: " . json_encode($ingredientes)); // <--- AÑADE ESTA LÍNEA
echo json_encode($ingredientes);
?>