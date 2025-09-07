<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>KAM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
  <form method="POST" action="PDF/reporte_profesor.php" class="custom-card">
    <h5><i class="fas fa-file-pdf"></i> Generar Horario en PDF</h5>

    <div class="mb-3">
      <label class="form-label icon-label"><i class="fas fa-id-card"></i> Cédula del docente:</label>
      <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Ej. V-12345678" value="V-" required />


    </div>

    <div class="d-grid mt-4">
      <button type="submit" class="btn btn-custom">
        <i class="fas fa-download"></i> Generar PDF
      </button>
    </div>
  </form>
</div>

<!-- Botón de Inicio (derecha) -->
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

const cedulaInput = document.getElementById("cedula");

cedulaInput.addEventListener("input", function () {
  // Asegura que siempre comience con "V-"
  if (!this.value.startsWith("V-")) {
    this.value = "V-" + this.value.replace(/[^0-9]/g, "");
  } else {
    const soloNumeros = this.value.slice(2).replace(/[^0-9]/g, "");
    this.value = "V-" + soloNumeros;
  }
});

cedulaInput.addEventListener("keydown", function (e) {
  // Bloquea retroceso o flecha izquierda si el cursor está en el prefijo
  if (this.selectionStart <= 2 && (e.key === "Backspace" || e.key === "ArrowLeft")) {
    e.preventDefault();
  }
});


document.querySelector("form").addEventListener("submit", function (e) {
  const cedulaInput = document.getElementById("cedula");
  const valor = cedulaInput.value.trim();
  const soloNumeros = valor.slice(2);

  if (!/^V-\d{7,8}$/.test(valor)) {
    e.preventDefault(); // Detiene el envío
    Swal.fire({
      icon: 'warning',
      title: 'Formato inválido',
      text: 'La cédula debe tener el formato V-12345678',
    });
    cedulaInput.focus();
    return false;
  }

  // Limpieza final
  cedulaInput.value = "V-" + soloNumeros;
});

</script>

</body>
</html>
