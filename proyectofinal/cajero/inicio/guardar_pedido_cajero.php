<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

include_once("../../conexion/conectarBD.php");
session_start();

try {
    $json = file_get_contents("php://input");
    if (!$json) throw new Exception("No se recibió contenido JSON.");

    $data = json_decode($json, true);
    if (!$data) throw new Exception("Error al decodificar JSON.");

    $metodoPago = $data['metodo'] ?? null;
    $nombre = trim($data['nombre_cliente'] ?? '');
    $documento = trim($data['documento_cliente'] ?? '');
    $telefono = trim($data['telefono_cliente'] ?? '');
    $correo = trim($data['correo_cliente'] ?? '') ?: null; // ✅ Si está vacío, se guarda como NULL
    $total = $data['total'] ?? 0;
    $carrito = $data['carrito'] ?? [];

    if (!$metodoPago || !$nombre || !$documento || $total <= 0 || empty($carrito)) {
        throw new Exception("Campos faltantes o datos inválidos.");
    }

    // 1. Insertar cliente (apellido y teléfono pueden omitirse, correo puede ser NULL)
    $sqlCliente = "INSERT INTO clientes (cli_nombre, cli_documento, cli_telefono, cli_correo) VALUES (?, ?, ?, ?)";
    $stmtCliente = $connect->prepare($sqlCliente);
    $stmtCliente->bind_param("ssss", $nombre, $documento, $telefono, $correo);
    $stmtCliente->execute();
    $clienteId = $stmtCliente->insert_id;

    // 2. Obtener ID del cajero desde sesión
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) throw new Exception("Sesión inválida. No se encontró user_id.");

    // 3. Insertar pedido
    $sqlPedido = "INSERT INTO pedidos (metopago_id, user_id, pedido_fecha, pedido_valor_pagar, cliente_id, estped_id)
                  VALUES (?, ?, NOW(), ?, ?, 1)";
    $stmtPedido = $connect->prepare($sqlPedido);
    $stmtPedido->bind_param("iidi", $metodoPago, $userId, $total, $clienteId);
    $stmtPedido->execute();
    $pedidoId = $stmtPedido->insert_id;

    // 4. Insertar los detalles del carrito
    $sqlDetalle = "INSERT INTO pedido_detalles (pedido_id, plato_nombre, cantidad, precio_unitario)
                   VALUES (?, ?, ?, ?)";
    $stmtDetalle = $connect->prepare($sqlDetalle);

    foreach ($carrito as $item) {
        $nombrePlato = $item['nombre'];
        $cantidad = $item['cantidad'];
        $precioUnitario = $item['precio'];
        $stmtDetalle->bind_param("isid", $pedidoId, $nombrePlato, $cantidad, $precioUnitario);
        $stmtDetalle->execute();
    }

    // 5. Crear factura
    $descFactura = "Factura generada para el pedido N° $pedidoId";
    $sqlFactura = "INSERT INTO facturas (pedido_id, factura_desc) VALUES (?, ?)";
    $stmtFactura = $connect->prepare($sqlFactura);
    $stmtFactura->bind_param("is", $pedidoId, $descFactura);
    $stmtFactura->execute();

    echo json_encode(["success" => true, "pedido_id" => $pedidoId]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
