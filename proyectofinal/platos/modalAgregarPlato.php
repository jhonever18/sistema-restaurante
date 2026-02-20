<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Agregar Plato</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div id="modalAgregarPlato" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
  <div id="contenidoAgregarPlato" class="bg-[#2C3E50] rounded-2xl w-full max-w-lg p-4 animate__animated">
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold text-center text-[#faf3e0]">Agregar Nuevo Plato</h2>
    </div>

    <form id="formAgregarPlato" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input type="hidden" name="action" value="add_dish">

      <div class="col-span-1">
        <label class="block text-[#faf3e0] font-semibold mb-1">Nombre:</label>
        <input type="text" name="nombre" class="w-full px-4 py-2 focus:border-[#784212] focus:outline-none border rounded-lg bg-[#faf3e0] text-black shadow-inner">
      </div>

      <div class="col-span-1">
        <label class="block text-[#faf3e0] font-semibold mb-1">Precio:</label>
        <input type="number" name="precio" min="0" step="0.01" class="w-full px-4 py-2 focus:border-[#784212] focus:outline-none border rounded-lg bg-[#faf3e0] text-black shadow-inner">
      </div>

      <div class="col-span-2">
        <label class="block text-[#faf3e0] font-semibold mb-1">Descripción:</label>
        <textarea name="descripcion" rows="2" class="w-full px-4 py-2 rounded-lg bg-[#faf3e0] focus:border-[#784212] focus:outline-none border text-black shadow-inner resize-none"></textarea>
      </div>

      <div class="col-span-1">
        <label class="block text-[#faf3e0] font-semibold mb-1">Imagen (URL):</label>
        <input type="url" name="plato_imagen_url" id="plato_imagen_url_agregar" placeholder="https://ejemplo.com/imagen.jpg" class="w-full px-4 py-2 focus:border-[#784212] focus:outline-none border rounded-lg bg-[#faf3e0] text-black shadow-inner">
      </div>

      <div class="col-span-1">
        <label class="block text-[#faf3e0] font-semibold mb-1">O subir imagen:</label>
        <input type="file" name="plato_imagen_file" id="plato_imagen_file_agregar" accept="image/*" class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <img id="previewImagenAgregar" class="mt-2 rounded-lg shadow max-h-40 hidden">
        <p class="text-xs text-gray-300 italic mt-1">La imagen cargada localmente tendrá prioridad sobre la URL.</p>
      </div>
      <div class="col-span-2">
        <label class="block text-[#faf3e0] font-semibold mb-1">Categoría:</label>
        <select name="categoria_id" class="w-full px-4 py-2 rounded-lg bg-[#faf3e0] focus:border-[#784212] focus:outline-none border text-black shadow-inner">
          <option value="">Selecciona una categoría</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['categoria_id'] ?>"><?= htmlspecialchars($cat['categoria_nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-span-2 flex items-center gap-2">
        <input type="checkbox" name="es_popular" value="1" id="popular" class="w-4 h-4 text-blue-500">
        <label for="popular" class="text-[#faf3e0] font-semibold">Marcar como plato popular</label>
      </div>

      <div class="col-span-2 text-white text-sm" id="ingredientesSeleccionados"></div>

      <div class="col-span-2 flex flex-col md:flex-row gap-3">
        <button type="button" onclick="abrirModalIngredientes()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg w-full">Seleccionar Ingredientes</button>
        <button type="button" onclick="mostrarIngredientesSeleccionados()" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-lg w-full">Ver Ingredientes Seleccionados</button>
      </div>

      <div class="col-span-2 flex gap-4 justify-end mt-4">
        <button type="button" id="cerrarAgregar" class="bg-[#C0392B] hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg">
          Cancelar
        </button>
        <button type="submit" class="bg-[#27AE60] hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg">Agregar Plato</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL INGREDIENTES -->
<div id="modalIngredientes" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex justify-center items-center">
  <div class="bg-[#2C3E50] rounded-lg shadow-lg p-6 w-full max-w-md relative animate__animated animate__fadeInDown">
    <h3 class="text-xl font-bold mb-4 text-center text-[#FAF3E0]">Seleccionar Ingredientes</h3>
    <div id="contenedorIngredientes" class="grid grid-cols-1 gap-3 text-[#FAF3E0] max-h-64 overflow-y-auto"></div>
    <div class="mt-6 flex justify-end gap-2">
      <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="cerrarModalIngredientes()">Cancelar</button>
      <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700" onclick="guardarIngredientesSeleccionados()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL VISTA INGREDIENTES -->
<div id="modalVistaIngredientes" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex justify-center items-center">
  <div class="bg-[#2C3E50] rounded-lg shadow-lg p-6 w-full max-w-md relative animate__animated animate__fadeInDown">
    <h3 class="text-xl font-bold mb-4 text-center text-[#faf3e0]">Ingredientes Seleccionados</h3>
    <ul id="listaVistaIngredientes" class="text-white space-y-2 list-disc pl-6 text-left"></ul>
    <div class="mt-6 flex justify-end">
      <button type="button" class="bg-[#C0392B] text-white px-4 py-2 rounded hover:bg-red-600" onclick="cerrarModalVistaIngredientes()">Cerrar</button>
    </div>
  </div>
</div>

<!-- Contenedor para ingredientes seleccionados (invisible) -->
<div id="ingredientesSeleccionados" class="hidden"></div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts -->

<script>
function abrirModalIngredientes() {
  document.getElementById("modalIngredientes").classList.remove("hidden");

  fetch("cargar_ingredientes.php")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("contenedorIngredientes");
      contenedor.innerHTML = '';
      data.forEach(ing => {
        const id = ing.ing_id;
        const nombre = ing.ing_nombre;
        const existente = document.querySelector(`#ingredientesSeleccionados input[name="ingrediente[${id}][cantidad]"]`);
        const cantidad = existente ? existente.value : '';
        contenedor.innerHTML += `
          <div class="flex items-center space-x-2">
            <input type="checkbox" class="checkboxIngrediente" data-id="${id}" data-nombre="${nombre}" ${cantidad ? "checked" : ""}>
            <span>${nombre}</span>
            <input type="number" class="cantidadIngrediente border rounded px-2 py-1 w-24" min="0.01" step="0.01" placeholder="Cantidad" value="${cantidad}">
          </div>`;
      });
    });
}

  
  document.addEventListener('DOMContentLoaded', () => {
  const abrirBtn = document.getElementById('agregar-btn');

  if (!abrirBtn) return;

  abrirBtn.addEventListener('click', () => {
    const modal = document.getElementById('modalAgregarPlato');
    const modalContent = document.getElementById('contenidoAgregarPlato');

    if (!modal || !modalContent) {
      console.error("Modal o contenido no encontrado.");
      return;
    }

    // Mostrar el modal
    modal.classList.remove('hidden');

    // Reiniciar animaciones previas
    modalContent.classList.remove('animate__fadeOutUp', 'animate__fadeInDown');

    // Forzar reflujo para reiniciar la animación
    void modalContent.offsetWidth;

    // Volver a aplicar la animación de entrada
    modalContent.classList.add('animate__fadeInDown');
  });
});


  document.getElementById('cerrarAgregar').addEventListener('click', () => {
    Swal.fire({
      icon: 'info',
      title: 'Cancelado',
      text: 'El formulario se ha cerrado.',
      background: '#2C3E50',
      color: '#FAF3E0',
      confirmButtonColor: '#27AE60',
      timer: 1500,
      showConfirmButton: false
    });

    const modal = document.getElementById('modalAgregarPlato');
    
    // Quitar animaciones si las tiene
    const modalContent = modal.querySelector('.animate__animated');
    if (modalContent) {
      modalContent.classList.remove('animate__fadeInDown');
    }

    // Ocultar directamente sin animación
    modal.classList.add('hidden');
  });


function guardarIngredientesSeleccionados() {
  const checkboxes = document.querySelectorAll('.checkboxIngrediente:checked');
  const inputHidden = document.getElementById('ingredientesSeleccionados');
  const listaVista = document.getElementById('listaVistaIngredientes');
  inputHidden.innerHTML = '';
  listaVista.innerHTML = '';

  let hayErrorCantidad = false;
  let haySeleccion = false;

  checkboxes.forEach(chk => {
    const id = chk.getAttribute('data-id');
    const nombre = chk.getAttribute('data-nombre');
    const cantidadInput = chk.parentElement.querySelector('.cantidadIngrediente');
    const cantidad = cantidadInput.value.trim();

    if (cantidad && parseFloat(cantidad) > 0) {
      haySeleccion = true;
      inputHidden.innerHTML += `<input type="hidden" name="ingrediente[${id}][id]" value="${id}">`;
      inputHidden.innerHTML += `<input type="hidden" name="ingrediente[${id}][cantidad]" value="${cantidad}">`;
      listaVista.innerHTML += `<li>${nombre} - ${cantidad}</li>`;
    } else {
      hayErrorCantidad = true;
    }
  });

  if (!haySeleccion) {
    Swal.fire({
      icon: 'warning',
      title: 'Sin ingredientes',
      background: "#2c3e50",
      color: "#faf3e0",
      text: 'Debes seleccionar al menos un ingrediente con cantidad válida.',
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    });
    return;
  }

  if (hayErrorCantidad) {
    Swal.fire({
      icon: 'error',
      background: "#2c3e50",
      color: "#faf3e0",
      title: 'Cantidad faltante',
      text: 'Todos los ingredientes seleccionados deben tener una cantidad mayor a 0.',
      showClass: {
        popup: 'animate__animated animate__shakeX'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    });
    return;
  }

  cerrarModalIngredientes();

  Swal.fire({
    icon: 'success',
    title: '¡Ingredientes guardados!',
    background: "#2c3e50",
    color: "#faf3e0",
    text: 'Los ingredientes se agregaron correctamente al plato.',
    showClass: {
      popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
      popup: 'animate__animated animate__fadeOutUp'
    }
  });
}

function mostrarIngredientesSeleccionados() {
  const listaModal = document.getElementById('listaVistaIngredientes');
  listaModal.innerHTML = '';
  const inputs = document.querySelectorAll('#ingredientesSeleccionados input');

  for (let i = 0; i < inputs.length; i += 2) {
    const id = inputs[i].value;
    const cantidad = inputs[i + 1].value;
    const nombre = document.querySelector(`.checkboxIngrediente[data-id="${id}"]`)?.getAttribute('data-nombre') || `Ingrediente #${id}`;
    listaModal.innerHTML += `<li>${nombre} - ${cantidad}</li>`;
  }

  document.getElementById('modalVistaIngredientes').classList.remove('hidden');
}

function cerrarModalIngredientes() {
  const modal = document.getElementById('modalIngredientes');
  const content = modal.querySelector('.animate__animated');

  if (!content) {
    modal.classList.add('hidden'); // fallback sin animación
    return;
  }

  // Quitar animación de entrada y poner la de salida
  content.classList.remove('animate__fadeInDown');
  content.classList.add('animate__fadeOutUp');

  // Esperar la animación y luego ocultar
  setTimeout(() => {
    modal.classList.add('hidden');
    content.classList.remove('animate__fadeOutUp');
    content.classList.add('animate__fadeInDown'); // volver a dejar entrada preparada
  }, 500); // duración de la animación
}
function cerrarModalVistaIngredientes() {
  const modal = document.getElementById('modalVistaIngredientes');
  const content = modal.querySelector('.animate__animated');

  if (!content) {
    modal.classList.add('hidden'); // fallback sin animación
    return;
  }

  // Quitar animación de entrada y poner la de salida
  content.classList.remove('animate__fadeInDown');
  content.classList.add('animate__fadeOutUp');

  // Esperar la animación y luego ocultar
  setTimeout(() => {
    modal.classList.add('hidden');
    content.classList.remove('animate__fadeOutUp');
    content.classList.add('animate__fadeInDown'); // volver a dejar entrada preparada
  }, 500); // duración de la animación
}

</script>

</body>