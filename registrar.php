<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Leer el cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);

    // Extraer los campos
    $usuario = $input['usuario'] ?? '';
    $nivel_usuario = $input['nivel_usuario'] ?? '';
    $correo = $input['correo'] ?? '';
    $contrasena = $input['contrasena'] ?? '';
    $confirmar_contrasena = $input['confirmar_contrasena'] ?? '';

    $response = [];

    // Validación básica
    if (!$usuario || !$nivel_usuario || !$correo || !$contrasena || !$confirmar_contrasena) {
        $response['status'] = 'error';
        $response['message'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit;
    }

    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "base_kam");

    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Conexión fallida: ' . $conn->connect_error;
    } else {
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'El usuario ya existe. Intente con otro.';
        } else {
            if ($contrasena !== $confirmar_contrasena) {
                $response['status'] = 'error';
                $response['message'] = 'Las contraseñas no coinciden.';
            } else {
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuarios (usuario, nivel_usuario, correo, contrasena) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $usuario, $nivel_usuario, $correo, $hashed_password);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Usuario registrado correctamente.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al registrar: ' . $stmt->error;
                }
                $stmt->close();
            }
        }

        $conn->close();
    }

    echo json_encode($response);
    exit;
}
?>
