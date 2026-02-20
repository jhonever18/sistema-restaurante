<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "restaurante";

$connect = mysqli_connect($server, $user, $password, $database);

if (!$connect) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8");

return $connect;
?>



