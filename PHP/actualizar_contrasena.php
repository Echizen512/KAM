<?php
$conn = new mysqli("localhost", "root", "", "base_kam");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $token = $_POST['token'];

    if (!$correo || !$nueva_contrasena || !$token) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos requeridos.']);
        exit;
    }

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT expiracion_token FROM usuarios WHERE correo = ? AND token_recuperacion = ?");
    $stmt->bind_param("ss", $correo, $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($expiracion_token);
        $stmt->fetch();

        if (strtotime($expiracion_token) > time()) {
            $hashed_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);

            $stmt2 = $conn->prepare("UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL, expiracion_token = NULL WHERE correo = ?");
            $stmt2->bind_param("ss", $hashed_password, $correo);
            $stmt2->execute();

            echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El token ha expirado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'El token es inválido o no coincide.']);
    }
}
?>
