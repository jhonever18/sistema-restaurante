

<?php
include '../conexion/conectarBD.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tipoID = $_POST['tipoID'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contra = $_POST['contra'];
    $telefono = $_POST['telefono'];
    $rol_nombre = $_POST['roles']; // esto es el nombre del rol (ej. "Administrador")
    $estado = $_POST['estado'];

    // Convertir nombre de rol a rol_id
    $rol_stmt = $connect->prepare("SELECT rol_id FROM roles WHERE LOWER(rol_desc) = ?");
    $rol_nombre_lower = strtolower(trim($rol_nombre));
    $rol_stmt->bind_param("s", $rol_nombre_lower);
    $rol_stmt->execute();
    $rol_result = $rol_stmt->get_result();

    if ($rol = $rol_result->fetch_assoc()) {
        $rol_id = $rol['rol_id'];
    } else {
        echo json_encode(["success" => false, "error" => "Rol no válido."]);
        exit;
    }

    // Actualizar datos
    $sql = "UPDATE usuarios SET 
            ti_desc=?, user_nombre=?, user_apellido=?, user_correo=?,
            user_contrasena=?, user_telefono=?, rol_id=?, esta_desc=? 
            WHERE user_id=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssssssss", $tipoID, $nombre, $apellido, $correo, $contra, $telefono, $rol_id, $estado, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
    exit;
}

// Si es GET, cargar datos del usuario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connect->prepare("SELECT * FROM usuarios WHERE user_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $user = $resultado->fetch_assoc();

    // Obtener todos los roles
    $roles_result = $connect->query("SELECT rol_id, rol_desc FROM roles");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Usuario</title>
</head>
<body>
    <div class="bg-white p-6 rounded shadow-md w-[400px] relative">
        <h2 class="text-xl font-bold mb-4">Editar Usuario</h2>
        <form id="formulario" action="editar1.php?id=<?= $id ?>" method="POST">
            <input type="hidden" name="id" value="<?= $user['user_id'] ?>">
            
            <select name="tipoID" class="w-full mb-2 p-2 border rounded" required>
                <option value="cedula ciudadana" <?= $user['ti_desc'] == 'cedula ciudadana' ? 'selected' : '' ?>>Cédula ciudadana</option>
                <option value="tarjeta de identidad" <?= $user['ti_desc'] == 'tarjeta de identidad' ? 'selected' : '' ?>>Tarjeta de identidad</option>
                <option value="cedula ciudadana dig" <?= $user['ti_desc'] == 'cedula ciudadana dig' ? 'selected' : '' ?>>Cédula ciudadana digital</option>
                <option value="pasaporte" <?= $user['ti_desc'] == 'pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                <option value="cedula extranjera" <?= $user['ti_desc'] == 'cedula extranjera' ? 'selected' : '' ?>>Cédula extranjera</option>
            </select>
            
            <input type="text" name="nombre" value="<?= $user['user_nombre'] ?>" placeholder="Nombre" class="soloTexto w-full mb-2 p-2 border rounded" required>
            <input type="text" name="apellido" value="<?= $user['user_apellido'] ?>" placeholder="Apellido" class="soloTexto w-full mb-2 p-2 border rounded" required>
            <input type="email" name="correo" value="<?= $user['user_correo'] ?>" placeholder="Correo" class="w-full mb-2 p-2 border rounded" required>
            <input type="number" name="contra" value="<?= $user['user_contrasena'] ?>" placeholder="Contraseña" class="w-full mb-2 p-2 border rounded" required>
            <input type="number" name="telefono" value="<?= $user['user_telefono'] ?>" placeholder="Teléfono" class="w-full mb-2 p-2 border rounded" required>
            
            <script>
            document.querySelectorAll(".soloTexto").forEach(input => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[0-9]/g, ''); // Elimina cualquier número
            });
            });
            </script>




            <select name="roles" class="w-full mb-2 p-2 border rounded" required>
                <?php while ($rol = $roles_result->fetch_assoc()): ?>
                    <option value="<?= $rol['rol_desc'] ?>" <?= $rol['rol_id'] == $user['rol_id'] ? 'selected' : '' ?>>
                        <?= ucfirst($rol['rol_desc']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="estado" class="w-full mb-2 p-2 border rounded" required>
                <option value="activo" <?= $user['esta_desc'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $user['esta_desc'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Actualizar</button>
            <button type="button" id="cerrarFormulario" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancelar</button>
        </form>
    </div>
</body>
</html>
