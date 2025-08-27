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

include_once 'DataBase/conexion.php'; 
include_once 'Includes/permisos.php'; 


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
                text: 'No tienes permiso para acceder al módulo reportes',
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
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>KAM</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="Assets/CSS/Reportes.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .module-card {
      transition: all 0.3s ease;
      height: 240px;
      cursor: pointer;
    }

    .module-card:hover {
      background-color: #0dcaf0 !important;
      color: #fff !important;
      transform: translateY(-5px);
      box-shadow: 0 0.75rem 1.5rem rgba(13, 202, 240, 0.3);
    }

    .module-card:hover i {
      color: #fff !important;
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
      z-index: 999;
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

    @media (max-width: 768px) {
      .module-card {
        height: 200px;
      }
    }
  </style>
</head>

<body>
  <canvas id="animated-bg"></canvas>

  <div class="container-fluid py-3 border-bottom bg-white shadow-sm position-relative z-1">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
    </div>
  </div>

  <div class="container py-5">
    <div class="row g-4 justify-content-center">

      <div class="col-lg-4 col-md-6">
        <a href="ReportesPersonal.php" class="text-decoration-none">
          <div class="card module-card border border-primary rounded-4 shadow-sm bg-light text-primary d-flex justify-content-center align-items-center">
            <div class="text-center">
              <i class="fas fa-users fs-1 mb-3"></i>
              <h5 class="fw-semibold mb-0">Personal</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-4 col-md-6">
        <a href="tabla_reportes_asistencias.php" class="text-decoration-none">
          <div class="card module-card border border-primary rounded-4 shadow-sm bg-light text-primary d-flex justify-content-center align-items-center">
            <div class="text-center">
              <i class="fas fa-fingerprint fs-1 mb-3"></i>
              <h5 class="fw-semibold mb-0">Asistencia</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- Card: Horario -->
      <div class="col-lg-4 col-md-6">
        <a href="tabla_horarios.php" class="text-decoration-none">
          <div class="card module-card border border-primary rounded-4 shadow-sm bg-light text-primary d-flex justify-content-center align-items-center">
            <div class="text-center">
              <i class="fas fa-calendar-alt fs-1 mb-3"></i>
              <h5 class="fw-semibold mb-0">Horario</h5>
            </div>
          </div>
        </a>
      </div>

    </div>
  </div>

  <!-- Botón flotante -->
  <a href="Inicio.php">
    <div class="floating-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>

  <!-- Scripts -->
  <script src="Assets/JavaScript/Reportes.js"></script>
  <script src="Assets/JavaScript/CanvasTabla.js"></script>
</body>
</html>
