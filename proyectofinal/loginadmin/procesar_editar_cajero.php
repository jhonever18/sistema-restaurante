<?php
session_start();
include '../conexion/conectarBD.php';

// Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
ob_start();

if (!isset($_POST['user_id'], $_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'])) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Faltan datos."]);
    exit;
}

$user_id  = intval($_POST['user_id']);
$nombre   = mysqli_real_escape_string($connect, $_POST['nombre']);
$apellido = mysqli_real_escape_string($connect, $_POST['apellido']);
$correo   = mysqli_real_escape_string($connect, $_POST['correo']);
$telefono = mysqli_real_escape_string($connect, $_POST['telefono']);
$contrasena = !empty($_POST['contrasena']) ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : null;
$eliminarFoto = isset($_POST['eliminar_foto']);

// Obtener foto actual del usuario
$sqlSelect = "SELECT user_foto FROM usuarios WHERE user_id = $user_id";
$result = mysqli_query($connect, $sqlSelect);
$user = mysqli_fetch_assoc($result);
$fotoActual = $user['user_foto'] ?? null;

// Verificar si hay carpeta
if (!file_exists("../../imagenes/perfil")) {
    mkdir("../../imagenes/perfil", 0777, true);
}

// Si hay foto nueva
$nombreArchivo = "";
if (!empty($_FILES['foto']['name'])) {
    $nombreOriginal = basename($_FILES['foto']['name']);
    $nombreArchivo = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);
    $rutaFoto = "../../imagenes/perfil/" . $nombreArchivo;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFoto)) {
        ob_clean();
        echo json_encode(["success" => false, "message" => "Error al subir la imagen."]);
        exit;
    }

    // Si sube nueva imagen, eliminamos la antigua
    if ($fotoActual && file_exists("../../imagenes/perfil/" . $fotoActual)) {
        unlink("../../imagenes/perfil/" . $fotoActual);
    }
}

// Si se pide eliminar la imagen sin subir una nueva
if ($eliminarFoto && !$nombreArchivo) {
    if ($fotoActual && file_exists("../../imagenes/perfil/" . $fotoActual)) {
        unlink("../../imagenes/perfil/" . $fotoActual);
    }
    $nombreArchivo = null; // se asignará NULL a user_foto
}

// Construir consulta SQL
$sql = "UPDATE usuarios SET 
    user_nombre = '$nombre',
    user_apellido = '$apellido',
    user_correo = '$correo',
    user_telefono = '$telefono'";

if ($contrasena) {
    $sql .= ", user_contrasena = '$contrasena'";
}

if ($eliminarFoto || $nombreArchivo) {
    $valorFoto = $nombreArchivo ? "'$nombreArchivo'" : "NULL";
    $sql .= ", user_foto = $valorFoto";
}

$sql .= " WHERE user_id = $user_id";

// Ejecutar
if (mysqli_query($connect, $sql)) {
    ob_clean();
    echo json_encode(["success" => true, "message" => "Perfil actualizado correctamente."]);
} else {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Error SQL: " . mysqli_error($connect)]);
}
?>
