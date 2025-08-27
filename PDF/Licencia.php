<?php
require('fpdf186/fpdf.php');

// Conectar a tu base de datos
$conn = new mysqli("localhost", "root", "", "base_kam");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del personal y las fechas de permiso vacacional
$id_personal = $_GET['id'];
$fecha_inicio = $_GET['fechaInicio'];
$fecha_fin = $_GET['fechaFin'];
$motivo = $_GET['motivo'];
$nombre_autoridad = $_GET['nombreAutoridad'];
$cedula_autoriza = $_GET['cedulaAutoriza'];
$condicion_permiso = $_GET['condicionPermiso'];
$sql = "SELECT * FROM persona WHERE id_personal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_personal);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre_personal = $row['nombre_personal'];
    $apellido_personal = $row['apellido_personal'];
    $cedula_personal = $row['cedula_personal'];
    $cargo_personal = $row['cargo_personal'];
} else {
    die("No se encontró el registro.");
}
$stmt->close();
$conn->close();

// Crear el PDF
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
        $this->Cell(0, 10, utf8_decode('La Victoria-Estado Aragua'), 0, 1, 'C');
        $this->Ln(20);
        $this->Cell(0, 10, utf8_decode('Permiso de Licencia'), 0, 1, 'C');
        $this->Ln(15);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$texto = "Por medio de la presente Yo $nombre_personal $apellido_personal, C.I. $cedula_personal, quien trabaja en esta institución como: $cargo_personal, deseo pedir permiso para ausentarme de la institución en los días $fecha_inicio por motivo de: $motivo. Condición de permiso: $condicion_permiso. Hasta los días: $fecha_fin.Autorizado por: $nombre_autoridad C.I: $cedula_autoriza.";
$pdf->MultiCell(0, 10, utf8_decode($texto));

// Insert a blank space of 20 lines
$pdf->Ln(30);

// Create signature fields using display flex concept in PHP
// Container
$pdf->Ln(15);
$pdf->Cell(0, 10, '', 0, 1); // Blank line to start new flex container

// Flex item - left
$pdf->Cell(90, 10, 'Firma del Solicitante:', 0, 0, 'C');
$pdf->Cell(90, 10, 'Firma de la Autoridad:', 0, 1, 'C');

// Flex item - middle
$pdf->Cell(90, 10, '_________________________', 0, 0, 'C');
$pdf->Cell(90, 10, '_________________________', 0, 1, 'C');

// Flex item - right
$pdf->Cell(90, 10, $nombre_personal . ' ' . $apellido_personal, 0, 0, 'C');
$pdf->Cell(90, 10, 'Nombre de la Autoridad: ' . $nombre_autoridad, 0, 1, 'C');

// Cedula
$pdf->Ln(1);
$pdf->Cell(90, 10, '', 0, 0); // Blank cell to align
$pdf->Cell(90, 10, utf8_decode('Cédula:') . $cedula_autoriza, 0, 1, 'C');

// Add "Sello" text at the bottom left
$pdf->Cell(0, 10, "Sello", 0, 0, 'C');

$pdf->Output('D', 'Permiso_Licencia.pdf');
?>
