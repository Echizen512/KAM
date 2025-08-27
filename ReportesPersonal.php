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

require('PDF/fpdf186/fpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualiza los nombres de las columnas según tu tabla 'persona'
$sql = "SELECT nombre_personal, apellido_personal, cedula_personal, titulo_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal FROM persona";
$result = $conn->query($sql);

// Verificar si la consulta se ejecutó correctamente
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

class PDF extends FPDF
{
    function Header()
    {
        $x_logo = 40;
        $y_logo = 0;
        $ancho_logo = 40;
        $alto_logo = 45;

        if (file_exists('./Assets/Images/mio.jpeg')) {
            $this->Image('./Assets/Images/mio.jpeg', $x_logo, $y_logo, $ancho_logo, $alto_logo);
        } else {
            echo "No se encontró el archivo de la imagen.";
            exit;
        }

        $x_logo = 215;
        $y_logo = 12;
        $ancho_logo = 35;
        $alto_logo = 20;

        if (file_exists('./Assets/Images/Edupalcubo.png')) {
            $this->Image('./Assets/Images/Edupalcubo.png', $x_logo, $y_logo, $ancho_logo, $alto_logo);
        } else {
            echo "No se encontró el archivo de la imagen.";
            exit;
        }

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Ministerio del Poder Popular para la Educación'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('U.E.P Colegio Edupal'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('La Victoria - Estado Aragua'), 0, 1, 'C');
        $this->Ln(05);
        $this->Cell(0, 10, utf8_decode('Reporte General de Personal'), 0, 1, 'C');
        $this->Ln(05);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function AddTableHeader()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(35, 120, 182);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(30, 10, 'Nombre', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Apellido', 1, 0, 'C', true);
        $this->Cell(30, 10,  utf8_decode('Cédula'), 1, 0, 'C', true);
        $this->Cell(50, 10, 'Correo', 1, 0, 'C', true);
        $this->Cell(45, 10, 'Fecha de Nacimiento', 1, 0, 'C', true);
        $this->Cell(45, 10, 'Fecha de Ingreso', 1, 0, 'C', true);
        $this->Cell(50, 10, 'Cargo', 1, 1, 'C', true);
    }

    function AddTableRow($persona)
    {
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 10, utf8_decode($persona['nombre_personal']), 1, 0, 'C');
        $this->Cell(30, 10, utf8_decode($persona['apellido_personal']), 1, 0, 'C');
        $this->Cell(30, 10, utf8_decode($persona['cedula_personal']), 1, 0, 'C');
        $this->Cell(50, 10, utf8_decode($persona['correo_personal']), 1, 0, 'C');
        $this->Cell(45, 10, utf8_decode($persona['nacimiento_personal']), 1, 0, 'C');
        $this->Cell(45, 10, utf8_decode($persona['ingreso_personal']), 1, 0, 'C');
        $this->Cell(50, 10, utf8_decode($persona['cargo_personal']), 1, 1, 'C');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT nombre_personal, apellido_personal, cedula_personal, titulo_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal FROM persona WHERE id = $id";
    $result = $conn->query($sql);
    $persona = $result->fetch_assoc();

    $pdf = new PDF();
    $pdf->AddPage('L'); // Cambia la orientación a horizontal
    $pdf->SetLeftMargin(20); // Centrar la tabla
    $pdf->AddTableHeader();
    $pdf->AddTableRow($persona);

    $pdf->Output("D", utf8_decode("{$persona['nombre_personal']}.pdf"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['all'])) {
    $sql = "SELECT nombre_personal, apellido_personal, cedula_personal, titulo_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal FROM persona";
    $result = $conn->query($sql);

    $pdf = new PDF();
    $pdf->AddPage('L'); // Cambia la orientación a horizontal
    $pdf->SetLeftMargin(10); // Centrar la tabla
    $pdf->AddTableHeader();
    while ($persona = $result->fetch_assoc()) {
        $pdf->AddTableRow($persona);
    }

    $pdf->Output("D", utf8_decode("todos_los_datos.pdf"));
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>KAM</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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


<style>
  #animated-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
  pointer-events: none; /* evita que interfiera con clics */
}

.z-1 {
  z-index: 1;
}

.container-fluid {
  position: relative;
  z-index: 2;
  background-color: #f8f9fa; /* equivalente a bg-light */
  backdrop-filter: none; /* evita efectos de desenfoque si los hay */
}


</style>

  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>
      <div class="text-end">
        <button class="btn btn-danger me-2" onclick="window.location.href='?all=1'">
          <i class="fas fa-file-download me-2"></i> PDF General
        </button>
        <button class="btn btn-outline-danger" onclick="window.location.href='ReportesIndividuales.php'">
          <i class="fas fa-file-pdf me-2"></i> PDF Individual
        </button>
      </div>
  </div>
</div>
   


 <canvas id="animated-bg"></canvas>

<div class="container mx-auto my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users icon fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Reportes</h5>
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
              <th>Correo</th>
              <th>Fecha de Nacimiento</th>
              <th>Fecha de Ingreso</th>
              <th>Cargo</th>
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
                        <td>" . htmlspecialchars($row["correo_personal"]) . "</td>
                        <td>" . date("m/Y", strtotime($row['nacimiento_personal'])) . "</td>
                        <td>" . date("m/Y", strtotime($row['ingreso_personal'])) . "</td>
                        <td>" . htmlspecialchars($row["cargo_personal"]) . "</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='7'>No hay registros</td></tr>";
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="Assets/JavaScript/CanvasTabla.js"></script>

        <a href="Inicio.php">
        <div class="floating-button">
        <i class="fas fa-house fa-xl text-white"></i>
        <div class="hover-message">Inicio</div>
        </div>
    </a>
  
  
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
