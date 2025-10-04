  $(document).ready(function () {
    $('#miTabla').DataTable({
      responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      pageLength: 10
    });
  });

  function confirmarEliminacion(id) {
  Swal.fire({
    title: '¿Eliminar horario?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('formEliminarHorario' + id).submit();
    }
  });
}

function editarHorario(id) {
    fetch('PHP/obtener_horario.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById("idHorarioEditar").value = data.horario_id;
            document.getElementById("cedulaEditar").value = data.cedula;
            document.getElementById("tipoEditar").value = data.tipo;
            document.getElementById("horasEditar").value = data.total_horas;

            window.bloquesEditar = data.bloques || [];
            renderBloquesEditar(data.tipo, window.bloquesEditar);
            new bootstrap.Modal(document.getElementById("modalEditarHorario")).show();
        })
        .catch(err => console.error("Error al cargar horario:", err));
}

function editarTipoHorarioChange(select) {
    renderBloquesEditar(select.value, window.bloquesEditar || []);
}

function renderBloquesEditar(tipo, bloques) {
    const container = document.getElementById("bloquesEditarContainer");
    container.innerHTML = "";

    if (tipo === "parcial" || tipo === "tiempo_completo") {
        let html = `
      <table class="table table-sm table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Día</th><th>Hora</th><th>Nivel</th><th>Sección</th><th>Materia</th>
          </tr>
        </thead>
        <tbody id="tbodyEditarParcial">
    `;

        bloques.forEach(b => {
            html += `
        <tr>
          <td><input type="text" name="dia[]" value="${b.dia}" class="form-control"></td>
          <td><input type="text" name="hora[]" value="${b.hora}" class="form-control"></td>
          <td><input type="text" name="anio[]" value="${b.nivel}" class="form-control"></td>
          <td><input type="text" name="seccion[]" value="${b.seccion}" class="form-control"></td>
          <td>
            <select name="materia_id[]" class="form-select materia-select"></select>
          </td>
        </tr>
      `;
        });

        html += "</tbody></table>";
        container.innerHTML = html;

    } else {
        container.innerHTML = `<p class="text-muted">Aquí cargamos los bloques por día...</p>`;
    }

    cargarMateriasEnSelects();
}

document.addEventListener("DOMContentLoaded", () => {
    fetch('PHP/obtener_materias.php')
        .then(res => res.json())
        .then(materias => {
            const select = document.getElementById("selectMateria");
            select.innerHTML = '<option value="">-- Selecciona una materia --</option>';
            materias.forEach(m => {
                const opt = document.createElement("option");
                opt.value = m.id;

                opt.textContent = m.nombre;
                select.appendChild(opt);
            });
        })
        .catch(err => {
            console.error("Error al cargar materias:", err);
            document.getElementById("selectMateria").innerHTML = '<option value="">Error al cargar materias</option>';
        });
});

const cedulaInput = document.getElementById("cedulaInput");

cedulaInput.addEventListener("input", function () {
    if (!this.value.startsWith("V-")) {
        this.value = "V-" + this.value.replace(/[^0-9]/g, "");
    } else {
        const soloNumeros = this.value.slice(2).replace(/[^0-9]/g, "");
        this.value = "V-" + soloNumeros;
    }
});

cedulaInput.addEventListener("keydown", function (e) {
    if (this.selectionStart <= 2 && (e.key === "Backspace" || e.key === "ArrowLeft")) {
        e.preventDefault();
    }
});

function verificarCedula() {
    const cedula = document.getElementById("cedulaInput").value.trim();
    const mensaje = document.getElementById("mensaje");

    if (!cedula) {
        mensaje.innerText = "Ingrese una cédula válida.";
        mensaje.className = "mb-2 text-danger";
        return;
    }

    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `cedula=${encodeURIComponent(cedula)}&ajax=1`
    })
        .then(resp => resp.json())
        .then(data => {
            if (data.encontrado) {
                mensaje.innerHTML = `<strong>${data.nombre} ${data.apellido}</strong> - ${data.cargo}`;
                mensaje.className = "mb-2 text-success";
                document.getElementById("cedulaOculta").value = cedula;
                document.getElementById("formularioHorario").style.display = "block";


            } else {
                mensaje.innerText = data.mensaje || "Cédula no encontrada.";
                mensaje.className = "mb-2 text-danger";
                document.getElementById("formularioHorario").style.display = "none";
            }
        })
        .catch(() => {
            mensaje.innerText = "Error al verificar la cédula.";
            mensaje.className = "mb-2 text-danger";
            document.getElementById("formularioHorario").style.display = "none";
        });
}
document.querySelector("form").addEventListener("submit", function (e) {
    const cedulaOculta = document.getElementById("cedulaOculta").value;
    if (!cedulaOculta) {
        e.preventDefault();
        alert("Debes verificar la cédula antes de guardar el horario.");
    }
});
document.addEventListener("DOMContentLoaded", () => {
    fetch('PHP/obtener_materias.php')
        .then(res => res.json())
        .then(materias => {
            const select = document.getElementById("selectMateria");
            select.innerHTML = '<option value="">-- Selecciona una materia --</option>';
            materias.forEach(m => {
                const opt = document.createElement("option");
                opt.value = m.id;
                opt.textContent = m.nombre;
                select.appendChild(opt);
            });

            window.listaMaterias = materias;
        })
        .catch(err => {
            console.error("Error al cargar materias:", err);
            document.getElementById("selectMateria").innerHTML =
                '<option value="">Error al cargar materias</option>';
            window.listaMaterias = [];
        });
});

document.getElementById("selectMateria").addEventListener("change", function () {
    document.getElementById("nuevoCampoMateria").style.display = this.value === "añadir" ? "block" : "none";
});

function tipoHorarioChange(select) {
    const container = document.getElementById("bloquesContainer");
    container.innerHTML = "";

    if (select.value === "parcial") {
        const parcialNode = generarParcial();
        container.appendChild(parcialNode);
    } else {
        container.innerHTML = generarCompleto();
    }
}

function generarParcial() {
    const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
    const opciones = dias.map(d => `<option value="${d}">${d}</option>`).join("");

    const container = document.createElement("div");
    container.className = "mb-3";

    container.innerHTML = `
    <label>Total de Horas a Trabajar:</label>
    <input type="number" name="total_horas" class="form-control" required>
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th class="text-center">Día</th>
          <th class="text-center">Hora</th>
          <th class="text-center">Nivel</th>
          <th class="text-center">Sección</th>
          <th class="text-center">Materia</th>
        </tr>
      </thead>
      <tbody id="tbodyParcial">${filaParcial(opciones)}</tbody>
    </table>
  `;

    const boton = document.createElement("button");
    boton.type = "button";
    boton.className = "btn btn-sm btn-outline-secondary mt-2";
    boton.textContent = "➕ Añadir fila";

    boton.addEventListener("click", () => {
        const tbody = container.querySelector("#tbodyParcial");
        tbody.insertAdjacentHTML("beforeend", filaParcial(opciones));
        cargarMateriasEnSelects();
    });

    container.appendChild(boton);

    setTimeout(cargarMateriasEnSelects, 100);

    return container;
}

function filaParcial(opciones) {
    return `<tr>
    <td class="text-center"><select name="dia[]" class="form-select" required><option value="">Seleccione</option>${opciones}</select></td>
    <td class="text-center"><input type="text" name="hora[]" class="form-control" required></td>
    <td class="text-center">
      <select name="anio[]" class="form-select" required>
        <option value="">Seleccione un nivel</option>
        <option value="1° nivel">1° nivel</option>
        <option value="2° nivel">2° nivel</option>
        <option value="3° nivel">3° nivel</option>
        <option value="Todos">Todos</option>
      </select>
    </td>
    <td class="text-center">
      <select name="seccion[]" class="form-control" required>
        <option value="U">U</option>
      </select>
    </td>
    <td class="text-center">
      <select name="materia_id[]" class="form-select materia-select" required>
        <option value="">Seleccione materia</option>
      </select>
    </td>
  </tr>`;
}

function generarCompleto() {
    const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
    const html = dias.map(dia => `
    <div class="mb-3 border p-3 rounded">
      <h6>${dia}</h6>
      <div id="bloques_${dia}">${bloqueCompletoHtml(dia)}</div>
      <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="agregarBloque('${dia}')">➕ Agregar clase</button>
    </div>
  `).join("");

    setTimeout(cargarMateriasEnSelects, 100);
    return html;
}

function bloqueCompletoHtml(dia) {
    return `
    <div class="row g-2 mb-2 align-items-center">
      <div class="col-md-3">
        <input type="text" name="bloques_${dia}[]" class="form-control" placeholder="Bloque de hora" required>
      </div>
      <div class="col-md-3">
        <select name="anio_${dia}[]" class="form-select" required>
          <option value="">Seleccione un nivel</option>
          <option value="1° nivel">1° nivel</option>
          <option value="2° nivel">2° nivel</option>
          <option value="3° nivel">3° nivel</option>
          <option value="Todos">Todos</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="seccion_${dia}[]" class="form-control" required>
          <option value="">Seleccione sección</option>
          <option value="U">U</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="materia_id_${dia}[]" class="form-select materia-select" required>
          <option value="">Seleccione materia</option>
        </select>
      </div>
    </div>
  `;
}

function agregarBloque(dia) {
    document.getElementById(`bloques_${dia}`).insertAdjacentHTML("beforeend", bloqueCompletoHtml(dia));
    cargarMateriasEnSelects();
}

function cargarMateriasEnSelects() {
    const selects = document.querySelectorAll(".materia-select");
    selects.forEach(sel => {
        const selected = sel.value;
        sel.innerHTML = '<option value="">Seleccione materia</option>';
        if (Array.isArray(window.listaMaterias)) {
            window.listaMaterias.forEach(m => {
                const opt = document.createElement("option");
                opt.value = m.id;
                opt.textContent = m.nombre;
                if (m.id == selected) opt.selected = true;
                sel.appendChild(opt);
            });
        }
    });
}