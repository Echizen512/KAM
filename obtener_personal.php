<?php
$pdo = new PDO("mysql:host=localhost;dbname=sistema_horarios;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$stmt = $pdo->prepare("SELECT nombre, apellido, cedula, cargo FROM personal WHERE cargo IN ('auxiliar', 'maestra') ORDER BY apellido ASC");
$stmt->execute();
$datos = $stmt->fetchAll();

// Mostrar los datos si existen, de lo contrario un mensaje
if (!empty($datos)) {
  echo json_encode($datos);
} else {
  echo json_encode(["mensaje" => "No se encontraron registros"]);
}
?>
