    function convertirFormatoHora(hora) {
        const [horas, minutos] = hora.split(':');
        let horas12 = horas % 12 || 12; 
        const amPm = horas < 12 ? 'AM' : 'PM';
        return `${horas12}:${minutos} ${amPm}`;
    }

    let registrosOriginales = []; 


    function otrosRegistros(cedula) {
        fetch('otros_registros.php?cedula=' + encodeURIComponent(cedula))
            .then(response => response.json())
            .then(data => {
                registrosOriginales = data;
                if (data.length > 0) {
                    actualizarTabla(data);
                } else {
                    document.getElementById("otrosRegistrosModalContent").innerHTML = "<p>No se encontraron registros.</p>";
                }
                document.getElementById("otrosRegistrosModal").style.display = "block";
            })
            .catch(error => {
                console.error('Error al obtener los registros:', error);
                document.getElementById("otrosRegistrosModalContent").innerHTML = "<p>Error al cargar los registros.</p>";
                document.getElementById("otrosRegistrosModal").style.display = "block";
            });
    }


    function actualizarTabla(registros) {
        const tablaRegistros = registros.map(registro => `
            <tr>
                <td>${registro.fecha_asistencia}</td>
                <td>${convertirFormatoHora(registro.hora_entrada)}</td>
                <td>${convertirFormatoHora(registro.hora_salida)}</td>
            </tr>
        `).join("");

        document.getElementById("otrosRegistrosModalContent").innerHTML = `
            <table class="table-modal">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora de Entrada</th>
                        <th>Hora de Salida</th>
                    </tr>
                </thead>
                <tbody id="tablaRegistros">
                    ${tablaRegistros}
                </tbody>
            </table>
        `;
    }

    function filtrarRegistros() {
        const criterioBusqueda = document.getElementById("buscarFecha").value.toLowerCase();
        const registrosFiltrados = registrosOriginales.filter(registro => 
            registro.fecha_asistencia.toLowerCase().includes(criterioBusqueda)
        );
        actualizarTabla(registrosFiltrados);
    }