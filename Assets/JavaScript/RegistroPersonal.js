function showAlert() {
    Swal.fire({
        title: '¿Desea registrar su huella?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            registrarHuella();
        }
    });
}

function registrarHuella() {
    // Validar inputs antes de enviar
    if (!validarInputs()) return;

    Swal.fire({
        title: 'Registrando huella...',
        text: 'Por favor, espere mientras procesamos la operación.',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading(); // Muestra un indicador de carga
        }
    });

    // Crear objeto FormData para enviar los datos al servidor
    const formData = new FormData();
    formData.append('nombre_personal', document.getElementById("nombre_personal").value);
    formData.append('apellido_personal', document.getElementById("apellido_personal").value);
    formData.append('nacionalidad', document.getElementById("nacionalidad").value);
    formData.append('cedula_personal', document.getElementById("cedula_personal").value);
    formData.append('titulo_personal', document.getElementById("titulo_personal").value);
    formData.append('correo_personal', document.getElementById("correo_personal").value);
    formData.append('nacimiento_personal', document.getElementById("nacimiento_personal").value);
    formData.append('ingreso_personal', document.getElementById("ingreso_personal").value);
    formData.append('cargo_personal', document.getElementById("cargo_personal").value);

    // Llamada al archivo PHP
    fetch('procesar_huella.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al conectar con el servidor.');
        }
        return response.json();
    })
    .then(data => {
        Swal.close(); // Cierra la alerta de carga

        if (data.error) {
            throw new Error(data.error); // Manejo de errores desde el servidor
        }

        // Mensajes según la respuesta del servidor
        if (data.id_personal) {
            Swal.fire({
                title: 'Éxito',
                text: `Huella registrada exitosamente para el usuario ID: ${data.id_personal}.`,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (data.new_user) {
            Swal.fire({
                title: 'Usuario Registrado',
                text: 'Se ha registrado un nuevo usuario y su huella fue almacenada correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        }

        // Mostrar imagen de la huella (si se devuelve)
        const container = document.querySelector('.image-tooltip-container') || crearContenedorImagen();
        container.innerHTML = ''; // Limpia cualquier imagen previa
        if (data.base64Image) {
            const huellaImg = document.createElement('img');
            huellaImg.src = `data:image/png;base64,${data.base64Image}`;
            huellaImg.alt = 'Huella Registrada';
            huellaImg.width = 200;
            huellaImg.height = 200;
            container.appendChild(huellaImg);
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error',
            text: error.message,
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    });
}

function validarInputs() {
    const campos = [
        { id: "nombre_personal", nombre: "Nombre" },
        { id: "apellido_personal", nombre: "Apellido" },
        { id: "nacionalidad", nombre: "Nacionalidad" },
        { id: "cedula_personal", nombre: "Cédula" },
        { id: "correo_personal", nombre: "Correo Electrónico" },
        { id: "nacimiento_personal", nombre: "Fecha de Nacimiento" },
        { id: "ingreso_personal", nombre: "Fecha de Ingreso" },
        { id: "cargo_personal", nombre: "Cargo" }
    ];

    for (const campo of campos) {
        const valor = document.getElementById(campo.id)?.value || "";
        if (!valor) {
            Swal.fire({
                title: 'Error',
                text: `El campo "${campo.nombre}" es obligatorio.`,
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return false;
        }
    }

    return true;
}

function crearContenedorImagen() {
    const newContainer = document.createElement('div');
    newContainer.className = 'image-tooltip-container';
    document.body.appendChild(newContainer);
    return newContainer;
}
