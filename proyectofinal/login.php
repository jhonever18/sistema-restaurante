<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JJJ'Pizzas - Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="min-h-screen bg-center bg-cover" style="background-image: url('img/fondo-pizza.jpg')">

  <div class="min-h-screen flex items-center justify-center backdrop-blur-sm bg-black/30 p-4">
    <div class="bg-[#1F2C3C] rounded-xl shadow-lg p-8 w-full max-w-md animate__animated animate__fadeInDown text-center">
      <h1 class="text-3xl font-bold text-white mb-2">Bienvenido a <span class="text-yellow-400">JJJ'Pizzas</span></h1>
      <p class="text-gray-300 mb-6">Selecciona cómo deseas ingresar</p>

      <div class="grid gap-6">
        <!-- Tarjeta Cliente -->
        <div class="bg-white/10 p-4 rounded-lg hover:shadow-lg cursor-pointer transition" onclick="window.location.href='cliente/login.php'">
          <h2 class="text-xl font-semibold text-white mb-1">Cliente</h2>
          <p class="text-sm text-gray-300">Haz pedidos, revisa el menú y mira tu historial.</p>
          <button class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">Acceder como Cliente</button>
        </div>

        <!-- Tarjeta Trabajador -->
        <div class="bg-white/10 p-4 rounded-lg hover:shadow-lg cursor-pointer transition" onclick="window.location.href='trabajador/login.php'">
          <h2 class="text-xl font-semibold text-white mb-1">Trabajador</h2>
          <p class="text-sm text-gray-300">Administra ingredientes, pedidos y usuarios.</p>
          <button class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">Acceder como Trabajador</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
