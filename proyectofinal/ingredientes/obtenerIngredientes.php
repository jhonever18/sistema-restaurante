<?php
header('Content-Type: application/json');

// Asegúrate de que esta ruta sea correcta para tu conexión
$connect = include("../conexion/conectarBD.php");

if (!$connect) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit();
}

$sql = "SELECT ing_id, ing_nombre, ing_desc FROM ingredientes ORDER BY ing_nombre ASC"; // Usamos ing_id, ing_nombre, ing_desc
$result = mysqli_query($connect, $sql);

$ingredientes = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Mapeamos los nombres de las columnas de la BD a nombres que el JS espera
        $ingredientes[] = [
            'ingrediente_id' => $row['ing_id'],
            'ingrediente_nombre' => $row['ing_nombre'],
            'ingrediente_descripcion' => $row['ing_desc']
        ];
    }
} else {
    echo json_encode(['error' => 'Error al obtener ingredientes: ' . mysqli_error($connect)]);
    exit();
}

mysqli_close($connect);
echo json_encode($ingredientes);
?>