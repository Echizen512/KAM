<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $conn = new mysqli("localhost", "root", "", "base_kam");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Función para registrar acciones en la bitácora
    function registrarAccion($conn, $usuario, $correo, $accion) {
        $stmt = $conn->prepare("INSERT INTO bitacora (usuario_id, nombre_usuario, correo, accion, fecha_hora) VALUES ((SELECT id FROM usuarios WHERE usuario = ?), ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $usuario, $usuario, $correo, $accion);
        $stmt->execute();
        $stmt->close();
    }

    // Consulta para obtener el usuario, contraseña y nivel
    $stmt = $conn->prepare("SELECT id, contrasena, correo, nivel_usuario FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    $response = array();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $hashed_password, $correo, $nivel_usuario);
        $stmt->fetch();

        if (password_verify($contrasena, $hashed_password)) {
            // Almacenar los datos en las variables de sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['correo'] = $correo;
            $_SESSION['nivel_usuario'] = $nivel_usuario; // Agregamos el nivel del usuario (Administrador, Secretaria, RRHH, etc.)

            // Registra el inicio de sesión en la bitácora
            registrarAccion($conn, $usuario, $correo, "Inicio de sesión");

            // Incluye más datos en la respuesta
            $response['status'] = 'success';
            $response['message'] = 'Ingreso exitoso!';
            $response['redirect'] = 'Inicio.php';
            $response['nombre'] = $usuario;
            $response['correo'] = $correo;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Usuario no encontrado.';
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lagn="es">
<meta charset="UTF-8"> 
<div class align ="center">
<head>   
    <title>KAM</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="Assets/CSS/Login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="Assets/JavaScript/Login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

  <style>
    #animated-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
      display: block;
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-container {
      margin-bottom: 1rem;
    }

    #login-form {
      background: rgba(255, 255, 255, 0.9);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }

.floating-button-wrapper {
  position: fixed;
  bottom: 35px;
  right: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  z-index: 1000;
}

.floating-button {
  background-color: #2378b2;
  opacity: 0.6;
  border: none;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: opacity 0.3s ease;
  text-decoration: none;
}

.floating-button:hover {
  opacity: 0.85;
}

.hover-message {
  display: none;
  margin-bottom: 10px;
  background-color: #2378b2;
  color: white;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 0.8rem;
  white-space: nowrap;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  transform: translateX(0); /* evita desplazamiento lateral */
  max-width: 100vw; /* evita que se salga del viewport */
}

.floating-button-wrapper:hover .hover-message {
  display: block;
}


  </style>

<body>

  <canvas id="animated-bg"></canvas>

  <div class="login-wrapper">
    <div class="login-container">
      <img src="Assets/images/KAM.png" alt="Logo KAM" width="300" />
    </div>

  <form id="login-form" method="post" action="Login.php"> 
    <div class="form-group"> 
      <div class="group p-2">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" class="form-control px-4" required />
      </div>

      <div class="group p-2">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="contrasena" class="form-control px-4" required />
      </div>

      <button type="submit" class="button">Continuar</button>

      <div style="margin-top: 10px; text-align: center;">
        <a href="recuperar.php" style="color: #2378b2; text-decoration: none; font-size: 13px;"
          onmouseover="this.style.color='#5faee3';" 
          onmouseout="this.style.color='#2378b2';">
          ¿Olvidaste tu usuario o contraseña?
        </a>
      </div>
    </form>
  </div>
</div>  

<div class="floating-button-wrapper">
  <div class="hover-message">Inicio</div>
  <a href="index.php" class="floating-button">
    <i class="fas fa-house fa-lg" style="color: white"></i>
  </a>
</div>


  <script src="Assets/JavaScript/Canvas.js"></script>

    <script>
        function mostrarError(mensaje) {
            alert(mensaje);
        }
        window.onload = function() {
            <?php if (!empty($error)): ?>
                mostrarError("<?php echo $error; ?>");
            <?php endif; ?>
        }
    </script>


  <script>
    $(document).ready(function() {
      $("#login-form").submit(function(e) {
        e.preventDefault(); // Evita el envío del formulario por defecto

        // Validación básica del lado del cliente
        var usuario = $("#usuario").val();
        var contrasena = $("#contrasena").val();

        if (usuario === "" || contrasena === "") {
          alert("Por favor, complete todos los campos.");
          return false;
        }

        // Envío del formulario al servidor
        $.ajax({
          url: "./PHP/Login.php",
          type: "POST",
          data: $(this).serialize(),
          success: function(response) {
            if (response === "") {
              window.location.href = "Inicio.php";
            } else {
        

              alert(response);
            }
          }
        });
      });
    });
  </script>

</body>

</html>

