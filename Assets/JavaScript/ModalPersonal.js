    function showModal() {
        document.getElementById("myModal").style.display = "flex";
    }

    function validateForm() {
        let valid = true;
        let mensajesError = "";

        const nacionalidad = document.getElementById("nacionalidad").value;
        const cedula = document.getElementById("cedula_personal").value;
        const correo = document.getElementById("correo_personal").value;
        const nacimiento = document.getElementById("nacimiento_personal").value;
        const ingreso = document.getElementById("ingreso_personal").value;
        const nombre = document.getElementById("nombre_personal").value;
        const apellido = document.getElementById("apellido_personal").value;

        // Validar si la cédula ya existe en la base de datos
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "verificar_cedula.php?cedula=" + cedula, false); // Sincrónico para esperar la respuesta
        xhr.send();

        if (xhr.responseText === "existe") {
            Swal.fire({
                icon: 'error',
                title: 'Error de Registro',
                text: 'La cédula ingresada ya ha sido registrada.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Validar Cédula (solo números y entre 7 y 8 dígitos, excluyendo nacionalidad)
        function validarCedula(cedula) {
            let mensajesError = "";
            let valid = true;

            if (!/^[VE]-\d{7,8}$/.test(cedula)) {
                mensajesError += "• Cédula debe contener entre 7 y 8 números y debe tener el formato V-00000000 o E-00000000.<br>";
                valid = false;
            }

            return {
                mensajesError: mensajesError,
                valid: valid
            };
        }

        const resultadoCedula = validarCedula(cedula);
        if (!resultadoCedula.valid) {
            mensajesError += resultadoCedula.mensajesError;
            valid = false;
        }

        // Validar Nombres y Apellidos (primera letra mayúscula y sin números)
        const nombreApellidoRegex = /^[A-Z][a-zA-Z]*$/;

        if (!nombreApellidoRegex.test(nombre)) {
            mensajesError += "• El nombre debe comenzar con una letra mayúscula y no debe contener números.<br>";
            valid = false;
        }

        if (!nombreApellidoRegex.test(apellido)) {
            mensajesError += "• El apellido debe comenzar con una letra mayúscula y no debe contener números.<br>";
            valid = false;
        }

        // Validar Correo
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            mensajesError += "• Correo electrónico no válido.<br>";
            valid = false;
        }

        // Validar Fechas
        const currentDate = new Date().toISOString().split("T")[0];

        if (nacimiento > currentDate) {
            mensajesError += "• Fecha de nacimiento no puede ser en el futuro.<br>";
            valid = false;
        }

        if (ingreso > currentDate) {
            mensajesError += "• Fecha de ingreso no puede ser posterior a la actual.<br>";
            valid = false;
        }

        if (!valid) {
            // Mostrar mensajes de error con SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: mensajesError,
                confirmButtonText: 'Entendido'
            });

            return false;
        } else {
            // Mostrar mensaje de registro exitoso y ocultar el formulario
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'El personal ha sido registrado correctamente.',
                confirmButtonText: 'Entendido',
              
            });

            return true;
        }
    }

    let cedulaMessageDisplayed = false;

    // Validaciones mientras el usuario escribe
    document.getElementById("cedula_personal").addEventListener("input", function(event) {
        const cedula = event.target.value;
        if (!cedulaMessageDisplayed) {
            if (!/^[VE]-/.test(cedula) || /[^0-9-]/.test(cedula) || !/^[VE]-\d*$/.test(cedula)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el campo cédula',
                    text: 'El campo cédula debe tener el formato V-00000000 o E-00000000 y contener solo números.',
                    confirmButtonText: 'Entendido'
                });
                cedulaMessageDisplayed = true;
            } else if (!/^\d{7,8}$/.test(cedula.split('-')[1])) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el campo cédula',
                    text: 'La cédula debe contener entre 7 y 8 números.',
                    confirmButtonText: 'Entendido'
                });
                cedulaMessageDisplayed = true;
            }
        }
    });

    document.getElementById("nacionalidad").addEventListener("change", function() {
        const nacionalidad = this.value;
        const cedulaInput = document.getElementById("cedula_personal");
        cedulaInput.value = `${nacionalidad}-`;
    });

    document.getElementById("nombre_personal").addEventListener("input", function(event) {
        const nombre = event.target.value;
        if (!/^[A-Z][a-zA-Z]*$/.test(nombre)) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el campo nombre',
                text: 'El nombre debe comenzar con una letra mayúscula y no debe contener números.',
                confirmButtonText: 'Entendido'
            });
        }
    });

    document.getElementById("apellido_personal").addEventListener("input", function(event) {
        const apellido = event.target.value;
        if (!/^[A-Z][a-zA-Z]*$/.test(apellido)) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el campo apellido',
                text: 'El apellido debe comenzar con una letra mayúscula y no debe contener números.',
                confirmButtonText: 'Entendido'
            });
        }
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = document.getElementById('searchInput').value.toUpperCase();
        const table = document.getElementById('miTabla');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            tr[i].style.display = 'none';
            const td = tr[i].getElementsByTagName('td');
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                        break;
                    }
                }
            }
        }
    });

      function ocultarModal() {
    // Aquí va el código para cerrar el modal
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
  }