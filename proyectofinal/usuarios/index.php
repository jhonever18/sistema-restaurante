<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pantallaseleccion/principal.php");
    exit();
}
?>

<?php
include '../conexion/conectarBD.php';

$buscar = mysqli_real_escape_string($connect, $_GET['buscar'] ?? '');
$por_pagina = 12;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

$sql = "SELECT usuarios.*, estado.esta_desc, roles.rol_desc
        FROM usuarios
        LEFT JOIN estado ON usuarios.esta_id = estado.esta_id
        LEFT JOIN roles ON usuarios.rol_id = roles.rol_id
        WHERE user_id LIKE '%$buscar%' 
        OR user_nombre LIKE '%$buscar%' 
        OR user_apellido LIKE '%$buscar%' 
        LIMIT $inicio, $por_pagina";

$res = mysqli_query($connect, $sql);

$sql_total = "SELECT COUNT(*) FROM usuarios 
              WHERE user_id LIKE '%$buscar%' 
              OR user_nombre LIKE '%$buscar%' 
              OR user_apellido LIKE '%$buscar%'";

$total_res = mysqli_query($connect, $sql_total);
$total_filas = mysqli_fetch_row($total_res)[0];
$total_paginas = ceil($total_filas / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Platos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<style>
  .fondo {
    background-image: url('/imagenes/fondopizzas.jpg');
    background-size: 500px;
    background-color: rgba(0, 0, 0, 0.4);
    background-blend-mode: overlay;
    background-attachment: fixed;
  }
</style>
<body class="m-0 font-sans  md:pl-[0px] relative">

    <div class="fondo absolute inset-0 " style="background-image: url('/imagenes/fondopizzas.jpg');">
    </div>
    
    <div class="flex min-h-screen  z-10 relative">

        <div class="group hover:w-56 w-20 bg-[#2C3E50] text-white flex flex-col transition-all duration-300 overflow-hidden">

             <div class="mb-4 flex py-2 items-center w-full px-4">
                <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png">
            </div>

            <a href="../loginadmin/perfil.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10 ">
                <i class="material-icons text-[24px]">account_circle</i>
                <span class="ml-4 hidden group-hover:inline-block">Perfil</span> 
            </a>
            <a href="../loginadmin/prueba.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">analytics</i>
                <span class="ml-4 hidden group-hover:inline-block">Estadisticas</span>
            </a>
            <a href="../pedidos/pedidos.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">shopping_cart</i>
                <span class="ml-4 hidden group-hover:inline-block">Pedidos</span>
            </a>

            <a href="../facturas/facturas.php" class=" flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">receipt_long</i>
                <span class="ml-4 hidden group-hover:inline-block">Facturas</span>
            </a>

            <a href="../platos/platos.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">local_dining</i>
                <span class="ml-4 hidden group-hover:inline-block">Platos</span>
            </a>
            <a href="../ingredientes/ingredientes.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">grass</i>
                <span class="ml-4 hidden group-hover:inline-block">Ingredientes</span>
            </a>
            <a href="../usuarios/index.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">people</i>
                <span class="ml-4 hidden group-hover:inline-block">Usuarios</span>
            </a>

            <a href="../clientes/clientes.php" class=" flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">person</i>
                <span class="ml-4 hidden group-hover:inline-block">Clientes</span>
            </a>
            </a>
             <a id="btnCerrarSesion" href="#" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">logout</i>
                <span class="ml-4 hidden group-hover:inline-block">Salir</span>
            </a>
            
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        document.getElementById('btnCerrarSesion').addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres cerrar sesión?",
                icon: 'warning',
                iconColor:'red',
                showCancelButton: true,
                confirmButtonColor: '#27AE60',
                cancelButtonColor: '#C0392B',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../cerrarSesion/cerrar_sesion.php';
                }
            });
        });
        </script>
        <div class="flex-1 p-5 overflow-auto">
            <div class="max-w-7xln w-11/12 mx-auto mt-8 border-[#784212] border p-6 m-6 rounded-lg shadow-md">
                <!-- Barra de búsqueda -->
                <div class="flex justify-between items-center mb-4">
                    <form id="buscarForm" method="GET" class="flex items-center shadow p-2 bg-white rounded">
                        <input type="text" name="buscar" id="buscarInput" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar por nombre" class="p-2 border rounded mr-2" />
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">Buscar</button>
                    </form>
                    <button id="agregarusuario" class="bg-[#27AE60] text-white px-4 py-2 ml-4 rounded hover:bg-green-700">
                        Agregar Usuario
                    </button>
                    <div id="contenedorModalAgregarUsuario" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
                        <!-- Aquí se cargará el formulario -->
                    </div>
                </div>
                    <div id="tablaUsuarios" class="grid w-full grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2">
                      <?php while ($usuario = mysqli_fetch_assoc($res)): ?>
                          <div class="usuario-card bg-[#2c3e50] rounded-xl hover:shadow-xl shadow-lg p-4 w-[200px] flex flex-col items-center text-white cursor-pointer transition-transform duration-150" data-id="<?= $usuario['user_id'] ?>">
                              <?php
                              $foto = $usuario['user_foto'] ?? '';
                              $nombre = $usuario['user_nombre'] ?? '';
                              $apellido = $usuario['user_apellido'] ?? '';

                              $fotoPerfil = ""; // Resultado final

                              // Ruta absoluta (funciona en la mayoría de casos como el cajero)
                              $ruta1Fisica = $_SERVER['DOCUMENT_ROOT'] . "/proyectofinal/imagenes/perfil/" . $foto;
                              $url1 = "//" . $_SERVER['HTTP_HOST'] . "/proyectofinal/imagenes/perfil/" . $foto;

                              // Ruta relativa para administrador (como tú pides)
                              $ruta2Fisica = __DIR__ . "/../../imagenes/perfil/" . $foto;
                              $url2 = "../../imagenes/perfil/" . $foto;

                              // Verificamos cuál existe
                              if (!empty($foto) && file_exists($ruta1Fisica)) {
                                  $fotoPerfil = $url1;
                              } elseif (!empty($foto) && file_exists($ruta2Fisica)) {
                                  $fotoPerfil = $url2;
                              } else {
                                  // Si no existe ninguna imagen, usamos avatar
                                  $fotoPerfil = "https://ui-avatars.com/api/?name=" . urlencode("$nombre $apellido") . "&background=0D8ABC&color=fff";
                              }
                              ?>
                              <img src="<?= $fotoPerfil ?>" class="w-20 h-20 rounded-full object-cover mb-2 border">
                              <h3 class="text-lg font-bold"><?= $usuario['user_nombre'] . ' ' . $usuario['user_apellido'] ?></h3>
                              <p class="text-sm"><?= $usuario['rol_desc'] ?></p>
                              <p class="text-sm mb-2"><?= $usuario['esta_desc'] ?></p>
                              <button class="ver-detalle bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-800 transition-all" data-id="<?= $usuario['user_id'] ?>">
                                  Ver información
                              </button>
                          </div>
                      <?php endwhile; ?>
                  </div>

                </div>
                <div  class="fixed bottom-6 right-6 flex gap-3 z-50 shadow-lg">
                    <button id="btn_CambiarEstado" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition shadow-md">🔁 Cambiar Estado</button>
                    <button id="btnCambiarRol" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition shadow-md">🔁 Cambiar Rol</button>
                </div>
                
                <div id="modalRol" class="fixed inset-0 hidden z-50">
                    <!-- Fondo oscuro -->
                    <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>

                    <div class="fixed inset-0 flex  justify-center items-center z-50">
                        <div class="modal-contenido bg-[#2C3E50] p-6 rounded-xl w-96 shadow-lg ">
                            <h2 class="text-xl font-bold mb-4 text-center text-[#faf3e0]">Cambiar Rol</h2>
                            <form id="formCambiarRol">
                                <select name="rol_id" id="selectRol" class="w-full mb-4 p-2 border border-gray-300 bg-[#faf3e0] rounded"></select>
                                <input type="hidden" name="usuarios" id="usuariosRol" />
                                <div class="flex justify-end gap-2">
                                    <button type="button" id="btnCancelarRol" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 transition-all">Cancelar</button>
                                    <button type="submit" class="bg-[#27AE60] text-white px-4 py-2 rounded hover:bg-green-700 transition-all">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
               <div class="flex justify-center mt-6 space-x-2">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                       <a href="?pagina=<?= $i ?>" class="px-3 py-1 rounded 
                            <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalDetalle" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
  <div id="modalContenido" class="bg-[#2C3E50] p-6 rounded-2xl w-full max-w-xl shadow-2xl animate__animated animate__fadeInDown">
    
    <!-- Encabezado con imagen y nombre -->
    <div class="flex items-center gap-5 border-b border-[#FAF3E0]/20 pb-4">
      <img id="modalFoto" src="" alt="Foto de usuario"
           class="w-28 h-28 rounded-full object-cover border-4 border-[#FAF3E0] shadow-md" />
      <div>
        <h2 id="modalNombre" class="text-2xl font-extrabold text-[#FAF3E0] tracking-wide leading-tight"></h2>
        <p class="text-sm text-[#FAF3E0]/70"><span id="modalTipo"></span> - ID: <span id="modalId"></span></p>
      </div>
    </div>

    <!-- Detalles del usuario -->
    <div class="mt-4 space-y-2 text-[#FAF3E0] font-bold text-xl leading-relaxed">
      <p><span class="font-semibold text-black">Correo:</span> <span id="modalCorreo"></span></p>
      <p><span class="font-semibold text-black">Teléfono:</span> <span id="modalTelefono"></span></p>
      <p><span class="font-semibold text-black">Rol:</span> <span id="modalDetalleRol"></span></p>
      <p><span class="font-semibold text-black">Estado:</span> <span id="modalEstado"></span></p>
      <p><span class="font-semibold text-black">Fecha de registro:</span><span id="modalFechaRegistro"></span></p>

    </div>

    <!-- Botón cerrar -->
    <div class="mt-6 text-right">
      <button id="cerrarModalDetalle"
              class="bg-[#C0392B] text-white px-5 py-2 rounded-lg hover:bg-red-800 transition-all shadow">
        Cerrar
      </button>
    </div>
  </div>
</div>


<script>
$(".ver-detalle").click(function () {
  const userId = $(this).data("id");

  $.get("../usuarios/verUsuario.php", { id: userId }, function (data) {
    if (data.error) {
      Swal.fire("Error", "No se pudo cargar la información del usuario", "error");
      return;
    }
    const rutaBase = window.location.pathname.includes("admin")
  ? "/proyectofinal/imagenes/perfil/"
  : "../../imagenes/perfil/";
  const imagen = data.user_foto
  ? `${rutaBase}${data.user_foto}`
  : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user_nombre + ' ' + data.user_apellido)}&background=0D8ABC&color=fff`;

$("#modalFoto").attr("src", imagen);



   const imagenRuta1 = "../../imagenes/perfil/" + data.user_foto;
    const imagenRuta2 = "/proyectofinal/imagenes/perfil/" + data.user_foto;

    // Verifica si la imagen existe en la primera ruta
    const testImage = new Image();
    testImage.onload = function () {
      $("#modalFoto").attr("src", imagenRuta1);
    };
    testImage.onerror = function () {
      $("#modalFoto").attr("src", imagenRuta2);
    };

    if (data.user_foto) {
      testImage.src = imagenRuta1;
    } else {
      $("#modalFoto").attr(
        "src",
        `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user_nombre + " " + data.user_apellido)}&background=0D8ABC&color=fff`
      );
    }



    $("#modalFoto").attr("src", imagen);
    $("#modalId").text(data.user_id ?? '');
    $("#modalNombre").text(`${data.user_nombre ?? ''} ${data.user_apellido ?? ''}`);
    $("#modalTipo").text(data.ti_desc ?? '');
    $("#modalCorreo").text(data.user_correo ?? '');
    $("#modalTelefono").text(data.user_telefono ?? '');
    $("#modalDetalleRol").text(data.rol_desc ?? '');
    $("#modalEstado").text(data.esta_desc ?? '');
    $('#modalFechaRegistro').text(data.fecha_registro);


    $("#modalDetalle").removeClass("hidden").addClass("flex");
    $("#modalContenido").removeClass("animate__fadeOutUp").addClass("animate__fadeInDown");
  }, "json");
});
$("#cerrarModalDetalle").click(function () {
  // Remueve la animación de entrada
  $("#modalContenido").removeClass("animate__fadeInDown");

  // Agrega la de salida
  $("#modalContenido").addClass("animate__fadeOutUp");

  // Espera que termine y oculta el modal
  setTimeout(() => {
    $("#modalDetalle").addClass("hidden");

    // Limpia solo las animaciones específicas (no la clase 'animate__animated')
    $("#modalContenido").removeClass("animate__fadeOutUp");
  }, 500);
});

</script>

<script>
// Esto debe ir después de cargar SweetAlert2 y antes de usar swalPersonalizado
const swalPersonalizado = Swal.mixin({
  customClass: {
    confirmButton: 'bg-[#27AE60] text-[#FAF3E0] px-4 py-2 rounded hover:bg-green-700',
    cancelButton: 'bg-[#C0392B] text-[#FAF3E0] px-4 py-2 rounded hover:bg-red-800'
  },
  buttonsStyling: false
});
</script>

<style>
.usuario-card {
    border: 4px solid transparent; /* O un color por defecto */
    /* ... otros estilos ... */
}
.usuario-card.seleccionado {
    border-color: #22c55e !important;
}
</style>
<script>
let usuariosSeleccionados = [];

$(document).on("click", ".usuario-card", function () {
  const id = $(this).data("id");
  $(this).toggleClass("seleccionado");
  if ($(this).hasClass("seleccionado")) {
    usuariosSeleccionados.push(id);
  } else {
    usuariosSeleccionados = usuariosSeleccionados.filter(item => item !== id);
  }
  
});

$("#btn_CambiarEstado").click(function () {
  if (usuariosSeleccionados.length === 0) {
    swalPersonalizado.fire({
      icon: "info",
      title: "Espera",
      text: "Selecciona al menos un usuario.",
      background: "#2C3E50",
      color: "#FAF3E0"
    });
    return;
  }

  swalPersonalizado.fire({
    title: "¿Cambiar estado?",
    text: "Se alternará entre activo e inactivo.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, cambiar",
    cancelButtonText: "Cancelar",
    background: "#2C3E50",
    color: "#FAF3E0"
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("cambiarEstado.php", { ids: usuariosSeleccionados }, function () {
        swalPersonalizado.fire({
          title: "Hecho",
          text: "Estado actualizado correctamente.",
          icon: "success",
          background: "#2C3E50",
          color: "#FAF3E0"
        }).then(() => location.reload());
      });
    }
  });
});

$("#btnCambiarRol").click(function () {
  if (usuariosSeleccionados.length === 0) {
    swalPersonalizado.fire({
      icon: "info",
      title: "Ups...",
      text: "Selecciona usuarios primero.",
      background: "#2C3E50",
      color: "#FAF3E0"
    });
    return;
  }

  $.get("cargarRoles.php", function (roles) {
    $("#selectRol").empty();
    roles.forEach(rol => {
        $("#selectRol").append(`<option value="${rol.rol_id}">${rol.rol_desc}</option>`);
    });

    $("#usuariosRol").val(JSON.stringify(usuariosSeleccionados));

    // Mostrar modal sin animación en fondo
    $("#modalRol").removeClass("hidden");
    
    // Agregar animación solo al contenido
    $(".modal-contenido").addClass("animate__animated animate__fadeInDown");
});

});

$("#formCambiarRol").submit(function (e) {
  e.preventDefault();
  $.post("cambiarRol.php", $(this).serialize(), function () {
    swalPersonalizado.fire({
      icon: "success",
      title: "Hecho",
      text: "Rol actualizado con éxito.",
      background: "#2C3E50",
      color: "#FAF3E0"
    }).then(() => location.reload());
  });
});

$("#btnCancelarRol").click(function () {
    const $modalContenido = $(".modal-contenido");

    // Quitar animaciones previas y aplicar animación de salida
    $modalContenido
        .removeClass("animate__fadeInDown")
        .addClass("animate__fadeOutUp");

    // Esperar la duración de la animación antes de ocultar el modal
    setTimeout(() => {
        $("#modalRol").addClass("hidden");
        $modalContenido.removeClass("animate__animated animate__fadeOutUp");
    }, 500); // tiempo en ms, debe coincidir con la duración de la animación
});

</script>

<div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
  Actualmente en: <strong>Usuarios</strong>
</div>

<script>
  setTimeout(() => {
    document.getElementById("mensajeUbicacion").style.display = "none";
  }, 3000);
</script>

<style>
@keyframes fade-in {
  0% { opacity: 0; transform: translateY(-10px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.5s ease-in-out;
}
</style>

<script>
$(document).on("click", "#agregarusuario", function () {
    $.ajax({
        url: "../usuarios/agregar.php", // Ruta al archivo con el formulario
        method: "GET",
        success: function (data) {
            $("#contenedorModalAgregarUsuario").html(data).removeClass("hidden").addClass("flex");
        },
        error: function () {
            Swal.fire("Error", "No se pudo cargar el formulario.", "error");
        }
    });
});

$(document).on("click", "#cerrarFormulario", function () {
    $("#contenedorModalAgregarUsuario").addClass("hidden").html('');
});
</script>

</body>
</html>
