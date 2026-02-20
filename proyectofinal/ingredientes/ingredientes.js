// Notificaciones SweetAlert2
function mostrarNotificacion(tipo, mensaje) {
  let icono = 'info';
  if (tipo === 'exito') icono = 'success';
  else if (tipo === 'error') icono = 'error';

  Swal.fire({
    icon: icono,
    title: mensaje,
    position: 'center',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    background: '#2c3e50',
    color: '#faf3e0',
    customClass: {
      popup: 'animate__animated animate__fadeInDown',
      title: 'text-lg font-semibold'
    }
  });
}

function notificarSeleccionRequerida() {
  Swal.fire({
    icon: 'warning',
    title: 'Selecciona un ingrediente primero',
    text: 'Debes seleccionar un ingrediente de la tabla antes de continuar.',
    background: '#2c3e50',
    color: '#faf3e0',
    confirmButtonColor: '#C0392B',
    customClass: {
      popup: 'animate__animated animate__headShake'
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const modalAgregar = document.getElementById("modalAgregar");
  const formAgregar = document.getElementById("formAgregarDirecto");
  const btnAbrirModal = document.getElementById("btnAbrirModal");
  const btnCerrarModalAgregar = document.getElementById("btnCerrarModalAgregar");

  btnAbrirModal?.addEventListener("click", () => {
    modalAgregar.classList.remove("hidden");
    formAgregar.classList.remove("animate__fadeOutUp");
    void formAgregar.offsetWidth;
    formAgregar.classList.add("animate__animated", "animate__fadeInDown");
  });

  btnCerrarModalAgregar?.addEventListener("click", () => {
  Swal.fire({
    icon: 'info',
    title: 'Cancelado',
    text: 'El formulario se ha cerrado.',
    background: '#2C3E50',
    color: '#FAF3E0',
    confirmButtonColor: '#27AE60',
    timer: 1200,
    showConfirmButton: false
  });

  // Ocultar el modal sin animación
  modalAgregar.classList.add("hidden");
});

});

async function submitForm(formId) {
  const form = document.getElementById(formId);
  const formData = new FormData(form);
  const actionMap = {
    formAgregarDirecto: 'agregar',
    formEditar: 'editar',
    formEliminar: 'eliminar',
  };
  const actionType = actionMap[formId];
  formData.append("action", actionType);

  if (formId !== "formEliminar") {
    const inputs = form.querySelectorAll("input[required], textarea[required], select[required]");
    for (const input of inputs) {
      if (!input.value.trim()) {
        Swal.fire({
          icon: 'warning',
          title: 'Completa todos los campos',
          text: 'Por favor, llena todos los campos obligatorios antes de continuar.',
          background: '#2c3e50',
          color: '#faf3e0',
          confirmButtonColor: '#C0392B',
          customClass: {
            popup: 'animate__animated animate__headShake'
          }
        });
        return;
      }
    }
  }

  try {
    const res = await fetch("ingredientes.php", {
      method: "POST",
      body: formData
    });
    const data = await res.json();

    if (data.success) {
      mostrarNotificacion("exito", data.message);
      if (formId === "formAgregarDirecto") {
        form.reset();
        modalAgregar.classList.add("hidden");
      }
      if (formId === "formEditar") {
        modalEditar.classList.add("hidden");
      }
      setTimeout(() => location.reload(), 1200);
    } else {
      mostrarNotificacion("error", data.message);
      console.error("⛔ Servidor respondió con error:", data.message);
    }
  } catch (error) {
    console.error("❌ Error en fetch:", error);
    mostrarNotificacion("error", "Error de comunicación con el servidor.");
  }
}

document.getElementById("formAgregarDirecto")?.addEventListener("submit", e => {
  e.preventDefault();
  submitForm("formAgregarDirecto");
});

document.getElementById("formEditar")?.addEventListener("submit", e => {
  e.preventDefault();
  submitForm("formEditar");
});

let ingredienteSeleccionado = null;
const filas = document.querySelectorAll("table tbody tr");
const btnEditar = document.getElementById("btnEditar");
const btnEliminar = document.getElementById("btnEliminar");
const btnCambiarEstado = document.getElementById("btnCambiarEstado");

filas.forEach(fila => {
  fila.addEventListener("click", () => {
    filas.forEach(f => f.classList.remove("bg-blue-800", "ring-2", "ring-blue-400", "text-white"));
    fila.classList.add("bg-blue-800", "ring-2", "ring-blue-400", "text-white");

    ingredienteSeleccionado = {
      id: fila.children[0].textContent.trim(),
      nombre: fila.children[1].textContent.trim(),
      descripcion: fila.children[2].textContent.trim(),
      cantidad: fila.children[3].textContent.trim(),
      unidad: fila.children[4].textContent.trim(),
      precio: fila.children[5].textContent.replace(/[^\d.]/g, ''),
      estado: fila.children[6].textContent.trim() === "Activo" ? 1 : 2
    };

    btnEditar.disabled = false;
    btnEliminar.disabled = false;
    btnCambiarEstado.disabled = false;
  });
});
btnEditar?.addEventListener("click", () => {
  if (!ingredienteSeleccionado) return notificarSeleccionRequerida();

  fetch("formEditarIngrediente.php")
    .then(res => res.text())
    .then(html => {
      document.getElementById("formularioEditarContainer").innerHTML = html;

      // Rellenar los campos
      document.getElementById("editar_id").value = ingredienteSeleccionado.id;
      document.getElementById("editar_nombre").value = ingredienteSeleccionado.nombre;
      document.getElementById("editar_desc").value = ingredienteSeleccionado.descripcion;
      document.getElementById("editar_cantidad").value = ingredienteSeleccionado.cantidad;
      document.getElementById("editar_precio").value = ingredienteSeleccionado.precio;

      const selectUnidad = document.getElementById("editar_unidad");
      if (selectUnidad && ingredienteSeleccionado.unidad) {
        for (let option of selectUnidad.options) {
          if (option.value === ingredienteSeleccionado.unidad) {
            option.selected = true;
            break;
          }
        }
      }

      const modalEditar = document.getElementById("modalEditar");
      modalEditar.classList.remove("hidden");

      const formulario = document.getElementById("formEditar");
      formulario.addEventListener("submit", function (e) {
        e.preventDefault();
        submitForm("formEditar");
      });

      const btnCerrar = document.getElementById("btnCerrarModalEditar");
      btnCerrar.addEventListener("click", () => {
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

         formulario.classList.remove('animate__animated', 'animate__fadeInDown', 'animate__fadeOutUp');
        formulario.classList.add('animate__fadeOutUp');

        formulario.addEventListener('animationend', () => {
          modalEditar.classList.add('hidden');
         
          setTimeout(() => {
      modal.style.display = 'none';
    }, 500);
        }, { once: true });
      });
    })
    
});

btnEliminar?.addEventListener("click", async () => {
  if (!ingredienteSeleccionado) return notificarSeleccionRequerida();

  const confirmacion = await Swal.fire({
    title: `¿Eliminar "${ingredienteSeleccionado.nombre}"?`,
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#27AE60",
    cancelButtonColor: "#C0392B",
    background: "#2c3e50",
    color: "#faf3e0",
    customClass: {
      popup: 'animate__animated animate__fadeInDown'
    }
  });

  if (confirmacion.isConfirmed) {
    const form = document.getElementById("formEliminar");
    document.getElementById("eliminar_id").value = ingredienteSeleccionado.id;
    submitForm("formEliminar");
  }
});

btnCambiarEstado?.addEventListener("click", async () => {
  if (!ingredienteSeleccionado) return notificarSeleccionRequerida();

  const nuevoEstado = ingredienteSeleccionado.estado === 1 ? 2 : 1;
  const estadoTexto = nuevoEstado === 1 ? "activar" : "desactivar";

  const confirmacion = await Swal.fire({
    title: `¿Quieres ${estadoTexto} este ingrediente?`,
    text: `Estás a punto de ${estadoTexto} "${ingredienteSeleccionado.nombre}".`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, confirmar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#27AE60',
    cancelButtonColor: '#C0392B',
    background: "#2c3e50",
    color: "#faf3e0",
    customClass: {
      popup: 'animate__animated animate__fadeInDown'
    }
  });

  if (confirmacion.isConfirmed) {
    const formData = new FormData();
    formData.append("action", "cambiar_estado");
    formData.append("ingrediente_id", ingredienteSeleccionado.id);
    formData.append("nuevo_estado", nuevoEstado);

    try {
      const res = await fetch("ingredientes.php", {
        method: "POST",
        body: formData
      });
      const data = await res.json();

      Swal.fire({
        icon: data.success ? 'success' : 'error',
        title: data.message,
        position: 'center',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        background: "#2c3e50",
        color: "#faf3e0",
        customClass: {
          popup: 'animate__animated animate__fadeInDown'
        }
      });

      if (data.success) setTimeout(() => location.reload(), 1200);
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: '❌ Error al cambiar el estado.',
        position: 'center',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: "#2c3e50",
        color: "#faf3e0",
        customClass: {
          popup: 'animate__animated animate__fadeInDown'
        }
      });
    }
  }
});
