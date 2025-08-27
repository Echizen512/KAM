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

// Consultar horarios
$sql = "
    SELECT 
        m.nombre AS materia,
        h.tipo,
        COALESCE(bp.dia, bc.dia) AS dia,
        COALESCE(bp.hora, bc.bloque_hora) AS bloque,
        COALESCE(bp.nivel, bc.nivel) AS nivel,
        COALESCE(bp.seccion, bc.seccion) AS seccion
    FROM horarios h
    JOIN materias m ON m.id = h.materia_id
    LEFT JOIN bloques_parcial bp ON bp.horario_id = h.id
    LEFT JOIN bloques_completo bc ON bc.horario_id = h.id
    WHERE h.cedula = ?
    ORDER BY dia, bloque
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

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(25, 8, utf8_decode('Día'), 1, 0, 'C', true);
$pdf->Cell(40, 8, utf8_decode('Bloque'), 1, 0, 'C', true);
$pdf->Cell(65, 8, utf8_decode('Materia'), 1, 0, 'C', true);
$pdf->Cell(35, 8, utf8_decode('Nivel'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('Sección'), 1, 1, 'C', true);

// Filas de horarios
$pdf->SetFont('Arial', '', 10);
while ($row = $horarios->fetch_assoc()) {
    $pdf->Cell(25, 8, utf8_decode($row['dia']), 1);
    $pdf->Cell(40, 8, utf8_decode($row['bloque']), 1);
    $pdf->Cell(65, 8, utf8_decode($row['materia']), 1);
    $pdf->Cell(35, 8, utf8_decode($row['nivel']), 1);
    $pdf->Cell(30, 8, utf8_decode($row['seccion']), 1);
    $pdf->Ln();
}

$pdf->Output('I', 'Horario_Profesor.pdf');
?>
