<?php
include '../DataBase/conexion.php';

$cedula = $_GET['cedula'];

$sql = "SELECT fecha_asistencia AS fecha, hora_entrada_12h AS hora_entrada, hora_salida_12h AS hora_salida FROM asistencia WHERE cedula_personal = ? ORDER BY fecha_asistencia";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
