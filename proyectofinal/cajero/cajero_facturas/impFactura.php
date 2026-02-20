<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

// Obtener el ID del pedido desde la URL de forma segura
$pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

// Consulta principal del pedido con método de pago
$query = "SELECT 
            p.pedido_id, 
            p.pedido_fecha, 
            p.pedido_valor_pagar, 
            c.cli_nombre AS user_nombre, 
            c.cli_apellido AS user_apellido, 
            c.cli_correo AS user_correo,
            mp.metopago_desc
          FROM pedidos p
          JOIN clientes c ON p.cliente_id = c.cliente_id
          JOIN metodo_pago mp ON p.metopago_id = mp.metopago_id
          WHERE p.pedido_id = $pedido_id";

$res = $connect->query($query);
$pedido = $res->fetch_assoc();

if (!$pedido) {
    echo "<p style='color:red; font-weight:bold;'>❌ No se encontró el pedido con ID: $pedido_id</p>";
    exit;
}

// Consulta de productos del pedido
$query2 = "SELECT plato_nombre AS plato_desc, precio_unitario AS plato_precio, cantidad
FROM pedido_detalles
WHERE pedido_id = $pedido_id";

$items = $connect->query($query2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF3E0]   px-4 text-white">

<div id="factura" class="bg-[#FAF3E0] p-10 rounded-2xl shadow-2xl w-full max-w-lg border border-gray-600">
  

  <div class="text-center mb-8">
    <h1 class="text-4xl font-bold"><span class="text-[#C0392B]">JJJ's</span> <span class="text-yellow-400">PIZZERIA</span></h1>
    <p class="text-black text-sm">www.jjjspizzeria.com</p>
  </div>

  <div class="grid grid-cols-3 gap-6 text-sm mb-10">
    <div>
      <h2 class="text-black font-semibold text-xs uppercase mb-1">Factura de</h2>
      <p class="text-black">JJJ's Pizzeria</p>
      <p class="text-black">Cra #7abis 82-49</p>
    </div>
    <div>
      <h2 class="text-black font-semibold text-xs uppercase mb-1">Cliente</h2>
      <p class="text-black"><?= $pedido['user_nombre'] . ' ' . $pedido['user_apellido'] ?></p>
      <p class="text-black"><?= $pedido['user_correo'] ?></p>
    </div>
    <div>
      <h2 class="text-black font-semibold text-xs uppercase mb-1">Fecha</h2>
      <p class="text-black"><?= $pedido['pedido_fecha'] ?></p>
      <p class="text-black">ID: #<?= $pedido['pedido_id'] ?></p>
    </div>
  </div>

  <table class="w-full text-sm text-white mb-8 border-t border-b border-gray-600">
    
    <thead class="text-gray-400 uppercase">
      <tr>
        <th class="py-3 text-left">Descripción</th>
        <th class="py-3 text-center">Cantidad</th>
        <th class="py-3 text-center">Precio</th>
        <th class="py-3 text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $subtotal = 0;
      while ($row = $items->fetch_assoc()):
        $total = $row['plato_precio'] * $row['cantidad'];
        $subtotal += $total;
      ?>
      <tr class="border-t text-black border-gray-700">
        <td class="py-2"><?= $row['plato_desc'] ?></td>
        <td class="py-2 text-center"><?= $row['cantidad'] ?></td>
        <td class="py-2 text-center">$<?= number_format($row['plato_precio'], 0, ',', '.') ?></td>
        <td class="py-2 text-right">$<?= number_format($total, 0, ',', '.') ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="text-right text-black text-sm space-y-1 mb-6">
    <p><span class="text-gray-400">Subtotal:</span> $<?= number_format($subtotal, 0, ',', '.') ?></p>
    <p><span class="text-gray-400">IVA (10%):</span> $<?= number_format($subtotal * 0.1, 0, ',', '.') ?></p>
    <p><span class="text-gray-400">Método de pago:</span> <?= $pedido['metopago_desc'] ?></p>
    <p class="text-xl font-bold"><span class="text-yellow-400">Total:</span> $<?= number_format($subtotal * 1.1, 0, ',', '.') ?></p>

   

  </div>

  <div class="text-xs text-gray-400 border-t border-gray-600 pt-4 text-center">
    <p><strong>Gracias por tu compra 🍕</strong></p>
    <p>¡Vuelve pronto!</p>
  </div>
</div>

<style>
@media print {
  body * {
    visibility: hidden;
  }

  #factura, #factura * {
    visibility: visible;
  }

  #factura {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    box-shadow: none;
  }

  .no-print {
    display: none;
  }
}
</style>

</body>
</html>


