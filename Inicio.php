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

include_once './DataBase/conexion.php';

// Contadores
$total_activos = $db->query("SELECT COUNT(*) FROM persona WHERE activo = 1")->fetchColumn();
$total_inactivos = $db->query("SELECT COUNT(*) FROM persona WHERE activo = 0")->fetchColumn();
$total_materias = $db->query("SELECT COUNT(*) FROM materias")->fetchColumn();

// Materias por nivel
$materias_preescolar = $db->query("SELECT COUNT(*) FROM materias WHERE nivel = '1'")->fetchColumn();
$materias_primaria = $db->query("SELECT COUNT(*) FROM materias WHERE nivel = '2'")->fetchColumn();
$materias_media = $db->query("SELECT COUNT(*) FROM materias WHERE nivel = '3'")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel KAM</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

  body {
      background-color: #f4f6f9;
    }
  .card {
      min-height: 140px;
    }
  canvas {
      max-height: 170px;
    }
  .menu-inferior {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #2378b6;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      padding: 10px 0;
      z-index: 1000;
    }
  .menu-inferior li {
      list-style: none;
    }
  .menu-inferior a {
      text-decoration: none;
      color: #333;
      text-align: center;
      font-size: 14px;
    }
  .logo-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  .icon {
      font-size: 20px;
    }
  .hover-message {
      font-size: 12px;
    }
  .graficas-wrapper {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  .graficas-wrapper .card {
    width: 320px;
  }
  .card-icon {
    font-size: 28px;
    margin-top: 20px;
  }
  .bg-kam {
    background-color: #2378b6 !important;
  }
#animated-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: auto;
  min-height: 100%;
  z-index: 0;
  pointer-events: none;
}


.z-1 {
  z-index: 1;
}

  </style>
</head>
<body>



  <div class="container-fluid py-3 border-bottom bg-white shadow-sm position-relative z-1">
    <img src="Assets/Images/KAM.png" width="220" height="40" alt="#">
  </div>

<canvas id="animated-bg"></canvas>
<div class="container py-4">
  <div class="row g-4 justify-content-center">

<!-- Contadores -->
<div class="col-md-3">
  <div class="card text-white bg-kam shadow-sm rounded-4 text-center">
    <div class="card-body">
      <i class="fas fa-user-check card-icon"></i>
      <h6 class="fw-bold">Personal Activo</h6>
      <h2 class="display-6"><?= $total_activos ?></h2>
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="card text-white bg-kam shadow-sm rounded-4 text-center">
    <div class="card-body">
      <i class="fas fa-user-slash card-icon"></i>
      <h6 class="fw-bold">Personal Inactivo</h6>
      <h2 class="display-6"><?= $total_inactivos ?></h2>
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="card text-white bg-kam shadow-sm rounded-4 text-center">
    <div class="card-body">
      <i class="fas fa-book card-icon"></i>
      <h6 class="fw-bold">Materias</h6>
      <h2 class="display-6"><?= $total_materias ?></h2>
    </div>
  </div>
</div>

<!-- Gráficas -->
<div class="graficas-wrapper mt-4">

  <div class="card shadow-sm rounded-4">
    <div class="card-body">
      <h6 class="fw-bold text-center">Materias por Nivel</h6>
      <canvas id="materiasPie"></canvas>
    </div>
  </div>

  <div class="card shadow-sm rounded-4">
    <div class="card-body">
      <h6 class="fw-bold text-center">Estado del Personal</h6>
      <canvas id="personalPie"></canvas>
    </div>
  </div>


</div>

<!-- Menú Inferior -->
<nav class="menu-inferior text-white">
  <li><a href="Inicio.php"><div class="logo-container text-white"><i class="fas fa-house icon"></i><div class="hover-message">Inicio</div></div></a></li>
  <li><a href="personal.php"><div class="logo-container text-white"><i class="fas fa-users icon"></i><div class="hover-message">Personal</div></div></a></li>
  <li><a href="asistencia.php"><div class="logo-container text-white"><i class="fas fa-user-check icon"></i><div class="hover-message">Asistencia</div></div></a></li>
  <li><a href="horarios.php"><div class="logo-container text-white"><i class="fas fa-calendar-alt icon"></i><div class="hover-message">Horarios</div></div></a></li>
  <li><a href="Reportes.php"><div class="logo-container text-white"><i class="fas fa-file-lines icon"></i><div class="hover-message">Reportes</div></div></a></li>
  <li><a href="Permisos.php"><div class="logo-container text-white"><i class="fas fa-key icon"></i><div class="hover-message">Permisos</div></div></a></li>
  <li><a href="mantenibilidad.php"><div class="logo-container text-white"><i class="fas fa-gear icon"></i><div class="hover-message">Mantenibilidad</div></div></a></li>
  <li><a href="Includes/Logout.php"><div class="logo-container text-white"><i class="fas fa-right-from-bracket icon"></i><div class="hover-message">Cerrar sesión</div></div></a></li>
</nav>

<script src="Assets/JavaScript/CanvasPrincipal.js"></script>

<script>

new Chart(document.getElementById('materiasPie'), {
  type: 'pie',
  data: {
    labels: ['Preescolar', 'Primaria', 'Media General'],
    datasets: [{
      data: [<?php echo $materias_preescolar; ?>, <?php echo $materias_primaria; ?>, <?php echo $materias_media; ?>],
      backgroundColor: ['#00b894', '#00a8ff', '#ff6b81'],
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});

new Chart(document.getElementById('personalPie'), {
  type: 'doughnut',
  data: {
    labels: ['Activo', 'Inactivo'],
    datasets: [{
      data: [<?php echo $total_activos; ?>, <?php echo $total_inactivos; ?>],
      backgroundColor: ['#00b894', '#d63031'],
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});
</script>

</body>
</html>
