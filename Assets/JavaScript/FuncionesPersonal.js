        document.querySelector('.logo-container').addEventListener('mouseover', function() {
        document.querySelector('.hover-message').style.display = 'block';
    });
        document.querySelector('.logo-container').addEventListener('mouseout', function() {
        document.querySelector('.hover-message').style.display = 'none';
    });


document.addEventListener('DOMContentLoaded', () => {
    displayRows();
    updatePageNumbers();
});

document.getElementById("prevButton").addEventListener("click", showPrevious);
document.getElementById("nextButton").addEventListener("click", showNext);

  // Función para guardar el estado del interruptor en localStorage
        function saveStatus(id, status) {
            localStorage.setItem(`status-${id}`, status);
        }

        // Función para cargar el estado del interruptor desde localStorage
        function loadStatus() {
            var rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                var checkbox = row.querySelector('.status-switch input');
                var statusText = row.querySelector('.status-text');
                var id = checkbox.getAttribute('data-id');
                var status = localStorage.getItem(`status-${id}`);
                if (status === 'inactivo') {
                    checkbox.checked = false;
                    row.classList.add('inactive-row');
                    statusText.textContent = 'Inactivo';
                } else {
                    checkbox.checked = true;
                    row.classList.remove('inactive-row');
                    statusText.textContent = 'Activo';
                }
            });
        }

        function toggleStatus(checkbox) {
            var row = checkbox.closest('tr');
            var statusText = row.querySelector('.status-text');
            var nombre = row.children[1].textContent;
            var apellido = row.children[2].textContent;
            var id = checkbox.getAttribute('data-id');

            if (!checkbox.checked) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas desactivar a ${nombre} ${apellido}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.classList.add('inactive-row');
                        statusText.textContent = 'Inactivo';
                        saveStatus(id, 'inactivo');
                    } else {
                        checkbox.checked = true;
                    }
                });
            } else {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas activar a ${nombre} ${apellido}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, activar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.classList.remove('inactive-row');
                        statusText.textContent = 'Activo';
                        saveStatus(id, 'activo');
                    } else {
                        checkbox.checked = false;
                    }
                });
            }
        }

        // Cargar el estado del interruptor al cargar la página
        window.onload = loadStatus;