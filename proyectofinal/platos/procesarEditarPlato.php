<?php
// procesarEditarPlato.php

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/phperrors.log');
error_reporting(E_ALL);

session_start();
require_once '../conexion/conectarBD.php';

if (!$connect) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor: No se pudo establecer conexión con la base de datos.']);
    error_log('Error fatal: No se pudo conectar a la base de datos en procesarEditarPlato.php');
    exit();
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
    exit();
}

$errors = [];

$plato_id = isset($_POST['plato_id']) ? (int)$_POST['plato_id'] : 0;
$plato_nombre = trim($_POST['plato_nombre'] ?? '');
$plato_desc = trim($_POST['plato_desc'] ?? '');
$plato_precio = $_POST['plato_precio'] ?? '';
$plato_imagen_url = trim($_POST['plato_imagen_url'] ?? '');

$ingredientes_enviados_raw = $_POST['ingredientes'] ?? [];
$ingredientes_validados = [];

if ($plato_id === 0) {
    $errors['plato_id'] = 'ID de plato no proporcionado o inválido.';
}
if (empty($plato_nombre)) {
    $errors['plato_nombre'] = 'El nombre del plato es obligatorio.';
}
if (empty($plato_desc)) {
    $errors['plato_desc'] = 'La descripción del plato es obligatoria.';
}
if (empty($plato_precio) || !is_numeric($plato_precio) || (float)$plato_precio <= 0) {
    $errors['plato_precio'] = 'Precio inválido. Debe ser un número positivo.';
} else {
    $plato_precio = (float)$plato_precio;
}

if (!is_array($ingredientes_enviados_raw)) {
    $errors['ingredientes'] = 'Formato de ingredientes inválido.';
} else {
    foreach ($ingredientes_enviados_raw as $index => $ingrediente_data) {
        $ing_id = isset($ingrediente_data['id']) ? (int)$ingrediente_data['id'] : 0;
        $cantidad = isset($ingrediente_data['cantidad']) ? trim($ingrediente_data['cantidad']) : '';

        if ($ing_id <= 0) {
            $errors["ingredientes[$index][id]"] = 'ID de ingrediente inválido.';
        }
        if (empty($cantidad) || !is_numeric($cantidad) || (float)$cantidad < 0) {
            $errors["ingredientes[$index][cantidad]"] = "Cantidad inválida para ingrediente con ID {$ing_id}.";
        } else {
            $ingredientes_validados[] = [
                'id' => $ing_id,
                'cantidad' => (float)$cantidad
            ];
        }
    }
}

$final_image_url = $plato_imagen_url;

if (isset($_FILES['plato_imagen_file']) && $_FILES['plato_imagen_file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['plato_imagen_file'];
    $uploadDir = '../uploads/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $errors['plato_imagen_file'] = 'No se pudo crear el directorio de subida.';
        }
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024;

    if (!in_array($file['type'], $allowedTypes)) {
        $errors['plato_imagen_file'] = 'Tipo de archivo de imagen no permitido.';
    } elseif ($file['size'] > $maxFileSize) {
        $errors['plato_imagen_file'] = 'El archivo de imagen es demasiado grande (máx 5MB).';
    } else {
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $final_image_url = '/proyectofinal/uploads/' . $fileName;
        } else {
            $errors['plato_imagen_file'] = 'Error desconocido al mover la imagen subida.';
        }
    }
} elseif (isset($_FILES['plato_imagen_file']) && $_FILES['plato_imagen_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $errors['plato_imagen_file'] = 'Error al subir la imagen: ' . $_FILES['plato_imagen_file']['error'];
} elseif (empty($plato_imagen_url) && (!isset($_FILES['plato_imagen_file']) || $_FILES['plato_imagen_file']['error'] === UPLOAD_ERR_NO_FILE)) {
    $final_image_url = null;
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => 'Errores de validación.', 'errors' => $errors]);
    exit();
}

mysqli_begin_transaction($connect);

try {
    $sql = "UPDATE platos SET plato_nombre = ?, plato_desc = ?, plato_precio = ?, plato_imagen_url = ? WHERE plato_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ssdsi", $plato_nombre, $plato_desc, $plato_precio, $final_image_url, $plato_id);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error al actualizar el plato: ' . mysqli_error($connect));
    }
    mysqli_stmt_close($stmt);

    // --- Insertar o actualizar ingredientes ---
    if (!empty($ingredientes_validados)) {
        $sqlUpsert = "INSERT INTO plato_ingredientes (plato_id, ing_id, cantidad)
                      VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE cantidad = VALUES(cantidad)";
        $stmtUpsert = mysqli_prepare($connect, $sqlUpsert);
        if (!$stmtUpsert) {
            throw new Exception('Error al preparar inserción/actualización de ingredientes: ' . mysqli_error($connect));
        }

        foreach ($ingredientes_validados as $ingrediente_data) {
            $ing_id = $ingrediente_data['id'];
            $cantidad = $ingrediente_data['cantidad'];

            mysqli_stmt_bind_param($stmtUpsert, "iid", $plato_id, $ing_id, $cantidad);
            if (!mysqli_stmt_execute($stmtUpsert)) {
                error_log("Error al insertar/actualizar ingrediente (ID: $ing_id, Plato ID: $plato_id): " . mysqli_error($connect));
                throw new Exception("Error al insertar/actualizar ingrediente ID: $ing_id.");
            }
        }
        mysqli_stmt_close($stmtUpsert);
    }

    mysqli_commit($connect);
    echo json_encode(['success' => true, 'message' => 'Plato y sus ingredientes actualizados exitosamente.']);
} catch (Exception $e) {
    mysqli_rollback($connect);
    error_log("Error crítico en procesarEditarPlato.php (transacción): " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en la transacción: ' . $e->getMessage()]);
} finally {
    if ($connect) {
        mysqli_close($connect);
    }
}
?>
