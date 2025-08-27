<?php
    session_start();

    $error = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];

        $conn = new mysqli("localhost", "root", "", "base_kam");

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, contrasena FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($contrasena, $hashed_password)) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id'] = $user_id;

                $roles_stmt = $conn->prepare("
                    SELECT r.rol FROM roles r
                    JOIN usuario_roles ur ON r.id = ur.rol_id
                    WHERE ur.usuario_id = ?
                ");
                $roles_stmt->bind_param("i", $user_id);
                $roles_stmt->execute();
                $roles_stmt->bind_result($rol);

                $_SESSION['roles'] = [];
                while ($roles_stmt->fetch()) {
                    $_SESSION['roles'][] = $rol;
                }
                $roles_stmt->close();
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }

        $stmt->close();
        $conn->close();
    }
?>
