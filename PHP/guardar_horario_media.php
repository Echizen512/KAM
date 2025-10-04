<?php
// Conexión PDO
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
if (empty($_POST['cedula']) || empty($_POST['tipo_horario'])) {
  die("Faltan datos obligatorios.");
}

$cedula = $_POST['cedula'];
$tipo = $_POST['tipo_horario'];
$totalHoras = $_POST['total_horas'] ?? null;

// Verificar que la cédula existe en persona
$stmt = $pdo->prepare("SELECT COUNT(*) FROM persona WHERE cedula_personal = ?");
$stmt->execute([$cedula]);
if (!$stmt->fetchColumn()) {
  die("La cédula no está registrada en la tabla persona.");
}

// Insertar horario (sin materia_id)
$stmt = $pdo->prepare("INSERT INTO horarios (cedula, tipo, total_horas) VALUES (?, ?, ?)");
$stmt->execute([$cedula, $tipo, $totalHoras]);
$horario_id = $pdo->lastInsertId();

// Insertar bloques
if ($tipo === 'parcial') {
  $dias = $_POST['dia'] ?? [];
  $horas = $_POST['hora'] ?? [];
  $niveles = $_POST['anio'] ?? [];
  $secciones = $_POST['seccion'] ?? [];
  $materias = $_POST['materia_id'] ?? [];

  for ($i = 0; $i < count($dias); $i++) {
    if (empty($dias[$i]) || empty($horas[$i]) || empty($niveles[$i]) || empty($secciones[$i]) || empty($materias[$i])) {
      continue;
    }

    $stmt = $pdo->prepare("
      INSERT INTO bloques_parcial (horario_id, dia, hora, nivel, seccion, materia_id)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$horario_id, $dias[$i], $horas[$i], $niveles[$i], $secciones[$i], $materias[$i]]);
  }

} elseif ($tipo === 'tiempo_completo') {
  $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
  foreach ($dias as $dia) {
    $bloques = $_POST["bloques_$dia"] ?? [];
    $niveles = $_POST["anio_$dia"] ?? [];
    $secciones = $_POST["seccion_$dia"] ?? [];
    $materias = $_POST["materia_id_$dia"] ?? [];

    for ($i = 0; $i < count($bloques); $i++) {
      if (empty($bloques[$i]) || empty($niveles[$i]) || empty($secciones[$i]) || empty($materias[$i])) {
        continue;
      }

      $stmt = $pdo->prepare("
        INSERT INTO bloques_completo (horario_id, dia, bloque_hora, nivel, seccion, materia_id)
        VALUES (?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute([$horario_id, $dia, $bloques[$i], $niveles[$i], $secciones[$i], $materias[$i]]);
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
