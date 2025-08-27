// Función para abrir el modal de edición con los datos de la persona
function openEditModal(id, nombre, apellido, cedula, correo, nacimiento, ingreso, cargo) {
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-nombre').value = nombre;
    document.getElementById('modal-apellido').value = apellido;
    document.getElementById('modal-cedula').value = cedula;
    document.getElementById('modal-correo').value = correo;
    document.getElementById('modal-nacimiento').value = nacimiento;
    document.getElementById('modal-ingreso').value = ingreso;
    document.getElementById('modal-cargo').value = cargo;
    document.getElementById('editModal').style.display = "block";
}

// Función para abrir el modal de vista previa con los datos de la persona
function openPreviewModal(id, nombre, apellido, cedula, correo, nacimiento, ingreso, cargo) {
    const previewContent = `
        <div class="cv-vitae">
        <div class align="center">
    <h2 style="color: #2378b6;">Vista Previa</h2>
</div><br>

            <p><label>Nombre:</label> ${nombre}</p>  <br>
            <p><label>Apellido:</label> ${apellido}</p>  <br>
            <p><label>Cédula:</label> ${cedula}</p>  <br>
            <p><label>Correo:</label> ${correo}</p>  <br>
            <p><label>Fecha de Nacimiento:</label> ${nacimiento}</p>  <br>
            <p><label>Fecha de Ingreso:</label> ${ingreso}</p>  <br>
            <p><label>Cargo:</label> ${cargo}</p>  <br>
        </div>
    `;
    document.getElementById('previewContent').innerHTML = previewContent;
    document.getElementById('previewModal').style.display = "block";
}

// Función para cerrar el modal
function closeModal() {
    document.getElementById('editModal').style.display = "none";
    document.getElementById('previewModal').style.display = "none";
}
