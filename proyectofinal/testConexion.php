<?php
$conexion = mysqli_connect("localhost", "root", "", "restaurante");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
echo "✅ Conexión exitosa a la base de datos.";
?>
