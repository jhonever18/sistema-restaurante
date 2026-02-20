<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pantallaseleccion/principal.php");
    exit();
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

            <a href="../perfil/cjPerfil.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10 ">
                <i class="material-icons text-[24px]">account_circle</i>
                <span class="ml-4 hidden group-hover:inline-block">Perfil</span> 
            </a>
            <a href="../inicio/cajero.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">dining</i>
                <span class="ml-4 hidden group-hover:inline-block">Menu</span>
            </a>
            <a href="../pedidos_cajero/Pedidos.php" class="flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">shopping_cart</i>
                <span class="ml-4 hidden group-hover:inline-block">Pedidos</span>
            </a>

            <a href="../cajero_facturas/facturas.php" class=" flex items-center w-full py-4 px-4 hover:bg-white/10">
                <i class="material-icons text-[24px]">receipt_long</i>
                <span class="ml-4 hidden group-hover:inline-block">Facturas</span>
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
                    window.location.href = '../../cerrarSesion/cerrar_sesion.php';

                }
            });
        });
        </script>
        
        <div class=" w-11/12  mt-5  border-[#784212] border p-6 m-6 rounded-lg shadow-md">

            
               <?php
                include_once(__DIR__ . "/../../conexion/conectarBD.php");


                // Paginación
                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $porPagina = 10;
                $offset = ($pagina - 1) * $porPagina;

                // Traer estados disponibles
                $estados = [];
                $resEstados = mysqli_query($connect, "SELECT * FROM estado_pedido");
                while ($row = mysqli_fetch_assoc($resEstados)) {
                    $estados[] = $row;
                }

                // Consulta principal con límite y offset
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
                        ORDER BY p.pedido_id DESC
                        LIMIT $porPagina OFFSET $offset";

                $resPedidos = mysqli_query($connect, $sql);

                // Consulta total de registros
                $totalRegistros = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM pedidos"))['total'];
                $totalPaginas = ceil($totalRegistros / $porPagina);
                ?>
                
                <!-- Tabla de pedidos -->
                <table class="w-full text-center mt-8 bg-[#2C3E50]  rounded">
                    <thead class=" text-[#2C3E50] bg-[#FAF3E0]">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Valor</th>
                            <th class="px-4 py-2">Estado Actual</th>
                            <th class="px-4 py-2">Cambiar Estado</th>
                        </tr>
                    </thead>
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

                    <tbody>
                        <?php while ($pedido = mysqli_fetch_assoc($resPedidos)): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2 text-[#faf3e0]"><?= $pedido['pedido_id'] ?></td>
                                <td class="px-4 py-2 text-[#faf3e0]"><?= $pedido['cli_nombre'] . " " . $pedido['cli_apellido'] ?></td>
                                <td class="px-4 py-2 text-[#faf3e0]"><?= $pedido['pedido_fecha'] ?></td>
                                <td class="px-4 py-2 text-[#faf3e0]"><?= $pedido['pedido_valor_pagar'] ?></td>
                                <td class="px-4 py-2" id="estado-<?= $pedido['pedido_id'] ?>">
                                    <span class="<?= $estadoClases[$pedido['estped_id']] ?? 'bg-gray-200 text-gray-800' ?> px-3 py-1 rounded font-semibold inline-block">
                                        <?= $estadoTextos[$pedido['estped_id']] ?? 'Sin estado' ?>
                                    </span>
                                </td>

                                <!-- Fragmento dentro del while -->
                               <td class="px-4 py-2">
                                    <?php if ($pedido['estped_id'] != 4 && $pedido['estped_id'] != 3): ?>
                                        <select 
                                            onchange="cambiarEstado(<?= $pedido['pedido_id'] ?>, this.value)" 
                                            class="bg-gray-100 px-2 py-1 rounded">
                                            <option disabled selected>Cambiar a...</option>
                                            <?php foreach ($estados as $estado): ?>
                                                <option value="<?= $estado['estped_id'] ?>">
                                                    <?= $estado['estped_desc'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <span class="<?= $pedido['estped_id'] == 4 ? 'text-red-600' : 'text-green-600' ?> font-semibold">
                                            <?= $pedido['estped_desc'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="mt-6 flex justify-center space-x-4">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="?pagina=<?= $i ?>" class="px-3 py-1 <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?> rounded"><?= $i ?></a>
                    <?php endfor; ?>
                </div>

            
        </div>
    </div>
    <script>
function cambiarEstado(pedidoId, nuevoEstadoId) {
    fetch('../pedidos_cajero/actualizar_estado_pedido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ pedido_id: pedidoId, estped_id: nuevoEstadoId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar página para reflejar cambio de color y estado
            location.reload();
        } else {
            alert('❌ Error al actualizar estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ No se pudo cambiar el estado');
    });
}
</script>

<div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
  Actualmente en: <strong>Pedidos</strong>
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

<script>
function cambiarEstado(pedidoId, nuevoEstadoId) {
    const estadoTexto = {
        1: "Pendiente",
        2: "En proceso",
        3: "Entregado",
        4: "Cancelado"
    };
    

    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres cambiar el estado del pedido #${pedidoId} a "${estadoTexto[nuevoEstadoId]}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#27AE60',
        cancelButtonColor: '#C0392B',
        color: '#FAF3E0',
        background: '#2c3e50',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../pedidos_cajero/actualizar_estado_pedido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ pedido_id: pedidoId, estped_id: nuevoEstadoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Estado actualizado',
                        text: `El estado del pedido #${pedidoId} fue cambiado a "${estadoTexto[nuevoEstadoId]}" correctamente.`,
                        timer: 2500,
                        color:'#FAF3E0',
                        background : '#2C3E50',
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', '❌ No se pudo cambiar el estado.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', '❌ Error de red o del servidor.', 'error');
            });
        }
    });
}
</script>




</body>
</html>
