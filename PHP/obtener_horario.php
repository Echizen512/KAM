<?php
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$id = $_GET['id'] ?? null;
if (!$id) exit;

$stmt = $pdo->prepare("SELECT id, cedula, materia_id, tipo, total_horas FROM horarios WHERE id = ?");
$stmt->execute([$id]);
echo json_encode($stmt->fetch());
