<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['correo'])) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: 'No puede acceder al sistema sin haber iniciado sesión',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'Login.php';
            });
        </script>
    </body>
    </html>";
    exit; // Detiene la ejecución del resto del código PHP.
}

// Obtén los datos del usuario desde la sesión
$usuario = $_SESSION['usuario'];
$correo = $_SESSION['correo'];
?>

<?php
// Verificar cédula vía AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajax'])) {
    $conn = new mysqli("localhost", "root", "", "base_kam");
    $response = ["encontrado" => false];

    if ($conn->connect_error) {
        $response["mensaje"] = "Error de conexión.";
        echo json_encode($response);
        exit;
    }

    $cedula = trim($_POST['cedula']);
    $stmt = $conn->prepare("SELECT nombre_personal, apellido_personal, cargo_personal FROM persona WHERE cedula_personal = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nombre, $apellido, $cargo);
        $stmt->fetch();
        $response = [
            "encontrado" => true,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "cargo" => $cargo
        ];
    } else {
        $response["mensaje"] = "La cédula no está registrada.";
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}
   
?>


<!DOCTYPE html>
<html lagn="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0"> 
<head>
    <title>KAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./Assets/CSS/Personal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>

<style>
    #animated-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
  pointer-events: none;
}

.z-1 {
  z-index: 1;
}

.container-fluid {
  position: relative;
  z-index: 2;
  background-color: #f8f9fa; 
  backdrop-filter: none; 
}

.floating-button {
  background-color: #2378b2;
  opacity: 0.6;
  border: none;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  position: fixed;
  bottom: 35px;
  right: 20px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: opacity 0.3s ease;
}

.floating-button:hover {
  opacity: 0.85;
}

.floating-button .hover-message {
  display: none;
  position: absolute;
  bottom: 75px;
  right: 0;
  background-color: #2378b2;
  color: white;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 0.8rem;
  white-space: nowrap;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.floating-button:hover .hover-message {
  display: block;
}
</style>

<body>

<canvas id="animated-bg"></canvas>
  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="./Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>

    <div class="text-end">
      <button data-bs-toggle="modal" data-bs-target="#modalHorario" class="btn btn-outline-primary rounded-circle">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$sql = "
  SELECT 
    h.id AS horario_id,
    p.nombre_personal AS docente,
    p.cedula_personal AS cedula,
    m.nombre AS materia,
    h.tipo,
    h.total_horas,
    COALESCE(bp.dia, bc.dia) AS dia,
    COALESCE(bp.hora, bc.bloque_hora) AS bloque,
    COALESCE(bp.nivel, bc.nivel) AS nivel,
    COALESCE(bp.seccion, bc.seccion) AS seccion
  FROM horarios h
  JOIN persona p ON p.cedula_personal = h.cedula
  JOIN materias m ON m.id = h.materia_id
  LEFT JOIN bloques_parcial bp ON bp.horario_id = h.id
  LEFT JOIN bloques_completo bc ON bc.horario_id = h.id
  WHERE m.nivel IN (3, 5, 6)
";


$datos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
    <title>KAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./Assets/CSS/Personal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
<canvas id="animated-bg"></canvas>

<!-- Contenedor principal -->
<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users-cog fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Horario Media General</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center w-100">
          <thead class="table-primary">
            <tr>
              <th class="text-center">Docente</th>
              <th class="text-center">Cédula</th>
              <th class="text-center">Materia</th>
              <th class="text-center">Tipo</th>
              <th class="text-center">Día</th>
              <th class="text-center">Bloque</th>
              <th class="text-center">Nivel</th>
              <th class="text-center">Sección</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($datos as $fila): ?>
              <tr>
                <td class="text-center"><?= $fila['docente'] ?></td>
                <td class="text-center"><?= $fila['cedula'] ?></td>
                <td class="text-center"><?= $fila['materia'] ?></td>
                <td class="text-center"><?= ucfirst($fila['tipo']) ?></td>
                <td class="text-center"><?= $fila['dia'] ?></td>
                <td class="text-center"><?= $fila['bloque'] ?></td>
                <td class="text-center"><?= $fila['nivel'] ?></td>
                <td class="text-center"><?= $fila['seccion'] ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-warning rounded-circle" onclick="editarHorario(<?= $fila['horario_id'] ?>)">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

  <!-- Modal de edición -->
  <form method="POST" action="PHP/editar_horario_media.php" id="formEditarHorario">
    <div class="modal fade" id="modalEditarHorario" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Horario</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="idHorarioEditar" />
            <input type="hidden" name="cedula" id="cedulaEditar" />
            <label>Materia:</label>
            <select name="materia" id="materiaEditar" class="form-control mb-2" required></select>
            <label>Tipo de Horario:</label>
            <select name="tipo_horario" id="tipoEditar" class="form-control mb-2" required>
              <option value="parcial">Parcial</option>
              <option value="tiempo_completo">Tiempo Completo</option>
            </select>
            <label>Total de Horas:</label>
            <input type="number" name="total_horas" id="horasEditar" class="form-control mb-2" required />
            <button type="submit" class="btn btn-success mt-3">Actualizar</button>
          </div>
        </div>
      </div>
    </div>
  </form>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
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
</script>



  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>

    function editarHorario(id) {
      fetch('PHP/obtener_horario.php?id=' + id)
        .then(res => res.json())
        .then(data => {
          document.getElementById("idHorarioEditar").value = data.id;
          document.getElementById("cedulaEditar").value = data.cedula;
          document.getElementById("tipoEditar").value = data.tipo;
          document.getElementById("horasEditar").value = data.total_horas;

          fetch('PHP/obtener_materias_media.php')
            .then(res => res.json())
            .then(materias => {
              const select = document.getElementById("materiaEditar");
              select.innerHTML = '<option value="">-- Selecciona una materia --</option>';
              materias.forEach(m => {
                const opt = document.createElement("option");
                opt.value = m.id;
                opt.textContent = m.nombre;
                select.appendChild(opt);
              });
              select.value = data.materia_id;
            });
          new bootstrap.Modal(document.getElementById("modalEditarHorario")).show();
        })
        .catch(err => console.error("Error al cargar horario:", err));
    }
  </script>


<form method="POST" action="PHP/guardar_horario_media.php">
  <div class="modal fade" id="modalHorario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content zoom">
        <div class="modal-header">
          <h5 class="modal-title">Asignar Horario</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <input type="text" id="cedulaInput" class="form-control mb-2" placeholder="Ingrese cédula" />
          <div class="button-group text-center mb-2">
            <button class="btn btn-primary mb-2" type="button" onclick="verificarCedula()">Verificar</button>
          </div>
          <div id="mensaje" class="mb-2"></div>

          <div id="formularioHorario" style="display:none;">

            <input type="hidden" name="cedula" id="cedulaOculta" />

            <label>Materia:</label>
              <select id="selectMateria" name="materia" class="form-control mb-2" required>
                <option value="">-- Cargando materias... --</option>
              </select>

            <label>Tipo de Horario:</label>
              <select name="tipo_horario" class="form-control" required onchange="tipoHorarioChange(this)">
                <option value="">Selecciona tipo</option>
                <option value="parcial">Parcial</option>
                <option value="tiempo_completo">Tiempo Completo</option>
              </select>

            <div id="bloquesContainer"></div>
              <button type="submit" class="btn btn-success mt-3">Guardar Horario</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch('PHP/obtener_materias_media.php')
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
</script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
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
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
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
document.querySelector("form").addEventListener("submit", function(e) {
  const cedulaOculta = document.getElementById("cedulaOculta").value;
  if (!cedulaOculta) {
    e.preventDefault();
    alert("Debes verificar la cédula antes de guardar el horario.");
  }
});

document.getElementById("selectMateria").addEventListener("change", function () {
  document.getElementById("nuevoCampoMateria").style.display = this.value === "añadir" ? "block" : "none";
});



function tipoHorarioChange(select) {
  const container = document.getElementById("bloquesContainer");
  container.innerHTML = "";

  if (select.value === "parcial") {
    const parcialNode = generarParcial(); // Devuelve un nodo real
    container.appendChild(parcialNode);
  } else {
    container.innerHTML = generarCompleto(); // Sigue como HTML string
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
      <thead><tr><th class="text-center">Día</th><th class="text-center">Hora</th><th class="text-center">Nivel</th><th class="text-center">Sección</th></tr></thead>
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
  });

  container.appendChild(boton);
  return container;
}

function filaParcial(opciones) {
  return `<tr>
    <td class="text-center"><select name="dia[]" class="form-select" required><option value="">Seleccione</option>${opciones}</select></td>
    <td class="text-center"><input type="text" name="hora[]" class="form-control" required></td>
    <td class="text-center">
      <select name="anio[]" class="form-select" required>
        <option value="">Seleccione un año</option>
          <option value="1° año">1° año</option>
          <option value="2° año">2° año</option>
          <option value="3° año">3° año</option>
          <option value="4° año">4° año</option>
          <option value="5° año">5° año</option>
          <option value="6° año">6° año</option>
      </select>
    </td>
    <td class="text-center"><input type="text" name="seccion[]" class="form-control" required></td>
  </tr>`;
}


// === Lógica Horario Tiempo Completo ===
function generarCompleto() {
  const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
  return dias.map(dia => `
    <div class="mb-3 border p-3 rounded">
      <h6>${dia}</h6>
      <div id="bloques_${dia}">${bloqueCompletoHtml(dia)}</div>
      <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="agregarBloque('${dia}')">➕ Agregar clase</button>
    </div>
  `).join("");
}

function bloqueCompletoHtml(dia) {
  return `
    <div class="row g-2 mb-2">
      <div class="col-md-4">
        <input type="text" name="bloques_${dia}[]" class="form-control" placeholder="Bloque de hora" required>
      </div>
      <div class="col-md-4">
        <select name="anio_${dia}[]" class="form-select" required>
          <option value="">Seleccione un año</option>
          <option value="1° año">1° año</option>
          <option value="2° año">2° año</option>
          <option value="3° año">3° año</option>
          <option value="4° año">4° año</option>
          <option value="5° año">5° año</option>
          <option value="6° año">6° año</option>
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="seccion_${dia}[]" class="form-control" placeholder="Sección" required>
      </div>
    </div>
  `;
}

function agregarBloque(dia) {
  document.getElementById(`bloques_${dia}`).insertAdjacentHTML("beforeend", bloqueCompletoHtml(dia));
}
</script>

</form>
 <a href="Inicio.php">
    <div class="floating-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>

<script src="Assets/JavaScript/CanvasTabla.js"></script>

</body>
</html>
