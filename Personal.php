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
                text: 'No tienes permiso para acceder al módulo personal',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'Inicio.php'; 
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./Assets/CSS/Personal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
</style>

<body>

<canvas id="animated-bg"></canvas>
  <div class="container-fluid py-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <img src="Assets/Images/KAM.png" alt="Logo" width="160" height="40">
      </div>

    <div class="text-end">
      <button type="button" class="btn btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#myModal">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</div>


<form
  id="formPersonal"
  action="PHP/Insert_Personal.php"
  method="post"
  enctype="multipart/form-data"
  onsubmit="return validateForm()"
  onsubmit="return validarCedula()"
>


<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Registro de Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="registroForm" method="post" action="#">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="nombre_personal">Nombre:<span style="color: red">*</span></label>
              <input type="text" id="nombre_personal" name="nombre_personal" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label for="apellido_personal">Apellido:<span style="color: red">*</span></label>
              <input type="text" id="apellido_personal" name="apellido_personal" class="form-control" required />
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="cedula_personal">Cédula de Identidad:<span style="color: red">*</span></label>
              <div class="d-flex align-items-center">
                <select id="nacionalidad" name="nacionalidad" class="form-select me-2" style="width: 60px" required>
                  <option value="" disabled selected>Seleccione</option>
                  <option value="V">V</option>
                  <option value="E">E</option>
                </select>
                <input type="text" id="cedula_personal" name="cedula_personal" class="form-control" required />
              </div>
              <span id="cedulaError" class="text-danger"></span>
            </div>

            <div class="col-md-6">
              <label for="titulo_personal">Nivel Académico:<span style="color: red">*</span></label>
              <select id="titulo_personal" name="titulo_personal" class="form-select" required>
                <option value="" disabled selected>Seleccione</option>
                <option value="bachiller">Bachiller</option>
                <option value="ingeniero">Ingeniero</option>
                <option value="doctor">Doctor</option>
                <option value="licenciado">Licenciado</option>
                <option value="tsu">TSU</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="correo_personal">Correo Electrónico:<span style="color: red">*</span></label>
            <input type="email" id="correo_personal" name="correo_personal" class="form-control" required />
            <span id="correoError" class="text-danger"></span>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="nacimiento_personal">Fecha de Nacimiento:<span style="color: red">*</span></label>
              <input type="date" id="nacimiento_personal" name="nacimiento_personal" class="form-control" required />
              <span id="nacimientoError" class="text-danger"></span>
            </div>
            <div class="col-md-6">
              <label for="ingreso_personal">Fecha de Ingreso:<span style="color: red">*</span></label>
              <input type="date" id="ingreso_personal" name="ingreso_personal" class="form-control" required />
              <span id="ingresoError" class="text-danger"></span>
            </div>
          </div>

          <div class="mb-3">
            <label for="cargo_personal">Cargo:<span style="color: red">*</span></label>
            <select id="cargo_personal" name="cargo_personal" class="form-select" required>
              <option value="" disabled selected>Seleccione</option>
              <option value="Profesor">Profesor</option>
              <option value="Profesora">Profesora</option>
              <option value="Coordinadora">Coordinadora</option>
              <option value="Coordinador">Coordinador</option>
              <option value="Asistente administrativo">Asst.administrativo</option>
              <option value="Aux.Inicial">Aux.Inicial</option>
              <option value="Prof.Matematica/fisica">Prof.Matematica/fisica</option>
              <option value="Prof.Sociales">Prof.Sociales</option>
              <option value="Prof.Biologia">Prof.Biologia</option>
              <option value="Prof.Ingles">Prof.Ingles</option>
              <option value="Prof.Quimica">Prof.Quimica</option>
              <option value="Castellano y Proyecto">Castellano y Proyecto</option>
              <option value="Director">Director G</option>
              <option value="Obrero">Obrero</option>
              <option value="Directora">Directora</option>
              <option value="Maestra">Maestra</option>
              <option value="Administradora">Administradora</option>
              <option value="Contador">Contador</option>
              <option value="RRHH">RRHH</option>
              <option value="Conserje">Conserje</option>
              <option value="Mensajero">Mensajero</option>
              <option value="Secretaria">Secretaria</option>
            </select>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="submit" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'PHP/Consultar_Personal.php'; ?>



<div class="container mx-auto my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users-cog fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Personal</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="d-flex justify-content-center">
        <div class="table-responsive">
          <table id="miTabla" class="table table-striped table-bordered table-hover align-middle text-center mb-0 rounded-3 overflow-hidden">
            <thead class="table-primary">
              <tr class="mt-4">
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Nacimiento</th>
                <th>Ingreso</th>
                <th>Cargo</th>
                <th>Acciones</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
             <?php 
              if ($result->num_rows > 0) { 
                  $numeroFila = 1;
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr id='row-" . htmlspecialchars($row['id_personal']) . "'> 
                      <td>" . htmlspecialchars($row['nombre_personal']) . "</td>
                      <td>" . htmlspecialchars($row['apellido_personal']) . "</td>
                      <td>" . htmlspecialchars($row['cedula_personal']) . "</td> 
                      <td>" . htmlspecialchars($row['correo_personal']) . "</td> 
                      <td>" . date("d/m/Y", strtotime($row['nacimiento_personal'])) . "</td>
                      <td>" . date("d/m/Y", strtotime($row['ingreso_personal'])) . "</td>
                      <td>" . htmlspecialchars($row['cargo_personal']) . "</td> 
                      <td>
                        <button class='btn btn-sm btn-outline-primary rounded-circle' title='Editar'
                          onclick=\"openEditModal('" . htmlspecialchars($row['id_personal']) . "', 
                          '" . htmlspecialchars($row['nombre_personal']) . "', 
                          '" . htmlspecialchars($row['apellido_personal']) . "', 
                          '" . htmlspecialchars($row['cedula_personal']) . "', 
                          '" . htmlspecialchars($row['correo_personal']) . "', 
                          '" . htmlspecialchars($row['nacimiento_personal']) . "', 
                          '" . htmlspecialchars($row['ingreso_personal']) . "', 
                          '" . htmlspecialchars($row['cargo_personal']) . "')\">
                          <i class='fas fa-pencil-alt'></i>
                        </button>
                      </td> 
                      <td>
                        <div class='form-check form-switch d-flex justify-content-center'>
                          <input class='form-check-input' type='checkbox' " . 
                            ($row['activo'] ? "checked" : "") . "
                            onclick='toggleStatus(this)'
                            data-id='" . htmlspecialchars($row['id_personal']) . "'>
                        </div>
                        <span class='badge mt-1 " . 
                            ($row['activo'] ? "bg-success" : "bg-secondary") . "' id='estado-" . $row['id_personal'] . "'>" . 
                            ($row['activo'] ? "Activo" : "Inactivo") . "
                        </span>
                      </td>
                    </tr>";

                      $numeroFila++;
                  }
              } else {
                  echo "<tr><td colspan='10'>No se encontraron registros</td></tr>";
              }
              ?>

            </tbody>
          </table>
        </div>
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
      info: false
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleStatus(checkbox) {
  const id = checkbox.dataset.id;
  const nuevoEstado = checkbox.checked ? 1 : 0;

  Swal.fire({
    title: '¿Cambiar estado?',
    text: nuevoEstado ? '¿Activar este personal?' : '¿Desactivar este personal?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, confirmar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch('./PHP/actualizar_estado_personal.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}&activo=${encodeURIComponent(nuevoEstado)}`
      })
      .then(resp => resp.json())
      .then(data => {
        if (data.success) {
          // Actualizar visualmente el badge sin recargar
          const badge = document.getElementById('estado-' + id);
          badge.textContent = nuevoEstado ? 'Activo' : 'Inactivo';
          badge.className = 'badge mt-1 ' + (nuevoEstado ? 'bg-success' : 'bg-secondary');

          Swal.fire({
            icon: 'success',
            title: 'Estado actualizado',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          });
        } else {
          throw new Error(data.message || 'Error inesperado');
        }
      })
      .catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: err.message
        });
        checkbox.checked = !nuevoEstado; // revertir si falla
      });
    } else {
      checkbox.checked = !nuevoEstado; // revertir si cancela
    }
  });
}
</script>



 <div id="editModal" class="modal2">
  <div class="modal-content2">
    <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
      <h5 class="modal-title" id="modalLabel">Editar Personal</h5>
    </div>

    <form method="post">
      <input type="hidden" id="modal-id" name="id_personal"> 

      <div class="modal-grid">
        <div class="modal-group">
          <label>Nombre:</label> 
          <input type="text" class="form-control mb-2" id="modal-nombre" name="nombre_personal" required>

          <label>Cédula:</label>
          <input type="text" class="form-control mb-2" id="modal-cedula" name="cedula_personal" required>

          <label>Fecha de Nacimiento:</label>
          <input type="date" class="form-control mb-2" id="modal-nacimiento" name="nacimiento_personal" required>
        </div>

        <div class="modal-group">
          <label>Apellido:</label>
          <input type="text" class="form-control mb-2" id="modal-apellido" name="apellido_personal" required>

          <label>Correo:</label>
          <input type="email" class="form-control mb-2" id="modal-correo" name="correo_personal" required>

          <label>Fecha de Ingreso:</label>
          <input type="date" class="form-control mb-2" id="modal-ingreso" name="ingreso_personal" required>
        </div>
      </div>

      <div class="mt-3">
        <label for="cargo_personal" class="fw-bold">Cargo:</label>
        <select class="form-control mb-2" id="modal-cargo" name="cargo_personal" required>
          <option value="" disabled selected>Seleccione</option>
          <option value="Profesor">Profesor</option>
          <option value="Profesora">Profesora</option>
          <option value="Coordinadora">Coordinadora</option>
          <option value="Coordinador">Coordinador</option>
          <option value="Asistente administrativo">Asst.administrativo</option>
          <option value="Aux.Inicial">Aux.Inicial</option>
          <option value="Prof.Matematica/fisica">Prof.Matematica/fisica</option>
          <option value="Prof.Sociales">Prof.Sociales</option>
          <option value="Prof.Biologia">Prof.Biologia</option>
          <option value="Prof.Ingles">Prof.Ingles</option>
          <option value="Prof.Quimica">Prof.Quimica</option>
          <option value="Castellano y Proyecto">Castellano y Proyecto</option>
          <option value="Director">Director</option>
          <option value="Obrero">Obrero</option>
          <option value="Directora">Directora</option>
          <option value="Maestra">Maestra</option>
          <option value="Administradora">Administradora</option>
          <option value="Contador">Contador</option>
          <option value="RRHH">RRHH</option>
          <option value="Conserje">Conserje</option>
          <option value="Mensajero">Mensajero</option>
          <option value="Secretaria">Secretaria</option>
        </select>
      </div>

      <div class="text-end mt-3 gap-2" style="display: flex; justify-content: flex-end;">
        <button class="btn btn-primary" type="submit" name="actualizar">Guardar</button>
        <button class="btn btn-outline-secondary" type="button" onclick="closeModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>


<div id="previewModal" class="modal2">
    <div class="modal-content2">
        <span class="close" onclick="closeModal()">&#x2715;</span> <!-- Replaced button with "X" -->
      
        <div id="previewContent"></div>
    </div>
</div>

<!-- Botón de Inicio (derecha) -->
<a href="Inicio.php">
  <div class="floating-button right-button">
    <i class="fas fa-house fa-xl text-white"></i>
    <div class="hover-message">Inicio</div>
  </div>
</a>



<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="Assets/JavaScript/RegistroPersonal.js"></script>
<script src="Assets/JavaScript/ModalPersonal.js"></script>
<script src="Assets/JavaScript/EditarPersonal.js"></script>
<script src="Assets/JavaScript/PerfilPersonal.js"></script>
<script src="Assets/JavaScript/FuncionesPersonal.js"></script>

</body>
</html>

