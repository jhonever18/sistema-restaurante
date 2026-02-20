<?php
header('Content-Type: application/json');
include '../conexion/conectarBD.php';

$id = intval($_GET['id'] ?? 0);

$sql = "SELECT u.user_id, u.user_nombre, u.user_apellido, u.user_correo, u.user_telefono, u.user_foto,
               u.ti_desc, r.rol_desc, es.esta_desc, u.fecha_registro
        FROM usuarios u
        LEFT JOIN roles r ON u.rol_id = r.rol_id
        LEFT JOIN estado es ON u.esta_id = es.esta_id
        WHERE u.user_id = $id";

$res = mysqli_query($connect, $sql);

if ($res && $usuario = mysqli_fetch_assoc($res)) {
    echo json_encode($usuario);
} else {
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>

