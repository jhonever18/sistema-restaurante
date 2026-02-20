<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - JJJ's Pizzeria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center z-50 justify-center min-h-screen">
<div id="loginModalFondo" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-[#FAF3E0] text-gray-900 rounded-xl shadow-lg p-8 w-full max-w-md relative">
        <h2 class="text-3xl font-bold text-center text-[#2C3E50] mb-4">JJJ's Pizzeria 🍕</h2>
        <h3 class="text-xl font-semibold text-center text-[#2C3E50] mb-6">Inicia sesión</h3>

        <form action="../clientes/procesarLogin.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-[#2C3E50] mb-1">Correo electrónico</label>
                <input type="email" name="correo" required
                       class="w-full p-2 rounded bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       placeholder="correo@ejemplo.com" />
            </div>

            <div>
                <label class="block text-sm text-[#2C3E50] mb-1">Contraseña</label>
                <input type="password" name="contrasena" required
                       class="w-full p-2 rounded bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       placeholder="••••••••" />
            </div>

            <button type="submit" class="w-full bg-[#C0392B] hover:bg-red-600 text-white font-bold py-2 rounded transition">
                Iniciar sesión
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="clientes/recuperar.php" class="text-sm text-[#C0392B] hover:text-red-500">¿Olvidaste tu contraseña?</a>
        </div>

        <p class="text-sm mt-4 text-center text-[#2C3E50]">
            ¿No tienes cuenta?
            <a href="../clientes/registros.php" class="text-[#C0392B] hover:text-red-500 font-semibold">Regístrate</a>
        </p>

        <!-- Botón para cerrar el modal -->
        <button onclick="document.getElementById('loginModalFondo').classList.add('hidden')" 
                class="absolute top-2 right-2 text-red-700 hover:text-red-900 text-lg font-bold">
            ✕
        </button>
    </div>
</div>


</body>
</html>



