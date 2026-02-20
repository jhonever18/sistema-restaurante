<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pantallaseleccion/principal.php");
    exit();
}
?>

<?php
// Asegúrate de que esta ruta sea correcta para tu conexión a la base de datos
$connect = include("../conexion/conectarBD.php"); 
$categorias = [];
$resultado = mysqli_query($connect, "SELECT * FROM categorias");
while ($fila = mysqli_fetch_assoc($resultado)) {
    $categorias[] = $fila;
}
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

            <a href="../facturas/facturas.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
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

        <div id="notificationContainer" class="fixed inset-x-0 z-50 flex justify-center opacity-0 transition-all duration-500 ease-out-translate-y-full pointer-events-none"
            style="top: 20px;">
            <div id="notificationMessage" class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <p>Mensaje de notificación.</p>
            </div>
        </div>

            <div class="flex-1 p-5 overflow-auto">
            <div class="max-w-7xln w-11/12 mx-auto mt-8 border-[#784212] border p-6 m-6 rounded-lg shadow-md">

                
                
                <div id="contenedor-platos">
                <?php include 'cargar_Platos.php'; ?>
                </div>
                <?php include 'modalesPlatos.php'; ?>
                <?php include 'modalAgregarPlato.php'; ?>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const tarjeta = document.getElementById("agregar-btn");
                        const modal = document.getElementById("modalAgregarPlato");

                        if (tarjeta && modal) {
                            tarjeta.addEventListener("click", () => {
                                modal.classList.remove("hidden");
                            });
                        } else {
                            console.warn("No se encontró la tarjeta o el modal");
                        }
                    });
                </script>
                  
            </div>
        </div>
    </div>

    <script src="../platos/js/agregarplatos.js"></script>

     <div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
  Actualmente en: <strong>Platos</strong>
</div>

<script>
  setTimeout(() => {
    document.getElementById("mensajeUbicacion").style.display = "none";
  }, 3000); // se oculta en 3 segundos
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
<script src="cerrarVer.js" defer></script>

<script>
function guardarIngredientesSeleccionadosEditar() {
    const ingredientes = [];
    document.querySelectorAll('.fila-ingrediente-editar').forEach(fila => {
        const id = fila.dataset.ingId;
        const cantidad = fila.querySelector('.cantidad-ingrediente').value;

        if (id && cantidad > 0) {
            ingredientes.push({ id: parseInt(id), cantidad: parseFloat(cantidad) });
        }
    });

    // Asignar ingredientes al input oculto
    const inputIngredientes = document.getElementById('inputIngredientesEditar');
    inputIngredientes.value = JSON.stringify(ingredientes);
}
</script>


</body>
</html>