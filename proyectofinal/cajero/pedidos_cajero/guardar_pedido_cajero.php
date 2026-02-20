<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once(__DIR__ . "/../../conexion/conectarBD.php");
header("Content-Type: application/json");

// Leer JSON desde el body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validar datos mínimos
if (
    !$data || 
    !isset($data['metodo']) || 
    !isset($data['carrito']) || 
    !isset($data['total']) || 

    !isset($data['nombre_cliente']) || 
    !isset($data['documento_cliente'])
) {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    exit;
}

$nombre     = $data['nombre_cliente'];
$documento  = $data['documento_cliente'];
$metodo     = intval($data['metodo']);
$total      = floatval($data['total']);
$carrito    = $data['carrito'];
$fecha      = date("Y-m-d H:i:s");
$correo     = !empty($data['correo_cliente']) ? $data['correo_cliente'] : null;
$telefono = !empty($data['telefono_cliente']) ? $data['telefono_cliente'] : null;


// Verificar si el cliente ya existe por documento
$sqlBuscar = "SELECT cliente_id FROM clientes WHERE cli_documento = ?";
$stmt = $connect->prepare($sqlBuscar);
$stmt->bind_param("s", $documento);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    $cliente_id = $cliente['cliente_id'];
} else {
    $sqlInsert = "INSERT INTO clientes (cli_nombre, cli_documento, cli_telefono, cli_correo) VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($sqlInsert);
    $stmt->bind_param("ssss", $nombre, $documento, $telefono, $correo);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Error al guardar cliente"]);
        exit;
    }
    $cliente_id = $stmt->insert_id;
}
$stmt->close();

// Insertar pedido
$sqlPedido = "INSERT INTO pedidos (cliente_id, metopago_id, pedido_fecha, pedido_valor_pagar) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($sqlPedido);
$stmt->bind_param("iiss", $cliente_id, $metodo, $fecha, $total);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => "Error al guardar pedido"]);
    exit;
}
$pedido_id = $stmt->insert_id;
$stmt->close();

// Insertar detalles
$sqlDetalle = "INSERT INTO pedido_detalles (pedido_id, plato_nombre, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($sqlDetalle);
foreach ($carrito as $item) {
    $nombre_plato = $item['nombre'];
    $cantidad     = intval($item['cantidad']);
    $precio       = floatval($item['precio']);
    $stmt->bind_param("isid", $pedido_id, $nombre_plato, $cantidad, $precio);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Error al guardar detalle"]);
        exit;
    }
}
$stmt->close();

echo json_encode(["success" => true, "pedido_id" => $pedido_id]);
