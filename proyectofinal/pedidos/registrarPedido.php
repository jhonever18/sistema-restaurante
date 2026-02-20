<?php
include("../conexion/conectarBD.php");

// Validar que lleguen los datos correctamente
$cliente_id = $_POST['cliente_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$metopago_id = $_POST['metopago_id'] ?? null;
$valor_pagar = $_POST['pedido_valor_pagar'] ?? null;

// Verificar que no haya campos vacíos
if (!$cliente_id || !$user_id || !$metopago_id || !$valor_pagar) {
    echo json_encode(["error" => "Faltan datos requeridos"]);
    exit;
}

// Definir estado inicial (6 = pendiente)
$estado_id = 6;

// Obtener la fecha actual
$fecha_actual = date("Y-m-d H:i:s");

// Insertar el pedido en la base de datos
$sql = "INSERT INTO pedidos (cliente_id, user_id, metopago_id, pedido_valor_pagar, pedido_fecha, esta_id) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "iiissi", $cliente_id, $user_id, $metopago_id, $valor_pagar, $fecha_actual, $estado_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true, "mensaje" => "Pedido registrado con éxito"]);
} else {
    echo json_encode(["error" => "No se pudo registrar el pedido"]);
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
