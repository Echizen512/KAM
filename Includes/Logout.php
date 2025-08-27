<?php
session_start();
require '../DataBase/conexion.php'; // Este archivo debe definir $db como instancia PDO

if (isset($_POST['confirm_logout']) && $_POST['confirm_logout'] === 'yes') {
    // Obtener datos de sesión
    $usuario_id = $_SESSION['usuario_id'] ?? null;
    $usuario = $_SESSION['usuario'] ?? '';
    $correo = $_SESSION['correo'] ?? '';
    $fecha_hora = date("Y-m-d H:i:s");
    $accion = 'Cierre de sesión';

    if ($usuario_id !== null) {
        try {
            $stmt = $db->prepare("INSERT INTO bitacora (usuario_id, nombre_usuario, correo, accion, fecha_hora) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $usuario, $correo, $accion, $fecha_hora]);
        } catch (PDOException $e) {
            echo "Error al registrar en la bitácora: " . $e->getMessage();
        }
    }

    // Cerrar sesión
    session_unset();
    session_destroy();

    header("Location: ../Login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Mostrar mensaje de confirmación con SweetAlert2
        Swal.fire({
            title: '¿Está seguro de cerrar su sesión?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar formulario para confirmar cierre de sesión
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'logout.php';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'confirm_logout';
                input.value = 'yes';
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                // Cancelar acción y redirigir a Inicio.php
                window.location.href = 'Inicio.php';
            }
        });
    </script>
</body>
</html>
