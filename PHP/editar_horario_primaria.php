<?php
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$id = $_POST['id'] ?? null;
$cedula = $_POST['cedula'] ?? null;
$tipo = $_POST['tipo_horario'] ?? null;
$total_horas = $_POST['total_horas'] ?? null;

if (!$id || !$cedula || !$tipo) {
  die("Faltan datos obligatorios.");
}

$pdo->beginTransaction();

try {
  $pdo->prepare("UPDATE horarios SET cedula = ?, tipo = ?, total_horas = ? WHERE id = ?")
      ->execute([$cedula, $tipo, $total_horas, $id]);

  if ($tipo === 'parcial') {
    $dias      = $_POST['dia'] ?? [];
    $horas     = $_POST['hora'] ?? [];
    $niveles   = $_POST['anio'] ?? [];
    $secciones = $_POST['seccion'] ?? [];
    $materias  = $_POST['materia_id'] ?? [];

    $bloquesValidos = array_filter($dias, function ($i) use ($dias, $horas, $niveles, $secciones, $materias) {
      return isset($dias[$i], $horas[$i], $niveles[$i], $secciones[$i], $materias[$i]) &&
             $dias[$i] !== '' && $horas[$i] !== '' && $niveles[$i] !== '' &&
             $secciones[$i] !== '' && $materias[$i] !== '';
    }, ARRAY_FILTER_USE_KEY);

    if (count($bloquesValidos) > 0) {
      $pdo->prepare("DELETE FROM bloques_parcial WHERE horario_id = ?")->execute([$id]);
    }

    foreach ($bloquesValidos as $i => $_) {
      $pdo->prepare("INSERT INTO bloques_parcial (horario_id, dia, hora, nivel, seccion, materia_id)
                     VALUES (?, ?, ?, ?, ?, ?)")
          ->execute([$id, $dias[$i], $horas[$i], $niveles[$i], $secciones[$i], $materias[$i]]);
    }

  } elseif ($tipo === 'tiempo_completo') {
    $diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
    $bloquesDetectados = false;

    foreach ($diasSemana as $dia) {
      $bloques   = $_POST["bloques_$dia"] ?? [];
      $niveles   = $_POST["anio_$dia"] ?? [];
      $secciones = $_POST["seccion_$dia"] ?? [];
      $materias  = $_POST["materia_id_$dia"] ?? [];

      for ($i = 0; $i < min(count($bloques), count($niveles), count($secciones), count($materias)); $i++) {
        if (
          trim($bloques[$i] ?? '') &&
          trim($niveles[$i] ?? '') &&
          trim($secciones[$i] ?? '') &&
          trim($materias[$i] ?? '')
        ) {
          $bloquesDetectados = true;
          break;
        }
      }
    }

    if ($bloquesDetectados) {
      $pdo->prepare("DELETE FROM bloques_completo WHERE horario_id = ?")->execute([$id]);
    }

    foreach ($diasSemana as $dia) {
      $bloques   = $_POST["bloques_$dia"] ?? [];
      $niveles   = $_POST["anio_$dia"] ?? [];
      $secciones = $_POST["seccion_$dia"] ?? [];
      $materias  = $_POST["materia_id_$dia"] ?? [];

      for ($i = 0; $i < min(count($bloques), count($niveles), count($secciones), count($materias)); $i++) {
        $bloque   = trim($bloques[$i] ?? '');
        $nivel    = trim($niveles[$i] ?? '');
        $seccion  = trim($secciones[$i] ?? '');
        $materia  = trim($materias[$i] ?? '');

        if ($bloque && $nivel && $seccion && $materia) {
          $pdo->prepare("INSERT INTO bloques_completo (horario_id, dia, bloque_hora, nivel, seccion, materia_id)
                         VALUES (?, ?, ?, ?, ?, ?)")
              ->execute([$id, $dia, $bloque, $nivel, $seccion, $materia]);
        }
      }
    }
  }

  $pdo->commit();

} catch (Exception $e) {
  $pdo->rollBack();
  die("Error al guardar el horario.");
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
  window.location.href = '../Horario_Primaria.php';
});
</script>
</body>
</html>
