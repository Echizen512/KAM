function validarCedula() {
    const cedulaInput = document.getElementById("cedula");
    let cedula = cedulaInput.value;

    if (!cedula.startsWith("V-")) {
        cedula = "V-" + cedula.replace(/\D/g, "");
        cedulaInput.value = cedula;
    }

    const cedulaPattern = /^V-\d{7,8}$/;
    if (cedula) {
        if (cedulaPattern.test(cedula)) {
            fetch(`PHP/Verificar_Cedula.php?cedula=${cedula}`)
                .then((response) => response.text())
                .then((data) => {
                    if (data === "existe") {
                        mostrarModalAsistencia(cedula); // directo al modal de entrada/salida
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Cédula no encontrada",
                            text: "La cédula no existe en el sistema.",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error al verificar la cédula:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error del servidor",
                        text: "No se pudo verificar la cédula. Inténtelo más tarde.",
                    });
                });
        } else {
            Swal.fire(
                "Formato Inválido",
                "La cédula debe comenzar con V- seguido de 7 u 8 números.",
                "error"
            );
        }
    } else {
        Swal.fire("Campo Vacío", "Por favor ingrese su cédula.", "warning");
    }
}

document.getElementById("cedula").addEventListener("input", function (e) {
    let value = e.target.value.replace(/[^0-9]/g, "");
    e.target.value = `V-${value}`;
});

function mostrarModalAsistencia(cedula) {
    Swal.fire({
        title: "Marque su Asistencia",
        html: `
        <div style="display: flex; justify-content: center; align-items: center; margin-top: 20px; gap: 80px;">
            <!-- Icono Entrada -->
            <div style="display: flex; flex-direction: column; align-items: center;">
                <i class="fas fa-user-check" style="color: #1E90FF; font-size: 48px;"></i>
                <i id="flechaEntrada" class="fas fa-arrow-circle-left" style="color: #1E90FF; font-size: 36px; cursor: pointer;" title="Registrar Hora de Entrada"></i>
                <span style="margin-top: 10px; font-weight: bold;">Entrada</span>
            </div>
            <!-- Icono Salida -->
            <div style="display: flex; flex-direction: column; align-items: center;">
                <i class="fas fa-user-check" style="color: #2378b6; font-size: 48px;"></i>
                <i id="flechaSalida" class="fas fa-arrow-circle-right" style="color: #2378b6; font-size: 36px; cursor: pointer;" title="Registrar Hora de Salida"></i>
                <span style="margin-top: 10px; font-weight: bold;">Salida</span>
            </div>
        </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
    });

    document.getElementById("flechaEntrada").onclick = () => {
        mostrarModalHoraEntrada(cedula);
    };

    document.getElementById("flechaSalida").onclick = () => {
        mostrarModalHoraSalida(cedula);
    };
}


function registrarEntradaConHuella(cedula) {
    const horaActual = new Date().toLocaleTimeString("en-US", { hour12: false });
    const fechaActual = obtenerFechaActual();

    Swal.fire({
        title: "Procesando Huella",
        text: "Por favor espere mientras verificamos su huella...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    fetch(
        `PHP/Registrar_Entrada.php?cedula=${cedula}&hora_entrada=${horaActual}&fecha=${fechaActual}`
    )
        .then((response) => response.json())
        .then((data) => {
            Swal.close();
            if (data.success) {
                Swal.fire(
                    "Entrada Registrada",
                    `Hora: ${horaActual}\nFecha: ${fechaActual}`,
                    "success"
                );
            } else {
                Swal.fire(
                    "Error",
                    data.error || "No se pudo registrar la entrada.",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire("Error", "Hubo un error al registrar la entrada.", "error");
        });
}

function registrarSalidaConHuella(cedula) {
    const horaActual = new Date().toLocaleTimeString("en-US", { hour12: false });
    const fechaActual = obtenerFechaActual();

    Swal.fire({
        title: "Procesando Huella",
        text: "Por favor espere mientras verificamos su huella...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    fetch(
        `Registrar_Salida.php?cedula=${cedula}&hora_salida=${horaActual}&fecha=${fechaActual}`
    )
        .then((response) => response.json())
        .then((data) => {
            Swal.close();
            if (data.success) {
                Swal.fire(
                    "Salida Registrada",
                    `Hora: ${horaActual}\nFecha: ${fechaActual}`,
                    "success"
                );
            } else {
                Swal.fire(
                    "Error",
                    data.error || "No se pudo registrar la salida.",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire("Error", "Hubo un error al registrar la salida.", "error");
        });
}

function verificarRegistroCompleto(cedula) {
    fetch(`PHP/Verificar_Registro.php?cedula=${cedula}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.registroCompleto) {
                Swal.fire(
                    "Registro Completado",
                    "Este usuario ya completó su registro diario.",
                    "info"
                );
            } else {
                mostrarOpcionesDeRegistro(cedula);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire(
                "Error",
                "Hubo un error al verificar el estado de registro.",
                "error"
            );
        });
}

function mostrarOpcionesDeRegistro(cedula) {
    Swal.fire({
        title: "Seleccionar Acción",
        text: "¿Qué acción desea realizar?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Registrar Entrada",
        cancelButtonText: "Registrar Salida",
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarModalHoraEntrada(cedula);
        } else {
            mostrarModalHoraSalida(cedula);
        }
    });
}

function mostrarModalHoraEntrada(cedula) {
    const horaActual = new Date().toLocaleTimeString("en-US", { hour12: false });
    const fechaActual = obtenerFechaActual();
    Swal.fire({
        title: "Confirmar Hora de Entrada",
        text: `Hora: ${horaActual}\nFecha: ${fechaActual}`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Registrar Entrada",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            registrarHoraEntrada(cedula, fechaActual, horaActual);
        }
    });
}

function mostrarModalHoraSalida(cedula) {
    const horaActual = new Date().toLocaleTimeString("en-US", { hour12: false });
    const fechaActual = obtenerFechaActual();
    Swal.fire({
        title: "Confirmar Hora de Salida",
        text: `Hora: ${horaActual}\nFecha: ${fechaActual}`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Registrar Salida",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            registrarHoraSalida(cedula, fechaActual, horaActual);
        }
    });
}

function registrarHoraEntrada(cedula, fecha, hora) {
    fetch(
        `PHP/Registrar_Entrada.php?cedula=${cedula}&hora_entrada=${hora}&fecha=${fecha}`
    )
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire(
                    "Entrada Registrada",
                    `Hora: ${hora}\nFecha: ${fecha}`,
                    "success"
                );
            } else {
                Swal.fire(
                    "Error",
                    data.error || "No se pudo registrar la entrada.",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire("Error", "Hubo un error al registrar la entrada.", "error");
        });
}

function registrarHoraSalida(cedula, fecha, hora) {
    fetch(
        `PHP/Registrar_Salida.php?cedula=${cedula}&hora_salida=${hora}&fecha=${fecha}`
    )
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire(
                    "Salida Registrada",
                    `Hora: ${hora}\nFecha: ${fecha}\nAsistencia diaria completada.`,
                    "success"
                );
            } else {
                Swal.fire(
                    "Error",
                    data.error || "No se pudo registrar la salida.",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire("Error", "Hubo un error al registrar la salida.", "error");
        });
}

function obtenerFechaActual() {
    const hoy = new Date();
    const año = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, "0");
    const dia = String(hoy.getDate()).padStart(2, "0");
    return `${año}-${mes}-${dia}`;
}
