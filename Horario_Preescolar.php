<?php
session_start();

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
    exit; 
}
$usuario = $_SESSION['usuario'];
$correo = $_SESSION['correo'];

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
LEFT JOIN bloques_parcial bp ON bp.horario_id = h.id
LEFT JOIN bloques_completo bc ON bc.horario_id = h.id
LEFT JOIN materias m 
    ON m.id = COALESCE(bp.materia_id, bc.materia_id)  
WHERE m.nivel IN (1, 4, 6);

";

$datos = $pdo->query($sql)->fetchAll();


include './Includes/Head.php';


?>

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

<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users-cog fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Horario Preescolar</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center w-100">
          <thead class="table-primary">
            <tr>
              <th>Docente</th>
              <th>Cédula</th>
              <th>Materia</th>
              <th>Tipo</th>
              <th>Día</th>
              <th>Bloque</th>
              <th>Nivel</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($datos as $fila): ?>
              <tr>
                <td><?= $fila['docente'] ?></td>
                <td><?= $fila['cedula'] ?></td>
                <td><?= $fila['materia'] ?></td>
                <td class="text-center">
                    <?= $fila['tipo'] === 'tiempo_completo' ? 'Completo' : ucfirst($fila['tipo']) ?>
                  </td>
                <td><?= $fila['dia'] ?></td>
                <td><?= $fila['bloque'] ?></td>
                <td><?= $fila['nivel'] ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-warning rounded-circle" onclick="editarHorario(<?= $fila['horario_id'] ?>)">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <form method="POST" action="PHP/eliminar_horario_preescolar.php" id="formEliminarHorario<?= $fila['horario_id'] ?>" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $fila['horario_id'] ?>" />
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="confirmarEliminacion(<?= $fila['horario_id'] ?>)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

  <form method="POST" action="PHP/editar_horario.php" id="formEditarHorario">
     <div class="modal fade" id="modalEditarHorario" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-edit me-2"></i> Editar Horario
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="idHorarioEditar" />
            <input type="hidden" name="cedula" id="cedulaEditar" />
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Tipo de Horario:</label>
                <select name="tipo_horario" id="tipoEditar" class="form-select" required onchange="editarTipoHorarioChange(this)">
                  <option value="parcial">Parcial</option>
                  <option value="tiempo_completo">Tiempo Completo</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Total de Horas:</label>
                <input type="number" name="total_horas" id="horasEditar" class="form-control" required />
              </div>
            </div>
            <div id="bloquesEditarContainer"></div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Actualizar
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>


<form method="POST" action="PHP/guardar_horario.php">
    <div class="modal fade" id="modalHorario" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content zoom">
          <div class="modal-header">
            <h5 class="modal-title">Asignar Horario</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="text" id="cedulaInput" class="form-control mb-2" placeholder="Ingrese cédula" value="V-" />
            <div class="button-group text-center mb-2">
              <button class="btn btn-primary mb-2" type="button" onclick="verificarCedula()">Verificar</button>
            </div>
            <div id="mensaje" class="mb-2"></div>
            <div id="formularioHorario" style="display:none;">
              <input type="hidden" name="cedula" id="cedulaOculta" />
              <label>Materia:</label>
              <input type="hidden" name="materia" id="selectMateria">
              <label>Tipo de Horario:</label>
              <select name="tipo_horario" class="form-control" required onchange="tipoHorarioChange(this)">
                <option value="">Selecciona tipo</option>
                <option value="parcial">Parcial</option>
                <option value="tiempo_completo">Tiempo Completo</option>
              </select>
              <div id="bloquesContainer"></div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success mt-3">Guardar Horario</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

<a href="Inicio.php">
  <div class="floating-button right-button">
    <i class="fas fa-house fa-xl text-white"></i>
    <div class="hover-message">Inicio</div>
  </div>
</a>

<a href="Horarios.php">
  <div class="floating-button left-button">
    <i class="fas fa-arrow-left fa-xl text-white"></i>
    <div class="hover-message">Atrás</div>
  </div>
</a>

<script src="Assets/JavaScript/FuncionesPreescolar.js"></script>
<script src="Assets/JavaScript/CanvasTabla.js"></script>

</body>
</html>
