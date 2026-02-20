<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="fixed bottom-6 right-6 flex gap-3 z-50 shadow-lg">
    <button id="agregar-btn" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition shadow-md">➕ Agregar</button>
    <button id="view-btn" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition shadow-md">👁️ Ver</button>
    <button id="edit-btn" class="bg-yellow-500 text-white px-4 py-2 rounded-full hover:bg-yellow-600 transition shadow-md">✏️ Editar</button>
    <button id="delete-btn" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition shadow-md">🗑️ Eliminar</button>
</div>

<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-all duration-300">
    <div class="bg-[#2C3E50] p-6 rounded-2xl shadow-2xl w-96 max-w-full text-center relative space-y-4 animate__animated animate__fadeInDown">
        <h3 class="text-2xl font-bold text-[#C0392B]" id="modal-plato-nombre-view"></h3>
        <div>
            <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Ingredientes</p>
            <p class="text-[#FAF3E0] text-base" id="modal-ingredientes-view">No especificados</p>
        </div>
        <div>
            <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Descripción</p>
            <p class="text-[#FAF3E0] text-base" id="modal-descripcion-view"></p>
        </div>
        <div>
            <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Precio</p>
            <p class="text-[#27AE60] text-lg font-bold" id="modal-precio-view"></p>
        </div>
        <button onclick="cerrarViewModal()" class="bg-[#C0392B] text-white px-6 py-2 rounded-full hover:bg-red-700 transition">Cerrar</button>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div id="editModalContent" class="bg-[#2C3E50] rounded-lg shadow-lg w-full max-w-lg p-4 relative animate__animated animate__fadeInDown">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-center text-[#faf3e0]">Editar Plato</h2>
        </div>
        <div id="modalContent"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// =========================================================================
// VARIABLES GLOBALES (o accesibles por todo el script)
// =========================================================================
let platoSeleccionadoId = null;
let tarjetaSeleccionada = null;
let datosOriginales = null; // Para el formulario de edición

// =========================================================================
// FUNCIÓN PARA CERRAR EL MODAL DE VER PLATO
// =========================================================================
function cerrarViewModal() {
    const modal = document.getElementById('viewModal');
    const content = modal.querySelector('.animate__animated');

    content.classList.remove('animate__fadeInDown');
    content.classList.add('animate__fadeOutUp');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        content.classList.remove('animate__fadeOutUp');
    }, 500);
}
window.cerrarViewModal = cerrarViewModal;

// =========================================================================
// FUNCIÓN PARA CERRAR EL MODAL DE EDICIÓN
// =========================================================================
window.cerrarEditModal = function() {
    Swal.fire({
        icon: 'info',
        title: 'Edición Cancelada',
        text: 'Los cambios no han sido guardados.',
        background: '#2C3E50',
        color: '#FAF3E0',
        confirmButtonColor: '#27AE60',
        timer: 1500,
        showConfirmButton: false
    });

    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');

    modalContent.classList.remove('animate__fadeInDown');
    modalContent.classList.add('animate__fadeOutUp');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        modalContent.classList.remove('animate__fadeOutUp');
        modalContent.classList.add('animate__fadeInDown'); // Reset animation class
    }, 500);
};

// =========================================================================
// FUNCIÓN PARA CONFIRMAR Y ELIMINAR PLATO
// =========================================================================
window.confirmarEliminarPlato = function(platoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto! Se eliminará el plato.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#2C3E50',
        color: '#FAF3E0'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('procesarEliminarPlato.php', { // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `plato_id=${platoId}`
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const jsonError = JSON.parse(text);
                            throw new Error(jsonError.message || 'Error de red desconocido.');
                        } catch (e) {
                            throw new Error('Error HTTP ' + response.status + ': ' + text);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        background: '#2C3E50',
                        color: '#FAF3E0',
                        title: '¡Eliminado!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        if (typeof cargarPlatos === 'function') {
                            cargarPlatos(); // Asume que esta función recarga la lista de platos
                        } else {
                            location.reload(); // Si no hay, recarga la página
                        }
                    });
                } else {
                    Swal.fire(
                        'Error',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error al eliminar el plato:', error);
                Swal.fire(
                    'Error',
                    error.message || 'Hubo un problema al comunicarse con el servidor.',
                    'error'
                );
            });
        }
    });
};

// =========================================================================
// FUNCIÓN AUXILIAR PARA CONVERTIR FormData a un objeto plano
// =========================================================================
function formDataToObject(formData) {
    const obj = {};
    for (const [key, value] of formData.entries()) {
        obj[key] = value;
    }
    return obj;
}

// =========================================================================
// FUNCIÓN PARA MANEJAR EL ENVÍO DEL FORMULARIO DE EDICIÓN
// =========================================================================
function handleEditarFormSubmit(event) {
    event.preventDefault();

    const editarForm = event.target;
    const currentFormData = new FormData(editarForm);
    // Ya no se compara con datosOriginales, se envía siempre

    fetch('../platos/procesarEditarPlato.php', { // Asegúrate de que esta ruta sea correcta
        method: 'POST',
        body: currentFormData // FormData puede manejar archivos y texto
    })
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => {
                try {
                    const jsonError = JSON.parse(text);
                    throw new Error(jsonError.message || `Error de servidor: ${text.substring(0, 200)}...`);
                } catch (e) {
                    throw new Error(`Error HTTP ${res.status}: ${text.substring(0, 200)}...`);
                }
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Plato actualizado!',
                text: data.message,
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#27ae60',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.cerrarEditModal();
                if (typeof cargarPlatos === 'function') {
                    cargarPlatos(); // Asume que esta función recarga la lista de platos
                } else {
                    location.reload(); // Si no hay, recarga la página
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: data.message || 'Hubo un problema desconocido.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#e74c3c'
            });
        }
    })
    .catch(error => {
        console.error('Error en la solicitud Fetch del formulario de edición:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de red o JSON',
            text: error.message || 'No se pudo procesar la solicitud o hubo un problema con la respuesta del servidor.',
            background: '#2C3E50',
            color: '#FAF3E0',
            confirmButtonColor: '#e74c3c'
        });
    });
}

// Función para deshabilitar inputs de imagen
function toggleImagenInput(event) {
    const platoImagenUrlInput = document.getElementById('plato_imagen_url_input');
    const platoImagenFileInput = document.getElementById('plato_imagen_file_input');

    if (event.target.id === 'plato_imagen_url_input') {
        if (event.target.value.trim() !== '') {
            platoImagenFileInput.disabled = true;
            platoImagenFileInput.value = ''; // Clear file input
        } else {
            platoImagenFileInput.disabled = false;
        }
    } else if (event.target.id === 'plato_imagen_file_input') {
        if (event.target.files.length > 0) {
            platoImagenUrlInput.disabled = true;
            platoImagenUrlInput.value = ''; // Clear URL input
        } else {
            platoImagenUrlInput.disabled = false;
        }
    }
}


// =========================================================================
// INICIALIZACIÓN PRINCIPAL DEL DOM Y EVENT LISTENERS
// =========================================================================
document.addEventListener("DOMContentLoaded", () => {
    // Event listener para la selección de platos (tarjetas)
    document.body.addEventListener('click', (event) => {
        const clickedItem = event.target.closest('.food-item');

        if (clickedItem) {
            if (tarjetaSeleccionada && tarjetaSeleccionada !== clickedItem) {
                tarjetaSeleccionada.classList.remove('selected');
            }
            clickedItem.classList.add('selected');

            tarjetaSeleccionada = clickedItem;
            platoSeleccionadoId = clickedItem.dataset.id;

            console.log('Plato seleccionado:', platoSeleccionadoId);
        }
    });

    // Listener para el botón VER (👁️)
    document.getElementById('view-btn').addEventListener('click', () => {
        if (!tarjetaSeleccionada || !tarjetaSeleccionada.dataset.id) {
            Swal.fire({
                icon: 'info',
                title: 'Selecciona un plato',
                text: 'Debes seleccionar una carta para ver los detalles.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        const platoId = tarjetaSeleccionada.dataset.id;
        document.getElementById('modal-plato-nombre-view').textContent = 'Cargando...';
        document.getElementById('modal-ingredientes-view').textContent = 'Cargando ingredientes...';
        document.getElementById('modal-descripcion-view').textContent = 'Cargando descripción...';
        document.getElementById('modal-precio-view').textContent = 'Cargando precio...';

        const modal = document.getElementById('viewModal');
        const content = modal.querySelector('.animate__animated');

        modal.classList.remove('hidden');
        modal.style.display = 'flex';

        content.classList.remove('animate__fadeOutUp');
        content.classList.add('animate__fadeInDown');

        fetch(`/proyectofinal/platos/obtenerPlatoDetalles.php?id=${platoId}`) // Asegúrate de que esta ruta sea correcta
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => {
                        try {
                            const jsonError = JSON.parse(text);
                            throw new Error(jsonError.message || `Error de servidor: ${text.substring(0, 100)}...`);
                        } catch (e) {
                            throw new Error(`Error HTTP ${res.status}: ${text.substring(0, 100)}...`);
                        }
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.success && data.plato) {
                    const p = data.plato;
                    document.getElementById('modal-plato-nombre-view').textContent = p.plato_nombre;
                    document.getElementById('modal-descripcion-view').textContent = p.plato_desc;
                    document.getElementById('modal-precio-view').textContent = `Precio: $${parseFloat(p.plato_precio).toFixed(2)}`;

                    if (p.ingredientes && p.ingredientes.length > 0) {
                        const listaIngredientesHtml = p.ingredientes.map(ing =>
                            `<span class="text-blue-400">${ing.ing_nombre} (${ing.cantidad})</span>`
                        ).join(', ');
                        document.getElementById('modal-ingredientes-view').innerHTML = `Ingredientes: ${listaIngredientesHtml}`;
                    } else {
                        document.getElementById('modal-ingredientes-view').textContent = 'Ingredientes: No especificados';
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar plato',
                        text: data.message || 'No se pudo obtener la información del plato.',
                        background: '#2C3E50',
                        color: '#FAF3E0',
                        confirmButtonColor: '#e74c3c'
                    });
                    cerrarViewModal();
                }
            })
            .catch(error => {
                console.error('Error en la solicitud Fetch:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo cargar la información del plato. Verifica la conexión o la ruta del archivo PHP.',
                    background: '#2C3E50',
                    color: '#FAF3E0',
                    confirmButtonColor: '#e74c3c'
                });
                cerrarViewModal();
            });
    });

    // Listener para el botón EDITAR (✏️)
    document.getElementById('edit-btn').addEventListener('click', () => {
        if (!platoSeleccionadoId) {
            Swal.fire({
                icon: 'info',
                title: 'Selecciona un plato',
                text: 'Debes seleccionar una carta para editar.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        const modal = document.getElementById('editModal');
        const modalContentDiv = document.getElementById('modalContent');

        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        modalContentDiv.innerHTML = '<p class="text-gray-600 text-center">Cargando formulario...</p>';

        fetch('obtenerPlatoParaEditar.php?id=' + platoSeleccionadoId) // Asegúrate de que esta ruta sea correcta
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => { throw new Error('Error HTTP ' + res.status + ': ' + text); });
                }
                return res.text();
            })
           .then(html => {
                modalContentDiv.innerHTML = html;

                const editarForm = document.getElementById('editarForm');
                if (editarForm) {
                    // Initialize datosOriginales AFTER the form is loaded
                    // Aunque ya no se usa para la comparación, se mantiene por si en el futuro se quiere reincorporar
                    datosOriginales = formDataToObject(new FormData(editarForm));
                    console.log("Datos Originales capturados al abrir edición:", datosOriginales);

                    // Re-attach event listener to avoid duplicates if modal is opened multiple times
                    editarForm.removeEventListener('submit', handleEditarFormSubmit);
                    editarForm.addEventListener('submit', handleEditarFormSubmit);

                    const cancelButton = editarForm.querySelector('#cancelarEditarFormBtn');
                    if (cancelButton) {
                        cancelButton.removeEventListener('click', window.cerrarEditModal);
                        cancelButton.addEventListener('click', window.cerrarEditModal);
                    }
                    
                    // Asegúrate de que los inputs de imagen se manejen correctamente si están en el formulario cargado
                    const platoImagenUrlInput = document.getElementById('plato_imagen_url_input');
                    const platoImagenFileInput = document.getElementById('plato_imagen_file_input');

                    if (platoImagenUrlInput && platoImagenFileInput) {
                        // Limpiar listeners viejos si existen y añadir nuevos
                        platoImagenUrlInput.removeEventListener('input', toggleImagenInput);
                        platoImagenFileInput.removeEventListener('change', toggleImagenInput);

                        platoImagenUrlInput.addEventListener('input', toggleImagenInput);
                        platoImagenFileInput.addEventListener('change', toggleImagenInput);

                        // Estado inicial al abrir el modal de edición
                        if (platoImagenUrlInput.value.trim() !== '') {
                            platoImagenFileInput.disabled = true;
                        } else if (platoImagenFileInput.files.length > 0) { // Check if a file was previously selected
                            platoImagenUrlInput.disabled = true;
                        }
                    }

                    // === LA LÍNEA CRÍTICA A AÑADIR/VERIFICAR AQUÍ ===
                    // Esta función se define en obtenerPlatoParaEditar.php y debe llamarse cuando el formulario se carga
                    if (typeof window.initializeEditFormIngredientes === 'function') {
                        window.initializeEditFormIngredientes();
                    } else {
                        console.error('Error: La función initializeEditFormIngredientes no está definida. Asegúrate de que obtenerPlatoParaEditar.php la incluya.');
                    }
                    // ============================================
                }
            })
    });

    // Listener para el botón ELIMINAR (🗑️)
    document.getElementById('delete-btn').addEventListener('click', () => {
        if (!platoSeleccionadoId) {
            Swal.fire({
                icon: 'info',
                title: 'Selecciona un plato',
                text: 'Debes seleccionar una carta para eliminar.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#f39c12'
            });
            return;
        }
        window.confirmarEliminarPlato(platoSeleccionadoId);
    });

}); // Fin DOMContentLoaded
</script>

<?php include("modalAgregarPlato.php"); // Incluye tu modal de agregar, si no está en este mismo archivo ?>

<style>
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down {
    animation: fade-in-down 0.3s ease-out;
}
/* Estilo para el plato seleccionado */
.food-item.selected {
    border: 2px solid #f39c12; /* Borde amarillo para selección */
    box-shadow: 0 0 15px rgba(243, 156, 18, 0.6); /* Sombra para selección */
}
</style>