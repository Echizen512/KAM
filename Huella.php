<!DOCTYPE html>
<html lagn="es">
<meta charset="UTF-8">
    <head>
        <title>KAM</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

            <form action="" method="post">
                <div class="group">
                    <input type="text" class="form-control p-2" id="cedula" name="cedula" required />
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label for="cedula">Ingrese su Cédula</label>
                </div>
                <div class="button-group text-center">
                    <button type="button" class="btn btn-primary" onclick="validarCedula()">Aceptar</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='index.php'">Volver</button>
                </div>
            </form>
        </div>

        <script src="Assets/JavaScript/Canvas.js"></script>
        <script src="Assets/JavaScript/FunctionLogin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>