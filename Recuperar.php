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
.z-1 {
  z-index: 1;
}

.container-fluid {
  position: relative;
  z-index: 2;
  background-color: #f8f9fa; 
  backdrop-filter: none; 
}

html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#animated-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 0;
  pointer-events: none;
}

.login-wrapper {
  position: relative;
  z-index: 1;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}


    .login-container {
      margin-bottom: 1rem;
    }

    #formulario {
      background: #ffffff;
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
    <div class="login-container bg-white">
      <div class="logo-container text-center mb-3">
        <img src="./Assets/Images/edupal.png" width="160" height="80" alt="" border="0"/>
      </div>

      <form id="formulario" class="p-4 rounded shadow-sm">
        <h2 class="mb-4 mt-4 text-primary text-center">Actualizar Información</h2>

        <div class="mb-3">
          <select id="opcion" name="opcion" class="form-select" required>
            <option value="seleccione">Seleccione</option>
            <option value="usuario">Usuario</option>
            <option value="contrasena">Contraseña</option>
          </select>
        </div>

        <div class="mb-3">
          <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico" required>
        </div>

        <div id="campo_usuario" style="display: none;">
          <div class="mb-3">
            <input type="text" name="nuevo_usuario" id="nuevo_usuario" class="form-control" placeholder="Nuevo usuario">
          </div>
          <div class="mb-3">
            <input type="text" name="token_usuario" id="token_usuario" class="form-control" placeholder="introduzca el token recibido">
          </div>
        </div>

        <div id="campo_contrasena" style="display: none;">
          <div class="mb-3">
            <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" placeholder="Nueva contraseña">
          </div>
          <div class="mb-3">
            <input type="text" name="token_contrasena" id="token_contrasena" class="form-control" placeholder="introduzca el token recibido">
          </div>
        </div>

    <span id="contador_token" class="text-muted small d-block text-center mb-2" style="display: none;"></span>

    <div class="d-flex justify-content-center gap-3 mt-4">
      <button class="btn btn-outline-primary" type="button" id="enviar_token">Enviar Token</button>
      <button class="btn btn-primary" type="button" id="actualizar_dato">Actualizar</button>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let contadorIntervalo;

    // Mostrar campos según selección
    $("#opcion").change(function() {
        const opcion = $(this).val();
        if (opcion === "usuario") {
            $("#campo_usuario").show();
            $("#campo_contrasena").hide();
        } else if (opcion === "contrasena") {
            $("#campo_usuario").hide();
            $("#campo_contrasena").show();
        }
    });

    // Enviar token al correo
    $("#enviar_token").click(function() {
        const correo = $("#correo").val();

        $.post("PHP/enviar_token.php", { correo: correo }, function(response) {
            const res = JSON.parse(response);
            Swal.fire({
                icon: res.status === "success" ? "success" : "error",
                title: res.status === "success" ? "¡Éxito!" : "Error",
                text: res.message,
            });

            if (res.status === "success") {
                iniciarContador(60);
            }
        });
    });

    // Función para iniciar el contador
    function iniciarContador(segundos) {
        clearInterval(contadorIntervalo); 
        const contador = $("#contador_token");
        contador.removeClass("text-danger").addClass("text-muted").show();
        contador.text(`Token válido por ${segundos} segundos`);

        let tiempo = segundos;
        contadorIntervalo = setInterval(() => {
            tiempo--;
            if (tiempo > 0) {
                contador.text(`Token válido por ${tiempo} segundos`);
            } else {
                clearInterval(contadorIntervalo);
                contador.text("Token expirado");
                contador.removeClass("text-muted").addClass("text-danger");
            }
        }, 1000);
    }

    // Actualizar usuario o contraseña
    $("#actualizar_dato").click(function() {
        const correo = $("#correo").val();
        const opcion = $("#opcion").val();
        let data = { correo: correo };

        if (opcion === "usuario") {
            data.nuevo_usuario = $("#nuevo_usuario").val();
            data.token = $("#token_usuario").val();
            $.post("PHP/actualizar_usuario.php", data, function(response) {
                const res = JSON.parse(response);
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "¡Éxito!",
                        text: res.message,
                    });
                    $("#formulario")[0].reset();
                    $("#campo_usuario, #campo_contrasena").hide();
                    $("#contador_token").hide();
                    clearInterval(contadorIntervalo);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message,
                    });
                }
            });
        } else if (opcion === "contrasena") {
            data.nueva_contrasena = $("#nueva_contrasena").val();
            data.token = $("#token_contrasena").val();
            $.post("PHP/actualizar_contrasena.php", data, function(response) {
                const res = JSON.parse(response);
                if (res.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "¡Éxito!",
                        text: res.message,
                    });
                    $("#formulario")[0].reset();
                    $("#campo_usuario, #campo_contrasena").hide();
                    $("#contador_token").hide();
                    clearInterval(contadorIntervalo);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message,
                    });
                }
            });
        }
    });
});
</script>

    </body>
    
    </html>