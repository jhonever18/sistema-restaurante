<?php
session_start();
include ("../conexion/conectarBD.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>JJJ'Pizzas - Iniciar Sesión</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<style>
  .bg-overlay {
    background-image: url('/imagenes/fondopizzas.jpg');
    background-size: 500px;
    background-repeat: repeat;
    background-color: rgba(0, 0, 0, 0.5);
    background-blend-mode: overlay;
  }

  .glass {
    backdrop-filter: blur(12px);
    background: rgba(44, 62, 80, 0.85); /* fondo más opaco y sólido */
  }
</style>

<body class="min-h-screen bg-overlay flex items-center justify-center text-white relative">

  <!-- LOGO SUPERIOR IZQUIERDO -->
  <div class="absolute top-6 left-6 flex items-center space-x-3 z-50">
    <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png" alt="Logo" class="w-20 h-20 rounded-full shadow-lg" />
    <span class="text-2xl font-bold text-yellow-300"></span>
  </div>

  <!-- FORMULARIO -->
  <div class="glass animate__animated animate__fadeInDown shadow-2xl rounded-2xl p-10 w-full max-w-md z-10">
    <h2 class="text-2xl font-bold text-yellow-300 text-center mb-6">Acceso para Trabajadores</h2>

    <form id="loginForm" method="POST" class="space-y-5">
      
      <!-- Correo -->
      <div>
        <label for="email" class="block text-sm text-white mb-2">Correo electrónico</label>
        <input type="email" name="email" required placeholder="correo@ejemplo.com"
          class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 text-white" />
      </div>

      <!-- Rol -->
      <div>
        <label for="rol" class="block text-sm text-white mb-2">Selecciona tu rol</label>
        <select name="rol" required
          class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
          <option value="">Selecciona tu rol</option>
          <option value="administrador">Administrador</option>
          <option value="cajero">Cajero</option>
        </select>
      </div>

      <!-- Contraseña -->
      <div>
        <label for="clave" class="block text-sm text-white mb-2">Contraseña</label>
        <input type="password" name="clave" required placeholder="••••••••"
          class="w-full px-4 py-2 rounded-lg bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 text-white" />
      </div>

      <!-- Error -->
      <div id="error-message" class="text-red-500 text-sm mt-2 hidden"></div>

      <!-- Botón Iniciar Sesión -->
      <button type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 shadow-lg">
        Iniciar Sesión
      </button>
    </form>

    <!-- Botón Volver -->
    <div class="text-center mt-6">
      <a href="../pantallaseleccion/principal.php"
         class="inline-block text-yellow-300 hover:underline hover:text-yellow-200 transition text-sm">
        ← Volver a la pantalla principal
      </a>
    </div>
  </div>

  <script src="../js/inicioValidacion.js"></script>
</body>
</html>
