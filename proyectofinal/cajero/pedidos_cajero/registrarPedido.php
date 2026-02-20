<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");
header("Content-Type: application/json");

// Obtener datos y asegurar tipos
$cliente_id    = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : null;
$user_id       = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$metopago_id   = isset($_POST['metopago_id']) ? intval($_POST['metopago_id']) : null;
$valor_pagar   = isset($_POST['pedido_valor_pagar']) ? floatval($_POST['pedido_valor_pagar']) : null;

// Validar que no haya campos vacíos
if (!$cliente_id || !$user_id || !$metopago_id || !$valor_pagar) {
    echo json_encode(["success" => false, "error" => "Faltan datos requeridos"]);
    exit;
}

// Definir estado inicial (6 = pendiente)
$estado_id = 6;

// Fecha y hora actual
$fecha_actual = date("Y-m-d H:i:s");

// Insertar el pedido
$sql = "INSERT INTO pedidos (cliente_id, user_id, metopago_id, pedido_valor_pagar, pedido_fecha, esta_id) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "iiidsi", $cliente_id, $user_id, $metopago_id, $valor_pagar, $fecha_actual, $estado_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true, "mensaje" => "Pedido registrado con éxito"]);
} else {
    echo json_encode(["success" => false, "error" => "No se pudo registrar el pedido", "detalle" => mysqli_error($connect)]);
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
