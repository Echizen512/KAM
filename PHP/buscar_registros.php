<?php
header('Content-Type: application/json');

$cedula = $_GET['cedula'] ?? '';

if (!$cedula) {
  echo json_encode(['error' => 'Cédula no proporcionada']);
  exit;
}

$conn = new mysqli("localhost", "root", "", "base_kam");

if ($conn->connect_error) {
  echo json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]);
  exit;
}

$stmt = $conn->prepare("
  SELECT 
    DATE_FORMAT(fecha_asistencia, '%d/%m/%Y') AS fecha_asistencia,
    DATE_FORMAT(hora_entrada, '%h:%i %p') AS hora_entrada_12h,
    IF(hora_salida IS NOT NULL, DATE_FORMAT(hora_salida, '%h:%i %p'), 'No registrado') AS hora_salida_12h
  FROM asistencia 
  WHERE cedula_personal = ? 
  ORDER BY fecha_asistencia DESC
");

$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

$registros = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($registros);

$stmt->close();
$conn->close();
