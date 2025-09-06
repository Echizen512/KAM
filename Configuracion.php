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
      <div class="text-end">
<button type="button" class="btn btn-outline-primary rounded-circle" title="Agregar Usuario" id="btnAbrirModal">
  <i class="fas fa-plus"></i>
</button>

      </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-5">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
      <i class="fas fa-users-cog fa-lg me-2"></i>
      <h5 class="mb-0">Gestión de Usuarios</h5>
    </div>
    <div class="card-body rounded-bottom-4">
      <div class="table-responsive">
        <table id="dataTable" class="table table-striped table-bordered align-middle text-center mb-0 w-100">
          <thead class="table-primary">
            <tr>
              <th style="width: 10%" class="text-center">N°</th>
              <th style="width: 20%" class="text-center">Usuario</th>
              <th style="width: 30%" class="text-center">Correo</th>
              <th style="width: 20%" class="text-center">Rol</th>
              <th style="width: 20%" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Los datos se cargarán aquí mediante JavaScript -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
  <!-- Botón de Inicio (derecha) -->
  <a href="Inicio.php">
    <div class="floating-button right-button">
      <i class="fas fa-house fa-xl text-white"></i>
      <div class="hover-message">Inicio</div>
    </div>
  </a>

  <!-- Botón de Atrás (izquierda) -->
  <a href="Mantenibilidad.php">
    <div class="floating-button left-button">
      <i class="fas fa-arrow-left fa-xl text-white"></i>
      <div class="hover-message">Atrás</div>
    </div>
  </a>

<script src="Assets/JavaScript/CanvasTabla.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
  $(document).ready(function () {
    $('#dataTable').DataTable({
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


<script>

document.getElementById('btnAbrirModal').onclick = function() {
  document.getElementById('miModal').style.display = 'block';
};

document.getElementById('cerrarModal').onclick = function() {
  document.getElementById('miModal').style.display = 'none';
};

window.onclick = function(event) {
  if (event.target == document.getElementById('miModal')) {
    document.getElementById('miModal').style.display = 'none';
  }
};

function mostrarMensaje(mensaje, tipo) {
    let iconColor = tipo === 'success' ? '#4caf50' : '#f44336'; // Verde para éxito, rojo para error
    Swal.fire({
        icon: tipo,
        title: mensaje,
        background: 'white',
        iconColor: iconColor,
        color: 'black',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'mensaje-popup'
        }
    });
}

function redireccionarExito() {
    // Cambia la URL de redirección por la que necesites
    window.location.href = 'http://localhost/html/Configuracion.php';
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const usuario = form.usuario.value.trim();
        const correo = form.correo.value.trim();
        const contrasena = form.contrasena.value.trim();
        const confirmar_contrasena = form.confirmar_contrasena.value.trim();

        if (!usuario || !correo || !contrasena || !confirmar_contrasena) {
            mostrarMensaje('Por favor, completa todos los campos.', 'error');
            return;
        }

        const contrasenaRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[._*]).{8,}$/;
        if (!contrasenaRegex.test(contrasena)) {
            mostrarMensaje('La contraseña debe tener mínimo 8 caracteres, incluyendo una letra mayúscula, un número y un símbolo (., *, _).', 'error');
            return;
        }

        const formData = new FormData(form);
        const response = await fetch(form.action, { method: 'POST', body: formData });
        const result = await response.json();

        mostrarMensaje(result.message, result.status === 'success' ? 'success' : 'error');

        if (result.status === 'success') {
            redireccionarExito();
            limpiarFormulario();
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  cargarUsuarios();
});

// Función para cargar usuarios desde la API
function cargarUsuarios() {
  fetch('PHP/API_User.php')
    .then((response) => {
      if (!response.ok) throw new Error('Error al obtener los usuarios.');
      return response.json();
    })
    .then((data) => {
      const userTableBody = document.querySelector('#dataTable tbody');
      userTableBody.innerHTML = ''; // Limpiar tabla antes de cargar datos
      data.forEach((user) => {
        userTableBody.innerHTML += `
          <tr>
            <td>${user.id}</td>
            <td>${user.usuario}</td>
            <td>${user.correo}</td>
            <td>${user.nivel_usuario}</td>
            <td>
              <a href="javascript:editarUsuario(${user.id})" class="btn btn-outline-primary rounded-circle" title="Editar">
                <i class="fas fa-edit"></i>
              </a>
              <a href="javascript:eliminarUsuario(${user.id})" class="btn btn-outline-danger rounded-circle" title="Eliminar">
                <i class="fas fa-trash""></i>
              </a>
            </td>
          </tr>`;
      });
    })
    .catch((error) => {
      console.error(error);
      Swal.fire('Error', 'No se pudieron cargar los usuarios.', 'error');
    });
}


  function insertarUsuario() {
  Swal.fire({
    title: 'Añadir Usuario',
    html: `
      <div class="mb-3 text-start">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" id="usuario" class="form-control">
      </div>
      <div class="mb-3 text-start">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" class="form-control">
      </div>
      <div class="mb-3 text-start">
        <label for="nivel_usuario" class="form-label">Rol</label>
        <select id="nivel_usuario" class="form-select">
          <option value="" disabled selected>Seleccione una opción</option>
          <option value="administrador">Administrador</option>
          <option value="Secretaria">Secretaria</option>
          <option value="RRHH">RRHH</option>
        </select>
      </div>
      <div class="mb-3 text-start">
        <label for="contrasena" class="form-label">Contraseña</label>
        <input type="password" id="contrasena" class="form-control">
      </div>
      <div class="mb-3 text-start">
        <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
        <input type="password" id="confirmar_contrasena" class="form-control">
      </div>
    `,
    confirmButtonText: 'Registrar',
    showCancelButton: true,
    preConfirm: () => {
      const usuario = document.getElementById('usuario').value.trim();
      const correo = document.getElementById('correo').value.trim();
      const nivel_usuario = document.getElementById('nivel_usuario').value;
      const contrasena = document.getElementById('contrasena').value;
      const confirmar_contrasena = document.getElementById('confirmar_contrasena').value;

      if (!usuario || !correo || !nivel_usuario || !contrasena || !confirmar_contrasena) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return null;
      }

      return { usuario, correo, nivel_usuario, contrasena, confirmar_contrasena };
    },
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      fetch('registrar.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(result.value),
      })
        .then((response) => {
          if (!response.ok) throw new Error('Error al registrar el usuario.');
          return response.json();
        })
        .then((data) => {
          if (data.status === 'success') {
            Swal.fire('¡Hecho!', data.message, 'success');
            cargarUsuarios(); // recarga la tabla si tienes esta función
          } else {
            Swal.fire('Error', data.message, 'error');
          }
        })
        .catch((error) => {
          console.error(error);
          Swal.fire('Error', 'No se pudo registrar el usuario.', 'error');
        });
    }
  });
}


// Función para editar usuario con rol
function editarUsuario(id) {
  fetch(`PHP/API_User.php?id=${id}`)
    .then((response) => {
      if (!response.ok) throw new Error('Error al cargar los datos del usuario.');
      return response.json();
    })
    .then((data) => {
      Swal.fire({
        title: 'Editar Usuario',
        html: `
          <div class="mb-3 text-start">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" id="usuario" class="form-control" value="${data.usuario}">
          </div>
          <div class="mb-3 text-start">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" class="form-control" value="${data.correo}">
          </div>
          <div class="mb-3 text-start">
            <label for="nivel_usuario" class="form-label">Rol</label>
            <select id="nivel_usuario" class="form-select">
              <option value="administrador" ${data.nivel_usuario === 'administrador' ? 'selected' : ''}>Administrador</option>
              <option value="Secretaria" ${data.nivel_usuario === 'Secretaria' ? 'selected' : ''}>Secretaria</option>
              <option value="RRHH" ${data.nivel_usuario === 'RRHH' ? 'selected' : ''}>RRHH</option>
            </select>
          </div>
        `,
        confirmButtonText: 'Guardar',
        showCancelButton: true,
        preConfirm: () => {
          const usuario = document.getElementById('usuario').value.trim();
          const correo = document.getElementById('correo').value.trim();
          const nivel_usuario = document.getElementById('nivel_usuario').value;

          if (!usuario || !correo || !nivel_usuario) {
            Swal.showValidationMessage('Todos los campos son obligatorios');
            return null;
          }

          return { usuario, correo, nivel_usuario };
        },
      }).then((result) => {
        if (result.isConfirmed && result.value) {
          fetch(`PHP/API_User.php?id=${id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(result.value),
          })
            .then((response) => {
              if (!response.ok) throw new Error('Error al guardar los datos del usuario.');
              return response.json();
            })
            .then(() => {
              Swal.fire('¡Hecho!', 'Usuario actualizado correctamente.', 'success');
              cargarUsuarios();
            })
            .catch((error) => {
              console.error(error);
              Swal.fire('Error', 'No se pudo actualizar el usuario.', 'error');
            });
        }
      });
    })
    .catch((error) => {
      console.error(error);
      Swal.fire('Error', 'No se pudieron cargar los datos del usuario.', 'error');
    });
}

// Función para eliminar usuario
function eliminarUsuario(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás revertir esta acción.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`PHP/API_User.php?id=${id}`, {
        method: 'DELETE',
      })
        .then((response) => {
          if (!response.ok) throw new Error('Error al eliminar el usuario.');
          return response.json();
        })
        .then(() => {
          Swal.fire('¡Eliminado!', 'El usuario ha sido eliminado.', 'success');
          cargarUsuarios();
        })
        .catch((error) => {
          console.error(error);
          Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error');
        });
    }
  });
}


</script>
  

  
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
  // Asegura que el botón llama a la función correctamente
  document.getElementById('btnAbrirModal').addEventListener('click', function () {
    insertarUsuario();
  });
</script>

</body>
</html>