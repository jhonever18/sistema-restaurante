<?php
session_start();
require_once("../conexion/conectarBD.php");
header("Content-Type: application/json");

// Validar sesión
if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(["success" => false, "error" => "No autenticado"]);
    exit;
}

// Leer el JSON del body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['metodo']) || !isset($data['carrito']) || !isset($data['total'])) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$metodo_id = intval($data['metodo']);
$total = $data['total']; // Guardado como string por la estructura de la tabla
$carrito = $data['carrito'];
$fecha = date("Y-m-d H:i:s");

// Insertar el pedido en la tabla `pedidos`
$sql = "INSERT INTO pedidos (cliente_id, metopago_id, pedido_fecha, pedido_valor_pagar) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($sql);
$stmt->bind_param("iiss", $cliente_id, $metodo_id, $fecha, $total);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => "Error al insertar pedido"]);
    exit;
}

$pedido_id = $stmt->insert_id;
$stmt->close();

// Insertar los detalles del pedido
$detalle_sql = "INSERT INTO pedido_detalles (pedido_id, plato_nombre, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
$detalle_stmt = $connect->prepare($detalle_sql);

foreach ($carrito as $item) {
    $nombre = $item['nombre'];
    $cantidad = intval($item['cantidad']);
    $precio = floatval($item['precio']);

    $detalle_stmt->bind_param("isid", $pedido_id, $nombre, $cantidad, $precio);
    
    if (!$detalle_stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Error al guardar detalles"]);
        exit;
    }
}

$detalle_stmt->close();

echo json_encode(["success" => true, "pedido_id" => $pedido_id]);
