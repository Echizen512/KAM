  function mostrarMensaje(mensaje, tipo) {
            let iconColor = tipo === 'success' ? '#4caf50' : '#f44336'; // Green for success, red for error
            Swal.fire({
                icon: tipo,
                title: mensaje,
                background: 'white',
                iconColor: iconColor,
                color: 'black',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'mensaje-popup'
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const usuario = form.usuario.value.trim();
                const contrasena = form.contrasena.value.trim();

                if (!usuario || !contrasena) {
                    mostrarMensaje('Por favor, completa todos los campos.', 'error');
                    return;
                }

                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                mostrarMensaje(result.message, result.status === 'success' ? 'success' : 'error');

                if (result.status === 'success' && result.redirect) {
                    setTimeout(() => {
                        window.location.replace(result.redirect);
                    }, 2000);
                }
            });
        });

        