<?php

$connect = require_once("../conexion/conectarBD.php");

if (!$connect) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

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
            $stmt->bind_param("ssdiid", $nombre, $descripcion, $cantidad, $unidad_id, $precio, $estado);

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

   if ($action === 'editar') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cantidad = floatval($_POST['cantidad'] ?? 0);
    $unidad_id = intval($_POST['unidad'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);

    // Validar que todos los datos obligatorios estén presentes
    if ($id > 0 && $nombre && $unidad_id > 0 && $cantidad >= 0 && $precio >= 0) {
        $stmt = $connect->prepare("UPDATE ingredientes SET ing_nombre = ?, ing_desc = ?, ing_cantidad = ?, unidad_id = ?, ing_precio = ? WHERE ing_id = ?");
        $stmt->bind_param("ssdddi", $nombre, $descripcion, $cantidad, $unidad_id, $precio, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => '✅ Ingrediente editado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Error al actualizar el ingrediente.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => '⚠️ Datos inválidos o incompletos.']);
    }

    $connect->close();
    exit;
}
    if ($action === 'cambiar_estado') {
    $id = intval($_POST['ingrediente_id'] ?? 0);
    $nuevoEstado = intval($_POST['nuevo_estado'] ?? 1);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de ingrediente no válido.']);
        exit;
    }

    $stmt = $connect->prepare("UPDATE ingredientes SET esta_id = ? WHERE ing_id = ?");
    $stmt->bind_param("ii", $nuevoEstado, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Estado actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Error al actualizar estado.']);
    }

    $stmt->close();
    $connect->close();
    exit;
}
    if ($action === 'eliminar') {
    $id = intval($_POST['ingrediente_id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido.']);
        exit;
    }

    $sql = "DELETE FROM ingredientes WHERE ing_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ingrediente eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $stmt->error]);
    }

    $stmt->close();
    $connect->close();
    exit;
}


    // Si la acción no coincide
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}
?>