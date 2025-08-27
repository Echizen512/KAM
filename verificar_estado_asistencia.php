<?php
$cedula = $_GET['cedula'] ?? null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => "Conexión fallida: " . $conn->connect_error]));
}

$response = ['exists' => false, 'estado' => ''];

if ($cedula) {
    $sql = "SELECT hora_entrada, hora_salida FROM asistencia WHERE cedula_personal = ? AND DATE(hora_entrada) = CURDATE()";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response['exists'] = true;
            if ($row['hora_entrada'] !== null && $row['hora_salida'] === null) {
                $response['estado'] = 'entrada';
            } elseif ($row['hora_salida'] !== null) {
                $response['estado'] = 'salida';
            }
        }
        $stmt->close();
    } else {
        $response['error'] = "Error en la preparación de la consulta: " . $conn->error;
    }
} else {
    $response['error'] = "No se proporcionó cédula.";
}

$conn->close();
echo json_encode($response);
?>
