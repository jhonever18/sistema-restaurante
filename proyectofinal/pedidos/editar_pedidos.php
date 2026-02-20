<?php
include("conexion.php");

// Validar ID recibido por GET
if (!isset($_GET['id'])) {
    die("Error: Falta el parámetro ID.");
}
$id = intval($_GET['id']);

// Obtener datos del pedido
$resultado = $conexion->query("SELECT * FROM pedidos WHERE pedido_id = $id");
$pedido = $resultado->fetch_assoc();

if (!$pedido) {
    die("Pedido no encontrado.");
}

// Procesar formulario si fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $conexion->real_escape_string($_POST['cliente']);
    $plato = $conexion->real_escape_string($_POST['plato']);
    $cantidad = intval($_POST['cantidad']);
    $estado = $conexion->real_escape_string($_POST['estado']);

    $conexion->query("UPDATE pedidos SET cliente='$cliente', plato='$plato', cantidad=$cantidad, estado='$estado' WHERE id=$id");

    // Generar factura si entregado y aún no existe
    if ($estado == 'entregado') {
        $verificar = $conexion->query("SELECT * FROM facturas WHERE pedido_id = $id");
        if ($verificar->num_rows == 0) {
            $precio_unitario = 15000;
            $total = $precio_unitario * $cantidad;

            $conexion->query("INSERT INTO facturas (pedido_id, cliente, plato, cantidad, total)
                              VALUES ($id, '$cliente', '$plato', $cantidad, $total)");
        }
    }

    header("Location: listar_pedidos.php");
    exit;
}
?>

<!-- Formulario HTML -->

 <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<h2 class="text-2xl font-semibold mb-6 text-gray-800">Editar Pedido</h2>

<form method="post" class="bg-white p-6 rounded shadow-md max-w-md mx-auto space-y-4">
    <div>
        <label class="block text-gray-700 font-medium mb-1">Cliente:</label>
        <input type="text" name="cliente" value="<?= htmlspecialchars($pedido['cliente']) ?>" 
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-1">Plato:</label>
        <input type="text" name="plato" value="<?= htmlspecialchars($pedido['plato']) ?>" 
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-1">Cantidad:</label>
        <input type="number" name="cantidad" value="<?= $pedido['cantidad'] ?>" 
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-1">Estado:</label>
        <select name="estado" 
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="preparando" <?= $pedido['estado'] == 'preparando' ? 'selected' : '' ?>>Preparando</option>
            <option value="entregado" <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
        </select>
    </div>

    <div>
        <button type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition duration-200">
            Guardar cambios
        </button>
    </div>
</form>
