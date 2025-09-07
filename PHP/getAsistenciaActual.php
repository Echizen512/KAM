<?php
include '../DataBase/conexion.php'; 

$cedula = $_GET['cedula'];
$fecha_actual = date('Y-m-d');

$sql = "SELECT nombre_personal AS nombre, apellido_personal AS apellido, cedula_personal AS cedula, fecha_asistencia AS fecha, hora_entrada_12h AS hora_entrada, hora_salida_12h AS hora_salida FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cedula, $fecha_actual);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'No se encontraron registros.']);
}

$stmt->close();
$conn->close();
?>
