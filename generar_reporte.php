<?php
require('fpdf181/fpdf.php');

// Configuración de la zona horaria
date_default_timezone_set('America/Caracas'); // Aseguramos que usa la zona horaria correcta

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Error en la conexión: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die(json_encode(['exito' => false, 'mensaje' => $e->getMessage()]));
}

class PDF extends FPDF {
    function Header() {
        if (file_exists('mio.jpeg')) {
            $this->Image('mio.jpeg', 40, 0, 40, 45);
        }
        if (file_exists('Edupalcubo.png')) {
            $this->Image('Edupalcubo.png', 215, 12, 35, 20);
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
        $textoGeneral = utf8_decode("Registro $tipoReporte de: $nombre $apellido - Cédula: $cedula");
        $this->Cell(0, 10, $textoGeneral, 0, 1, 'C');

        if ($tipoReporte === 'General' && $fechaInicio && $fechaFin) {
            $rangoFechas = utf8_decode("Rango de fechas: Desde $fechaInicio Hasta $fechaFin");
            $this->Cell(0, 10, $rangoFechas, 0, 1, 'C');
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
        $this->SetTextColor(0, 0, 0);
        $fecha = utf8_decode($asistencia['fecha_asistencia']);
        $entrada = date("h:i A", strtotime($asistencia['hora_entrada']));
        $salida = date("h:i A", strtotime($asistencia['hora_salida']));
        $this->SetX(30);
        $this->Cell(80, 10, $fecha, 1, 0, 'C');
        $this->Cell(80, 10, $entrada, 1, 0, 'C');
        $this->Cell(80, 10, $salida, 1, 1, 'C');
    }
}

// Procesamiento de reportes
if (isset($_GET['tipo']) && isset($_GET['cedula'])) {
    $tipo = htmlspecialchars($_GET['tipo']);
    $cedula = htmlspecialchars($_GET['cedula']);

    if ($tipo === 'diario') {
        $fecha = isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : date("Y-m-d");
        $sql = "SELECT p.nombre_personal, p.apellido_personal, p.cedula_personal, a.fecha_asistencia, a.hora_entrada, a.hora_salida 
                FROM persona p 
                INNER JOIN asistencia a ON p.cedula_personal = a.cedula_personal 
                WHERE p.cedula_personal = ? AND a.fecha_asistencia = ?";
        $params = [$cedula, $fecha];
    } elseif ($tipo === 'general') {
        if (isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
            $fechaInicio = htmlspecialchars($_GET['fechaInicio']);
            $fechaFin = htmlspecialchars($_GET['fechaFin']);
            $sql = "SELECT p.nombre_personal, p.apellido_personal, p.cedula_personal, a.fecha_asistencia, a.hora_entrada, a.hora_salida 
                    FROM persona p 
                    INNER JOIN asistencia a ON p.cedula_personal = a.cedula_personal 
                    WHERE p.cedula_personal = ? AND a.fecha_asistencia BETWEEN ? AND ?";
            $params = [$cedula, $fechaInicio, $fechaFin];
        } else {
            die(json_encode(['exito' => false, 'mensaje' => 'Faltan los parámetros de rango de fechas.']));
        }
    } else {
        die(json_encode(['exito' => false, 'mensaje' => 'Tipo de reporte inválido.']));
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die(json_encode(['exito' => false, 'mensaje' => 'Error al preparar la consulta.']));
    }

    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $asistencia_general = $result->fetch_assoc();
        $pdf = new PDF();
        $pdf->AddPage('L');
        $pdf->SetLeftMargin(15);
        $tipoReporte = $tipo === 'diario' ? 'Diario' : 'General';
        $fechaInicio = $_GET['fechaInicio'] ?? '';
        $fechaFin = $_GET['fechaFin'] ?? '';
        $pdf->AddGeneralInfo(
            $asistencia_general['nombre_personal'],
            $asistencia_general['apellido_personal'],
            $asistencia_general['cedula_personal'],
            $tipoReporte,
            $fechaInicio,
            $fechaFin
        );
        $pdf->AddTableHeader();

        do {
            $pdf->AddTableRow($asistencia_general);
        } while ($asistencia_general = $result->fetch_assoc());

        $fileName = "reporte_{$tipo}_{$cedula}.pdf";
        $pdf->Output('F', $fileName);
        echo json_encode(['exito' => true, 'mensaje' => 'Reporte generado correctamente.', 'url_descarga' => $fileName]);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'No se encontraron registros de asistencia.']);
    }
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Parámetros faltantes.']);
}

$conn->close();
?>
