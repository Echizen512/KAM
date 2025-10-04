<?php
require('fpdf186/fpdf.php');

// Función para manejar errores
function exitWithError($message) {
    echo "<strong>Error:</strong> " . htmlspecialchars($message);
    exit;
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "base_kam");
if ($conn->connect_error) {
    exitWithError("Conexión fallida: " . $conn->connect_error);
}

// Obtener cédula del profesor
$cedula = $_POST['cedula'] ?? null;
if (!$cedula) {
    exitWithError("No se recibió la cédula.");
}

// Consultar datos del docente
$stmt = $conn->prepare("SELECT nombre_personal, apellido_personal, cedula_personal, cargo_personal FROM persona WHERE cedula_personal = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    exitWithError("No se encontró el docente.");
}
$docente = $result->fetch_assoc();
$stmt->close();

// Consultar total de horas por tipo
$stmt = $conn->prepare("SELECT tipo, total_horas FROM horarios WHERE cedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$horas = $stmt->get_result();
$tipos_horario = [];
while ($row = $horas->fetch_assoc()) {
    $tipo_legible = ($row['tipo'] === 'tiempo_completo') ? 'Completo' : ucfirst($row['tipo']);
    $tipos_horario[] = [
        'tipo' => $tipo_legible,
        'total_horas' => $row['total_horas']
    ];
}
$stmt->close();

// Consultar todos los bloques (parcial y completo)
$sql = "
    SELECT 
        h.tipo,
        COALESCE(bp.dia, bc.dia) AS dia,
        COALESCE(bp.hora, bc.bloque_hora) AS bloque,
        COALESCE(m.nombre, 'Sin asignar') AS materia,
        COALESCE(bp.nivel, bc.nivel) AS nivel,
        COALESCE(bp.seccion, bc.seccion) AS seccion
    FROM horarios h
    LEFT JOIN bloques_parcial bp ON bp.horario_id = h.id
    LEFT JOIN bloques_completo bc ON bc.horario_id = h.id
    LEFT JOIN materias m ON m.id = COALESCE(bp.materia_id, bc.materia_id)
    WHERE h.cedula = ?
    ORDER BY 
        FIELD(COALESCE(bp.dia, bc.dia), 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
        bloque
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$horarios = $stmt->get_result();
$stmt->close();
$conn->close();

// Clase PDF personalizada
class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../Assets/Images/mio.jpeg', 10, 0, 40, 45);
        $this->Image('../Assets/Images/Edupalcubo.png', 155, 12, 35, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Ministerio del Poder Popular para la Educación'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('U.E.P Colegio Edupal'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('La Victoria - Estado Aragua'), 0, 1, 'C');
        $this->Ln(20);
        $this->SetFont('Arial', 'BU', 14);
        $this->Cell(0, 10, utf8_decode('Horario Académico del Docente'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Datos del docente
$pdf->Cell(0, 10, utf8_decode("Nombre: {$docente['nombre_personal']} {$docente['apellido_personal']}"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Cédula: {$docente['cedula_personal']}"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Cargo: {$docente['cargo_personal']}"), 0, 1);
$pdf->Ln(5);

$pdf->Ln(5);

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(25, 8, utf8_decode('Tipo'), 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode('Día'), 1, 0, 'C', true);
$pdf->Cell(35, 8, utf8_decode('Bloque'), 1, 0, 'C', true);
$pdf->Cell(60, 8, utf8_decode('Materia'), 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode('Nivel'), 1, 0, 'C', true);
$pdf->Cell(20, 8, utf8_decode('Sección'), 1, 1, 'C', true);

// Filas de horarios
$pdf->SetFont('Arial', '', 10);
while ($row = $horarios->fetch_assoc()) {
    $tipo_legible = ($row['tipo'] === 'tiempo_completo') ? 'Completo' : ucfirst($row['tipo']);
    $pdf->Cell(25, 8, utf8_decode($tipo_legible), 1);
    $pdf->Cell(25, 8, utf8_decode($row['dia']), 1);
    $pdf->Cell(35, 8, utf8_decode($row['bloque']), 1);
    $pdf->Cell(60, 8, utf8_decode($row['materia']), 1);
    $pdf->Cell(25, 8, utf8_decode($row['nivel']), 1);
    $pdf->Cell(20, 8, utf8_decode($row['seccion']), 1);
    $pdf->Ln();
}

$pdf->Output('D', 'Horario_Profesor.pdf');

?>
