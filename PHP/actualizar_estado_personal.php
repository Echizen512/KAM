<?php
header('Content-Type: application/json');

try {
    // Conexión PDO
    $pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Validar entrada
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $activo = isset($_POST['activo']) ? intval($_POST['activo']) : null;

    if ($id === null || !in_array($activo, [0, 1])) {
        throw new Exception("Datos inválidos.");
    }

    // Actualizar estado
    $stmt = $pdo->prepare("UPDATE persona SET activo = ? WHERE id_personal = ?");
    $stmt->execute([$activo, $id]);

    echo json_encode([
        "success" => true,
        "message" => $activo ? "Personal activado correctamente." : "Personal desactivado correctamente."
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>