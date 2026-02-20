<?php
ob_start(); // Inicia buffer de salida para evitar contenido inesperado
header('Content-Type: application/json');
require_once("../conexion/conectarBD.php");

$sql = "SELECT metopago_id, metopago_desc FROM metodo_pago";
$resultado = mysqli_query($connect, $sql);

$metodos = [];

if ($resultado) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $metodos[] = $fila;
    }
    echo json_encode($metodos);
} else {
    echo json_encode(["error" => "No se pudieron obtener los métodos de pago."]);
}
ob_end_flush(); // Vacía el buffer
