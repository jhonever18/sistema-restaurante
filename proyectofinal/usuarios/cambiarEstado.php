<?php
include '../conexion/conectarBD.php';

if (isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    foreach ($ids as $id) {
        // Obtener estado actual
        $sql = "SELECT esta_id FROM usuarios WHERE user_id = $id";
        $res = mysqli_query($connect, $sql);
        $row = mysqli_fetch_assoc($res);
        $estadoActual = $row['esta_id'];

        // Cambiar estado: si está en 1 (Activo), cambiar a 2 (Inactivo), y viceversa
        $nuevoEstado = ($estadoActual == 1) ? 2 : 1;

        // Actualizar en la base de datos
        $update = "UPDATE usuarios SET esta_id = $nuevoEstado WHERE user_id = $id";
        mysqli_query($connect, $update);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibieron IDs']);
}
?>
