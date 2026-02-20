<?php
include '../conexion/conexion.php';

$buscar = isset($_GET['buscar']) ? mysqli_real_escape_string($connect, $_GET['buscar']) : '';

$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;


$sql = "SELECT * FROM usuarios 
        WHERE user_id LIKE '%$buscar%' 
        OR user_nombre LIKE '%$buscar%' 
        OR user_apellido LIKE '%$buscar%'
        LIMIT $inicio, $por_pagina";

$res = mysqli_query($connect, $sql);

while ($row = mysqli_fetch_assoc($res)) {
    echo "<tr class='border-b'>
        <td class='p-2 border text-center bg-white'>{$row['user_id']}</td>
        <td class='p-2 border text-center bg-white'>{$row['ti_desc']}</td>
        <td class='p-2 border text-center bg-white'>{$row['user_nombre']}</td>
        <td class='p-2 border text-center bg-white'>{$row['user_apellido']}</td>
        <td class='p-2 border text-center bg-white'>{$row['user_correo']}</td>
        <td class='p-2 border text-center bg-white'>{$row['user_contrasena']}</td>
        <td class='p-2 border text-center bg-white'>{$row['user_telefono']}</td>
        <td class='p-2 border text-center bg-white'>{$row['rol_desc']}</td>
        <td class='p-2 border text-center bg-white'>{$row['esta_desc']}</td>
        <td class='p-2 border text-center bg-white'>
            <a data-id='{$row['user_id']}' class='editar-btn bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 items-center'>Editar</a>
            <a href='eliminar.php?id={$row['user_id']}' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");' class='bg-[#C0392B] text-white px-3 py-1 rounded hover:bg-red-700'>Eliminar</a>
        </td>
    </tr>";
}
?>