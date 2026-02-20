<?php
session_start();
include("../conexion/conectarBD.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Manejo de imagen
    $foto_nombre = "";
    if (!empty($_FILES['foto']['name'])) {
        $foto_nombre = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $ruta = "../fotos/" . $foto_nombre;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta);
    }

    // Actualizar
    if ($foto_nombre !== "") {
        $sql = "UPDATE usuarios SET user_nombre=?, user_apellido=?, user_correo=?, user_telefono=?, user_foto=? WHERE user_id=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $apellido, $correo, $telefono, $foto_nombre, $user_id);
    } else {
        $sql = "UPDATE usuarios SET user_nombre=?, user_apellido=?, user_correo=?, user_telefono=? WHERE user_id=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $telefono, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire('¡Éxito!', 'Perfil actualizado correctamente.', 'success').then(() => {
                    window.location.href = 'perfilAdmin.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire('Error', 'No se pudo actualizar.', 'error').then(() => {
                    window.location.href = 'perfilAdmin.php';
                });
              </script>";
    }
}
?>
