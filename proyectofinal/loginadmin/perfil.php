<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pantallaseleccion/principal.php");
    exit();
}
?>

<?php

include '../conexion/conectarBD.php';


$userId = intval($_SESSION['user_id']); // Previene inyecciones
$query = "SELECT u.user_id, u.user_nombre, u.user_apellido, u.user_correo, u.user_telefono, u.user_foto, 
                 r.rol_desc, e.esta_desc
          FROM usuarios u
          LEFT JOIN roles r ON u.rol_id = r.rol_id
          LEFT JOIN estado e ON u.esta_id = e.esta_id
          WHERE u.user_id = $userId";


$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Cajero</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

            <a href="perfil.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10 ">
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
        
        <div class="w-11/12 max-w-7xln mt-5 border-[#784212] border p-6 m-6 rounded-lg shadow-md bg-opacity-90">
  <div class="w-[400px] h-auto mx-auto bg-[#2C3E50] shadow-xl rounded-lg p-6 animate__animated animate__fadeInDown">
    
    <div class="flex flex-col items-center text-center">
      <?php
      $fotoPerfil = !empty($user['user_foto']) 
          ? "../../imagenes/perfil/" . $user['user_foto'] 
          : "https://ui-avatars.com/api/?name=" . urlencode($user['user_nombre'] . ' ' . $user['user_apellido']) . "&background=0D8ABC&color=fff";
      ?>
      <img src="<?= $fotoPerfil ?>" alt="Foto de perfil" class="w-28 h-28 rounded-full object-cover border-4 border-[#FAF3E0] shadow-md">
      
      <h2 class="mt-4 text-2xl font-extrabold text-[#FAF3E0] tracking-wide"><?= $user['user_nombre'] . " " . $user['user_apellido'] ?></h2>
      <p class="text-sm text-[#FAF3E0]/70">ID: <?= $user['user_id'] ?></p>
      
      <div class="mt-4 space-y-2 text-[#FAF3E0] font-bold text-lg leading-relaxed">
        <p><span class="font-semibold text-black">Correo:</span> <?= $user['user_correo'] ?></p>
        <p><span class="font-semibold text-black">Teléfono:</span> <?= $user['user_telefono'] ?></p>
        <p><span class="font-semibold text-black">Rol:</span> <?= $user['rol_desc'] ?? '' ?></p>
        <p><span class="font-semibold text-black">Estado:</span> <?= $user['esta_desc'] ?? '' ?></p>
      </div>

      <button onclick="abrirModalEditar()" class="bg-blue-600 text-[#FAF3E0] px-4 py-2 rounded font-semibold hover:bg-blue-700 mt-6">
        Editar Perfil
      </button>
    </div>

  </div>
</div>

                <div id="modalEditarPerfil" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                    <div class="bg-[#2C3E50] rounded-lg shadow-lg w-full max-w-lg p-6 relative animate__animated animate__fadeInDown">
                        <h2 class="text-xl font-bold text-[#faf3e0] text-center mb-4">Editar Perfil</h2>
                        <form id="formEditarPerfil" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?= $userId ?>">

                            <label class="block text-[#faf3e0] font-semibold">Nombre</label>
                            <input type="text" name="nombre" value="<?= $user['user_nombre'] ?>" required class="w-full p-2 border focus:border-[#784212] focus:outline-none bg-[#faf3e0] rounded mb-2">

                            <label class="block text-[#faf3e0] font-semibold">Apellido</label>
                            <input type="text" name="apellido" value="<?= $user['user_apellido'] ?>" required class="w-full p-2 border focus:border-[#784212] focus:outline-none bg-[#faf3e0] rounded mb-2">

                            <label class="block text-[#faf3e0] font-semibold">Correo</label>
                            <input type="email" name="correo" value="<?= $user['user_correo'] ?>" required class="w-full p-2 border focus:border-[#784212] focus:outline-none bg-[#faf3e0] rounded mb-2">

                            <label class="block text-[#faf3e0] font-semibold">Teléfono</label>
                            <input type="text" name="telefono" value="<?= $user['user_telefono'] ?>" required class="w-full p-2 border focus:border-[#784212] focus:outline-none bg-[#faf3e0] rounded mb-2">

                            <label class="block text-[#faf3e0] font-semibold">Nueva contraseña (opcional)</label>
                            <input type="password" name="contrasena" class="w-full p-2 border focus:border-[#784212] focus:outline-none bg-[#faf3e0] rounded mb-2" placeholder="Dejar vacío para no cambiar">

                            <label class="block text-[#faf3e0] font-semibold">Cambiar Foto</label>
                            <input type="file" name="foto" class="w-full p-2 font-semibold text-[#faf3e0] border-[#faf3e0] border rounded mb-4">

                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="eliminar_foto" class="form-checkbox text-red-500">
                                <span class="ml-2 text-[#faf3e0] font-semibold">Eliminar foto actual</span>
                            </label>

                            <div class="flex justify-end gap-3 mt-4">
                                <button type="button" onclick="cerrarModalEditar()" class="bg-[#C0392B] text-white px-4 py-2 rounded hover:bg-red-600">Cancelar</button>
                                <button type="submit" class="bg-[#27AE60] text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
        Actualmente en: <strong>Perfil</strong>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function abrirModalEditar() {
            document.getElementById("modalEditarPerfil").classList.remove("hidden");
        }

         function cerrarModalEditar() {
        const modal = document.getElementById("modalEditarPerfil");
        const modalContent = modal.querySelector(".animate__animated");

        // Cambia clase de entrada por salida
        modalContent.classList.remove("animate__fadeInDown");
        modalContent.classList.add("animate__fadeOutUp");

        // Espera la animación y oculta
        setTimeout(() => {
            modal.classList.add("hidden");
            modalContent.classList.remove("animate__fadeOutUp");
            modalContent.classList.add("animate__fadeInDown");
        }, 500); // duración similar a animate.css
         }




        const form = document.getElementById("formEditarPerfil");

        const valoresOriginales = {
            nombre: "<?= $user['user_nombre'] ?>",
            apellido: "<?= $user['user_apellido'] ?>",
            correo: "<?= $user['user_correo'] ?>",
            telefono: "<?= $user['user_telefono'] ?>"
        };

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            const nombre = formData.get("nombre");
            const apellido = formData.get("apellido");
            const correo = formData.get("correo");
            const telefono = formData.get("telefono");
            const contrasena = formData.get("contrasena");
            const foto = formData.get("foto");
            const eliminarFoto = formData.get("eliminar_foto");

            const sinCambios =
                nombre === valoresOriginales.nombre &&
                apellido === valoresOriginales.apellido &&
                correo === valoresOriginales.correo &&
                telefono === valoresOriginales.telefono &&
                !contrasena &&
                (!foto || !foto.name) &&
                !eliminarFoto;

            if (sinCambios) {
                Swal.fire({
                    title: "Sin cambios",
                    text: "No realizaste ningún cambio para guardar.",
                    icon: "info",
                    background: "#2c3e50",
                    color: "#faf3e0",
                    iconColor: "#c0392b",
                    confirmButtonColor: "#c0392b"
                });
                return;
            }


            fetch("procesar_editar_cajero.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: data.message,
                        icon: "success",
                        background: "#2C3E50",
                        color: "#faf3e0",
                        iconColor: "#27AE60",
                        confirmButtonColor: "#27AE60"
                    });
                    cerrarModalEditar();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error",
                        background: "#faf3e0",
                        color: "#784212",
                        iconColor: "#a10000",
                        confirmButtonColor: "#a10000"
                    });
                }
            })
            .catch(error => {
                console.error("Error en AJAX:", error);
                Swal.fire({
                    title: "Error",
                    text: "Hubo un problema al guardar los cambios",
                    icon: "error",
                    background: "#faf3e0",
                    color: "#784212",
                    iconColor: "#a10000",
                    confirmButtonColor: "#a10000"
                });
            });
        });

        setTimeout(() => {
            document.getElementById("mensajeUbicacion").style.display = "none";
        }, 3000);

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
</body>
</html>
