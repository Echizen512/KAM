<?php
require('fpdf186/fpdf.php');

class PDF extends FPDF {
    // Encabezado de la página
    function Header() {
        // Logo de la izquierda
        $x_logo_izq = 10;
        $y_logo_izq = 0;
        $ancho_logo_izq = 40;
        $alto_logo_izq = 45;

        if (file_exists('../Assets/Images/mio.jpeg')) {
            $this->Image('../Assets/Images/mio.jpeg', $x_logo_izq, $y_logo_izq, $ancho_logo_izq, $alto_logo_izq);
        }

        // Logo de la derecha
        $x_logo_der = 155;
        $y_logo_der = 12;
        $ancho_logo_der = 35;
        $alto_logo_der = 20;

        if (file_exists('../Assets/Images/Edupalcubo.png')) {
            $this->Image('../Assets/Images/Edupalcubo.png', $x_logo_der, $y_logo_der, $ancho_logo_der, $alto_logo_der);
        }

        // Encabezado
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode('República Bolivariana de Venezuela'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Ministerio del Poder Popular para la Educación'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('U.E.P Colegio Edupal'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('La Victoria-Estado Aragua'), 0, 1, 'C');
        $this->Ln(10);
        $this->Cell(0, 10, utf8_decode('Información Personal'), 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_kam";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id_personal'])) {
    $id_personal = intval($_GET['id_personal']);
    
    $sql = "SELECT nombre_personal, apellido_personal, cedula_personal, correo_personal, nacimiento_personal, ingreso_personal, cargo_personal 
            FROM persona 
            WHERE id_personal = $id_personal";
    
    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Formatear fechas si son válidas
        foreach (['nacimiento_personal', 'ingreso_personal'] as $fechaCampo) {
            if (!empty($row[$fechaCampo]) && strtotime($row[$fechaCampo])) {
                $row[$fechaCampo] = date('d/m/Y', strtotime($row[$fechaCampo]));
            }
        }

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Contenido de la tabla
        foreach ($row as $key => $value) {
            $label = ucfirst(str_replace("_personal", "", $key));
            $pdf->Cell(50, 10, utf8_decode($label . ':'), 1);
            $pdf->Cell(0, 10, utf8_decode($value), 1, 1);
        }

        $pdf->Output('D', 'informacion_personal_' . $id_personal . '.pdf');
    } else {
        echo "No se encontraron datos.";
    }
}
$conn->close();
?>
