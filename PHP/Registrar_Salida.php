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
$horaSalida = filter_input(INPUT_GET, 'hora_salida', FILTER_SANITIZE_STRING);
$fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);

if (!$cedula || !$fecha || !$horaSalida) {
    echo json_encode(['success' => false, 'error' => 'Parámetros no válidos']);
    exit;
}

$sql = "SELECT hora_entrada, hora_salida FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cedula, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['hora_entrada'] && !$row['hora_salida']) {
        $sqlUpdate = "UPDATE asistencia SET hora_salida = ? WHERE cedula_personal = ? AND fecha_asistencia = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sss", $horaSalida, $cedula, $fecha);

        if ($stmtUpdate->execute()) {
            echo json_encode(['success' => true, 'message' => 'Hora de salida registrada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar la hora de salida: ' . $stmtUpdate->error]);
        }
        $stmtUpdate->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Registro de salida no permitido: Entrada inexistente o ya tiene salida.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No hay registros previos para esta fecha.']);
}

$stmt->close();
$conn->close();
?>
