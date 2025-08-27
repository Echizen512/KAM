<?php
$host = "localhost";
$dbname = "base_kam";
$username = "root";
$password = "";

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT id, usuario, correo, nivel_usuario FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            echo $usuario ? json_encode($usuario) : json_encode(['error' => 'Usuario no encontrado']);
        } else {
            $sql = "SELECT id, usuario, nivel_usuario, correo FROM usuarios";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener usuarios: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $putData = json_decode(file_get_contents("php://input"), true);

    if (isset($putData['usuario'], $putData['correo'], $putData['nivel_usuario'], $_GET['id'])) {
        try {
            $id = intval($_GET['id']);
            $sql = "UPDATE usuarios SET usuario = :usuario, correo = :correo, nivel_usuario = :nivel_usuario WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':usuario' => $putData['usuario'],
                ':correo' => $putData['correo'],
                ':nivel_usuario' => $putData['nivel_usuario']
            ]);

            echo json_encode(['mensaje' => 'Usuario actualizado correctamente']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        try {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Usuario eliminado correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo eliminar el usuario']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado para eliminar']);
    }
} else {
    // Manejo de métodos HTTP no permitidos
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

// Finalizar conexión
$conn = null;
?>