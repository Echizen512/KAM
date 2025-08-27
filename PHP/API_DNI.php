<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'])) {
    $cedula = trim($_POST['cedula']);

    $conn = new mysqli("localhost", "root", "", "base_kam");
    if ($conn->connect_error) {
        echo json_encode(['encontrado' => false, 'mensaje' => 'Error de conexión']);
        exit;
    }

    $stmt = $conn->prepare("SELECT nombre, apellido, cargo FROM persona WHERE cedula_personal = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'encontrado' => true,
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'cargo' => $row['cargo']
        ]);
    } else {
        echo json_encode(['encontrado' => false, 'mensaje' => 'Cédula no encontrada']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['encontrado' => false, 'mensaje' => 'Solicitud inválida']);
}
?>
