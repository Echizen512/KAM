<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$conn = new mysqli("localhost", "root", "", "base_kam");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);

    if (!$correo) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, ingresa un correo válido.']);
        exit;
    }

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($usuario);
        $stmt->fetch();

        $token = mt_rand(100000, 999999);
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 minute"));

        $stmt2 = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, expiracion_token = ? WHERE correo = ?");
        $stmt2->bind_param("sss", $token, $expiracion, $correo);
        $stmt2->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'codelgado60@gmail.com';
            $mail->Password = 'jkeq egvu utip uasu'; // Cambia esto
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('codelgado60@gmail.com', 'KAM');
            $mail->addAddress($correo, $usuario);
            $mail->isHTML(true);
            $mail->Subject = 'Recupera el acceso a tu cuenta';
            $mail->Body = "Hola $usuario,<br>
                Usa el siguiente código para confirmar la actualización de tus datos: <b>$token</b><br>
                Este código expira en 1 minuto. <br><br>Si no solicitaste este cambio, te recomendamos ignorar este mensaje y mantener tus credenciales seguras.<br>

                Por tu seguridad, nunca compartas este código ni tus datos de acceso.<br><br>
                
                Gracias, Atentamente equipo KAM.";

            $mail->send();

            echo json_encode(['status' => 'success', 'message' => 'El código de recuperación ha sido enviado.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al enviar el correo.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado.']);
    }
}
?>
