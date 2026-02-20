<?php
include '../conexion/conectarBD.php';
$id = $_GET['id'];
$delete = "DELETE FROM usuarios WHERE user_id = $id";
mysqli_query($connect, $delete);
header("Location: index.php");
?>