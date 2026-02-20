<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pantallaseleccion/principal.php");
    exit();
}
?>

<?php
    include("../conexion/conectarBD.php");

    // Traer estados disponibles
    $estados = [];
    $resEstados = mysqli_query($connect, "SELECT * FROM estado_pedido");
    while ($row = mysqli_fetch_assoc($resEstados)) {
        $estados[] = $row;
    }

    // Consulta para pedidos incluyendo nombres de clientes
    $sql = "SELECT 
            p.pedido_id,
            p.pedido_fecha,
            p.pedido_valor_pagar,
            c.cli_nombre,
            c.cli_apellido,
            ep.estped_desc,
            p.estped_id
        FROM pedidos p
        LEFT JOIN clientes c ON p.cliente_id = c.cliente_id
        LEFT JOIN estado_pedido ep ON p.estped_id = ep.estped_id
        ORDER BY p.pedido_fecha DESC
        LIMIT 3";

$resPedidos = mysqli_query($connect, $sql);
?>

<?php
date_default_timezone_set('America/Bogota');

// Fechas para los últimos 7 días
$labels = [];
$pedidosPorDia = [];
$gananciasPorDia = [];

for ($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $labels[] = $fecha;

    // Pedidos
    $sqlPedidos = "SELECT COUNT(*) AS total FROM pedidos 
                   WHERE DATE(CONVERT_TZ(pedido_fecha, '+00:00', '-05:00')) = '$fecha'";
    $resPedidosDia = mysqli_query($connect, $sqlPedidos);
    $pedidosPorDia[] = mysqli_fetch_assoc($resPedidosDia)['total'] ?? 0;

    // Ganancias
    $sqlGanancias = "SELECT SUM(cantidad * precio_unitario) AS total 
                     FROM pedido_detalles pd
                     INNER JOIN pedidos p ON pd.pedido_id = p.pedido_id
                     WHERE p.estped_id = 3 AND DATE(CONVERT_TZ(p.pedido_fecha, '+00:00', '-05:00')) = '$fecha'";
    $resGananciasDia = mysqli_query($connect, $sqlGanancias);
    $gananciasPorDia[] = mysqli_fetch_assoc($resGananciasDia)['total'] ?? 0;
}

?>

<?php
$notificaciones = [];

// 1. Ingredientes con bajo stock
$sqlStock = "SELECT ing_nombre, ing_cantidad FROM ingredientes WHERE ing_cantidad <= 5";
$resStock = mysqli_query($connect, $sqlStock);
while ($row = mysqli_fetch_assoc($resStock)) {
    $notificaciones[] = "🛑 Bajo stock: Te quedan solo {$row['ing_cantidad']} unidades de <strong>{$row['ing_nombre']}</strong>";
}

// 2. Pedidos en proceso por más de 30 min
$sqlPedidosTiempo = "SELECT pedido_id, pedido_fecha FROM pedidos 
                     WHERE estped_id = 2 AND TIMESTAMPDIFF(MINUTE, pedido_fecha, UTC_TIMESTAMP()) > 30";
$resTiempo = mysqli_query($connect, $sqlPedidosTiempo);
while ($row = mysqli_fetch_assoc($resTiempo)) {
    $notificaciones[] = "⏱️ Pedido #{$row['pedido_id']} lleva más de 30 minutos en proceso";
}

// 3. Platos sin imagen
$sqlSinImagen = "SELECT COUNT(*) AS total FROM platos WHERE plato_imagen_url IS NULL OR plato_imagen_url = ''";
$resImagen = mysqli_query($connect, $sqlSinImagen);
$filaImagen = mysqli_fetch_assoc($resImagen);
if ($filaImagen['total'] > 0) {
    $notificaciones[] = "🖼️ Hay <strong>{$filaImagen['total']}</strong> platos sin imagen";
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

        <div class="flex-1  overflow-auto">
            
            <div class="max-w-7xln w-11/12 mx-auto  border-[#784212] border p-6 m-6 rounded-lg shadow-md">
                <div class="relative inline-block text-left">
                    <!-- Botón de campana -->
                    <button id="btnNotificaciones" onclick="toggleNotificaciones()" class="relative focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#2c3e50] hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5">
                            <?= count($notificaciones) ?>
                        </span>
                    </button>

                    <!-- Contenedor de notificaciones -->
                    <div id="notificacionesDropdown" class="hidden origin-top-left absolute left-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="p-4 max-h-80 overflow-y-auto">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">🔔 Notificaciones</h3>
                            <?php if (count($notificaciones) === 0): ?>
                                <p class="text-gray-500 text-sm">No hay notificaciones por ahora.</p>
                            <?php else: ?>
                                <ul class="text-sm text-gray-700  space-y-2">
                                    <?php foreach ($notificaciones as $alerta): ?>
                                        <li class="bg-red-100 text-red-800 px-3 py-2 rounded-md"><?= $alerta ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 my-6">
                    
                <!-- Pedidos de Hoy -->
               <div class="bg-[#2C3E50] shadow-md rounded-md p-6">
                    <h3 class="text-lg text-[#FAF3E0] font-bold mb-2">Pedidos de Hoy</h3>
                    <?php
                    date_default_timezone_set('America/Bogota');

                   $sqlHoy = "SELECT COUNT(*) AS total 
                    FROM pedidos 
                    WHERE pedido_fecha >= CONVERT_TZ(CONCAT(CURDATE(), ' 00:00:00'), '-05:00', '+00:00')
                    AND pedido_fecha <  CONVERT_TZ(CONCAT(CURDATE() + INTERVAL 1 DAY, ' 00:00:00'), '-05:00', '+00:00')";

                    $resultHoy = mysqli_query($connect, $sqlHoy);
                    $filaHoy = mysqli_fetch_assoc($resultHoy);
                    $pedidosHoy = $filaHoy['total'];

                    ?>
                    <p class="text-3xl font-bold text-[#FAF3E0]"><?= $pedidosHoy ?></p>
                    <p class="text-sm text-[#FAF3E0]">Fecha de sistema: <?= date('Y-m-d') ?></p>
                </div>



                <!-- Ganancias -->
                <div class="bg-[#2C3E50] shadow-md rounded-md p-6">
                    <h3 class="text-lg font-bold text-[#FAF3E0] mb-2">Ganancias</h3>
                    <?php
                    $sqlGanancias = "SELECT SUM(cantidad * precio_unitario) AS ganancias
                                    FROM pedido_detalles pd
                                    INNER JOIN pedidos p ON pd.pedido_id = p.pedido_id
                                    WHERE p.estped_id = 3 AND DATE(p.pedido_fecha) = CURDATE()";
                    
                    $resultGanancias = mysqli_query($connect, $sqlGanancias);
                    $filaGanancias = mysqli_fetch_assoc($resultGanancias);
                    $ganancias = $filaGanancias['ganancias'] ?? 0;
                    ?>
                    <p class="text-3xl font-bold text-[#27ae60]">$<?= number_format($ganancias, 0, ',', '.') ?></p>
                </div>



                <!-- Producto más vendido -->
                <div class="bg-[#2C3E50] shadow-md rounded-md p-6">
                    <h3 class="text-lg font-bold text-[#FAF3E0] mb-2">Producto más vendido</h3>
                    <?php
                    $sqlMasVendido = "SELECT pd.plato_nombre, SUM(pd.cantidad) AS total_vendidos
                                    FROM pedido_detalles pd
                                    INNER JOIN pedidos p ON pd.pedido_id = p.pedido_id
                                    WHERE DATE(p.pedido_fecha) = CURDATE() AND p.estped_id = 3
                                    GROUP BY pd.plato_nombre
                                    ORDER BY total_vendidos DESC
                                    LIMIT 1";

                    $resultMasVendido = mysqli_query($connect, $sqlMasVendido);
                    $producto = mysqli_fetch_assoc($resultMasVendido);

                    if ($producto) {
                        echo "<p class='text-xl font-medium text-[#FAF3E0]'>{$producto['plato_nombre']}</p>";
                        echo "<p class='text-sm text-[#FAF3E0]'>Cantidad vendida: {$producto['total_vendidos']}</p>";
                    } else {
                        echo "<p class='text-[#FAF3E0]'>No hay productos vendidos hoy.</p>";
                    }
                    ?>
                </div>


            </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#2C3E50] shadow-md rounded-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl text-[#FAF3E0] font-semibold mb-4">Pedidos Recientes</h3>
                            <div class="flex items-center mb-4">
                                <bottom class="bg-blue-500 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                                    <div class="h-5 w-20 text-center font-semibold inline-block mr-1" fill="none" stroke="currentColor"><a href="../pedidos/pedidos.php">ver mas</a></div>
                                </bottom>
                            </div>
                        </div>
                        <?php
                        // Clases por estado para mostrar con color
                        $estadoClases = [
                            1 => 'bg-yellow-200 text-yellow-900',  // Pendiente
                            2 => 'bg-blue-200 text-blue-900',      // En proceso
                            3 => 'bg-green-200 text-green-900',    // Entregado
                            4 => 'bg-red-200 text-red-900',        // Cancelado
                        ];

                        // Texto con íconos
                        $estadoTextos  = [
                            1 => ' Pendiente',
                            2 => ' En proceso',
                            3 => ' Entregado',
                            4 => ' Cancelado',
                        ];
                        ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#FAF3E0] ">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#2C3E50] uppercase tracking-wider">Pedido</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#2C3E50] uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#2C3E50] uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#2C3E50] uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-[#2C3E50] uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-[#2C3E50] divide-y divide-gray-200">
                                     <?php while ($pedido = mysqli_fetch_assoc($resPedidos)): ?>
                                        <tr class="border-b">
                                            <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['pedido_id'] ?></td>
                                            <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['cli_nombre'] . " " . $pedido['cli_apellido'] ?></td>
                                            <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['pedido_fecha'] ?></td>
                                           <td class="px-4 py-2" id="estado-<?= $pedido['pedido_id'] ?>">
                                                <span class="<?= $estadoClases[$pedido['estped_id']] ?? 'bg-gray-200 text-gray-800' ?> px-3 py-1 rounded font-semibold inline-block">
                                                    <?= $estadoTextos[$pedido['estped_id']] ?? 'Sin estado' ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['pedido_valor_pagar'] ?></td>
                                            
                                        </tr>
                                    <?php endwhile; ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div class="bg-[#2C3E50] p-6 rounded-md shadow-md mt-0">
                        <h3 class="text-xl font-semibold text-[#FAF3E0] mb-4">Pedidos y Ganancias de los Últimos 7 Días</h3>
                        <canvas id="graficoPedidosGanancias" height="100"></canvas>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>


    



    
    <script>
function toggleNotificaciones() {
    const dropdown = document.getElementById("notificacionesDropdown");
    dropdown.classList.toggle("hidden");
    dropdown.classList.toggle("block");
}

// Cerrar al hacer clic fuera del dropdown
window.addEventListener("click", function(e) {
    const btn = document.getElementById("btnNotificaciones");
    const dropdown = document.getElementById("notificacionesDropdown");

    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add("hidden");
        dropdown.classList.remove("block");
    }
});
</script>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoPedidosGanancias').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Pedidos',
                    data: <?= json_encode($pedidosPorDia) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderRadius: 5
                },
                {
                    label: 'Ganancias ($)',
                    data: <?= json_encode($gananciasPorDia) ?>,
                    type: 'line',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    yAxisID: 'y1'
                    
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Pedidos',
                        color: '#FAF3E0'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Ganancias (COP)',
                        color: '#FAF3E0'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>


    <div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
  Actualmente en: <strong>Estadisticas</strong>
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




        
</body>
</html>