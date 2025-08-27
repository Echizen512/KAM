<?php
    if (!extension_loaded('pdo_mysql')) {
        die("La extensión pdo_mysql no está instalada.");
    }

    try {
        $db = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8", "root", "");

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al conectar con la base de datos: " . $e->getMessage());
    }
?>
