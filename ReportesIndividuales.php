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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";


$conn = new mysqli("localhost", "root", "", "base_kam");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT id_personal, nombre_personal, apellido_personal, cedula_personal, titulo_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal FROM persona";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

?>


<!DOCTYPE html>
<html lagn="es">
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
      <i class="fas fa-users icon fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Reportes Individuales</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="d-flex justify-content-center">
        <div class="table-responsive w-100">
          <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center mb-0 rounded-3 overflow-hidden">
            <thead class="table-primary">
              <tr class="mt-4">
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Fecha de Nacimiento</th>
                <th>Fecha de Ingreso</th>
                <th>Cargo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>" . htmlspecialchars($row["nombre_personal"]) . "</td>
                          <td>" . htmlspecialchars($row["apellido_personal"]) . "</td>
                          <td>" . htmlspecialchars($row["cedula_personal"]) . "</td>
                          <td>" . htmlspecialchars($row["nacimiento_personal"]) . "</td>
                          <td>" . date("m Y", strtotime($row["ingreso_personal"])) . "</td>
                          <td>" . htmlspecialchars($row["cargo_personal"]) . "</td>
                          <td>
                            <a href='PDF/ReporteIndividual.php?id_personal=" . $row["id_personal"] . "' class='btn btn-sm btn-outline-danger rounded-circle' title='Descargar PDF'>
                              <i class='fas fa-file-download'></i>
                            </a>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='8'>No hay registros</td></tr>";
              }
              $conn->close();
              ?>
            </tbody>
          </table>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <script>

            document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = document.getElementById('searchInput').value.toUpperCase();
        const table = document.getElementById('miTabla');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            tr[i].style.display = 'none';
            const td = tr[i].getElementsByTagName('td');
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                        break;
                    }
                }
            }
        }
    });
    </script>
   
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

</body>
</html>
