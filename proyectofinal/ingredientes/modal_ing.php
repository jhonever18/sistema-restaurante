<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Ingrediente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<!-- Modal Agregar Ingrediente -->
<div id="modalAgregar" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">


  <form id="formAgregarDirecto" method="POST" action="ingredientes.php" class="animate__animated animate__fadeInDown bg-[#2C3E50] p-8 rounded-lg shadow-lg w-full max-w-xl space-y-4">


    <h2 class="text-2xl font-bold text-center text-[#f5f5dc]">Agregar Ingrediente</h2>
    <input type="hidden" name="action" value="agregar">

    <!-- Nombre -->
    <div>
      <label class="block text-sm font-semibold text-[#FAF3E0]">Nombre</label>
      <input name="nombre" type="text"  class="mt-1 w-full bg-[#FAF3E0] border border-gray-600 text-[#2C3E50] placeholder-gray-400 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: Tomate">
    </div>

    <!-- Descripción -->
    <div>
      <label class="block text-sm font-semibold text-[#FAF3E0]">Descripción</label>
      <textarea name="descripcion" rows="2" class="mt-1 w-full bg-[#FAF3E0] border border-gray-600 text-[#2C3E50] placeholder-gray-400 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: Tomate rojo fresco..."></textarea>
    </div>

    <!-- Cantidad y Unidad -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold text-[#FAF3E0]">Cantidad</label>
        <input name="cantidad" type="number" step="0.01" class="mt-1 w-full bg-[#FAF3E0] border border-gray-600 text-[#2C3E50] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: 1.5">
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#FAF3E0]">Unidad</label>
        <select name="unidad" class="mt-1 w-full bg-[#FAF3E0] border bg-[#FAF3E0] text-[#2C3E50] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <option value="">Selecciona una unidad</option>
          <?php
            $connect = include("../conexion/conectarBD.php");
            $sql = "SELECT unidad_id, unidad_nombre, unidad_abreviacion FROM unidades_medida";
            $result = mysqli_query($connect, $sql);
            if ($result && mysqli_num_rows($result) > 0):
              while ($row = mysqli_fetch_assoc($result)):
          ?>
            <option value="<?= $row['unidad_id'] ?>">
              <?= htmlspecialchars($row['unidad_nombre']) ?> (<?= htmlspecialchars($row['unidad_abreviacion']) ?>)
            </option>
          <?php endwhile; endif; ?>
        </select>
      </div>
    </div>

    <!-- Precio -->
    <div>
      <label class="block text-sm font-semiboold text-[#FAF3E0]  ">Precio Unitario (COP)</label>
      <input name="precio" type="number" step="0.01" class="mt-1 w-full bg-[#FAF3E0] border border-gray-600 text-[#2C3E50] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: 500">
    </div>

    <!-- Estado -->
    <div>
      <label class="block text-sm font-semibold text-[#FAF3E0] ">Estado</label>
      <select name="estado" class="mt-1 w-full bg-[#FAF3E0] border border-gray-600 text-[#2C3E50] rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <?php
          $sql = "SELECT esta_id, esta_desc FROM estado";
          $result = mysqli_query($connect, $sql);
          if ($result && mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
          <option value="<?= $row['esta_id'] ?>">
            <?= htmlspecialchars($row['esta_desc']) ?>
          </option>
        <?php endwhile; endif; ?>
      </select>
    </div>

    <!-- Botones -->
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCerrarModalAgregar" class="bg-red-700 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
        Cancelar
      </button>
      <button type="submit" class="bg-[#27AE60] hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
        Guardar
      </button>
    </div>

   
  </form>
</div>


</body>
</html>
