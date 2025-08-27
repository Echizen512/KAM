<?php
try {
    // Captura todos los datos enviados desde el formulario
    $nombre = filter_input(INPUT_POST, 'nombre_personal', FILTER_SANITIZE_STRING);
    $apellido = filter_input(INPUT_POST, 'apellido_personal', FILTER_SANITIZE_STRING);
    $nacionalidad = filter_input(INPUT_POST, 'nacionalidad', FILTER_SANITIZE_STRING);
    $cedula = filter_input(INPUT_POST, 'cedula_personal', FILTER_SANITIZE_STRING);
    $titulo = filter_input(INPUT_POST, 'titulo_personal', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo_personal', FILTER_SANITIZE_EMAIL);
    $nacimiento = filter_input(INPUT_POST, 'nacimiento_personal', FILTER_SANITIZE_STRING);
    $ingreso = filter_input(INPUT_POST, 'ingreso_personal', FILTER_SANITIZE_STRING);
    $cargo = filter_input(INPUT_POST, 'cargo_personal', FILTER_SANITIZE_STRING);

    // Validación de parámetros obligatorios
    if (!$nombre || !$apellido || !$nacionalidad || !$cedula || !$correo || !$cargo || !$nacimiento || !$ingreso) {
        echo json_encode([
            "error" => "Faltan datos obligatorios para el registro."
        ]);
        exit;
    }

    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "base_kam");

    if ($conn->connect_error) {
        echo json_encode([
            "error" => "Error de conexión a la base de datos: " . $conn->connect_error
        ]);
        exit;
    }

    // Inserción de los datos del usuario en la tabla `persona`
    $sqlInsert = "INSERT INTO persona (nombre_personal, apellido_personal, nacionalidad_personal, cedula_personal, 
        titulo_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);

    if (!$stmtInsert) {
        echo json_encode([
            "error" => "Error al preparar la consulta para insertar al usuario: " . $conn->error
        ]);
        exit;
    }

    $stmtInsert->bind_param(
        "sssssssss",
        $nombre,
        $apellido,
        $nacionalidad,
        $cedula,
        $titulo,
        $correo,
        $nacimiento,
        $ingreso,
        $cargo
    );

    if ($stmtInsert->execute()) {
        $id_personal = $conn->insert_id; // ID único generado automáticamente
    } else {
        echo json_encode([
            "error" => "Error al registrar el usuario: " . $stmtInsert->error
        ]);
        $stmtInsert->close();
        $conn->close();
        exit;
    }

    $stmtInsert->close();

    // Captura de la huella digital
    $output = [];
    $returnCode = 0;

    exec('java -jar C:\\xampp\\htdocs\\html\\src\\Huella.jar', $output, $returnCode);

    if ($returnCode !== 0) {
        echo json_encode([
            "error" => "No se pudo ejecutar el archivo JAR.",
            "detalle" => implode("\n", $output)
        ]);
        exit;
    }

    $base64Template = $output[0] ?? null;

    if (!$base64Template) {
        echo json_encode([
            "error" => "La huella no se capturó correctamente."
        ]);
        exit;
    }

    // Actualización del registro con la huella digital
    $sqlUpdate = "UPDATE persona SET huella_dactilar = ? WHERE id_personal = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);

    if (!$stmtUpdate) {
        echo json_encode([
            "error" => "Error al preparar la consulta para actualizar la huella: " . $conn->error
        ]);
        exit;
    }

    $stmtUpdate->bind_param("si", $base64Template, $id_personal);

    if ($stmtUpdate->execute()) {
        echo json_encode([
            "success" => "Usuario y huella registrados exitosamente.",
            "id_personal" => $id_personal
        ]);
    } else {
        echo json_encode([
            "error" => "Error al registrar la huella: " . $stmtUpdate->error
        ]);
    }

    $stmtUpdate->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        "error" => "Excepción capturada: " . $e->getMessage()
    ]);
}
?>
