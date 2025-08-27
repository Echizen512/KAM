<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_personal = $_POST['nombre_personal'];
    $apellido_personal = $_POST['apellido_personal'];
    $cedula_personal = $_POST['cedula_personal'];
    $correo_personal = $_POST['correo_personal'];
    $nacimiento_personal = $_POST['nacimiento_personal'];
    $ingreso_personal = $_POST['ingreso_personal'];
    $cargo_personal = $_POST['cargo_personal'];

    $conn = new mysqli("localhost", "root", "", "base_kam");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO persona (nombre_personal, apellido_personal, cedula_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error al preparar la sentencia: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $nombre_personal, $apellido_personal, $cedula_personal, $correo_personal, $nacimiento_personal, $ingreso_personal, $cargo_personal);

    $stmt->execute();
    $stmt->store_result();
    $stmt->close();
    $conn->close();

    // Redirection to editar.php
    header("Location: PERSONAL.php");
    exit();
}
?>
