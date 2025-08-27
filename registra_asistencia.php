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
$horaSalida = filter_input(INPUT_GET, 'hora_salida', FILTER_SANITIZE_STRING);
$fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);

if (!$cedula || !$fecha) {
    echo json_encode(['success' => false, 'error' => 'Parámetros no válidos']);
    exit;
}

// Verificar si ya existe un registro para la fecha actual
$sql = "SELECT hora_entrada, hora_salida FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cedula, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['hora_entrada'] && !$row['hora_salida']) {
        // Actualizar la hora de salida en el registro existente
        $sqlUpdate = "UPDATE asistencia SET hora_salida = ? WHERE cedula_personal = ? AND fecha_asistencia = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sss", $horaSalida, $cedula, $fecha);

        if ($stmtUpdate->execute()) {
            echo json_encode(['success' => true, 'message' => 'Hora de salida registrada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar la hora de salida: ' . $stmtUpdate->error]);
        }

        $stmtUpdate->close();
    } elseif ($row['hora_entrada'] && $row['hora_salida']) {
        // El registro ya está completo
        echo json_encode(['success' => false, 'error' => 'Este usuario ya culminó su registro de asistencia diario.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error desconocido en la validación del registro.']);
    }
} else {
    // Registrar una nueva entrada si no existe un registro para la fecha actual
    if ($horaEntrada) {
        $sqlInsert = "INSERT INTO asistencia (cedula_personal, fecha_asistencia, hora_entrada) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sss", $cedula, $fecha, $horaEntrada);

        if ($stmtInsert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Hora de entrada registrada exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar la hora de entrada: ' . $stmtInsert->error]);
        }

        $stmtInsert->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Debe proporcionar una hora de entrada o salida válida.']);
    }
}

$stmt->close();
$conn->close();
?>
