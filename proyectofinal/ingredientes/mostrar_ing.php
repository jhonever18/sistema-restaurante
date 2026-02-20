<?php
include("../conexion/conectarBD.php");

// Página actual (por GET), si no existe, mostrar la 1
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$inicio = ($pagina - 1) * $limite;

// Consulta con LIMIT
$sql = "SELECT 
            i.ing_id,
            i.ing_nombre,
            i.ing_desc,
            i.ing_cantidad,
            u.unidad_abreviacion,
            i.ing_precio,
            i.esta_id
        FROM ingredientes i
        LEFT JOIN unidades_medida u ON i.unidad_id = u.unidad_id
        LIMIT $inicio, $limite";

$resultado = mysqli_query($connect, $sql);

// Para contar cuántos ingredientes hay en total (para paginación)
$total_sql = "SELECT COUNT(*) AS total FROM ingredientes";
$total_result = mysqli_query($connect, $total_sql);
$total_fila = mysqli_fetch_assoc($total_result);
$total_ingredientes = $total_fila['total'];
$total_paginas = ceil($total_ingredientes / $limite);

// Mostramos los datos
while($fila = mysqli_fetch_assoc($resultado)):
?>
<tr class="border-b bg-[#2C3E50] text-[#faf3e0]">
    <td class="py-2 px-4"><?= $fila['ing_id'] ?></td>
    <td class="py-2 px-4"><?= $fila['ing_nombre'] ?></td>
    <td class="py-2 px-4"><?= $fila['ing_desc'] ?></td>
    <td class="py-2 px-4"><?= $fila['ing_cantidad'] ?></td>
    <td class="py-2 px-4"><?= $fila['unidad_abreviacion'] ?></td>
    <td class="py-2 px-4">$<?= number_format($fila['ing_precio'], 0, ',', '.') ?></td>
    <td class="py-2 px-4">
        <span class="<?= $fila['esta_id'] == 1 ? 'text-green-600' : 'text-red-600' ?>">
            <?= $fila['esta_id'] == 1 ? 'Activo' : 'Inactivo' ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>

    </tbody>



<script>
document.getElementById("btnCambiarEstado").addEventListener("click", () => {
    const seleccionados = document.querySelectorAll(".checkSeleccionar:checked");

    if (seleccionados.length === 0) {
        alert("⚠️ Selecciona al menos un ingrediente.");
        return;
    }

    const datos = [];

    seleccionados.forEach(chk => {
        const id = chk.value;
        const estadoActual = parseInt(chk.dataset.estado);
        const nuevoEstado = estadoActual === 1 ? 0 : 1;

        datos.push({ id, estado: nuevoEstado });
    });

    fetch("ingredientes.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action: "cambiar_estado", ingredientes: datos })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert("❌ " + data.message);
        }
    })
    .catch(err => {
        console.error("Error al cambiar el estado:", err);
        alert("❌ Ocurrió un error al cambiar el estado.");
    });
});
</script>



