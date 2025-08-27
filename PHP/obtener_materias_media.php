<?php
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo json_encode(
  $pdo->query("SELECT id, nombre FROM materias WHERE nivel IN (3, 5, 6)")->fetchAll()
);
