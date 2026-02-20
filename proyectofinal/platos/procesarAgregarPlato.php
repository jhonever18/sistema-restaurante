<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

$connect = include("../conexion/conectarBD.php");

if (!$connect) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]);
    exit;
}

mysqli_autocommit($connect, FALSE); // Iniciar transacción
$transaction_successful = true;

// Directorio donde se guardarán las imágenes
// ASEGÚRATE DE QUE ESTE DIRECTORIO EXISTA Y TENGA PERMISOS DE ESCRITURA (0755 o 0777 para pruebas)
$target_dir = "../uploads/platos/"; // Ajusta esta ruta si es necesario

// Crear el directorio si no existe
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) { // Permisos 0777 para desarrollo, ajustar a 0755 en producción
        echo json_encode(["success" => false, "message" => "Error: No se pudo crear el directorio de subida de imágenes."]);
        mysqli_close($connect);
        exit();
    }
}


// Validar que haya datos
if (empty($_POST)) {
    echo json_encode(["success" => false, "message" => "No se recibieron datos."]);
    exit;
}

// Validar campos obligatorios
$required = ['nombre', 'precio'];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos",
            "detalle" => "Por favor completa todos los campos obligatorios."
        ]);
        exit;
    }
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre']);
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$precio = floatval($_POST['precio']);

// Originalmente tenías 'imagen'. Ahora vamos a manejar 'plato_imagen_url' y 'plato_imagen_file'.
$plato_imagen_url_from_input = isset($_POST['plato_imagen_url']) ? trim($_POST['plato_imagen_url']) : null;
$final_image_path = $plato_imagen_url_from_input; // Por defecto, usamos la URL si no se sube archivo

$categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] !== '' ? intval($_POST['categoria_id']) : null;
$es_popular = isset($_POST['es_popular']) ? 1 : 0;
$ingredientes = isset($_POST['ingrediente']) && is_array($_POST['ingrediente']) ? $_POST['ingrediente'] : [];

try {
    // 1. Manejo de la subida de archivo de imagen
    // El input file en el modalAgregarPlato.php debería llamarse 'plato_imagen_file'
    if (isset($_FILES['plato_imagen_file']) && $_FILES['plato_imagen_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['plato_imagen_file']['tmp_name'];
        $file_name = basename($_FILES['plato_imagen_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_extensions)) {
            throw new Exception("Error: Solo se permiten archivos JPG, JPEG, PNG y GIF.");
        }

        // Generar un nombre único para el archivo para evitar colisiones
        $new_file_name = uniqid('plato_') . '.' . $file_ext; // Prefijo 'plato_' para identificar
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_name, $target_file)) {
            // La subida fue exitosa, esta será la URL final de la imagen
            $final_image_path = $target_file; // Guardamos la ruta local en la BD
        } else {
            throw new Exception("Error al subir la imagen. Código de error: " . $_FILES['plato_imagen_file']['error']);
        }
    } 
    // Si no se subió un archivo, y el campo de URL también está vacío, la URL final será null.
    // De lo contrario, ya se usó el valor de $plato_imagen_url_from_input.
    else if (empty($plato_imagen_url_from_input)) {
        $final_image_path = null;
    }


    // Insertar plato
    // Usamos $final_image_path que contiene la URL del archivo subido o la URL del input
    $stmt = $connect->prepare("INSERT INTO platos (plato_nombre, plato_desc, plato_precio, plato_imagen_url, categoria_id, es_popular) VALUES (?, ?, ?, ?, ?, ?)");
    
    // El tipo para categoria_id puede ser 's' (string) si a veces es null, o 'i' si siempre es un entero.
    // Para manejar nulls con bind_param, a veces es mejor pasar la variable directamente a null.
    // Aunque para 'i' PHP suele convertir null a 0.
    // Si categoria_id puede ser null en la base de datos, asegúrate de que la columna lo permita.
    // Si estás usando PDO, es más sencillo. Con mysqli, 's' es más flexible para null.
    $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $final_image_path, $categoria_id, $es_popular);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al insertar plato: " . $stmt->error);
    }

    $plato_id = $connect->insert_id;
    $stmt->close();

    // Insertar ingredientes
    if (!empty($ingredientes)) {
        $stmtIng = $connect->prepare("INSERT INTO plato_ingredientes (plato_id, ing_id, cantidad) VALUES (?, ?, ?)");
        foreach ($ingredientes as $ing) {
            if (!isset($ing['id']) || !isset($ing['cantidad'])) continue;
            $ing_id = intval($ing['id']);
            $cantidad = floatval($ing['cantidad']);
            if ($cantidad <= 0) continue; // No insertar ingredientes con cantidad 0 o menos

            $stmtIng->bind_param("iid", $plato_id, $ing_id, $cantidad);
            if (!$stmtIng->execute()) {
                throw new Exception("Error al insertar ingrediente: " . $stmtIng->error);
            }
        }
        $stmtIng->close();
    }

    mysqli_commit($connect);
    echo json_encode(["success" => true, "message" => "Plato agregado correctamente.", "categoria_id" => $categoria_id]);

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$connect->close();
?>