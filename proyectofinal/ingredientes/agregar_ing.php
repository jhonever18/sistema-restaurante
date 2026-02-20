<?php
session_start();
$connect = require_once("../conexion/conectarBD.php");

if (!$connect) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'agregar') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $cantidad = floatval($_POST['cantidad'] ?? 0);
        $unidad_id = intval($_POST['unidad'] ?? 0);
        $precio = floatval($_POST['precio'] ?? 0);
        $estado = intval($_POST['estado'] ?? 1);

        if ($nombre && $cantidad > 0 && $unidad_id > 0) {
            $stmt = $connect->prepare("INSERT INTO ingredientes 
                (ing_nombre, ing_desc, ing_cantidad, unidad_id, ing_precio, esta_id)
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdidssi", $nombre, $descripcion, $cantidad, $unidad_id, $precio, $estado);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => '✅ Ingrediente agregado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => '❌ Error al insertar el ingrediente.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => '⚠️ Datos incompletos o inválidos.']);
        }
        $connect->close();
        exit;
    

    }

    // RESPUESTA FINAL
    header('Content-Type: application/json');
    echo json_encode($response);
    $connect->close();
    exit;
}

?>
