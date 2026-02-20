<?php
include '../conexion/conectarBD.php';
header("Content-Type: application/json; charset=UTF-8");

// ✅ Verificar que todos los campos existen antes de usarlos
$campos = ['id', 'tipoID', 'nombre', 'apellido', 'correo', 'contra', 'telefono', 'rol', 'estado'];
foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(["success" => false, "error" => "Falta el campo: $campo"]);
        exit;
    }
}

// 📥 Asignar valores después de verificar existencia
$id = $_POST['id'];
$tipoID = $_POST['tipoID'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contra = $_POST['contra'];
$telefono = $_POST['telefono'];
$rolNombre = $_POST['rol'];
$estadoInput = $_POST['estado'];


// 🔁 Convertir nombre de rol a rol_id
$rol_id = null;
$rol_sql = "SELECT rol_id FROM roles WHERE rol_desc = ?";
$rol_stmt = $connect->prepare($rol_sql);
$rol_stmt->bind_param("s", $rolNombre);
$rol_stmt->execute();
$rol_result = $rol_stmt->get_result();
if ($fila = $rol_result->fetch_assoc()) {
    $rol_id = $fila['rol_id'];
} else {
    echo json_encode(["success" => false, "error" => "Rol no válido."]);
    exit;
}

// ✅ Convertir estado a esta_id
$estadoTexto = strtolower(trim($estadoInput));
$estado_id = null;

if ($estadoTexto === "activo") {
    $estado_id = 1;
} elseif ($estadoTexto === "inactivo") {
    $estado_id = 2;
} elseif (in_array($estadoTexto, ["1", "2"])) {
    $estado_id = intval($estadoTexto);
} else {
    echo json_encode(["success" => false, "error" => "Estado inválido."]);
    exit;
}

// 🔎 Verificar duplicados
$check_sql = "SELECT * FROM usuarios WHERE user_id = ? OR user_correo = ? OR user_telefono = ?";
$stmt = $connect->prepare($check_sql);
$stmt->bind_param("sss", $id, $correo, $telefono);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "El ID, correo o teléfono ya está registrado."]);
    exit;
}

// ✅ Insertar nuevo usuario
$insert_sql = "INSERT INTO usuarios 
    (user_id, ti_desc, user_nombre, user_apellido, user_correo, user_contrasena, user_telefono, rol_id, esta_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $connect->prepare($insert_sql);
$stmt->bind_param("sssssssis", $id, $tipoID, $nombre, $apellido, $correo, $contra, $telefono, $rol_id, $estado_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}
exit;
?>
