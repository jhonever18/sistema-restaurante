<?php
// Iniciar el buffer de salida para evitar que se envíe contenido antes de los headers
ob_start();
// Establecer el encabezado para indicar que la respuesta será JSON
header('Content-Type: application/json; charset=UTF-8');
session_start();

// Incluir el archivo de conexión a la base de datos
// Asegúrate de que esta ruta sea correcta desde la ubicación de este archivo PHP
$connect = include("../conexion/conectarBD.php");

// Verificar si la conexión a la base de datos fue exitosa y es un objeto mysqli
if (!$connect || !($connect instanceof mysqli)) {
    echo json_encode(['success' => false, 'message' => '❌ Error de conexión a la base de datos.']);
    // Limpiar el buffer y salir
    ob_end_clean();
    exit;
}

// Validar que se recibió el ID del plato y que es un número válido
// Cambiado de $_GET a $_POST para mayor seguridad en operaciones de eliminación
if (!isset($_POST['plato_id']) || !is_numeric($_POST['plato_id'])) {
    echo json_encode(['success' => false, 'message' => '⚠️ ID de plato no proporcionado o inválido.']);
    ob_end_clean();
    exit;
}

// Sanitizar el ID del plato a un entero
$plato_id = intval($_POST['plato_id']);

// Iniciar una transacción para asegurar que todas las operaciones (ingredientes, plato, imagen) sean atómicas
mysqli_begin_transaction($connect);

try {
    // 1. Obtener la URL de la imagen del plato antes de eliminarlo
    // Esto es necesario para poder eliminar el archivo físico después
    $sql_get_image = "SELECT plato_imagen_url FROM platos WHERE plato_id = ?";
    $stmt_get_image = $connect->prepare($sql_get_image);
    
    if (!$stmt_get_image) {
        throw new Exception("Error al preparar la consulta para obtener URL de imagen: " . $connect->error);
    }
    $stmt_get_image->bind_param("i", $plato_id);
    $stmt_get_image->execute();
    $result_image = $stmt_get_image->get_result();
    $plato_data = $result_image->fetch_assoc();
    $stmt_get_image->close();

    // Obtener la URL de la imagen, si existe
    $plato_imagen_url = $plato_data['plato_imagen_url'] ?? null;

    // 2. Eliminar todos los ingredientes asociados a este plato
    // Es crucial eliminar primero las dependencias (registros en plato_ingredientes)
    $sql_delete_ingredientes = "DELETE FROM plato_ingredientes WHERE plato_id = ?";
    $stmt_ingredientes = $connect->prepare($sql_delete_ingredientes);
    
    if (!$stmt_ingredientes) {
        throw new Exception("Error al preparar la consulta de eliminación de ingredientes: " . $connect->error);
    }
    $stmt_ingredientes->bind_param("i", $plato_id);
    
    if (!$stmt_ingredientes->execute()) {
        throw new Exception("Error al eliminar ingredientes del plato: " . $stmt_ingredientes->error);
    }
    $stmt_ingredientes->close();

    // 3. Eliminar el plato de la tabla 'platos'
    $sql_delete_plato = "DELETE FROM platos WHERE plato_id = ?";
    $stmt_plato = $connect->prepare($sql_delete_plato);

    if (!$stmt_plato) {
        throw new Exception("Error al preparar la consulta de eliminación del plato: " . $connect->error);
    }
    $stmt_plato->bind_param("i", $plato_id);

    if (!$stmt_plato->execute()) {
        throw new Exception("Error al eliminar el plato: " . $stmt_plato->error);
    }
    $stmt_plato->close();

    // 4. Si todo lo anterior fue exitoso, intentar eliminar el archivo de imagen del servidor
    // Asegúrate de que la ruta del directorio de subidas sea correcta
    $target_dir = "../uploads/platos/"; 
    // Verificar si la URL de la imagen es una ruta local que empieza con $target_dir
    if ($plato_imagen_url && strpos($plato_imagen_url, $target_dir) === 0 && file_exists($plato_imagen_url)) {
        // Usar @unlink para suprimir advertencias si el archivo no existe o no se puede borrar (permisos, etc.)
        @unlink($plato_imagen_url); 
    }

    // Confirmar la transacción si todas las operaciones fueron exitosas
    mysqli_commit($connect);
    
    // Limpiar el buffer de salida antes de enviar la respuesta JSON
    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'Plato eliminado correctamente.']);

} catch (Exception $e) {
    // Si ocurre cualquier error en el bloque try, revertir la transacción
    mysqli_rollback($connect);
    
    // Limpiar el buffer de salida
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => '❌ Error al eliminar el plato: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
mysqli_close($connect);
exit;
?>