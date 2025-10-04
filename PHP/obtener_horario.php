<?php
$pdo = new PDO("mysql:host=localhost;dbname=base_kam;charset=utf8mb4", "root", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Primero obtienes el tipo
$stmt = $pdo->prepare("SELECT tipo FROM horarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$tipo = $stmt->fetchColumn();

if ($tipo === "parcial") {
  $sql = "
    SELECT h.id AS horario_id, p.nombre_personal AS docente, p.cedula_personal AS cedula,
           h.tipo, h.total_horas, bp.materia_id, bp.dia, bp.hora AS bloque,
           bp.nivel, bp.seccion
    FROM horarios h
    JOIN persona p ON p.cedula_personal = h.cedula
    JOIN bloques_parcial bp ON bp.horario_id = h.id
    WHERE h.id = :id
  ";
} else {
  $sql = "
    SELECT h.id AS horario_id, p.nombre_personal AS docente, p.cedula_personal AS cedula,
           h.tipo, h.total_horas, bc.materia_id, bc.dia, bc.bloque_hora AS bloque,
           bc.nivel, bc.seccion
    FROM horarios h
    JOIN persona p ON p.cedula_personal = h.cedula
    JOIN bloques_completo bc ON bc.horario_id = h.id
    WHERE h.id = :id
  ";
}

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$resultados = $stmt->fetchAll();

if ($resultados) {
  // Construimos la respuesta estructurada
  $horario = [
    "horario_id" => $resultados[0]["horario_id"],
    "docente"    => $resultados[0]["docente"],
    "cedula"     => $resultados[0]["cedula"],
    "tipo"       => $resultados[0]["tipo"],
    "total_horas"=> $resultados[0]["total_horas"],
    "bloques"    => []
  ];

  foreach ($resultados as $fila) {
    if ($fila["dia"] && $fila["bloque"]) {
      $horario["bloques"][] = [
        "materia_id" => $fila["materia_id"],
        "dia"        => $fila["dia"],
        "hora"       => $fila["bloque"],   // 👈 ahora SIEMPRE existe
        "nivel"      => $fila["nivel"],
        "seccion"    => $fila["seccion"]
      ];
    }
  }

  echo json_encode($horario);
} else {
  echo json_encode(["error" => "Horario no encontrado"]);
}
