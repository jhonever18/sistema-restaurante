<?php
session_start();
include("../conexion/conectarBD.php");

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM clientes WHERE cli_correo = '$correo'";
$resultado = mysqli_query($connect, $sql);
$cliente = mysqli_fetch_assoc($resultado);

if ($cliente && password_verify($contrasena, $cliente['cli_contrasena'])) {
    $_SESSION['cliente_id'] = $cliente['cliente_id'];
    $_SESSION['cliente_nombre'] = $cliente['cli_nombre'];
    $_SESSION['cliente_apellido'] = $cliente['cli_apellido'];
    $_SESSION['cli_correo'] = $cliente['cli_correo'];

    header("Location: menuPlatos.php");
    exit();
} else {
    echo "❌ Correo o contraseña incorrectos.";
}
?>
