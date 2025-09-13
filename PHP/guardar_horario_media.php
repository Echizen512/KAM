<?php
// Configuración PDO
$host = 'localhost';
$db = 'base_kam';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}

// Validación mínima
if (empty($_POST['cedula']) || empty($_POST['materia']) || empty($_POST['tipo_horario'])) {
  die("Faltan datos obligatorios.");
}

$cedula = $_POST['cedula'];
$materia_id = (int) $_POST['materia'];
$nuevaMateria = trim($_POST['nueva_materia'] ?? '');
$tipo = $_POST['tipo_horario'];
$totalHoras = $_POST['total_horas'] ?? null;

// Verificar si la cédula existe en persona
$stmt = $pdo->prepare("SELECT COUNT(*) FROM persona WHERE cedula_personal = ?");
$stmt->execute([$cedula]);
if (!$stmt->fetchColumn()) {
  die("La cédula no está registrada en la tabla persona.");
}

// Insertar materia si es nueva
if ($materia_id === 0 && $nuevaMateria !== '') {
  $stmt = $pdo->prepare("INSERT INTO materias (nombre) VALUES (?)");
  $stmt->execute([$nuevaMateria]);
  $materia_id = $pdo->lastInsertId();
}

// Validar materia existente
if ($materia_id === 0) {
  die("La materia no existe.");
}

// Insertar horario
$stmt = $pdo->prepare("INSERT INTO horarios (cedula, materia_id, tipo, total_horas) VALUES (?, ?, ?, ?)");
$stmt->execute([$cedula, $materia_id, $tipo, $totalHoras]);
$horario_id = $pdo->lastInsertId();

// Insertar bloques
if ($tipo === 'parcial') {
  $dias = $_POST['dia'] ?? [];
  $horas = $_POST['hora'] ?? [];
  $niveles = $_POST['anio'] ?? [];
  $secciones = $_POST['seccion'] ?? [];

  for ($i = 0; $i < count($dias); $i++) {
    $dia = $dias[$i] ?? null;
    $hora = $horas[$i] ?? null;
    $nivel = $niveles[$i] ?? null;
    $seccion = $secciones[$i] ?? null;

    if ($dia === null || $hora === null || $nivel === null || $seccion === null) {
      error_log("Bloque parcial incompleto en índice $i: dia=$dia, hora=$hora, nivel=$nivel, seccion=$seccion");
      continue;
    }

    $stmt = $pdo->prepare("INSERT INTO bloques_parcial (horario_id, dia, hora, nivel, seccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$horario_id, $dia, $hora, $nivel, $seccion]);
  }

} elseif ($tipo === 'tiempo_completo') {
  $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
  foreach ($dias as $dia) {
    $bloques = $_POST["bloques_$dia"] ?? [];
    $niveles = $_POST["anio_$dia"] ?? [];
    $secciones = $_POST["seccion_$dia"] ?? [];

    for ($i = 0; $i < count($bloques); $i++) {
      $bloque = $bloques[$i] ?? null;
      $nivel = $niveles[$i] ?? null;
      $seccion = $secciones[$i] ?? null;

      if ($bloque === null || $nivel === null || $seccion === null) {
        error_log("Bloque completo incompleto en $dia índice $i: bloque=$bloque, nivel=$nivel, seccion=$seccion");
        continue;
      }

      $stmt = $pdo->prepare("INSERT INTO bloques_completo (horario_id, dia, bloque_hora, nivel, seccion) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$horario_id, $dia, $bloque, $nivel, $seccion]);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Guardado</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
  icon: 'success',
  title: '¡Horario guardado!',
  text: 'Los datos se registraron correctamente.',
  confirmButtonText: 'Aceptar'
}).then(() => {
  window.location.href = '../Horario_Media.php';
});
</script>
</body>
</html>
