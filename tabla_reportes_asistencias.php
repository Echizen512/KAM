<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>KAM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="./Assets/CSS/Personal.css">
 <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
    }

    .form-wrapper {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      max-width: 700px;
      z-index: 10;
    }

    .custom-card {
      background: linear-gradient(to bottom right, #007bff, #0056b3);
      color: white;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      padding: 40px;
    }

    .custom-card h5 {
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
      font-size: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 8px;
    }

    .form-control {
      border-radius: 8px;
      border: none;
      padding: 12px 14px;
      font-size: 1rem;
    }

    .btn-custom {
      background-color: #ffffff;
      color: #007bff;
      font-weight: bold;
      border-radius: 8px;
      padding: 12px;
      transition: all 0.3s ease;
      font-size: 1rem;
    }

    .btn-custom:hover {
      background-color: #e0e0e0;
      color: #0056b3;
    }

    .icon-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
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

    .hidden {
      display: none;
    }

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

  </style>
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

<div class="form-wrapper">
  <form method="GET" action="PDF/Asistencia.php" class="custom-card">
    <h5><i class="fas fa-file-pdf"></i> Generar Reporte de Asistencia</h5>

    <div class="mb-3">
      <label class="form-label icon-label"><i class="fas fa-id-card"></i> Cédula del docente:</label>
      <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Ej. V12345678" required oninput="validarCedula()" />
    </div>

    <div class="mb-3">
      <label class="form-label icon-label"><i class="fas fa-filter"></i> Tipo de reporte:</label>
      <select id="tipo" name="tipo" class="form-control" onchange="toggleFechas()" required>
        <option value="diario">Diario</option>
        <option value="general">General</option>
      </select>
    </div>

    <div id="fechas" class="hidden">
      <div class="mb-3">
        <label class="form-label icon-label"><i class="fas fa-calendar-day"></i> Fecha inicio:</label>
        <input type="date" name="fechaInicio" class="form-control" />
      </div>
      <div class="mb-3">
        <label class="form-label icon-label"><i class="fas fa-calendar-day"></i> Fecha fin:</label>
        <input type="date" name="fechaFin" class="form-control" />
      </div>
    </div>

    <div class="d-grid mt-4">
      <button type="submit" class="btn btn-custom">
        <i class="fas fa-download"></i> Generar PDF
      </button>
    </div>
  </form>
</div>

<a href="Inicio.php">
  <div class="floating-button right-button">
    <i class="fas fa-house fa-xl text-white"></i>
    <div class="hover-message">Inicio</div>
  </div>
</a>

<!-- Botón de Atrás (izquierda) -->
<a href="javascript:history.back()">
  <div class="floating-button left-button">
    <i class="fas fa-arrow-left fa-xl text-white"></i>
    <div class="hover-message">Atrás</div>
  </div>
</a>

<script src="Assets/JavaScript/CanvasPrincipal.js"></script>

<script>
function validarCedula() {
  const cedulaInput = document.getElementById("cedula");
  let cedula = cedulaInput.value.trim();

  if (!cedula.startsWith("V-")) {
    cedula = "V-" + cedula.replace(/\D/g, "");
    cedulaInput.value = cedula;
  }

  if (!/^V-\d{7,8}$/.test(cedula)) {
    Swal.fire({
      icon: 'warning',
      title: 'Formato inválido',
      text: 'La cédula debe tener el formato V-12345678',
    });
    cedulaInput.focus();
  }
}

function toggleFechas() {
  const tipo = document.getElementById("tipo").value;
  const fechas = document.getElementById("fechas");
  fechas.classList.toggle("hidden", tipo !== "general");
}
</script>

</body>
</html>
