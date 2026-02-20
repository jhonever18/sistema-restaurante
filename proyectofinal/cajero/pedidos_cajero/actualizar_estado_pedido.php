<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

$input = json_decode(file_get_contents("php://input"), true);

$pedido_id = $input["pedido_id"];
$estped_id = $input["estped_id"];

// Verificar estado actual del pedido
$sqlVerificar = "SELECT estped_id FROM pedidos WHERE pedido_id = $pedido_id";
$resVerificar = mysqli_query($connect, $sqlVerificar);
$rowVerificar = mysqli_fetch_assoc($resVerificar);
$estadoActual = $rowVerificar['estped_id'] ?? null;

// ❌ Si está cancelado, no se puede cambiar a ningún estado
if ($estadoActual == 4) {
    echo json_encode([
        'success' => false,
        'error' => 'Este pedido está cancelado y no puede modificarse.'
    ]);
    exit;
}

// Obtener el nombre del nuevo estado
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
?>

