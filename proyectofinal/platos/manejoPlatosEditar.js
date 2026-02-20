// =========================================================================
// Variables Globales para Edición (serán inicializadas por obtenerPlatoParaEditar.php)
// Deben ser accesibles a través de window.
// =========================================================================
// window.allIngredientesData;        // Contendrá todos los ingredientes disponibles
// window.ingredientesAsociadosDataInicial; // Contendrá los ingredientes y cantidades originales del plato
// window.currentIngredientesAsociados; // Copia mutable de los ingredientes asociados para el JS

// =========================================================================
// FUNCIONES ESPECÍFICAS PARA EL MODAL DE EDICIÓN DE PLATO E INGREDIENTES
// Todas las funciones llamadas directamente desde HTML (onclick) deben ser globales (window.nombreFuncion)
// =========================================================================

// Función para abrir el modal de selección de ingredientes en EDICIÓN
window.abrirModalIngredientesEditar = function() {
    const modal = document.getElementById("modalIngredientesEditar");
    const contenedor = document.getElementById("contenedorIngredientesEditar");
    contenedor.innerHTML = ''; // Limpiar contenido previo del modal de ingredientes

    if (!window.allIngredientesData) {
        console.error("allIngredientesData no está definido. Asegúrate de que obtenerPlatoParaEditar.php lo inicialice.");
        Swal.fire({
            icon: 'error',
            title: 'Error de Carga',
            text: 'No se pudieron cargar los ingredientes disponibles. Intenta recargar la página.',
            background: '#2C3E50',
            color: '#FAF3E0',
            confirmButtonColor: '#e74c3c'
        });
        return;
    }

    // Cargar los ingredientes disponibles en el modal, marcando los que ya están asociados
    window.allIngredientesData.forEach(ing => {
        const id = ing.ing_id;
        const nombre = ing.ing_nombre;
        // Obtener la cantidad del ingrediente si ya está asociado al plato, si no, cadena vacía
        const cantidad = window.currentIngredientesAsociados[id] !== undefined ? window.currentIngredientesAsociados[id] : '';

        // Determinar si el checkbox debe estar marcado inicialmente
        const isChecked = cantidad !== ''; 

        contenedor.innerHTML += `
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="checkboxIngredienteEditar" data-id="${id}" data-nombre="${nombre}" ${isChecked ? "checked" : ""} onchange="window.toggleCantidadInputEditar(this)">
                <span class="text-white">${nombre}</span>
                <input type="number" class="cantidadIngredienteEditar border rounded px-2 py-1 w-24 bg-gray-700 text-white border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" min="0.01" step="0.01" placeholder="Cantidad" value="${cantidad}" ${isChecked ? '' : 'disabled'}>
            </div>`;
    });

    modal.classList.remove("hidden"); // Mostrar el modal
};

// Función para habilitar/deshabilitar el input de cantidad en EDICIÓN
window.toggleCantidadInputEditar = function(checkbox) {
    const container = checkbox.parentElement;
    const inputCantidad = container.querySelector('.cantidadIngredienteEditar');
    if (checkbox.checked) {
        inputCantidad.disabled = false;
        inputCantidad.focus();
    } else {
        inputCantidad.disabled = true;
        inputCantidad.value = ''; // Vaciar la cantidad si se desmarca
    }
};

// Función para guardar los ingredientes seleccionados y actualizar la lista en el formulario principal
window.guardarIngredientesSeleccionadosEditar = function() {
    window.currentIngredientesAsociados = {}; // Limpiar la lista actual para reconstruirla
    document.querySelectorAll("#modalIngredientesEditar .checkboxIngredienteEditar").forEach((checkbox) => {
        const id = checkbox.dataset.id;
        const cantidadInput = checkbox.parentElement.querySelector('.cantidadIngredienteEditar');
        const cantidad = cantidadInput.value;

        // Si el checkbox está marcado y la cantidad es válida, añadir al objeto de ingredientes actuales
        if (checkbox.checked && parseFloat(cantidad) > 0) {
            window.currentIngredientesAsociados[id] = parseFloat(cantidad);
        }
    });

    generarHiddenInputsIngredientesEditar(); // Vuelve a generar los inputs hidden en el formulario principal
    mostrarIngredientesSeleccionadosEditar(); // Actualiza la lista visible de ingredientes seleccionados
    cerrarModalIngredientesEditar(); // Cierra el modal de selección
};

// Función para cerrar el modal de selección de ingredientes en EDICIÓN
window.cerrarModalIngredientesEditar = function() {
    document.getElementById("modalIngredientesEditar").classList.add("hidden");
};

// Función para generar los inputs hidden del formulario principal de edición
// Esto es crucial para enviar los datos de ingredientes al servidor
function generarHiddenInputsIngredientesEditar() {
    const container = document.getElementById("selectedIngredientsContainerEditar");
    if (!container) {
        console.error("ERROR: El contenedor 'selectedIngredientsContainerEditar' no se encontró en el DOM.");
        return;
    }
    container.innerHTML = ""; // Limpiar inputs hidden anteriores

    for (const id in window.currentIngredientesAsociados) {
        const cantidad = window.currentIngredientesAsociados[id];
        if (parseFloat(cantidad) > 0) {
            container.innerHTML += `
                <input type="hidden" name="ingredientes[${id}][id]" value="${id}">
                <input type="hidden" name="ingredientes[${id}][cantidad]" value="${cantidad}">
            `;
        }
    }
    // Muestra u oculta el contenedor visual de ingredientes seleccionados
    if (Object.keys(window.currentIngredientesAsociados).length > 0) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

// Función para mostrar la lista legible de ingredientes seleccionados en el formulario principal
window.mostrarIngredientesSeleccionadosEditar = function() {
    const container = document.getElementById("selectedIngredientsContainerEditar");
    if (!container) {
        console.error("ERROR: El contenedor 'selectedIngredientsContainerEditar' no se encontró para mostrar ingredientes.");
        return;
    }
    container.innerHTML = ''; // Limpiar contenido anterior

    const listaVisible = document.createElement('ul');
    listaVisible.className = 'list-disc list-inside text-gray-300';
    let hasIngredients = false;

    if (!window.allIngredientesData) {
        console.warn("allIngredientesData no disponible para mostrar ingredientes seleccionados.");
        container.classList.add('hidden');
        return;
    }

    // Recorre todos los ingredientes disponibles para encontrar sus nombres y mostrarlos
    window.allIngredientesData.forEach(ing => {
        if (window.currentIngredientesAsociados[ing.ing_id]) {
            const cantidad = window.currentIngredientesAsociados[ing.ing_id];
            const listItem = document.createElement('li');
            listItem.textContent = `${ing.ing_nombre} - ${cantidad}`;
            listaVisible.appendChild(listItem);
            hasIngredients = true;
        }
    });

    if (hasIngredients) {
        const title = document.createElement('p');
        title.className = 'text-white font-semibold mb-2';
        title.textContent = 'Ingredientes seleccionados:';
        container.appendChild(title);
        container.appendChild(listaVisible);
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
};

// =========================================================================
// LÓGICA DE INICIALIZACIÓN CUANDO SE CARGA EL FORMULARIO DE EDICIÓN
// =========================================================================
document.addEventListener('platoEditContentLoaded', () => {
    // Estas variables globales ya deberían estar definidas por el script PHP de obtenerPlatoParaEditar.php
    if (typeof window.allIngredientesData === 'undefined' || typeof window.ingredientesAsociadosDataInicial === 'undefined') {
        console.error("Las variables allIngredientesData o ingredientesAsociadosDataInicial no están definidas. El script de edición no puede inicializarse correctamente.");
        return;
    }

    // Resetear currentIngredientesAsociados con los datos iniciales del plato
    window.currentIngredientesAsociados = { ...window.ingredientesAsociadosDataInicial };

    // Lógica para deshabilitar inputs de imagen
    const platoImagenUrlInput = document.getElementById('plato_imagen_url_input');
    const platoImagenFileInput = document.getElementById('plato_imagen_file_input');

    if (platoImagenUrlInput && platoImagenFileInput) {
        // Limpiar listeners viejos y añadir nuevos para evitar duplicados
        platoImagenUrlInput.removeEventListener('input', window.toggleImagenInput);
        platoImagenFileInput.removeEventListener('change', window.toggleImagenInput);
        
        platoImagenUrlInput.addEventListener('input', window.toggleImagenInput);
        platoImagenFileInput.addEventListener('change', window.toggleImagenInput);

        // Estado inicial al cargar el formulario de edición
        if (platoImagenUrlInput.value.trim() !== '') {
            platoImagenFileInput.disabled = true;
        } else if (platoImagenFileInput.files && platoImagenFileInput.files.length > 0) {
            platoImagenUrlInput.disabled = true;
        } else {
            platoImagenFileInput.disabled = false;
            platoImagenUrlInput.disabled = false;
        }
    }

    // Generar los inputs hidden y mostrar la lista de ingredientes al cargar el formulario
    generarHiddenInputsIngredientesEditar();
    mostrarIngredientesSeleccionadosEditar();
});

// Nota sobre el reseteo del modal de AGREGAR PLATO:
// Si el modal de agregar plato también muestra ingredientes "heredados",
// necesitas aplicar una lógica de reseteo similar en la función que abre ESE modal (`abrirAddModal`).
// Idealmente, esta lógica debería estar en un archivo JS separado para agregar platos.
// Por ejemplo, dentro de `window.abrirAddModal` (si la tienes global):
/*
window.abrirAddModal = function() {
    // ... tu código existente para abrir el modal add
    // Resetear los inputs hidden para ingredientes
    const addIngredientsContainer = document.getElementById('selectedIngredientsContainer'); // Asumiendo este ID
    if (addIngredientsContainer) {
        addIngredientsContainer.innerHTML = ''; 
        addIngredientsContainer.classList.add('hidden'); 
    }

    // Resetear checkboxes y cantidades en el modal de ingredientes de agregar
    document.querySelectorAll('#modalIngredientesAdd .checkboxIngredienteAdd').forEach(checkbox => {
        checkbox.checked = false;
        const cantidadInput = checkbox.parentElement.querySelector('.cantidadIngredienteAdd');
        if (cantidadInput) {
            cantidadInput.value = '';
            cantidadInput.disabled = true;
        }
    });
    // ...
};
*/