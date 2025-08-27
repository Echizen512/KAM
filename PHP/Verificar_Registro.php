<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]));
}

// Obtención de parámetros de entrada
$cedula = filter_input(INPUT_GET, 'cedula', FILTER_SANITIZE_STRING);

if (!$cedula) {
    echo json_encode(['success' => false, 'error' => 'Cédula no proporcionada o no válida']);
    exit;
}

// Consulta para verificar si el registro diario está completo
$sql = "SELECT hora_entrada, hora_salida FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($registro = $result->fetch_assoc()) {
    if ($registro['hora_entrada'] && $registro['hora_salida']) {
        echo json_encode([
            'success' => true,
            'registroCompleto' => true,
            'message' => 'Este usuario ya completó su asistencia diaria para la fecha actual'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'registroCompleto' => false,
            'message' => 'Registro diario incompleto'
        ]);
    }
} else {
    echo json_encode([
        'success' => true,
        'registroCompleto' => false,
        'message' => 'No se encontraron registros para hoy'
    ]);
}

$stmt->close();
$conn->close();
?>
