<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body>
<!-- Modal de Método de Pago -->
<div id="modal-metodo-pago" class="hidden fixed inset-0 z-90 flex items-center justify-center bg-black bg-opacity-60">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Selecciona un método de pago</h2>
            <button id="cerrar-modal-metodo-pago" class="text-gray-600 hover:text-black text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul id="resumen-pedido" class="text-sm text-gray-700 mb-4"></ul>
        <p class="font-semibold mb-4">Total: <span id="resumen-total" class="text-green-600"></span></p>

        <!-- Select dinámico cargado con PHP -->
        <select id="metodo-pago" class="w-full border border-gray-300 p-2 rounded mb-4">
            <option disabled selected>Selecciona una opción</option>
            <?php
            include("../conexion/conectarBD.php");
            $query = "SELECT * FROM metodo_pago";
            $resultado = mysqli_query($connect, $query);
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo '<option value="' . $fila['metopago_id'] . '">' . $fila['metopago_desc'] . '</option>';
            }
            ?>
        </select>

        <button id="confirmar-pedido" class="bg-green-500 text-white w-full py-2 rounded hover:bg-green-600">
            Confirmar
        </button>

<script>
document.getElementById("confirmar-pedido").addEventListener("click", () => {
    const metodoPago = document.getElementById("metodo-pago").value; 
    
    if (!metodoPago) {
        alert("Selecciona un método de pago.");
        return;
    }


    const total = carrito.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);

    fetch("../pedidos/guardar_pedido.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            metodo: metodoPago,
            carrito: carrito,
            total: total
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("✅ Pedido registrado con éxito. ID Pedido: " + data.pedido_id);
            carrito = [];
            renderCarrito();
            document.getElementById("modal-metodo-pago").classList.add("hidden");
        } else {
            alert("❌ Error al registrar el pedido.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("❌ Error al procesar el pedido.");
    });
});
</script>




<script>
document.querySelector("#btn-proceder-pago").addEventListener("click", () => {
    const modal = document.getElementById("modal-metodo-pago");
    modal.classList.remove("hidden");

    // Mostrar resumen
    const resumen = document.getElementById("resumen-pedido");
    const total = carrito.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);
    resumen.innerHTML = "";
    carrito.forEach(p => {
        resumen.innerHTML += `<li>${p.cantidad}x ${p.nombre} - $${(p.precio * p.cantidad).toLocaleString()}</li>`;
    });
    document.getElementById("resumen-total").textContent = `$${total.toLocaleString()}`;

    // Cargar métodos de pago
    fetch("php/obtenerMetodo.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("metodo-pago");
            select.innerHTML = '<option value="">Selecciona un método</option>';
            data.forEach(metodo => {
                select.innerHTML += `<option value="${metodo.metopago_id}">${metodo.metopago_desc}</option>`;
            });
        });
});

// Cerrar modal
document.getElementById("cerrar-modal-metodo-pago").addEventListener("click", () => {
    document.getElementById("modal-metodo-pago").classList.add("hidden");
});
</script>

    
</body>
</html>




