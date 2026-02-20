<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
include("../conexion/conectarBD.php");

$response = ['success' => false, 'error' => ''];

if (!$connect) {
    $response['error'] = 'Fallo la conexión a la base de datos.';
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['clave'] ?? '');
    $rol_nombre = strtolower(trim($_POST['rol'] ?? ''));

    if (empty($email) || empty($password) || empty($rol_nombre)) {
        $response['error'] = 'Por favor, completa todos los campos.';
        echo json_encode($response);
        exit();
    }

    // Buscar rol_id por nombre
    $rol_stmt = $connect->prepare("SELECT rol_id, rol_desc FROM roles WHERE LOWER(rol_desc) = ?");
    $rol_stmt->bind_param("s", $rol_nombre);
    $rol_stmt->execute();
    $rol_result = $rol_stmt->get_result();

    if ($rol_result->num_rows === 0) {
        $response['error'] = 'Rol inválido.';
        echo json_encode($response);
        exit();
    }

    $rol_data = $rol_result->fetch_assoc();
    $rol_id = $rol_data['rol_id'];
    $rol_desc = $rol_data['rol_desc'];
    $rol_stmt->close();

    // Buscar al usuario con correo, contraseña y rol_id
    $sql = "SELECT * FROM usuarios WHERE user_correo = ? AND user_contrasena = ? AND rol_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ssi", $email, $password, $rol_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();

        // Si usas password_verify() con contraseñas encriptadas, reemplaza esta condición
        if ($fila['user_contrasena'] === $password) {
            $_SESSION['user_id'] = $fila['user_id'];
            $_SESSION['user_email'] = $fila['user_correo'];
            $_SESSION['user_role_id'] = $fila['rol_id'];
            $_SESSION['user_role_name'] = $rol_desc;
            


            $response['success'] = true;

            // Redirigir según el rol
            switch ($rol_desc) {
                case 'administrador':
                    $response['redirect'] = '../loginadmin/prueba.php';
                    break;
                case 'cajero':
                    $response['redirect'] = '../cajero/inicio/cajero.php';
                    break;
                default:
                    $response['redirect'] = '../loginadmin/prueba.php';
                    break;
            }
        } else {
            $response['error'] = 'Credenciales incorrectas.';
        }
    } else {
        $response['error'] = 'Credenciales incorrectas.';
    }

    $stmt->close();
} else {
    $response['error'] = 'Método no permitido.';
}

echo json_encode($response);
exit();
