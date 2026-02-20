<?php
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'plato' => null
];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = 'ID de plato no proporcionado.';
    echo json_encode($response);
    exit;
}

$platoId = $_GET['id'];

if (!filter_var($platoId, FILTER_VALIDATE_INT)) {
    $response['message'] = 'ID de plato inválido.';
    echo json_encode($response);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurante";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    $response['message'] = 'Error de conexión a la base de datos: ' . $conn->connect_error;
    echo json_encode($response);
    exit;
}

try {
    $sql = "SELECT plato_id, plato_nombre, plato_desc, plato_precio, plato_imagen_url FROM platos WHERE plato_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $platoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $plato = $result->fetch_assoc();
        $response['success'] = true;
        $response['message'] = 'Plato encontrado exitosamente.';
        $response['plato'] = $plato;

        // ✅ Obtener ingredientes del plato
        $sqlIngredientes = "SELECT i.ing_id, i.ing_nombre, pi.cantidad 
                            FROM plato_ingredientes pi
                            JOIN ingredientes i ON i.ing_id = pi.ing_id
                            WHERE pi.plato_id = ?";
        $stmtIng = $conn->prepare($sqlIngredientes);
        $stmtIng->bind_param("i", $platoId);
        $stmtIng->execute();
        $resultIng = $stmtIng->get_result();

        $ingredientes = [];
        while ($row = $resultIng->fetch_assoc()) {
            $ingredientes[] = $row;
        }

        $response['plato']['ingredientes'] = $ingredientes;
        $stmtIng->close();

    } else {
        $response['message'] = 'Plato no encontrado.';
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Error en el servidor: ' . $e->getMessage();
    error_log('Error en obtenerPlatoDetalles.php: ' . $e->getMessage());
} finally {
    $conn->close();
}

// 👇 El echo va al final, después de obtener todo
echo json_encode($response);
exit;
?>
