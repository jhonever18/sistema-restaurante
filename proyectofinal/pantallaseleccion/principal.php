<?php
session_start();
$alerta_cierre = isset($_GET['cerrado']) && $_GET['cerrado'] == 1;
?>
<!-- luego va tu HTML... -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JJJ'Pizzas - Bienvenido</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-image: url('/imagenes/fondopizzas.jpg');
      background-size: 500px;
      background-attachment: fixed;
      background-repeat: repeat;
      background-blend-mode: overlay;
      background-color: rgba(0, 0, 0, 0.5);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center text-white">

    <!-- Logo fijo arriba a la izquierda -->
  <div class="absolute top-4 left-4 z-50 flex items-center space-x-2">
    <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png" alt="Logo de JJJ'Pizzas" class="w-20 h-20 rounded-full shadow-md">
    <span class="text-xl font-bold text-yellow-400"></span>
  </div>

  <div class="bg-[#2C3E50] bg-opacity-90 rounded-xl shadow-2xl p-8 w-full max-w-md text-center animate__animated animate__fadeInDown">
    <h1 class="text-3xl font-bold mb-6">Bienvenido a <span class="text-yellow-400">JJJ'Pizzas</span></h1>
    <p class="text-gray-300 mb-6">¿Cómo deseas ingresar?</p>

    <div class="space-y-6">
      <!-- Cliente -->
      <div class="bg-white/10 p-4 rounded-lg hover:shadow-lg transition">
        <h2 class="text-xl font-semibold mb-1">Cliente</h2>
        <p class="text-sm text-gray-300">Haz pedidos, explora el menú y revisa tu historial.</p>
        <a href="../clientes/menu.php" class="mt-4 block bg-[#C0392B] hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">Acceder como Cliente</a>
      </div>

      <!-- Trabajador -->
      <div class="bg-white/10 p-4 rounded-lg hover:shadow-lg transition">
        <h2 class="text-xl font-semibold mb-1">Trabajador</h2>
        <p class="text-sm text-gray-300">Administra ingredientes, pedidos y usuarios del sistema.</p>
        <a href="../loginadmin/trabajadores.php" class="mt-4 block bg-[#27AE60] hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">Acceder como Trabajador</a>
      </div>
    </div>
  </div>

  <?php if (!empty($alerta_cierre)) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Sesión cerrada!',
    text: 'Has salido correctamente.',
    confirmButtonColor: '#3085d6',
    background: '#2C3E50',
    color: '#FAF3E0'
}).then(() => {
    const url = new URL(window.location);
    url.searchParams.delete('cerrado');
    window.history.replaceState({}, document.title, url);
});
</script>
<?php endif; ?>

</body>
</html>
