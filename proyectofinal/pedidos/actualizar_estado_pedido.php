<?php
include("../conexion/conectarBD.php");

$input = json_decode(file_get_contents("php://input"), true);

$pedido_id = $input["pedido_id"];
$estped_id = $input["estped_id"];

// Obtener nombre del estado
$sqlEstado = "SELECT estped_desc FROM estado_pedido WHERE estped_id = $estped_id";
$resEstado = mysqli_query($connect, $sqlEstado);
$rowEstado = mysqli_fetch_assoc($resEstado);
$nombreEstado = $rowEstado['estped_desc'] ?? 'Desconocido';

// Actualizar el estado del pedido
$sql = "UPDATE pedidos SET estped_id = $estped_id WHERE pedido_id = $pedido_id";
$resultado = mysqli_query($connect, $sql);

if ($resultado) {
    echo json_encode(['success' => true, 'nuevo_estado' => $nombreEstado]);
} else {
    echo json_encode(['success' => false]);
}
