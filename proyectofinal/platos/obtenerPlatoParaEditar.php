<?php
session_start();
$connect = include("../conexion/conectarBD.php");

if (!$connect) {
    echo "<p class='text-red-500'>Error: No se pudo conectar a la base de datos para cargar el formulario.</p>";
    exit();
}

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo "<p class='text-red-500'>Error: ID de plato no proporcionado para edición.</p>";
    exit();
}

$plato_id_a_editar = intval($_GET['id']);

$sqlPlato = "SELECT plato_id, plato_nombre, plato_desc, plato_precio, plato_imagen_url FROM platos WHERE plato_id = ?";
$stmt = mysqli_prepare($connect, $sqlPlato);
mysqli_stmt_bind_param($stmt, "i", $plato_id_a_editar);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$plato = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$plato) {
    echo "<p class='text-red-500'>Error: Plato no encontrado con el ID proporcionado.</p>";
    exit();
}

$sqlIng = "
    SELECT i.ing_id, i.ing_nombre, pi.cantidad 
    FROM plato_ingredientes pi
    INNER JOIN ingredientes i ON pi.ing_id = i.ing_id
    WHERE pi.plato_id = ?
";
$stmtIng = mysqli_prepare($connect, $sqlIng);
mysqli_stmt_bind_param($stmtIng, "i", $plato_id_a_editar);
mysqli_stmt_execute($stmtIng);
$resultIng = mysqli_stmt_get_result($stmtIng);

$ingredientes = [];
while ($row = mysqli_fetch_assoc($resultIng)) {
    $ingredientes[] = $row;
}

mysqli_stmt_close($stmtIng);
// No cerramos la conexión aquí porque si cargamos todos los ingredientes, la necesitaremos.
// mysqli_close($connect); // Desactiva esta línea por ahora, la cerraremos al final del script si es necesario

// --- Obtener TODOS los ingredientes disponibles para el modal de selección ---
$sqlAllIngredientes = "SELECT ing_id, ing_nombre FROM ingredientes ORDER BY ing_nombre ASC";
$resultAllIng = mysqli_query($connect, $sqlAllIngredientes);
$allIngredientes = [];
while ($row = mysqli_fetch_assoc($resultAllIng)) {
    $allIngredientes[] = $row;
}
mysqli_close($connect); // Cierra la conexión después de obtener todos los datos

?>
<form id="editarForm" method="POST" action="procesarEditarPlato.php" enctype="multipart/form-data">
    <input type="hidden" name="plato_id" value="<?= htmlspecialchars($plato['plato_id']) ?>">

    <label class="block font-medium mb-1 text-white">Nombre:</label>
    <input type="text" name="plato_nombre" value="<?= htmlspecialchars($plato['plato_nombre']) ?>" required class="w-full mb-3 border rounded px-3 py-2 text-black">

    <label class="block font-medium mb-1 text-white">Descripción:</label>
    <textarea name="plato_desc" required class="w-full mb-3 border rounded px-3 py-2 text-black"><?= htmlspecialchars($plato['plato_desc']) ?></textarea>

    <label class="block font-medium mb-1 text-white">Precio:</label>
    <input type="number" name="plato_precio" step="0.01" value="<?= htmlspecialchars($plato['plato_precio']) ?>" required class="w-full mb-3 border rounded px-3 py-2 text-black">

    <div class="mb-3">
        <label class="block font-medium mb-1 text-white">Imagen:</label>
        <div class="flex flex-col md:flex-row gap-4 mb-2 items-end">
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium mb-1 text-gray-300">URL de Imagen:</label>
                <input type="text" name="plato_imagen_url" id="plato_imagen_url_input" value="<?= htmlspecialchars($plato['plato_imagen_url'] ?? '') ?>" class="w-full border rounded px-3 py-2 text-black">
            </div>
            <div class="flex-shrink-0 text-gray-400 font-semibold mb-2">O</div>
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium mb-1 text-gray-300">Subir desde Computadora:</label>
                <input type="file" name="plato_imagen_file" id="plato_imagen_file_input" accept="image/*" class="w-full text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
            </div>
        </div>
        <p class="text-xs text-gray-300 italic mt-1">La imagen cargada localmente reemplaza la URL existente.</p>
    </div>

    <div class="flex flex-wrap gap-4 mb-4 justify-start">
        <div>
            <button type="button" onclick="mostrarIngredientesSeleccionados()" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-lg">Ver Ingredientes</button>
        </div>
        <div>
            <button type="button" onclick="abrirModalIngredientes()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Seleccionar Ingredientes</button>
        </div>
    </div>

    <div id="ingredientesHiddenContainer">
        </div>

    <div class="flex justify-end gap-2 mt-4">
        <button type="button" id="cancelarEditarFormBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cancelar</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar Cambios</button>
    </div>
</form>

<div id="ingredientesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[60] transition-all duration-300">
    <div class="bg-[#2C3E50] p-6 rounded-2xl shadow-2xl w-full max-w-lg relative space-y-4 animate__animated animate__fadeInDown">
        <h3 class="text-2xl font-bold text-[#C0392B] mb-4 text-center">Seleccionar Ingredientes</h3>
        <div id="ingredientesList" class="max-h-80 overflow-y-auto pr-2 custom-scrollbar">
            </div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="cerrarModalIngredientes()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cancelar</button>
            <button type="button" onclick="guardarIngredientesSeleccionados()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Confirmar</button>
        </div>
    </div>
</div>

<style>
/* Estilos para la scrollbar personalizada */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #34495E;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #7F8C8D;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #95A5A6;
}
</style>

<script>
    // Variables JavaScript globales para manejar los ingredientes
    // Inicializamos con los ingredientes actuales del plato
    let selectedIngredientes = <?= json_encode($ingredientes) ?>;
    // Guardamos todos los ingredientes disponibles para el modal de selección
    const allAvailableIngredientes = <?= json_encode($allIngredientes) ?>;

    // Función para abrir el modal de selección de ingredientes
    function abrirModalIngredientes() {
        const modal = document.getElementById('ingredientesModal');
        const ingredientesListDiv = document.getElementById('ingredientesList');
        ingredientesListDiv.innerHTML = ''; // Limpiar la lista

        // Renderizar todos los ingredientes disponibles
        allAvailableIngredientes.forEach(ing => {
            const isSelected = selectedIngredientes.some(si => si.ing_id == ing.ing_id);
            const currentQuantity = isSelected ? selectedIngredientes.find(si => si.ing_id == ing.ing_id).cantidad : '';

            const ingItem = document.createElement('div');
            ingItem.className = 'flex items-center justify-between p-2 mb-2 bg-[#34495E] rounded-md shadow-sm';
            ingItem.innerHTML = `
                <label class="flex items-center cursor-pointer flex-1">
                    <input type="checkbox" data-ing-id="${ing.ing_id}" data-ing-nombre="${ing.ing_nombre}" ${isSelected ? 'checked' : ''} 
                           class="form-checkbox h-5 w-5 text-blue-600 rounded mr-3">
                    <span class="text-white text-lg">${ing.ing_nombre}</span>
                </label>
                <input type="number" data-ing-id-qty="${ing.ing_id}" value="${currentQuantity}" placeholder="Cantidad" 
                       class="w-24 px-2 py-1 border rounded text-black text-center" 
                       min="0" step="0.01" ${isSelected ? '' : 'disabled'}>
            `;
            ingredientesListDiv.appendChild(ingItem);
        });

        // Añadir event listeners para los checkboxes y inputs de cantidad
        ingredientesListDiv.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                const qtyInput = ingredientesListDiv.querySelector(`input[data-ing-id-qty="${event.target.dataset.ingId}"]`);
                if (event.target.checked) {
                    qtyInput.disabled = false;
                    qtyInput.focus();
                } else {
                    qtyInput.disabled = true;
                    qtyInput.value = ''; // Limpiar cantidad si se deselecciona
                }
            });
        });

        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        modal.querySelector('.animate__animated').classList.remove('animate__fadeOutUp');
        modal.querySelector('.animate__animated').classList.add('animate__fadeInDown');
    }

    // Función para cerrar el modal de ingredientes
    function cerrarModalIngredientes() {
        const modal = document.getElementById('ingredientesModal');
        const content = modal.querySelector('.animate__animated');

        content.classList.remove('animate__fadeInDown');
        content.classList.add('animate__fadeOutUp');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            content.classList.remove('animate__fadeOutUp');
        }, 500);
    }
    window.cerrarModalIngredientes = cerrarModalIngredientes; // Hacer global si se usa en HTML

    // Función para guardar los ingredientes seleccionados del modal
    function guardarIngredientesSeleccionados() {
        const ingredientesListDiv = document.getElementById('ingredientesList');
        const checkboxes = ingredientesListDiv.querySelectorAll('input[type="checkbox"]');
        let newSelectedIngredientes = [];
        let validationErrors = [];

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const ingId = parseInt(checkbox.dataset.ingId);
                const ingNombre = checkbox.dataset.ingNombre;
                const qtyInput = ingredientesListDiv.querySelector(`input[data-ing-id-qty="${ingId}"]`);
                const cantidad = parseFloat(qtyInput.value);

                if (isNaN(cantidad) || cantidad <= 0) {
                    validationErrors.push(`La cantidad para "${ingNombre}" debe ser un número positivo.`);
                } else {
                    newSelectedIngredientes.push({ ing_id: ingId, ing_nombre: ingNombre, cantidad: cantidad });
                }
            }
        });

        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: validationErrors.join('<br>'),
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#e74c3c'
            });
            return; // No cerrar el modal si hay errores
        }

        selectedIngredientes = newSelectedIngredientes;
        actualizarHiddenInputsIngredientes(); // Actualizar los campos hidden del formulario principal
        cerrarModalIngredientes();
        mostrarIngredientesSeleccionados(); // Opcional: mostrar un resumen de lo seleccionado
    }

    // Función para actualizar los campos hidden del formulario principal
    function actualizarHiddenInputsIngredientes() {
        const container = document.getElementById('ingredientesHiddenContainer');
        container.innerHTML = ''; // Limpiar campos hidden anteriores

        selectedIngredientes.forEach(ing => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `ingredientes[${ing.ing_id}][id]`;
            inputId.value = ing.ing_id;
            container.appendChild(inputId);

            const inputCantidad = document.createElement('input');
            inputCantidad.type = 'hidden';
            inputCantidad.name = `ingredientes[${ing.ing_id}][cantidad]`;
            inputCantidad.value = ing.cantidad;
            container.appendChild(inputCantidad);
        });
        console.log("Ingredientes hidden actualizados:", selectedIngredientes);
    }

    // Función para mostrar los ingredientes seleccionados (opcional, para depuración/UX)
    function mostrarIngredientesSeleccionados() {
        if (selectedIngredientes.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Ingredientes del Plato',
                text: 'Este plato no tiene ingredientes seleccionados.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        let htmlList = '<ul class="list-disc list-inside text-left">';
        selectedIngredientes.forEach(ing => {
            htmlList += `<li><strong class="text-blue-300">${ing.ing_nombre}:</strong> ${ing.cantidad}</li>`;
        });
        htmlList += '</ul>';

        Swal.fire({
            title: 'Ingredientes Seleccionados',
            html: htmlList,
            icon: 'info',
            background: '#2C3E50',
            color: '#FAF3E0',
            confirmButtonColor: '#3498db'
        });
    }

    // Asegúrate de que al cargar el formulario de edición, los hidden inputs estén correctos
    document.addEventListener('DOMContentLoaded', () => {
        // Esto solo se ejecuta una vez al cargar platos.php, no cuando se carga el formulario de edición dinámicamente.
        // La inicialización de selectedIngredientes y allAvailableIngredientes ya se hace arriba con PHP.
        // Lo que necesitamos es que cuando se cargue el contenido de obtenerPlatoParaEditar.php en el modal,
        // se llame a actualizarHiddenInputsIngredientes() para que los inputs estén presentes.

        // Añadir un observador de mutaciones o llamar a la función después de que el HTML del formulario se inserte.
        // En platos.php, dentro del .then(html => { modalContentDiv.innerHTML = html; ... });
        // deberías llamar a window.actualizarHiddenInputsIngredientes(); una vez que el formulario se carga.
    });

    // Esta función debe llamarse cada vez que el formulario de edición se cargue en el modal
    // para asegurar que los inputs ocultos de ingredientes están presentes y correctos.
    window.initializeEditFormIngredientes = function() {
        actualizarHiddenInputsIngredientes();
    };

</script>