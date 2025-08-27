<?php
require('../PDF/fpdf186/fpdf.php');

// Conectar a tu base de datos
$conn = new mysqli("localhost", "root", "", "base_kam");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del personal y las fechas de permiso vacacional
$id_personal = $_GET['id'];
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

// Valores predefinidos para las variables

$fecha_inicio = "01/03/2025";
$motivo = "Reposo";
$condicion_permiso = "aprobado";
$fecha_fin = "15/03/2025";
$nombre_autoridad = "Carmen Delgado";
$cedula_autoriza = "V-8576270";

// Generar el contenido del PDF en HTML
echo '
<!DOCTYPE html>
<html>
<head>
    <style>
    <style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: white;
    margin: 0.5% auto;
    padding: 15px;
    border: 1px solid #888;
    width: 50%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
        .membrete {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }
        .membrete .left-logo {
            position: absolute;
            left: 5%;
            top: -4%;
        }
        .membrete .right-logo {
            position: absolute;
            right: 5%;
            top:-4%;
        }
        .content {
            font-family: Arial, sans-serif;
            margin: 10px;
            text-align: justify;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="membrete">
        <img src="./Assets/Images/mio.jpeg" alt="Logo 1" height="100" witdh="80" class="left-logo">
        <img src="./Assets/Images/Edupalcubo.png" alt="Logo 2" height="70" class="right-logo">
        <p>República Bolivariana de Venezuela</p>
        <p>Ministerio del Poder Popular para la Educación</p>
        <p>U.E.P Colegio Edupal</p>
        <p>La Victoria-Estado Aragua</p>
    </div><br>
    <div class="content">
        <h3 style="text-align: center;">Permiso de Reposo</h3><br>
        <p>Por medio de la presente Yo ' . $nombre_personal . ' ' . $apellido_personal . ', C.I. ' . $cedula_personal . ', quien trabaja en esta institución como: ' . $cargo_personal . ', deseo pedir permiso para ausentarme de la institución en los días ' . $fecha_inicio . ' por motivo de: ' . $motivo . '. Condición de permiso: ' . $condicion_permiso . '. Hasta los días: ' . $fecha_fin . '. Aprobado por: ' . $nombre_autoridad . '. C.I: ' . $cedula_autoriza . '.</p>
        
        <div class="signature-container">
            <div class="signature">
                <p>Firma del Solicitante:<br><br>_________________________<br>' . $nombre_personal . ' ' . $apellido_personal . '</p>
            </div>
            <div class="signature">
                <p>Firma de autoridad:<br><br>_________________________<br>' . $nombre_autoridad . ' <br>' . $cedula_autoriza . '</p>
            </div>
        </div>
    </div>
    
</body>
</html>
';
?>
