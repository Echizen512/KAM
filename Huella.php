<!DOCTYPE html>
<html lagn="es">
<meta charset="UTF-8">
    <head>
        <title>KAM</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="Assets/CSS/Huella.css">
        
    </head>

    <body>
        <canvas id="animated-bg"></canvas>

        <div class="kam-logo">
            <img src="Assets/Images/KAM.png" alt="Logo KAM" />
        </div>

        <div class="login-container">
            <div class="logo-container">
                <img src="Assets/Images/edupal.png" alt="Logo Edupal" />
            </div>

            <form action="validar_cedula.php" method="post">
                <div class="group">
                    <input type="text" id="cedula" name="cedula" required />
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label for="cedula">Ingrese su Cédula</label>
                </div>
                <button type="button" class="button" onclick="validarCedula()">Aceptar</button>
                <button type="button" class="button" onclick="window.location.href='index.php'">Volver</button>
            </form>
        </div>

        <script src="Assets/JavaScript/Canvas.js"></script>
        <script src="Assets/JavaScript/FunctionLogin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>