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

// Obtener y sanitizar la cédula
$cedula = filter_input(INPUT_GET, 'cedula', FILTER_SANITIZE_STRING);
$fechaActual = date('Y-m-d');

if (!$cedula) {
    echo json_encode(['success' => false, 'error' => 'Parámetro cédula no válido']);
    exit;
}

// Verificar si ya se registró entrada en el día actual
$sql = "SELECT hora_entrada, hora_salida FROM asistencia WHERE cedula_personal = ? AND fecha_asistencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cedula, $fechaActual);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['hora_entrada'] && $row['hora_salida']) {
        // Si ya registró entrada y salida
        echo json_encode([
            'success' => false,
            'estado' => 'asistencia_completa',
            'message' => 'Este usuario ya culminó su registro de asistencia diario.'
        ]);
    } elseif ($row['hora_entrada']) {
        // Si ya registró solo la entrada
        echo json_encode([
            'success' => false,
            'estado' => 'entrada_ya_registrada',
            'message' => 'Ya has registrado tu hora de entrada para hoy. Falta registrar la salida.'
        ]);
    } else {
        // Si el registro es inconsistente
        echo json_encode([
            'success' => false,
            'estado' => 'registro_incompleto',
            'message' => 'Hay un registro incompleto. Por favor, verifica los datos.'
        ]);
    }
} else {
    // No hay registros para este día
    echo json_encode([
        'success' => true,
        'estado' => 'sin_registro',
        'message' => 'No hay registros previos para este usuario hoy. Puede proceder a registrar su entrada.'
    ]);
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
