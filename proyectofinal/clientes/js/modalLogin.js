console.log("JS cargado");

document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM listo");

    document.body.addEventListener("click", function (event) {
        const perfilBtn = event.target.closest("#perfilBtn");
        if (perfilBtn) {
            console.log("Click en perfilBtn");
            fetch("../clientes/loginModal.php")
                .then(response => {
                    if (!response.ok) throw new Error("Error en la respuesta del servidor");
                    return response.text();
                })
                .then(html => {
                    console.log("Modal recibido correctamente");
                    const contenedor = document.getElementById("contenedorLoginModal");
                    contenedor.innerHTML = html;

                    const cerrar = document.getElementById("cerrarLoginModal");
                    if (cerrar) {
                        cerrar.addEventListener("click", () => {
                            contenedor.innerHTML = "";
                        });
                    }
                })
                .catch(error => {
                    console.error("Error cargando modal:", error);
                });
        }
    });
});
