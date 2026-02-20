<?php
$connect = include("../conexion/conectarBD.php");

$plato_id = 204;
$ing_id = 1;
$cantidad = 1.0;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Mostrará errores exactos

$stmt = $connect->prepare("INSERT INTO plato_ingredientes (plato_id, ing_id, cantidad) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $plato_id, $ing_id, $cantidad);

if ($stmt->execute()) {
    echo "✅ Ingrediente insertado correctamente.";
} else {
    echo "❌ Error: " . $stmt->error;
}
