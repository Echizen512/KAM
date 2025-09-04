<?php
$conexion = new mysqli("localhost", "root", "", "base_kam");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (isset($_POST['add'])) {
    $nombre = $_POST['nombre'];
    $nivel = $_POST['nivel'];
    $conexion->query("INSERT INTO materias (nombre, nivel) VALUES ('$nombre', '$nivel')");
    header("Location: ?status=success");
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $nivel = $_POST['nivel'];
    $conexion->query("UPDATE materias SET nombre='$nombre', nivel='$nivel' WHERE id=$id");
    header("Location: ?status=success");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conexion->query("DELETE FROM materias WHERE id=$id");
    header("Location: ?status=success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>KAM</title>
    <link rel="stylesheet" href="./Assets/CSS/Personal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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


<body class="bg-light">

<canvas id="animated-bg"></canvas>

  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="./Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>

    <div class="text-end">
        <button class="btn btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#addModal">     
            <i class="fas fa-plus"></i>
        </button>
    </div>
  </div>
</div>




<div class="container my-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
            <i class="fas fa-book fa-lg me-2"></i>
            <h5 class="mb-0">Gestión de Materias</h5>
            </div>
            <div class="card-body rounded-bottom-4">
            <div class="table-responsive">
                <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center w-100">
                <thead class="table-primary">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Nivel</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $materias = $conexion->query("SELECT id, nombre, nivel FROM materias");
                    $modals = ""; // Acumulador de modales
                    while ($row = $materias->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['nombre'] ?></td>
                            <td class="text-center"><?= $row['nivel'] ?></td>
                            <td class="text-center">
                               <button class="btn btn-sm btn-info rounded-circle text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('¿Eliminar esta materia?')">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>

        <?php
        // Acumula el modal de edición
        $modals .= '
        <div class="modal fade" id="editModal'.$row['id'].'" tabindex="-1" aria-labelledby="editLabel'.$row['id'].'" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLabel'.$row['id'].'">Editar Materia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="'.$row['id'].'">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="'.$row['nombre'].'" required>
                        </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel</label>
                        <select name="nivel" class="form-select" required>
                            <option value="1" '.($row['nivel'] == '1' ? 'selected' : '').'>Nivel 1</option>
                            <option value="2" '.($row['nivel'] == '2' ? 'selected' : '').'>Nivel 2</option>
                            <option value="3" '.($row['nivel'] == '3' ? 'selected' : '').'>Nivel 3</option>
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button name="edit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>';
        endwhile;
        ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Añadir -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLabel">Añadir Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nivel</label>
                    <select name="nivel" id="nivel" class="form-select" required>
                        <option value="1">Nivel 1 (Preescolar)</option>
                        <option value="2">Nivel 2 (Primaria)</option>
                        <option value="3">Nivel 3 (Bachillerato)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button name="add" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>

 <a href="Inicio.php">
    <div class="floating-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>


<!-- Renderiza todos los modales de edición fuera del <table> -->
<?= $modals ?>
<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

<?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Operación Exitosa',
        text: 'La acción se realizó correctamente.',
        confirmButtonColor: '#3085d6'
    });
</script>
<?php endif; ?>
</body>
</html>
