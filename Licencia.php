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
$conn = new mysqli("localhost", "root", "", "base_kam");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el número total de registros
$total_result = $conn->query("SELECT COUNT(*) AS total FROM persona");
if ($total_result === false) {
    die("Error en la consulta de total: " . $conn->error);
}
$row = $total_result->fetch_assoc();
$total = $row['total'];

// Ejecutar tu consulta SQL con límite
$sql = "SELECT * FROM persona";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
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

<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-fingerprint fa-lg me-2"></i>
      <h5 class="mb-0">Permisos por Licencia</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="miTabla" class="table table-striped table-bordered align-middle text-center mb-0">
          <thead class="table-primary">
            <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido</th>
                <th class="text-center">Cédula</th>
                <th class="text-center">Cargo</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["nombre_personal"] . "</td>
                            <td>" . $row["apellido_personal"] . "</td>
                            <td>" . $row["cedula_personal"] . "</td>
                            <td>" . $row["cargo_personal"] . "</td>
                            <td>

                        <div class='d-flex justify-content-center gap-3'>
                            <a href='#' class='preview-link btn btn-outline-primary rounded-circle' data-id='" . $row['id_personal'] . "' onclick='openModal(" . $row['id_personal'] . ")'>
                                <i class='fas fa-eye fa-lg'></i> 
                            </a>
                                    <a href='generar_pdf.php?id=" . $row['id_personal'] . "' class='download-link btn btn-outline-danger rounded-circle' data-id='" . $row['id_personal'] . "'>
                                        <i class='fas fa-file-download fa-lg'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron registros</td></tr>";
            }

            // Liberar el resultado y cerrar la conexión
            if ($result instanceof mysqli_result) {
                $result->close();
            }
            $conn->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#miTabla').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      pageLength: 10,
      lengthChange: false,
      ordering: true,
      info: true
    });
  });
</script>

<div class="modal fade" id="editModal" tabindex="1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content w-100">
            <div class="modal-header">
                <h5 align="center"class="modal-title" id="editModalLabel"> Permiso de Licencia</h5>
        
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="fechaFin" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fechaFin" required>
                    </div>
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo</label>
                        <input type="text" class="form-control capitalize" id="motivo" required>
                    </div>
                    <div class="mb-3">
                        <label for="condicionPermiso" class="form-label">Condición de Permiso</label>
                        <input type="text" class="form-control capitalize" id="condicionPermiso" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombreAutoridad" class="form-label">Nombre de autoridad</label>
                        <input type="text" class="form-control" id="nombreAutoridad" list="autores" required>
                        <datalist id="autores">
                            <option value="Carmen Delgado">
                            <option value="Naydelis Biel">
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="cedulaAutoriza" class="form-label">Cédula que Autoriza</label>
                        <input type="text" class="form-control" id="cedulaAutoriza" required>
                    </div>
                    <input type="hidden" id="personaId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Aceptar</button>
            </div>
        </div>
    </div>
</div>


    <!-- Modal -->
    <div id="modalPreview" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

<div class="toast-container" id="toastContainer"></div>

  <a href="Inicio.php">
    <div class="floating-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>
  
<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        let editModal = new bootstrap.Modal(document.getElementById('editModal'), {});
        let saveChangesButton = document.getElementById('saveChanges');
        let personaIdField = document.getElementById('personaId');
        let fechaInicioField = document.getElementById('fechaInicio');
        let fechaFinField = document.getElementById('fechaFin');
        let motivoField = document.getElementById('motivo');
        let condicionPermisoField = document.getElementById('condicionPermiso');
        let nombreAutoridadField = document.getElementById('nombreAutoridad');
        let cedulaAutorizaField = document.getElementById('cedulaAutoriza');

        document.querySelectorAll('.download-link').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                let personaId = this.getAttribute('data-id');
                personaIdField.value = personaId;
                editModal.show();
            });
        });

        saveChangesButton.addEventListener('click', function () {
            let personaId = personaIdField.value;
            let fechaInicio = fechaInicioField.value;
            let fechaFin = fechaFinField.value;
            let motivo = motivoField.value;
            let condicionPermiso = condicionPermisoField.value;
            let nombreAutoridad = nombreAutoridadField.value;
            let cedulaAutoriza = cedulaAutorizaField.value;

            if (!fechaInicio || !fechaFin || !motivo || !condicionPermiso || !nombreAutoridad || !cedulaAutoriza) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, complete todos los campos.'
                });
                return;
            }

            if (!validateStartAndEndDates(fechaInicio, fechaFin)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La fecha de inicio debe ser el día actual o una fecha posterior y la fecha de fin debe ser posterior a la fecha de inicio.'
                });
                return;
            }

            window.location.href = `PDF/Licencia.php?id=${personaId}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}&motivo=${motivo}&condicionPermiso=${condicionPermiso}&nombreAutoridad=${nombreAutoridad}&cedulaAutoriza=${cedulaAutoriza}`;
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Descarga exitosa'
            }).then(() => {
                editModal.hide();
            });
        });
    });

    function validateStartAndEndDates(startDate, endDate) {
        const today = new Date().setHours(0, 0, 0, 0);
        const start = new Date(startDate).setHours(0, 0, 0, 0);
        const end = new Date(endDate).setHours(0, 0, 0, 0);

        if (start < today) {
            return false;
        }

        return start <= end;
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalPreview');
    const closeModal = document.getElementsByClassName('close')[0];
    const previewLinks = document.getElementsByClassName('preview-link');

    Array.from(previewLinks).forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const id = link.getAttribute('data-id');
            fetch(`./Preview/Licencia.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalBody').innerHTML = data;
                    modal.style.display = 'block';
                });
        });
    });

    closeModal.onclick = () => {
        modal.style.display = 'none';
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>


<script>
    // Modal para el perfil de usuario
    const userProfileButton = document.getElementById('userProfileButton');
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfile = document.getElementById('closeProfile');
    userProfileButton.onclick = function() {
        userProfileModal.style.display = "block";
    }
    closeProfil.onclick = function() {
        userProfileModal.style.display = "none";
    }

   
    window.onclick = function(event) {
        if (event.target == userProfileModal) {
            userProfileModal.style.display = "none";
        }
     
    }
</script>
</body>
</html>
