<?php
include("../conexion/conectarBD.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $estado = intval($_POST['estado'] ?? 1);

    if ($id > 0 && in_array($estado, [1, 2])) {
        $stmt = $connect->prepare("UPDATE ingredientes SET esta_id = ? WHERE ing_id = ?");
        $stmt->bind_param("ii", $estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    }
    $connect->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
