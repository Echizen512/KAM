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
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KAM</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="Assets/CSS/Principal.css">
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

</style>

<body>
<canvas id="animated-bg"></canvas>
  <div class="left">
    <img src="Assets/Images/KAM.png" width="220" height="40" alt="#">
  </div>


  <br>
  <div id="userProfileModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeProfile">&times;</span>
      <div align="left">
        <h2>Perfil de Usuario</h2>
        <p><strong>Nombre de Usuario:</strong>
          <?php echo htmlspecialchars($usuario); ?>
        </p>
        <p><strong>Correo Electrónico:</strong>
          <?php echo htmlspecialchars($correo); ?>
        </p>

      </div>
    </div>
  </div>
  <div id="calendarModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeCalendar">&times;</span>
      <div align="center">
        <h2>Calendario</h2>
      </div>
      <div class="calendar">
        <h6>Fecha de inicio</h6>
        <input type="date" id="startDate" placeholder="Fecha de inicio">
        <h6>Fecha de fin</h6>
        <input type="date" id="endDate" placeholder="Fecha de fin">
        <input type="text" id="commitment" placeholder="Compromiso">
        <div align="center">
          <button id="addCommitment">Agendar</button>
        </div>
      </div>
    </div>
  </div>
  <div id="notifications" class="modal">
    <div class="modal-content">
      <span class="close" id="closeNotifications">&times;</span>
      <h3>Notificaciones</h3>
      <ul id="notificationList"></ul>
    </div>
  </div>

<style>
.carousel-container {
  position: relative;
  max-width: 100%;
  overflow: hidden;
  border-radius: 16px;
  backdrop-filter: blur(12px);
  background: rgba(255, 255, 255, 0.6);
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.carousel-wrapper {
  display: flex;
  align-items: center;
  overflow: hidden;
}

.carousel {
  display: flex;
  transition: transform 0.8s ease-in-out;
  width: 100%;
}

.carousel img {
  min-width: 100%;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  transform: scale(0.92);
  transition: transform 0.5s ease;
}

.carousel img:hover {
  transform: scale(1);
}
</style>

<div class="carousel-container">
  <div class="carousel-wrapper">
    <div class="carousel" id="auto-carousel">
      <img src="Assets/Images/bienvenida.png" alt="Bienvenida">
      <img src="Assets/Images/carrusel_personal.png" alt="Personal">
      <img src="Assets/Images/carrusel_asistencia.png" alt="Asistencia">
      <img src="Assets/Images/carrusel_horario.png" alt="Horario">
      <img src="Assets/Images/carrusel_reportes.png" alt="Reportes">
      <img src="Assets/Images/carrusel_permisos.png" alt="Permisos">
      <img src="Assets/Images/carrusel_ayuda.png" alt="Ayuda">
      <img src="Assets/Images/carrusel_mante.png" alt="Mantenimiento">
    </div>
  </div>
</div>

<script>
let index = 0;
const carousel = document.getElementById("auto-carousel");
const totalSlides = carousel.children.length;

function autoSlide() {
  index = (index + 1) % totalSlides;
  carousel.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(autoSlide, 4000); // cambia cada 4 segundos

window.addEventListener("resize", () => {
  carousel.style.transform = `translateX(-${index * 100}%)`;
});
</script>

    <!-- Gráficas -->
    <div id="graficas" class="hidden">
      <div class="modulo">
        <div class='porcentajes' style="--porcentaje: 75; --color: #2378b6">
          <svg width="150" height="150">
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
          </svg>
          <span>75%</span>
        </div>
        <h5>Personal Activo</h5>
      </div>

      <div class="modulo">
        <div class='porcentajes' style="--porcentaje: 25; --color: #2378b6">
          <svg width="150" height="150">
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
          </svg>
          <span>25%</span>
        </div>
        <h5>Personal Inactivo</h5>
      </div>

      <div class="modulo_1">
        <div class='porcentajes' style="--porcentaje: 50; --color: #2378b6">
          <svg width="150" height="150">
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
            <circle r="65" cx="50%" cy="50%" pathlength="100" />
          </svg>
          <span>50%</span>
        </div>
        <h5> Horarios Generados</h5>
      </div>
      <div class="modulo_grafica">
        <div class="titulo">Asistencias e Inasistencias</div>
        <div class="grafica">
          <div class="linea" style="bottom: 20%;"></div>
          <div class="linea" style="bottom: 40%;"></div>
          <div class="linea" style="bottom: 60%;"></div>
          <div class="linea" style="bottom: 80%;"></div>
          <div class="eje-y">
            <div>8</div>
            <div>6</div>
            <div>4</div>
            <div>2</div>
            <div>0</div>
          </div>
          <div class="barra" id="asistencias" style="left: 80px; height: 0;">
            <span>5</span>
          </div>
          <div class="barra" id="inasistencias" style="left: 250px; height: 0; background-color: #779ef4;">
            <span>3</span>
          </div>
          <div class="eje-x">
            <div>Asistencias</div>
            <div>Inasistencias</div>
          </div>
        </div>
      </div>
    </div>

    <nav class="menu-inferior">
      <li>
        <a href="Inicio.php" class="tooltip" data-tooltip="Inicio">
          <div class="logo-container">
            <i class="fas fa-house icon"></i>
            <div class="hover-message">Inicio</div>
          </div>
        </a>
      </li>
      <li>
        <a href="personal.php" class="tooltip" data-tooltip="Personal">
          <div class="logo-container">
            <i class="fas fa-users icon"></i>
            <div class="hover-message">Personal</div>
          </div>
        </a>
      </li>
      <li>
        <a href="asistencia.php" class="tooltip" data-tooltip="Asistencia">
          <div class="logo-container">
            <i class="fas fa-fingerprint icon"></i>
            <div class="hover-message">Asistencia</div>
          </div>
        </a>
      </li>
      <li>
        <a href="horarios.php" class="tooltip" data-tooltip="Horarios">
          <div class="logo-container">
            <i class="fas fa-calendar-alt icon"></i>
            <div class="hover-message">Horarios</div>
          </div>
        </a>
      </li>
      <li>
        <a href="Reportes.php" class="tooltip" data-tooltip="Reportes">
          <div class="logo-container">
            <i class="fas fa-file-lines icon"></i>
            <div class="hover-message">Reportes</div>
          </div>
        </a>
      </li>
      <li>
        <a href="Permisos.php" class="tooltip" data-tooltip="Permisos">
          <div class="logo-container">
            <i class="fas fa-key icon"></i>
            <div class="hover-message">Permisos</div>
          </div>
        </a>
      </li>
      <li>
        <a href="Manual.php" class="tooltip" data-tooltip="Ayuda">
          <div class="logo-container">
            <i class="fas fa-circle-question icon"></i>
            <div class="hover-message">Ayuda</div>
          </div>
        </a>
      </li>
      <li>
        <a href="mantenibilidad.php" class="tooltip" data-tooltip="Mantenibilidad">
          <div class="logo-container">
            <i class="fas fa-gear icon"></i>
            <div class="hover-message">Mantenibilidad</div>
          </div>
        </a>
      </li>
      <li>
        <a href="Includes/Logout.php" class="tooltip" data-tooltip="Cerrar sesión">
          <div class="logo-container">
            <i class="fas fa-right-from-bracket icon"></i>
            <div class="hover-message">Cerrar sesión</div>
          </div>
        </a>
      </li>
    </nav>

    <script src="Assets/JavaScript/CanvasPrincipal.js"></script>

  <script src="Assets/JavaScript/Principal.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  </body>

</html>