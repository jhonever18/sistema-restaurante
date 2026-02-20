<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

// Flujo de estados: pendiente (6) → en proceso (3) → entregado (4)
$flujo_estado = [
    6 => 3,
    3 => 4
];

// Seleccionar todos los pedidos que no estén cancelados
$query = "SELECT pedido_id, esta_id FROM pedidos WHERE esta_id IN (6, 3) AND esta_id != 5";
$resultado = mysqli_query($connect, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($pedido = mysqli_fetch_assoc($resultado)) {
        $pedido_id = $pedido['pedido_id'];
        $estado_actual = $pedido['esta_id'];

        if (isset($flujo_estado[$estado_actual])) {
            $nuevo_estado = $flujo_estado[$estado_actual];
            $update = "UPDATE pedidos SET esta_id = $nuevo_estado WHERE pedido_id = $pedido_id";
            mysqli_query($connect, $update);
        }
    }
}

header("Location: pedidos.php");
exit();
?>

