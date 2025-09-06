<?php
require('fpdf186/fpdf.php');

// Zona horaria
date_default_timezone_set('America/Caracas');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
} catch (Exception $e) {
    exit("Error de conexión: " . $e->getMessage());
}

// Clase PDF personalizada
class PDF extends FPDF {
    function Header() {
        if (file_exists('../Assets/Images/mio.jpeg')) {
            $this->Image('../Assets/Images/mio.jpeg', 40, 0, 40, 45);
        }
        if (file_exists('../Assets/Images/Edupalcubo.png')) {
            $this->Image('../Assets/Images/Edupalcubo.png', 215, 12, 35, 20);
        }
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Ministerio del Poder Popular para la Educación'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('U.E.P Colegio Edupal'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('La Victoria - Estado Aragua'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function AddGeneralInfo($nombre, $apellido, $cedula, $tipoReporte, $fechaInicio = '', $fechaFin = '') {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode("Registro $tipoReporte de: $nombre $apellido - Cédula: $cedula"), 0, 1, 'C');
        if ($tipoReporte === 'General' && $fechaInicio && $fechaFin) {
            $this->Cell(0, 10, utf8_decode("Rango de fechas: Desde $fechaInicio Hasta $fechaFin"), 0, 1, 'C');
        }
        $this->Ln(10);
    }

    function AddTableHeader() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(35, 120, 182);
        $this->SetTextColor(255);
        $this->SetX(30);
        $this->Cell(80, 10, 'Fecha', 1, 0, 'C', true);
        $this->Cell(80, 10, 'Entrada', 1, 0, 'C', true);
        $this->Cell(80, 10, 'Salida', 1, 1, 'C', true);
    }

    function AddTableRow($asistencia) {
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0);
        $this->SetX(30);

        // Formateo de fecha: dd/mm/yyyy
        $fechaFormateada = date("d/m/Y", strtotime($asistencia['fecha_asistencia']));

        $this->Cell(80, 10, utf8_decode($fechaFormateada), 1, 0, 'C');
        $this->Cell(80, 10, date("h:i A", strtotime($asistencia['hora_entrada'])), 1, 0, 'C');
        $this->Cell(80, 10, date("h:i A", strtotime($asistencia['hora_salida'])), 1, 1, 'C');
    }

}

// Validación de parámetros
$tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
$cedula = filter_input(INPUT_GET, 'cedula', FILTER_SANITIZE_STRING);

if (!$tipo || !$cedula) {
    exit("Parámetros faltantes.");
}

$params = [];
$sql = "";

if ($tipo === 'diario') {
    $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING) ?? date("Y-m-d");
    $sql = "SELECT p.nombre_personal, p.apellido_personal, p.cedula_personal, a.fecha_asistencia, a.hora_entrada, a.hora_salida 
            FROM persona p 
            INNER JOIN asistencia a ON p.cedula_personal = a.cedula_personal 
            WHERE p.cedula_personal = ? AND a.fecha_asistencia = ?";
    $params = [$cedula, $fecha];
} elseif ($tipo === 'general') {
    $fechaInicio = filter_input(INPUT_GET, 'fechaInicio', FILTER_SANITIZE_STRING);
    $fechaFin = filter_input(INPUT_GET, 'fechaFin', FILTER_SANITIZE_STRING);
    if (!$fechaInicio || !$fechaFin) {
        exit("Faltan los parámetros de rango de fechas.");
    }
    $sql = "SELECT p.nombre_personal, p.apellido_personal, p.cedula_personal, a.fecha_asistencia, a.hora_entrada, a.hora_salida 
            FROM persona p 
            INNER JOIN asistencia a ON p.cedula_personal = a.cedula_personal 
            WHERE p.cedula_personal = ? AND a.fecha_asistencia BETWEEN ? AND ?";
    $params = [$cedula, $fechaInicio, $fechaFin];
} else {
    exit("Tipo de reporte inválido.");
}

// Consulta preparada
try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $asistencias = $result->fetch_all(MYSQLI_ASSOC);

    if (count($asistencias) > 0) {
        $pdf = new PDF();
        $pdf->AddPage('L');
        $pdf->SetLeftMargin(15);
        $pdf->AddGeneralInfo(
            $asistencias[0]['nombre_personal'],
            $asistencias[0]['apellido_personal'],
            $asistencias[0]['cedula_personal'],
            ucfirst($tipo),
            $fechaInicio ?? '',
            $fechaFin ?? ''
        );
        $pdf->AddTableHeader();

        foreach ($asistencias as $row) {
            $pdf->AddTableRow($row);
        }

        $fileName = "reporte_{$tipo}_{$cedula}.pdf";
        $pdf->Output('D', $fileName); // Fuerza descarga y muestra diálogo "Guardar como"
        exit;
    } else {
        exit("No se encontraron registros de asistencia.");
    }
} catch (Exception $e) {
    exit("Error en la consulta: " . $e->getMessage());
}

$conn->close();
?>
