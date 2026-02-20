document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();
        // 🧼 Ocultar mensaje de error si se está reintentando
        errorMessage.classList.add('hidden');
        errorMessage.textContent = '';

        const email = loginForm.querySelector('input[name="email"]').value.trim();
        const password = loginForm.querySelector('input[name="clave"]').value.trim();
        const rol = loginForm.querySelector('select[name="rol"]').value.trim();

        if (!email || !password || !rol) {
            errorMessage.textContent = 'Por favor, completa todos los campos.';
            errorMessage.classList.remove('hidden');
            return;
        }

        const formData = new FormData(loginForm);

        fetch('../loginadmin/validar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verifica si la respuesta es JSON válida
            return response.text().then(text => {
                try {
                    const json = JSON.parse(text);
                    return json;
                } catch (err) {
                    console.error("❌ Respuesta no es JSON válido:", text);
                    throw new Error("El servidor devolvió una respuesta inválida.");
                }
            });
        })
        .then(data => {
            console.log('✅ Respuesta del servidor:', data);

            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '../loginadmin/prueba.php';
                }
            } else {
                errorMessage.textContent = data.error || 'Error al iniciar sesión.';
                errorMessage.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error("⚠️ Error en el proceso:", error);
            errorMessage.textContent = error.message || 'Error de conexión.';
            errorMessage.classList.remove('hidden');
        });
    });
});
