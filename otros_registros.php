<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // Cambia esto según tu configuración
$dbname = "base_kam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se recibió la cédula
if (isset($_GET['cedula'])) {
    $cedula = $conn->real_escape_string($_GET['cedula']);

    // Consulta para obtener los registros de asistencia
    $sql = "SELECT fecha_asistencia, hora_entrada, hora_salida 
            FROM asistencia 
            WHERE cedula_personal = '$cedula' 
            ORDER BY fecha_asistencia DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $registros = [];
        while ($row = $result->fetch_assoc()) {
            $registros[] = $row;
        }
        // Retornar los datos como JSON
        echo json_encode($registros);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
