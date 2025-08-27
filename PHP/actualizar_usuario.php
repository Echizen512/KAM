<?php
$conn = new mysqli("localhost", "root", "", "base_kam");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
    $nuevo_usuario = $_POST['nuevo_usuario'];

    if (!$correo || !$nuevo_usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos requeridos.']);
        exit;
    }

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE usuarios SET usuario = ? WHERE correo = ?");
    $stmt->bind_param("ss", $nuevo_usuario, $correo);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el usuario.']);
    }
}
?>
