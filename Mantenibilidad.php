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
    exit;
}

// Obtén los datos del usuario desde la sesión
$usuario = $_SESSION['usuario'];
$correo = $_SESSION['correo'];
$nivel_usuario = $_SESSION['nivel_usuario'] ?? 'Invitado'; // Nivel de usuario desde la sesión

// Incluir los archivos necesarios
include_once 'DataBase/conexion.php'; // Archivo de conexión a la base de datos
include_once 'Includes/permisos.php'; // Archivo con las funciones de permisos

// Obtener el nombre del archivo actual
$modulo = basename(__FILE__); // Ejemplo: PERSONAL.php

// Verificar permisos del usuario para este módulo
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
                text: 'No tienes permiso para acceder al módulo mantenibilidad',
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


<html lagn="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0"> 
<head>
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


  <div class="container-fluid py-3 border-bottom bg-white shadow-sm position-relative z-1">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
    </div>
  </div>

    <canvas id="animated-bg"></canvas>

<div class="container my-5">
  <div class="row g-4 justify-content-center">

    <!-- Módulo Personal -->
    <div class="col-md-4 text-center">
      <a href="Configuracion.php" class="text-decoration-none">
        <div class="card module-card d-flex flex-column justify-content-center align-items-center text-primary">
          <div class="icon-container mb-3">
            <i class="fas fa-users"></i>
          </div>
            <h4 class="fw-bold">Configuración de Usuario</h4>
        </div>
      </a>
    </div>

    <!-- Módulo Asistencia -->
    <div class="col-md-4 text-center">
      <a href="bitacora.php" class="text-decoration-none">
        <div class="card module-card d-flex flex-column justify-content-center align-items-center text-primary">
          <div class="icon-container mb-3">
            <i class="fas fa-database fa-4x text-primary"></i>
          </div>
          <h4 class="fw-bold">Bitácora</h4>
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
  <script src="Assets/JavaScript/CanvasPrincipal.js"></script>

  <script>
        
        document.querySelectorAll('.logo-container').forEach(container => {
    const logo = container.querySelector('.logo');
    const hoverMessage = container.querySelector('.hover-message');

    logo.addEventListener('mouseenter', () => {
        hoverMessage.style.display = 'block';
    });

    logo.addEventListener('mouseleave', () => {
        hoverMessage.style.display = 'none';
    });
});

    </script>


  <script>
    // Modal para el perfil de usuario
    const userProfileButton = document.getElementById('userProfileButton');
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfile = document.getElementById('closeProfile');
    
    // Mostrar modal solo cuando se hace clic en el botón (logo)
    userProfileButton.onclick = function() {
        userProfileModal.style.display = "block";
    };
    
    // Cerrar modal cuando se hace clic en el botón de cerrar
    closeProfile.onclick = function() {
        userProfileModal.style.display = "none";
    };
    
    // Cerrar modal si se hace clic fuera de él
    window.onclick = function(event) {
        if (event.target === userProfileModal) {
            userProfileModal.style.display = "none";
        }
    };
</script>
  </body>
</html>