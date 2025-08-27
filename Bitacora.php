<?php
session_start();
require_once 'DataBase/conexion.php';

date_default_timezone_set('America/Caracas');

// Verificar sesión activa
if (empty($_SESSION['usuario']) || empty($_SESSION['correo'])) {
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
                text: 'Debe iniciar sesión para acceder al sistema',
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
$fecha_actual = date("Y-m-d");

try {
    $query = "SELECT id, usuario_id, nombre_usuario, correo, accion, 
              DATE_FORMAT(fecha_hora, '%d-%m-%Y %h:%i %p') AS fecha_hora 
              FROM bitacora 
              WHERE DATE(fecha_hora) = :fecha 
              ORDER BY fecha_hora DESC";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':fecha', $fecha_actual);
    $stmt->execute();
    $result = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error en la consulta',
                text: 'No se pudo recuperar la bitácora.',
                confirmButtonText: 'Aceptar'
            });
          </script>";
    exit;
}
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
        .capitalize {
        text-transform: capitalize;
    }

    .toast-container {
        position: fixed;
        top: 7px;
        right: 20px;
        z-index: 10000;
    }

    .toast {
        background-color: #343a40;
        color: #fff;
        border-radius: 5px;
        padding: 10px 20px;
        margin-bottom: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
         border: 0px solid #fff;
        box-shadow: rgba(2,2,5,5) 0px 1px 4px 0px;
        animation: zoomIn 0.5s;
    }
   @keyframes zoomIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .toast h5 {
        text-align: center;
      margin: 5em 0em 5em 0em;
        color: #2378b6;
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

  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
  </div>
</div>

<!-- Card contenedora -->
<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-clipboard-list fa-lg me-2"></i>
      <h5 class="mb-0">Bitácora del Sistema</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="tablaBitacora" class="table table-striped table-bordered align-middle text-center mb-0">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Usuario</th>
              <th>Correo</th>
              <th>Acción</th>
              <th>Fecha y Hora</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($result as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['accion']) ?></td>
                <td><?= htmlspecialchars($row['fecha_hora']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

  <a href="Inicio.php">
    <div class="floating-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>
  
<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- DataTables init -->
<script>
  $(document).ready(function () {
    $('#tablaBitacora').DataTable({
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
</script>

</body></html>
