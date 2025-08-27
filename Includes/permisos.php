<?php
// Función para verificar acceso de un usuario a un módulo
function verificar_acceso($nivel_usuario, $modulo, $db) {
    $query = "SELECT permitido FROM permisos_modulos WHERE nivel_usuario = :nivel_usuario AND modulo = :modulo";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nivel_usuario', $nivel_usuario);
    $stmt->bindParam(':modulo', $modulo);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado && $resultado['permitido'] == 1; // Retorna true si el permiso existe y está permitido
}
?>
