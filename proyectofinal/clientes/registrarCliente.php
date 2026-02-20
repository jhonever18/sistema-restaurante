<?php
include("../conexion/conectarBD.php");

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
$telefono = $_POST['telefono'];

// Validación básica y guardado
$sql = "INSERT INTO clientes (cli_nombre, cli_apellido, cli_correo, cli_contrasena, cli_telefono)
        VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$telefono')";

if (mysqli_query($connect, $sql)) {
    echo "<script>alert('✅ Registro exitoso'); window.location.href='../clientes/menuPlatos.php';</script>";
} else {
    echo "<script>alert('❌ Error al registrar'); window.history.back();</script>";
}
?>
