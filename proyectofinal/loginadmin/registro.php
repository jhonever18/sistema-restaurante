<?php
    include ("../conexion/conectarBD.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = $_POST['rol'];

    
        if (empty($nombre) || empty($email) || empty($password) || empty($rol)) {
            $error_registro = "Por favor, completa todos los campos.";
        } else {
        
            $sql_check_email = "SELECT correo FROM usuarios WHERE correo = '$email'";
            $consulta_check_email = mysqli_query($connect, $sql_check_email);

            if (mysqli_num_rows($consulta_check_email) > 0) {
                $error_registro = "Este correo electrónico ya está registrado.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                
                $sql_select_rol_id = "SELECT id_rol FROM roles WHERE nombre = '$rol'";
                $consulta_rol_id = mysqli_query($connect, $sql_select_rol_id);

                if ($fila_rol_id = mysqli_fetch_assoc($consulta_rol_id)) {
                    $id_rol = $fila_rol_id['id_rol'];

                    
                    $sql_insert = "INSERT INTO usuarios (nombre, correo, password, id_rol) VALUES ('$nombre', '$email', '$hashed_password', '$id_rol')";

                    if (mysqli_query($connect, $sql_insert)) {
                        header("Location: trabajadores.php?registro_exitoso=1");
                        exit();
                    } else {
                        $error_registro = "Error al registrar el usuario: " . mysqli_error($connect);
                    }
                } else {
                    $error_registro = "Error al obtener el ID del rol.";
                }
            }
        }
    }

    $sql_roles = "SELECT * FROM roles ORDER BY nombre ASC";
    $consulta_roles = mysqli_query($connect, $sql_roles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaFood - Registro de Trabajadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .relative.z-10 { position: relative; z-index: 10; }
    </style>
</head>

<body class="relative min-h-screen flex items-center justify-center bg-gray-900">
    <div class="absolute inset-0 bg-cover bg-center z-0 opacity-9 blur-sm"
        style="background-image: url('/imagenes/e74ecc3271fa1791f0e4d9067fc85d3f.jpg');">
    </div>
    <div class="absolute inset-0 bg-black opacity-50 z-0"></div>

    <div class="relative z-10 bg-[#FAF3E0] text-white rounded-xl shadow-lg flex overflow-hidden w-full max-w-md">
        <div class="p-8 flex flex-col justify-center w-full">
            <h2 class="text-2xl text-[#2C3E50] font-bold mb-6 text-center">Registro de Trabajadores</h2>

            <?php if (isset($error_registro)): ?>
                <div class="text-red-500 mb-4"><?php echo $error_registro; ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="nombre" class="block mb-2 text-[#2C3E50] text-sm">Nombre Completo</label>
                    <input type="text" name="nombre" required class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Tu nombre">
                </div>
                <div>
                    <label for="email" class="block mb-2 text-[#2C3E50] text-sm">Correo electrónico</label>
                    <input type="email" name="email" required class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="correo@ejemplo.com">
                </div>
                <div>
                    <label for="password" class="block mb-2 text-[#2C3E50] text-sm">Contraseña</label>
                    <input type="password" name="password" required class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="••••••••">
                </div>
                <div>
                    <label for="rol" class="block mb-2 text-[#2C3E50] text-sm">Rol</label>
                    <select name="rol" required class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="">Seleccionar Rol</option>
                        <?php
                        if (!$consulta_roles) {
                            echo "<option>Error al cargar roles</option>";
                        } else {
                            while ($fila_rol = mysqli_fetch_array($consulta_roles)) {
                                echo "<option value='{$fila_rol['nombre']}'>{$fila_rol['nombre']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-[#FAF3E0] font-bold py-2 rounded transition">Registrarse</button>
            </form>

            <div class="mt-4 text-center text-[#2C3E50] text-sm">
                ¿Ya tienes una cuenta? <a href="trabajadores.php" class="text-green-500 font-bold hover:underline">Inicia Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>