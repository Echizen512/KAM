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
?>

<!DOCTYPE html>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <head>
        <title>KAM</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="Assets/CSS/Reportes.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
<body>

<style>
  .floating-button {
    background-color: #2378b2;
    opacity: 0.6;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    position: fixed;
    bottom: 35px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: opacity 0.3s ease;
    z-index: 10;
  }

  .right-button {
    right: 20px;
  }

  .left-button {
    left: 20px;
  }

  .floating-button:hover {
    opacity: 0.85;
  }

  .floating-button .hover-message {
    display: none;
    position: absolute;
    bottom: 75px;
    background-color: #2378b2;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  }

  .right-button .hover-message {
    right: 0;
  }

  .left-button .hover-message {
    left: 0;
  }

  .floating-button:hover .hover-message {
    display: block;
  }

  div.dataTables_filter {
    margin-bottom: 1.5rem;
  }

  div.dataTables_filter input {
    margin-left: 0.5rem;
    padding: 0.4rem 0.6rem;
    border-radius: 0.375rem;
  }

</style>

  <canvas id="animated-bg"></canvas>

  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar'])) {
      $usuario = $_POST['usuario'] ?? '';
      $correo = $_POST['correo'] ?? '';
      $nivel = $_POST['nivel_usuario'] ?? '';
      $pass = $_POST['contrasena'] ?? '';
      $confirm = $_POST['confirmar_contrasena'] ?? '';

      echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

      if (!$usuario || !$correo || !$nivel || !$pass || !$confirm) {
        echo "<script>Swal.fire('Error', 'Todos los campos son obligatorios.', 'error').then(() => history.back());</script>";
        exit;
      }

      if ($pass !== $confirm) {
        echo "<script>Swal.fire('Error', 'Las contraseñas no coinciden.', 'error').then(() => history.back());</script>";
        exit;
      }

      $conn = new mysqli("localhost", "root", "", "base_kam");
      $stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE usuario = ?");
      $stmt->bind_param("s", $usuario);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        echo "<script>Swal.fire('Error', 'El usuario ya existe.', 'error').then(() => history.back());</script>";
        exit;
      }

      $stmt->close();
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO usuarios (usuario, correo, nivel_usuario, contrasena) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $usuario, $correo, $nivel, $hash);

      if ($stmt->execute()) {
        echo "<script>Swal.fire('¡Hecho!', 'Usuario registrado correctamente.', 'success').then(() => window.location.href='".$_SERVER['PHP_SELF']."');</script>";
      } else {
        echo "<script>Swal.fire('Error', 'No se pudo registrar.', 'error').then(() => history.back());</script>";
      }

      $stmt->close();
      $conn->close();
      exit;
    }
?>


<!-- Modal de inserción -->
<div id="modalInsertar" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="insertar" value="1">
        <div class="mb-3">
          <label class="form-label">Usuario</label>
          <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Rol</label>
          <select name="nivel_usuario" class="form-select" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="administrador">Administrador</option>
            <option value="Secretaria">Secretaria</option>
            <option value="RRHH">RRHH</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="contrasena" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirmar Contraseña</label>
          <input type="password" name="confirmar_contrasena" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Registrar</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
      <div class="text-end">
        <button type="button" class="btn btn-outline-primary rounded-circle" title="Agregar Usuario" id="btnAbrirModal">
          <i class="fas fa-plus"></i>
        </button>
      </div>
  </div>
</div>

<?php
  if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn = new mysqli("localhost", "root", "", "base_kam");
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

    if ($stmt->execute()) {
      echo "<script>Swal.fire('¡Eliminado!', 'Usuario eliminado correctamente.', 'success').then(() => window.location.href='".$_SERVER['PHP_SELF']."');</script>";
    } else {
      echo "<script>Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error').then(() => window.location.href='".$_SERVER['PHP_SELF']."');</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
  }
?>


<?php
// Procesamiento del formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
  $id = intval($_POST['id']);
  $usuario = $_POST['usuario'] ?? '';
  $correo = $_POST['correo'] ?? '';
  $nivel_usuario = $_POST['nivel_usuario'] ?? '';

  echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

  if (!$usuario || !$correo || !$nivel_usuario) {
    echo "<script>Swal.fire('Error', 'Todos los campos son obligatorios.', 'error').then(() => history.back());</script>";
    exit;
  }

  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "<script>Swal.fire('Error', 'El correo electrónico no es válido.', 'error').then(() => history.back());</script>";
    exit;
  }

  $conn = new mysqli("localhost", "root", "", "base_kam");
  $stmt = $conn->prepare("UPDATE usuarios SET usuario = ?, correo = ?, nivel_usuario = ? WHERE id = ?");
  $stmt->bind_param("sssi", $usuario, $correo, $nivel_usuario, $id);

  if ($stmt->execute()) {
    echo "<script>Swal.fire('¡Hecho!', 'Usuario actualizado correctamente.', 'success').then(() => window.location.href='".$_SERVER['PHP_SELF']."');</script>";
  } else {
    echo "<script>Swal.fire('Error', 'No se pudo actualizar el usuario.', 'error').then(() => history.back());</script>";
  }

  $stmt->close();
  $conn->close();
  exit;
}
?>

<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users-cog fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Usuarios</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="dataTable" class="table table-striped table-bordered align-middle text-center mb-0 w-100">
          <thead class="table-primary">
            <tr>
              <th style="width: 10%" class="text-center">N°</th>
              <th style="width: 20%" class="text-center">Usuario</th>
              <th style="width: 30%" class="text-center">Correo</th>
              <th style="width: 20%" class="text-center">Rol</th>
              <th style="width: 20%" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              $conn = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8", "root", "");
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $sql = "SELECT id, usuario, correo, nivel_usuario FROM usuarios";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($usuarios as $user) {
                echo "<tr>
                        <td>{$user['id']}</td>
                        <td>{$user['usuario']}</td>
                        <td>{$user['correo']}</td>
                        <td>{$user['nivel_usuario']}</td>
                        <td>
                          <a href='?editar_id={$user['id']}' class='btn btn-outline-primary rounded-circle' title='Editar'>
                            <i class='fas fa-edit'></i>
                          </a>
                          <a href='?eliminar={$user['id']}' onclick='return confirmarEliminar(event)' class='btn btn-outline-danger rounded-circle' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                          </a>
                        </td>
                      </tr>";
              }
            } catch (PDOException $e) {
              echo "<tr><td colspan='5'>Error al cargar usuarios: {$e->getMessage()}</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
// Modal de edición fuera del <tbody>
if (isset($_GET['editar_id'])) {
  $id = intval($_GET['editar_id']);
  $conn = new mysqli("localhost", "root", "", "base_kam");
  $stmt = $conn->prepare("SELECT usuario, correo, nivel_usuario FROM usuarios WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
  $conn->close();

  if ($user) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <div class='modal fade show' style='display:block; background-color: rgba(0,0,0,0.5);' tabindex='-1'>
      <div class='modal-dialog'>
        <form method='POST' class='modal-content'>
          <div class='modal-header bg-primary text-white'>
            <h5 class='modal-title'>Editar Usuario</h5>
            <a href='".$_SERVER['PHP_SELF']."' class='btn-close'></a>
          </div>
          <div class='modal-body'>
            <input type='hidden' name='editar' value='1'>
            <input type='hidden' name='id' value='{$id}'>
            <div class='mb-3'>
              <label class='form-label'>Usuario</label>
              <input type='text' name='usuario' class='form-control' value='".htmlspecialchars($user['usuario'], ENT_QUOTES)."' required>
            </div>
            <div class='mb-3'>
              <label class='form-label'>Correo</label>
              <input type='email' name='correo' class='form-control' value='".htmlspecialchars($user['correo'], ENT_QUOTES)."' required>
            </div>
            <div class='mb-3'>
              <label class='form-label'>Rol</label>
              <select name='nivel_usuario' class='form-select' required>
                <option value='administrador' ".($user['nivel_usuario'] === 'administrador' ? 'selected' : '').">Administrador</option>
                <option value='Secretaria' ".($user['nivel_usuario'] === 'Secretaria' ? 'selected' : '').">Secretaria</option>
                <option value='RRHH' ".($user['nivel_usuario'] === 'RRHH' ? 'selected' : '').">RRHH</option>
              </select>
            </div>
          </div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary'>Guardar Cambios</button>
            <a href='".$_SERVER['PHP_SELF']."' class='btn btn-secondary'>Cancelar</a>
          </div>
        </form>
      </div>
    </div>
    ";
  }
}
?>

  <!-- Botón de Inicio (derecha) -->
  <a href="Inicio.php">
    <div class="floating-button right-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>

  <!-- Botón de Atrás (izquierda) -->
  <a href="Mantenibilidad.php">
    <div class="floating-button left-button">
      <i class="fas fa-arrow-left fa-xl text-white"></i>
      <div class="hover-message">Atrás</div>
    </div>
  </a>

<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
  document.getElementById('btnAbrirModal').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('modalInsertar'));
    modal.show();
  });
</script>

<script>
  $(document).ready(function () {
    $('#dataTable').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      pageLength: 10,
      lengthChange: false,
      ordering: true,
      info: true,
      responsive: true
    });
  });
</script><script>
function confirmarEliminar(e) {
  e.preventDefault();
  const url = e.currentTarget.getAttribute('href');
  Swal.fire({
    title: '¿Estás seguro?',
    text: 'No podrás revertir esta acción.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
  return false;
}
</script>

<script>
function confirmarEliminar(e) {
  e.preventDefault();
  const url = e.currentTarget.getAttribute('href');
  Swal.fire({
    title: '¿Estás seguro?',
    text: 'No podrás revertir esta acción.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
  return false;
}
</script>


</body>
</html>