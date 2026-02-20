<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

$id = $_GET['id'];
$conexion->query("DELETE FROM pedidos WHERE id = $id");

header("Location: listar_pedidos.php");