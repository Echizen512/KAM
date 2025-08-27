<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]));
}

$cedula = filter_input(INPUT_GET, 'cedula', FILTER_SANITIZE_STRING);
$horaEntrada = filter_input(INPUT_GET, 'hora_entrada', FILTER_SANITIZE_STRING);
$fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);

if (!$cedula || !$fecha || !$horaEntrada) {
    echo json_encode(['success' => false, 'error' => 'Parámetros no válidos']);
    exit;
}

$sql = "SELECT hora_entrada FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cedula, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->fetch_assoc()) {
    echo json_encode(['success' => false, 'error' => 'Ya existe una entrada registrada para esta fecha.']);
} else {
    $sqlInsert = "INSERT INTO asistencia (cedula_personal, fecha_asistencia, hora_entrada) VALUES (?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("sss", $cedula, $fecha, $horaEntrada);

    if ($stmtInsert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Hora de entrada registrada exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar la hora de entrada: ' . $stmtInsert->error]);
    }
    $stmtInsert->close();
}

$stmt->close();
$conn->close();
?>
