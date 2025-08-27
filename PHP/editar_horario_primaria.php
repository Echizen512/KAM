<?php
// Conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Validación básica
$id = $_POST['id'] ?? null;
$cedula = $_POST['cedula'] ?? null;
$materia_id = $_POST['materia'] ?? null;
$tipo = $_POST['tipo_horario'] ?? null;
$total_horas = $_POST['total_horas'] ?? null;

if (!$id || !$cedula || !$materia_id || !$tipo) {
  die("Faltan datos obligatorios para editar el horario.");
}

// Actualizar horario
$stmt = $pdo->prepare("
  UPDATE horarios
  SET cedula = ?, materia_id = ?, tipo = ?, total_horas = ?
  WHERE id = ?
");
$stmt->execute([$cedula, $materia_id, $tipo, $total_horas, $id]);
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
