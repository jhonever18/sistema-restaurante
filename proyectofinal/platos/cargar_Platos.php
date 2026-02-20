<style>
  .food-item.selected {
    border: 3px solid #FFD700;
    box-shadow: 0 0 10px #FFD700;
  }

  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

<?php
$connect = include("../conexion/conectarBD.php");

if (!$connect) {
    echo '<p class="text-red-500 text-center">Error: Conexión a la base de datos no disponible.</p>';
    exit();
}

// Paginación
$limite = 12;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $limite;

// Consulta con paginación
$sql = "SELECT 
            p.plato_id,
            p.plato_nombre,
            p.plato_desc,
            p.plato_precio,
            p.plato_imagen_url,
            (
                SELECT GROUP_CONCAT(i.ing_nombre ORDER BY i.ing_nombre ASC SEPARATOR ', ')
                FROM plato_ingredientes pi
                JOIN ingredientes i ON pi.ing_id = i.ing_id
                WHERE pi.plato_id = p.plato_id
            ) AS ing_nombre
        FROM platos p
        ORDER BY p.plato_id DESC
        LIMIT $inicio, $limite";

$result = mysqli_query($connect, $sql);

// Total de platos
$totalQuery = mysqli_query($connect, "SELECT COUNT(*) as total FROM platos");
$total = mysqli_fetch_assoc($totalQuery)['total'];
$totalPaginas = ceil($total / $limite);
?>


  <div class="grid w-full grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6  gap-2 ">
    <?php while ($plato = mysqli_fetch_assoc($result)) : ?>
      <div class="food-item bg-[#2C3E50] p-4 rounded-lg w-[200px] text-center transition-transform duration-150 hover:scale-105 hover:shadow-xl border flex flex-col  border-gray-800 relative cursor-pointer"
        data-id="<?= $plato['plato_id'] ?>"
        data-nombre="<?= htmlspecialchars($plato['plato_nombre'], ENT_QUOTES) ?>"
        data-ingredientes="<?= htmlspecialchars($plato['ing_nombre'] ?? 'Sin ingredientes', ENT_QUOTES) ?>"
        data-descripcion="<?= htmlspecialchars($plato['plato_desc'], ENT_QUOTES) ?>"
        data-precio="<?= number_format($plato['plato_precio'], 0, ',', '.') ?>"
      >
        <?php if (!empty($plato['plato_imagen_url'])) : ?>
          <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-40 h-40 object-cover rounded-full mx-auto mb-2 border-4 border-[#e44d26] shadow-lg">
        <?php else : ?>
          <div class="w-40 h-40 flex items-center justify-center mx-auto rounded-full mb-3 bg-gray-200 text-gray-700">Sin imagen</div>
        <?php endif; ?>

        <?php
          $nombrePartes = explode(' ', $plato['plato_nombre'], 2);
          $nombreFormateado = htmlspecialchars($nombrePartes[0]);
          if (isset($nombrePartes[1])) {
              $nombreFormateado .= '<br>' . htmlspecialchars($nombrePartes[1]);
          }
        ?>
        <div class="font-oswald text-lg text-[#e44d26] font-bold uppercase leading-tight mb-2">
          <?= $nombreFormateado ?>
        </div>

      </div>
    <?php endwhile; ?>
  </div>

  <!-- Paginación -->
  <div class="flex justify-center mt-6 space-x-2">
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
      <a href="?pagina=<?= $i ?>" class="px-3 py-1 rounded 
        <?= $i == $pagina ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
</div>

<!-- MODAL VER PLATO -->
<div id="ingredients-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-all duration-300">
  <div class="bg-[#2C3E50] p-6 rounded-2xl shadow-2xl w-96 max-w-full text-center relative space-y-4 animate__animated animate__fadeInDown">
    <h3 class="text-2xl font-bold text-[#C0392B]" id="modal-plato-nombre"></h3>
    <div>
      <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Ingredientes</p>
      <p class="text-[#FAF3E0] text-base" id="modal-ingredientes"></p>
    </div>
    <div>
      <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Descripción</p>
      <p class="text-[#FAF3E0] text-base" id="modal-descripcion"></p>
    </div>
    <div>
      <p class="text-sm font-semibold text-[#FAF3E0] uppercase">Precio</p>
      <p class="text-[#27AE60] text-lg font-bold" id="modal-precio"></p>
    </div>
    <button onclick="cerrarModal()" class="bg-[#C0392B] text-white px-6 py-2 rounded-full hover:bg-red-700 transition">Cerrar</button>
  </div>
</div>

