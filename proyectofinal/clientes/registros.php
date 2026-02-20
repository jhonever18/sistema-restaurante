<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-[#C0392B] mb-6">Crear Cuenta</h2>
        
        <form action="../clientes/registrarCliente.php" method="POST" class="space-y-4">
            <input type="text" name="nombre" placeholder="Nombre" required
                   class="w-full p-2 rounded border border-gray-300 bg-gray-100" />
            <input type="text" name="apellido" placeholder="Apellido" required
                   class="w-full p-2 rounded border border-gray-300 bg-gray-100" />
            <input type="email" name="correo" placeholder="Correo electrónico" required
                   class="w-full p-2 rounded border border-gray-300 bg-gray-100" />
            <input type="password" name="contrasena" placeholder="Contraseña" required
                   class="w-full p-2 rounded border border-gray-300 bg-gray-100" />
            <input type="text" name="telefono" placeholder="Teléfono (opcional)"
                   class="w-full p-2 rounded border border-gray-300 bg-gray-100" />

            <button type="submit"
                    class="w-full bg-[#C0392B] hover:bg-red-600 text-white font-bold py-2 rounded transition">
                Registrarse
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            ¿Ya tienes cuenta?
            <a href="menuPlatos.php" class="text-[#C0392B] hover:text-red-500 font-semibold">Iniciar sesión</a>
        </p>
    </div>

</body>
</html>
