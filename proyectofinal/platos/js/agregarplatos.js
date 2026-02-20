
document.addEventListener('DOMContentLoaded', function () {
    const addDishForm = document.getElementById('formAgregarPlato');
    if (!addDishForm) return;

    if (addDishForm.dataset.listenerAttached === "true") return;
    addDishForm.dataset.listenerAttached = "true";

    const submitBtn = addDishForm.querySelector('button[type="submit"]');

    addDishForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(this);
        const categoria = document.querySelector('[name="categoria_id"]');
        if (categoria) formData.append("categoria_id", categoria.value);

        // Validación previa (opcional, antes de enviar)
        const nombre = formData.get('nombre')?.trim();
        const precio = formData.get('precio')?.trim();

        if (!nombre || !precio) {
            Swal.fire({
                icon: 'warning',
                title: 'Faltan datos',
                text: 'Por favor completa todos los campos.',
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#27AE60'
            });
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        fetch('/proyectofinal/platos/procesarAgregarPlato.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error("Respuesta no válida del servidor");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                     background: '#2C3E50',
                    color: '#FAF3E0',
                    timer: 2000,
                    showConfirmButton: false
                });

                addDishForm.reset();

                const modalAgregar = document.getElementById('modalAgregarPlato');
                if (modalAgregar) modalAgregar.classList.add('hidden');

                fetch('/proyectofinal/platos/cargar_platos.php?pagina=1')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('contenedor-platos').innerHTML = html;
                });

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: data.message || 'Faltan datos',
                    text: data.text || 'Por favor completa todos los campos.',
                    background: '#2C3E50',
                    color: '#FAF3E0',
                    confirmButtonColor: '#27AE60'
                });
            }
        })
        .catch(error => {
            console.error('Error al agregar el plato:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'No se pudo completar la operación: ' + error.message,
                background: '#2C3E50',
                color: '#FAF3E0',
                confirmButtonColor: '#E74C3C'
            });
        })
        .finally(() => {
            if (submitBtn) submitBtn.disabled = false;
        });
    });
});





