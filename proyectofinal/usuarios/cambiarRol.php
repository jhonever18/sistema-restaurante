<?php
header('Content-Type: application/json');
include '../conexion/conectarBD.php';

if (isset($_POST['rol_id'], $_POST['usuarios'])) {
    $rol_id = intval($_POST['rol_id']);
    $usuarios = json_decode($_POST['usuarios'], true);

    foreach ($usuarios as $user_id) {
        $user_id = intval($user_id);
        $sql = "UPDATE usuarios SET rol_id = $rol_id WHERE user_id = $user_id";
        mysqli_query($connect, $sql);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
