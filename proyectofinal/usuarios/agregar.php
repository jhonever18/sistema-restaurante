<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div id="formContainer" class="bg-[#2C3E50] p-6 rounded-xl w-[500px] shadow-lg animate__animated animate__fadeInDown text-[#FAF3E0]">
    <h2 class="text-2xl font-bold mb-4 text-center">Agregar Usuario</h2>

    <form id="formulario" method="POST" action="agregar1.php">
      <input type="number" name="id" placeholder="ID" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black" />

      <select name="tipoID" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black">
        <option value="" disabled selected>Seleccione documento</option>
        <option value="cedula ciudadana">Cédula ciudadana</option>
        <option value="tarjeta de identidad">Tarjeta de identidad</option>
        <option value="cedula ciudadana dig">Cédula ciudadana digital</option>
        <option value="pasaporte">Pasaporte</option>
        <option value="cedula extranjera">Cédula extranjera</option>
      </select>

      <input type="text" name="nombre" placeholder="Nombre" class="w-full soloTexto mb-3 p-2 rounded bg-[#FAF3E0] text-black" />
      <input type="text" name="apellido" placeholder="Apellido" class="w-full soloTexto mb-3 p-2 rounded bg-[#FAF3E0] text-black" />
      <input type="email" name="correo" placeholder="Correo" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black" />
      <input type="password" name="contra" placeholder="Contraseña" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black" autocomplete="new-password" />
      <input type="number" name="telefono" placeholder="Teléfono" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black" />

      <select name="rol" class="w-full mb-3 p-2 rounded bg-[#FAF3E0] text-black">
        <option value="" disabled selected>Seleccione rol</option>
        <option value="Administrador">Administrador</option>
        <option value="Cajero">Cajero</option>
      </select>

      <input type="hidden" name="estado" value="1" />
      <p class="text-sm text-green-400 font-semibold mb-3">Estado: Activo</p>

      <div class="flex justify-end gap-3">
        <button type="button" id="cerrarFormulario" class="bg-[#C0392B] text-white px-4 py-2 rounded hover:bg-red-700 transition-all">Cancelar</button>
        <button type="submit" class="bg-[#27AE60] text-white px-4 py-2 rounded hover:bg-green-700 transition-all">Agregar</button>
      </div>
    </form>
  </div>

  <script>
  document.getElementById('formulario').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    let vacio = false;
    form.querySelectorAll('input, select').forEach(input => {
      if (input.type !== 'hidden' && !input.value.trim()) vacio = true;
    });

    if (vacio) {
      Swal.fire({
        icon: 'warning',
        title: 'Faltan datos',
        text: 'Por favor completa todos los campos.',
        background: '#2C3E50',
        color: '#FAF3E0',
        confirmButtonColor: '#27AE60'
      });
      return;
    }

    fetch('agregar1.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
  Swal.fire({
    icon: 'success',
    title: 'Usuario agregado',
    text: 'El usuario se ha registrado correctamente.',
    background: '#2C3E50',
    color: '#FAF3E0',
    confirmButtonColor: '#27AE60'
  });

  form.reset(); // Limpia el formulario

  const modal = document.getElementById('formContainer');
  modal.classList.remove('animate__fadeInDown');
  modal.classList.add('animate__fadeOutUp');

  setTimeout(() => {
    document.getElementById('contenedorModalAgregarUsuario').classList.add('hidden');
    modal.remove(); // quita el formulario del DOM
  }, 500);
}
 else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.error || 'No se pudo agregar el usuario.',
          background: '#2C3E50',
          color: '#FAF3E0',
          confirmButtonColor: '#27AE60'
        });
      }
    })
    .catch(error => {
      console.error('Error en fetch:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error de servidor',
        text: 'Hubo un problema al agregar el usuario.',
        background: '#2C3E50',
        color: '#FAF3E0',
        confirmButtonColor: '#27AE60'
      });
    });
  });
  </script>

  <script>
  document.getElementById('cerrarFormulario').addEventListener('click', () => {
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

    const modal = document.getElementById('formulario').parentElement;
    modal.classList.remove('animate__fadeInDown');
    modal.classList.add('animate__fadeOutUp');

    setTimeout(() => {
      modal.style.display = 'none';
    }, 500);
  });
  </script>

</body>
</html>
