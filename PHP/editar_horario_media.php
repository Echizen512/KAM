<?php
// Conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Validación básica
$id = $_POST['id'] ?? null;
$cedula = $_POST['cedula'] ?? null;
$tipo = $_POST['tipo_horario'] ?? null;
$total_horas = $_POST['total_horas'] ?? null;

if (!$id || !$cedula || !$tipo) {
  die("Faltan datos obligatorios para editar el horario.");
}

// === Actualizar horario ===
$stmt = $pdo->prepare("
  UPDATE horarios
  SET cedula = ?, tipo = ?, total_horas = ?
  WHERE id = ?
");
$stmt->execute([$cedula, $tipo, $total_horas, $id]);

// === Eliminar bloques previos ===
$stmt = $pdo->prepare("DELETE FROM bloques_parcial WHERE horario_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM bloques_completo WHERE horario_id = ?");
$stmt->execute([$id]);

// === Insertar bloques de nuevo ===
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
    $stmt->execute([$id, $dias[$i], $horas[$i], $niveles[$i], $secciones[$i], $materias[$i]]);
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
      $stmt->execute([$id, $dia, $bloques[$i], $niveles[$i], $secciones[$i], $materias[$i]]);
    }
  }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Horario actualizado</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
  icon: 'success',
  title: '¡Horario actualizado!',
  text: 'Los cambios se guardaron correctamente.',
  confirmButtonText: 'Aceptar'
}).then(() => {
  window.location.href = '../Horario_Media.php';
});
</script>
</body>
</html>
