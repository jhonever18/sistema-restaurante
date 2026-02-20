<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pantallaseleccion/principal.php");
    exit();
}
?>
<?php
include_once(__DIR__ . "/../../conexion/conectarBD.php");

// Mostrar métodos de pago
$query = "SELECT * FROM metodo_pago";
$resultado = mysqli_query($connect, $query);

// Paginación
$limite = 8;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $limite;

$sql = "SELECT * FROM platos LIMIT $inicio, $limite";
$resPlatos = mysqli_query($connect, $sql);

$totalConsulta = mysqli_query($connect, "SELECT COUNT(*) as total FROM platos");
$totalFilas = mysqli_fetch_assoc($totalConsulta)['total'];
$totalPaginas = ceil($totalFilas / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Interfaz Cajero</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .fondo {
            background-image: url('/imagenes/fondopizzas.jpg');
            background-size: 500px;
            background-color: rgba(0, 0, 0, 0.4);
            background-blend-mode: overlay;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="m-0 font-sans md:pl-0 relative">
    <div class="fondo absolute inset-0"></div>

    <div class="flex min-h-screen z-10 relative">

        <!-- Sidebar -->
        <div class="group hover:w-56 w-20 bg-[#2C3E50] text-white flex flex-col transition-all duration-300 overflow-hidden">
            <div class="mb-4 flex py-2 items-center w-full px-4">
                <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png">
            </div>
            <a href="../perfil/cjPerfil.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">account_circle</i>
                <span class="ml-4 hidden group-hover:inline-block">Perfil</span>
            </a>
            <a href="../inicio/cajero.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">dining</i>
                <span class="ml-4 hidden group-hover:inline-block">Menú</span>
            </a>
            <a href="../pedidos_cajero/Pedidos.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">shopping_cart</i>
                <span class="ml-4 hidden group-hover:inline-block">Pedidos</span>
            </a>
            <a href="../cajero_facturas/facturas.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">receipt_long</i>
                <span class="ml-4 hidden group-hover:inline-block">Facturas</span>
            </a>
            <a id="btnCerrarSesion" href="#" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">logout</i>
                <span class="ml-4 hidden group-hover:inline-block">Salir</span>
            </a>
        </div>

        <!-- Cierre de sesión con SweetAlert -->
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
                    window.location.href = '../../cerrarSesion/cerrar_sesion.php';
                }
            });
        });
        </script>

        <!-- Contenido -->
        <div class="w-11/12 mt-5 border-[#784212] border p-3 m-6 rounded-lg shadow-md">
            <div class="relative max-w-5xl mx-auto">
                <!-- Platos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 pb-4">
                    <?php while ($plato = mysqli_fetch_assoc($resPlatos)): ?>
                        
                        <div class="food-item bg-[#2C3E50] p-1 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800 relative cursor-pointer">
                           <?php
                                $imagen = $plato['plato_imagen_url'];
                                $imgSrc = (strpos($imagen, 'http') === 0) 
                                    ? $imagen 
                                    : '/proyectofinal/platos/' . $imagen;
                            ?>
                            <img src="<?= $imgSrc ?>" alt="<?= $plato['plato_nombre'] ?>" class="w-44 h-44 object-cover rounded-full mx-auto border-4 border-[#c0392b] shadow-lg">
                            
                            <h2 class="text-xl font-semibold text-[#faf3e0]"><?= $plato['plato_nombre'] ?></h2>
                            <p class="text-lg text-green-400 font-bold">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>
                            <div class="mt-1 flex justify-center items-center">
                                <button onclick="agregarAlCarrito(<?= $plato['plato_id'] ?>, '<?= $plato['plato_nombre'] ?>', <?= $plato['plato_precio'] ?>)" class="bg-[#27ae60] text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>

                <!-- Paginación -->
                <div class="flex justify-center mt-6 space-x-2">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="?pagina=<?= $i ?>" class="px-3 py-1 rounded <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Botón carrito flotante -->
            <button id="carrito-icono" class="fixed top-90 bottom-7 right-10 bg-[#c0392b] hover:bg-yellow-600 text-white p-3 rounded-full shadow-lg z-50">
                <i class="material-icons text-2xl">shopping_cart</i>
            </button>

            <!-- Sidebar del carrito -->
            <div id="sidebarCarrito" class="fixed top-0 right-0 w-80 h-full bg-[#2c3e50] shadow-lg transform translate-x-full transition-transform duration-300 z-50">
                <div class="p-4 flex justify-between items-center border-b">
                    <h2 class="text-xl  text-[#faf3e0] font-bold">Pedido</h2>
                    <button onclick="cerrarCarrito()">
                        <i class="material-icons text-[#faf3e0] hover:text-black">close</i>
                    </button>
                </div>
                

                <div id="carrito" class="p-4 space-y-3 max-h-40 overflow-y-hidden"></div>

                <script>
                    
                    function verificarScrollCarrito() {
                        const contenedor = document.getElementById("carrito");
                        const items = contenedor.querySelectorAll(".pedido-item");

                        if (items.length >= 3) { // 👈 CAMBIADO de 5 a 3
                            contenedor.classList.add("overflow-y-auto");
                            contenedor.classList.remove("overflow-y-hidden");
                        } else {
                            contenedor.classList.add("overflow-y-hidden");
                            contenedor.classList.remove("overflow-y-auto");
                        }
                    }

                </script>


                <div class="p-4 border-t">
                    <label class="block font-semibold text-[#faf3e0] mb-1">Método de Pago</label>
                    <select id="metodoPago" class="w-full border bg-[#faf3e0] px-2 py-1 rounded mb-3">
                        <option value="">Selecciona método de pago</option>
                        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                            <option value="<?= $fila['metopago_id'] ?>"><?= $fila['metopago_desc'] ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label class="block font-semibold text-[#faf3e0] ">Nombre Cliente</label>
                    <input type="text" id="nombreCliente" class="w-full border bg-[#faf3e0] rounded px-2 py-1 mb-2" />

                    <label class="block font-semibold text-[#faf3e0] ">Teléfono Cliente</label>
                    <input type="text" id="telefonoCliente" class="w-full border bg-[#faf3e0] rounded px-2 py-1 mb-2" />

                    <label class="block font-semibold text-[#faf3e0] ">Documento</label>
                    <input type="text" id="documentoCliente" class="w-full border bg-[#faf3e0] rounded px-2 py-1 mb-2" />

                   <label class="block font-semibold text-[#faf3e0] ">Correo Cliente</label>
                   <input type="email" id="correoCliente" class="w-full border rounded bg-[#faf3e0] px-2 py-1 mb-2" />

                    <button onclick="confirmarPedido()" class="w-full bg-green-600 text-white py-2 rounded mt-2">Confirmar Pedido</button>
                    <button onclick="cancelarPedido()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded mt-2 transition-colors">
                        Cancelar Pedido
                    </button>

                </div>
            </div>

            </div>
        </div>
    </div>
    <!-- Mensaje ubicación -->
    <div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
        Actualmente en: <strong>Menú</strong>
    </div>

    <!-- Scripts -->
    <script>
    setTimeout(() => {
        document.getElementById("mensajeUbicacion").style.display = "none";
    }, 2000);

    const carritoIcono = document.getElementById('carrito-icono');
    const sidebarCarrito = document.getElementById('sidebarCarrito');

    carritoIcono.addEventListener('click', () => {
        sidebarCarrito.classList.remove('translate-x-full');
    });

    function cerrarCarrito() {
        sidebarCarrito.classList.add('translate-x-full');
    }

    let carrito = [];

    function agregarAlCarrito(id, nombre, precio) {
    const index = carrito.findIndex(p => p.id === id);
    if (index !== -1) {
        carrito[index].cantidad++;
    } else {
        carrito.push({ id, nombre, precio, cantidad: 1 });
    }
    renderizarCarrito();
    mostrarToast(`"${nombre}" ¡Agregado al carrito!`);
}


    function modificarCantidad(id, cambio) {
        const item = carrito.find(p => p.id === id);
        if (!item) return;
        item.cantidad += cambio;
        if (item.cantidad <= 0) {
            carrito = carrito.filter(p => p.id !== id);
        }
        renderizarCarrito();
    }

    function renderizarCarrito() {
    const div = document.getElementById("carrito");
    div.innerHTML = "";
    carrito.forEach(p => {
        div.innerHTML += `
            <div class="flex justify-between items-center pedido-item">
                <div>
                    <p class="font-semibold text-[#faf3e0] ">${p.nombre}</p>
                    <p class="text-sm font-semibold text-[#faf3e0] ">$${(p.precio * p.cantidad).toLocaleString()}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="modificarCantidad(${p.id}, -1)" class="px-2 bg-red-500 text-white rounded">-</button>
                    <span class="font-semibold text-[#faf3e0] ">${p.cantidad}</span>
                    <button onclick="modificarCantidad(${p.id}, 1)" class="px-2 bg-blue-500 text-white rounded">+</button>
                </div>
            </div>
        `;
    });

    verificarScrollCarrito(); // ✅ Aquí se activa o desactiva el scroll


    }
   
    function confirmarPedido() {
    const metodoPago = document.getElementById("metodoPago").value;
    const nombre = document.getElementById("nombreCliente").value.trim();
    const documento = document.getElementById("documentoCliente").value.trim();
    const correo = document.getElementById("correoCliente").value.trim(); 
    const telefono = document.getElementById("telefonoCliente").value.trim();


    if (!metodoPago || !nombre || !documento) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor completa todos los campos del pedido.',
            background: '#1F2937',
            color: '#fff'
        });
        return;
    }

    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrito vacío',
            text: 'Agrega productos al carrito antes de confirmar el pedido.',
            background: '#1F2937',
            color: '#fff'
        });
        return;
    }

    const total = carrito.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);
    const datos = {
        metodo: metodoPago,
        nombre_cliente: nombre,
        documento_cliente: documento,
        correo_cliente: correo, 
        telefono_cliente: telefono,
        total: total,
        carrito: carrito
    };

    fetch("../pedidos_cajero/guardar_pedido_cajero.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: '✅ Pedido registrado',
                text: 'El pedido fue registrado con éxito.',
                background: '#1F2937',
                color: '#fff'
            });
            carrito = [];
            renderizarCarrito();
            document.getElementById("metodoPago").value = "";
            document.getElementById("nombreCliente").value = "";
            document.getElementById("documentoCliente").value = "";
            document.getElementById("correoCliente").value = ""; 
            document.getElementById("telefonoCliente").value = "";
            
            
            cerrarCarrito();
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ Error',
                text: result.error,
                background: '#1F2937',
                color: '#fff'
            });
        }
    })
    .catch(err => {
        console.error("Error:", err);
        Swal.fire({
            icon: 'error',
            title: '❌ Error al procesar',
            text: 'Ocurrió un error al registrar el pedido.',
            background: '#1F2937',
            color: '#fff'
        });
    });
}

function cancelarPedido() {
    // Limpiar campos
    document.getElementById('metodoPago').value = '';
    document.getElementById('nombreCliente').value = '';
    document.getElementById('documentoCliente').value = '';
    document.getElementById('correoCliente').value = '';

    // Limpiar carrito visualmente (si es necesario)
    document.getElementById('carrito').innerHTML = '';

    // Ocultar sidebar (si usas una clase para mostrarlo)
    document.getElementById('sidebarCarrito').classList.add('translate-x-full');

    // Opcional: mostrar notificación
    Swal.fire({
        title: 'Pedido cancelado',
        text: 'Se ha cancelado la creación del pedido.',
        icon: 'info',
        confirmButtonColor: '#3085d6',
        background: '#2c3e50',
        color: '#faf3e0'
    });
}


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

    <!-- Toast de notificación -->
    <div id="toast" class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50 hidden animate-fade-in"></div>

    <!-- Animación para el toast -->
    <style>
    @keyframes fade-in {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
    animation: fade-in 0.3s ease-out;
    }
    </style>

    <script>
    function mostrarToast(mensaje, color = "bg-green-600") {
        const toast = document.getElementById("toast");
        toast.textContent = mensaje;

        // Resetear clases
        toast.className = `fixed top-5 right-5 text-white px-4 py-2 rounded shadow-lg z-50 animate-fade-in ${color}`;

        toast.classList.remove("hidden");

        // Ocultar después de 2.5 segundos
        setTimeout(() => {
            toast.classList.add("hidden");
        }, 2500);
    }
    </script>

</body>
</html>
