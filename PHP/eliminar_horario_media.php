<?php
// Conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Validación básica
$id = $_POST['id'] ?? null;

if (!$id) {
  die("No se recibió el ID del horario a eliminar.");
}

// Eliminar horario
$stmt = $pdo->prepare("DELETE FROM horarios WHERE id = ?");
$stmt->execute([$id]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Horario eliminado</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
  icon: 'success',
  title: '¡Horario eliminado!',
  text: 'El registro fue eliminado correctamente.',
  confirmButtonText: 'Aceptar'
}).then(() => {
  window.location.href = '../Horario_Media.php';
});
</script>
</body>
</html>
