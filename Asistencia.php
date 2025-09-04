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
$nivel_usuario = $_SESSION['nivel_usuario'] ?? 'Invitado'; 

// Incluir los archivos necesarios
include_once './DataBase/conexion.php';
include_once './Includes/permisos.php'; 

$modulo = basename(__FILE__); 

if (!verificar_acceso($nivel_usuario, $modulo, $db)) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: 'No tienes permiso para acceder al módulo asistencia',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'Inicio.php'; // Redirige al usuario a la página principal
            });
        </script>
    </body>
    </html>";
    exit;
}

?>

<?php

$conn = new mysqli("localhost", "root", "", "base_kam");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$sql = "SELECT 
            p.nombre_personal, 
            p.apellido_personal, 
            p.cedula_personal, 
            IF(a.fecha_asistencia = CURDATE(), a.fecha_asistencia, 'No registrado') AS fecha_asistencia,
            IF(a.fecha_asistencia = CURDATE(), DATE_FORMAT(a.hora_entrada, '%h:%i %p'), 'No registrado') AS hora_entrada_12h,
            IF(a.fecha_asistencia = CURDATE() AND a.hora_salida IS NOT NULL, DATE_FORMAT(a.hora_salida, '%h:%i %p'), 'No registrado') AS hora_salida_12h
        FROM persona p
        LEFT JOIN asistencia a 
        ON p.cedula_personal = a.cedula_personal 
        AND a.fecha_asistencia = CURDATE()";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<head>
    <title>KAM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="Assets/CSS/Reportes.css">
    <link rel="stylesheet" href="Assets/CSS/Asistencia.css">
    <script src="Assets/JavaScript/Asistencia.js"></script>
    <script src="Assets/JavaScript/ConvertirHora.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

  <canvas id="animated-bg"></canvas>

  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
  </div>
</div>

<div class="container mx-auto my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-user-check icon fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Asistencia</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="d-flex justify-content-center">
        <div class="table-responsive w-100">
          <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center mb-0 rounded-3 overflow-hidden">
            <thead class="table-primary">
              <tr class="mt-4">
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Cédula</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Hora de Entrada</th>
                <th class="text-center">Hora de Salida</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr data-cedula="<?php echo htmlspecialchars($row['cedula_personal']); ?>">
                        <td class="nombre text-center"><?php echo htmlspecialchars($row['nombre_personal']); ?></td>
                        <td class="apellido text-center"><?php echo htmlspecialchars($row['apellido_personal']); ?></td>
                        <td class="cedula text-center"><?php echo htmlspecialchars($row['cedula_personal']); ?></td>
                        <td class="<?php echo $row['fecha_asistencia'] === 'No registrado' 
                        ? 'bg-danger-subtle text-danger fw-bold text-center' 
                        : 'bg-success-subtle text-success fw-semibold text-center'; ?>">
                        <?php echo $row['fecha_asistencia'] !== "No registrado" 
                            ? htmlspecialchars($row['fecha_asistencia']) 
                            : "No registrado"; ?>
                        </td>

                        <td class="entrada <?php echo $row['hora_entrada_12h'] === 'No registrado' 
                        ? 'bg-danger-subtle text-danger fw-bold text-center' 
                        : 'bg-success-subtle text-success fw-semibold text-center'; ?>">
                        <?php echo $row['hora_entrada_12h'] !== "No registrado" 
                            ? htmlspecialchars($row['hora_entrada_12h']) 
                            : "No registrado"; ?>
                        </td>

                        <td class="salida <?php echo $row['hora_salida_12h'] === 'No registrado' 
                        ? 'bg-danger-subtle text-danger fw-bold text-center' 
                        : 'bg-success-subtle text-success fw-semibold text-center'; ?>">
                        <?php echo $row['hora_salida_12h'] !== "No registrado" 
                            ? htmlspecialchars($row['hora_salida_12h']) 
                            : "No registrado"; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn btn-outline-primary rounded-circle">
                                <i class="fas fa-edit" onclick="otrosRegistros('<?php echo htmlspecialchars($row['cedula_personal']); ?>')"></i>
                                <span class="texto-hover">Otros registros</span>
                            </div>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='7'>No se encontraron resultados.</td></tr>";
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


    <div id="otrosRegistrosModal" class="modal">
        <div class="modal-content">
            <span onclick="cerrarModal('otrosRegistrosModal')" class="close">&times;</span>
            <h2>Registros Generales</h2>
            <input type="text" id="buscarFecha" class="input-search" placeholder="Buscar por fecha" oninput="filtrarRegistros()">
            <div id="otrosRegistrosModalContent"></div>
        </div>
    </div>

    <a href="Inicio.php">
        <div class="floating-button">
        <i class="fas fa-house fa-xl text-white"></i>
        <div class="hover-message">Inicio</div>
        </div>
    </a>

  <script>
    $(document).ready(function () {
    const tabla = $('#miTabla').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        info: false
    });

    // Aplica margen al buscador
    $('#miTabla_filter').css('margin-bottom', '1rem');
});
  </script>

    <script src="Assets/JavaScript/CanvasTabla.js"></script>

</body>
</html>
