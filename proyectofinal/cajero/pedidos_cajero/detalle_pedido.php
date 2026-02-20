<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

// Asegurar que se recibió el pedido_id por POST
if (!isset($_POST['id'])) {
    die("❌ No se recibió el ID del pedido.");
}

$pedido_id = intval($_POST['id']);

$query = "SELECT plato_nombre, cantidad, precio_unitario
    FROM pedido_detalles
    WHERE pedido_id = $pedido_id
";
$resultado = mysqli_query($connect, $query);

if (!$resultado) {
    die("Error al obtener los detalles del pedido: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido #<?= $pedido_id ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Detalle del Pedido #<?= $pedido_id ?></h1>

    <table class="w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-4 py-2">Plato</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Precio Unitario</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; while ($detalle = mysqli_fetch_assoc($resultado)): 
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                $total += $subtotal;
            ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2"><?= htmlspecialchars($detalle['plato_nombre']) ?></td>
                    <td class="px-4 py-2"><?= $detalle['cantidad'] ?></td>
                    <td class="px-4 py-2">$<?= number_format($detalle['precio_unitario']) ?></td>
                    <td class="px-4 py-2">$<?= number_format($subtotal) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr class="bg-gray-200 font-bold">
                <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                <td class="px-4 py-2">$<?= number_format($total) ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
