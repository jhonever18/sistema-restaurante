<?php
// actualizarIngredientesPlato.php

header('Content-Type: application/json');
include '../conexion/conectarBD.php'; // Ajusta la ruta a tu archivo de conexión

$response = ['success' => false, 'message' => ''];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['plato_id']) && isset($data['ingredientes'])) {
    $platoId = $data['plato_id'];
    $nuevosIngredientes = $data['ingredientes'];

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        // 1. Eliminar todos los ingredientes existentes para este plato
        $stmtDelete = $pdo->prepare("DELETE FROM plato_ingredientes WHERE plato_id = :plato_id");
        $stmtDelete->bindParam(':plato_id', $platoId, PDO::PARAM_INT);
        $stmtDelete->execute();

        // 2. Insertar los nuevos ingredientes
        $stmtInsert = $pdo->prepare("INSERT INTO plato_ingredientes (plato_id, ingrediente_id, cantidad) VALUES (:plato_id, :ingrediente_id, :cantidad)");
        foreach ($nuevosIngredientes as $ing) {
            if (isset($ing['id']) && isset($ing['cantidad']) && $ing['cantidad'] > 0) {
                $stmtInsert->bindParam(':plato_id', $platoId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':ingrediente_id', $ing['id'], PDO::PARAM_INT);
                $stmtInsert->bindParam(':cantidad', $ing['cantidad']); // Puede ser float o int, ajusta el tipo si es necesario
                $stmtInsert->execute();
            }
        }

        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'Ingredientes del plato actualizados con éxito.';

    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Datos incompletos para actualizar ingredientes.';
}

echo json_encode($response);
?>