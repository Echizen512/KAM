<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "base_kam";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar la actualización al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {

    $id = $_POST['id_personal'];
    $nombre = $_POST['nombre_personal'];
    $apellido = $_POST['apellido_personal'];
    $cedula = $_POST['cedula_personal'];
    $correo = $_POST['correo_personal'];
    $nacimiento = $_POST['nacimiento_personal'];
    $ingreso = $_POST['ingreso_personal'];
    $cargo = $_POST['cargo_personal'];

    $sql = "UPDATE persona SET 
                nombre_personal='$nombre', 
                apellido_personal='$apellido', 
                cedula_personal='$cedula', 
                correo_personal='$correo', 
                nacimiento_personal='$nacimiento', 
                ingreso_personal='$ingreso', 
                cargo_personal='$cargo' 
            WHERE id_personal=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Cambios actualizados correctamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
              </script>";
    }
}

// Consultar los datos para llenar la tabla
$sql = "SELECT id_personal, nombre_personal, apellido_personal, cedula_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal FROM persona";
$result = $conn->query($sql);
?>