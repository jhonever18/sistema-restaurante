<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pantallaseleccion/principal.php");
    exit();
}
?>

<?php
include ("../conexion/conectarBD.php");

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$total_resultado = mysqli_query($connect, "SELECT COUNT(*) AS total FROM pedidos");
$total_fila = mysqli_fetch_assoc($total_resultado);
$total_pedidos = $total_fila['total'];
$total_paginas = ceil($total_pedidos / $limite);

// CONSULTA CON LIMIT
$query = "SELECT 
        p.pedido_id,
        p.pedido_fecha,
        p.pedido_valor_pagar,
        m.metopago_desc,
        c.cli_nombre,
        c.cli_apellido,
        e.estped_desc
    FROM pedidos p
    LEFT JOIN metodo_pago m ON p.metopago_id = m.metopago_id
    LEFT JOIN clientes c ON p.cliente_id = c.cliente_id
    LEFT JOIN estado_pedido e ON p.estped_id = e.estped_id
    WHERE p.estped_id = 3
    ORDER BY p.pedido_id DESC
    LIMIT $offset, $limite";


$resultado = mysqli_query($connect, $query);
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

        i

        <!-- CONTENIDO -->
        <div class="flex-1 p-5 overflow-auto">

            <div class="max-w-7xl w-11/12 mx-auto mt-8  border border-[#784212] p-6 rounded-lg shadow-md">

                <table class="w-full bg-[#2C3E50] text-center rounded shadow overflow-hidden">
                    <thead class="bg-[#FAF3E0] text-[#2C3E50]">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Método de Pago</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pedido = mysqli_fetch_assoc($resultado)): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['pedido_id'] ?></td>
                                <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['pedido_fecha'] ?></td>
                                <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['cli_nombre'] . ' ' . $pedido['cli_apellido'] ?></td>
                                <td class="px-4 py-2 text-[#FAF3E0]"><?= $pedido['metopago_desc'] ?></td>
                                <td class="px-4 py-2 text-[#FAF3E0]">$<?= number_format($pedido['pedido_valor_pagar']) ?></td>
                               <td class="px-4 py-2">
                                    <a href="#" data-pedido="<?= $pedido['pedido_id'] ?>" class="btnVerFactura flex items-center justify-center px-2 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" 
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6M5 7h14M5 3h14a2 2 0 012 2v16l-4-4-4 4-4-4-4 4V5a2 2 0 012-2z" />
                                        </svg>
                                        Ver
                                    </a>

                                </td>

                                
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="flex justify-center mt-6 space-x-2">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a href="?pagina=<?= $i ?>"
                        class="px-3 py-1 rounded <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                        <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

            </div>
        </div>
    </div>

    <div id="mensajeUbicacion" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50 animate-fade-in">
  Actualmente en: <strong>Facturas</strong>
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
<!-- Contenedor del sidebar -->
<div id="sidebarFactura" class="fixed top-0 right-0 w-full md:w-[500px] h-full bg-[#2c3e50] shadow-lg z-50 transform translate-x-full transition-transform duration-300 overflow-y-auto">
    <div class="flex justify-between mt-6 no-print">
  <!-- Botón Volver -->
  <button onclick="document.getElementById('sidebarFactura').classList.add('translate-x-full');"
    class="bg-white text-[#C0392B] border border-[#C0392B] px-4 py-2 rounded-full shadow hover:bg-[#C0392B] hover:text-white transition font-semibold flex items-center gap-2">
    ← Volver
  </button>

  <!-- Botón Imprimir -->
  <button onclick="window.print()"
    class="bg-[#C0392B] text-white px-4 py-2 rounded-full shadow hover:bg-red-300 transition font-semibold flex items-center gap-2">
    🖨️ Imprimir
  </button>
</div>

    <div id="contenidoFactura" class="p-4"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Abrir factura
  document.querySelectorAll('.btnVerFactura').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const pedidoId = this.dataset.pedido;

      fetch(`../facturas/impFactura.php?pedido_id=${pedidoId}`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('contenidoFactura').innerHTML = html;
          document.getElementById('sidebarFactura').classList.remove('translate-x-full');
        })
        .catch(err => {
          document.getElementById('contenidoFactura').innerHTML = `<p class="text-red-500">Error al cargar la factura</p>`;
        });
    });
  });

  // Cerrar sidebar
  document.getElementById('cerrarSidebar').addEventListener('click', () => {
    document.getElementById('sidebarFactura').classList.add('translate-x-full');
    document.getElementById('contenidoFactura').innerHTML = '';
  });
});
</script>


</body>
</html>
