<form id="formEditar" class="w-full bg-[#2c3e50] text-[#faf3e0] rounded-xl shadow-md animate__animated animate__fadeInDown px-6 py-4 space-y-4">
    <h3 class=" text-center text-[#faf3e0] text-bold text-2xl">Editar Ingrediente</h3>
    <input type="hidden" name="id" id="editar_id" />

    <div>
        <label for="editar_nombre" class="block font-semibold">Nombre</label>
        <input type="text" name="nombre" id="editar_nombre" class="w-full bg-[#faf3e0] text-black border rounded px-3 py-2" >
    </div>

    <div>
        <label for="editar_desc" class="block font-semibold">Descripción</label>
        <textarea name="descripcion" id="editar_desc" class="w-full bg-[#faf3e0] text-black border rounded px-3 py-2" rows="2"></textarea>
    </div>

    <div>
        <label for="editar_cantidad" class="block font-semibold">Cantidad</label>
        <input type="number" name="cantidad" id="editar_cantidad" class="w-full bg-[#faf3e0] text-black border rounded px-3 py-2" step="0.01" >
    </div>

    <div>
        <label for="editar_unidad" class="block font-semibold">Unidad</label>
        <select name="unidad" id="editar_unidad" class="w-full bg-[#faf3e0] text-black border rounded px-3 py-2" >
            <option value="">Seleccione una unidad</option>
            <?php
                $connect = include("../conexion/conectarBD.php");
                $sql = "SELECT unidad_id, unidad_nombre, unidad_abreviacion FROM unidades_medida";
                $result = mysqli_query($connect, $sql);

                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $nombre = htmlspecialchars($row['unidad_nombre']);
                        $abreviacion = htmlspecialchars($row['unidad_abreviacion']);
            ?>
                <option value="<?= $row['unidad_id'] ?>"><?= "$nombre ($abreviacion)" ?></option>
            <?php endwhile; endif; ?>
        </select>
    </div>

    <div>
        <label for="editar_precio" class="block font-semibold">Precio Unitario (COP)</label>
        <input type="number" name="precio" id="editar_precio" class="w-full bg-[#faf3e0] text-black border rounded px-3 py-2" step="0.01" required>
    </div>

    <div class="flex justify-end space-x-3 pt-2">
        <button type="submit" class="bg-[#27AE60] text-white px-4 py-2 rounded hover:bg-green-700">Guardar Cambios</button>
        <button type="button" id="btnCerrarModalEditar" class="bg-[#C0392B] text-white px-4 py-2 rounded hover:bg-red-700">Cancelar</button>
    </div>
</form>


