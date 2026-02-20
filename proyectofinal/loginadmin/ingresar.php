 <?php
    session_start();
    include ("../conexion/conectarBD.php");
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('error-message');

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const email = loginForm.querySelector('input[name="email"]').value;
            const password = loginForm.querySelector('input[name="password"]').value;
            const rol = loginForm.querySelector('select[name="rol"]').value;

            if (!email || !password || !rol) {
                errorMessage.textContent = 'Por favor, completa todos los campos.';
                errorMessage.classList.remove('hidden');
                return;
            }

            const formData = new FormData(loginForm);

            fetch('validar.php', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = 'validar.php';
                    }
                } else {
                    errorMessage.textContent = data.error || 'Error al iniciar sesión.';
                    errorMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                errorMessage.textContent = 'Error de conexión.';
                errorMessage.classList.remove('hidden');
            });
        });
    });
</script>