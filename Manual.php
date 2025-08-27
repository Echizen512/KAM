<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAM</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }

        body {
            background-image: url("FONDO3.jpg");
            background-size: 110%;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: top center;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            position: relative;
            z-index: 10;
        }

        .logo img {
            height: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #2378b2;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #007BFF;
        }

        @keyframes titleAnimation {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .title {
            font-size: 60px;
            color: #2378b2;
            text-align: center;
            margin-top: 40px;
            animation: titleAnimation 1.5s ease-out forwards;
        }

        .title2 {
            font-size: 20px;
            color: #2378b2;
            text-align: center;
            margin-top: -10px;
            animation: titleAnimation 1.5s ease-out forwards;
        }

        .modulos-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 40px 20px;
            gap: 20px;
        }

        .modulo {
            width: 240px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-sizing: border-box;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .modulo:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modulo img {
            max-width: 100%;
            max-height: 150px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .modulo h2 {
            color: #2378B2;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .modulo p {
            color: #666;
            margin-bottom: 15px;
        }

        .modulo .ir-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #2378B2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .modulo .ir-button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Canvas animado -->
    <canvas id="animated-bg"></canvas>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="./Assets/Images/KAM.png" alt="KAM">
        </div>
        <nav>
            <ul>
                <li><a href="Manual.php">Inicio</a></li>
                <li><a href="Concepto.php">¿Qué es KAM?</a></li>
                <li><a href="Videos.php">Videos de Ayuda</a></li>
                <li><a href="modulos.php">Conoce Los Modulos</a></li>
            </ul>
        </nav>
    </header>

    <!-- Títulos -->
    <h1 class="title" style="padding-bottom: 12px;">Manual de Usuario</h1>
    <h6 class="title2">¿Cómo podemos ayudarte?</h6>

    <!-- Cards -->
    <div class="modulos-container">
        <div class="modulo">
            <img src="./Assets/Images/que_es_kam.png" alt="Imagen 1">
            <h2>¿Qué es KAM?</h2>
            <a href="Concepto.php".php" class="ir-button">Ir</a>
        </div>

        <div class="modulo">
            <img src="./Assets/Images/videos_ayuda.png" alt="Imagen 2">
            <h2>Videos de Ayuda</h2>
            <a href="Videos.php" class="ir-button">Ir</a>
        </div>

        <div class="modulo">
            <img src="./Assets/Images/modulos.png" alt="Imagen 3">
            <h2>Conoce Los Módulos</h2>
            <a href="Modulos.php" class="ir-button">Ir</a>
        </div>
    </div>

    <!-- Script para animar el fondo -->
    <script>
        const canvas = document.getElementById('animated-bg');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let circles = [];

        for (let i = 0; i < 40; i++) {
            circles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: Math.random() * 30 + 10,
                dx: (Math.random() - 0.5) * 0.8,
                dy: (Math.random() - 0.5) * 0.8,
                color: `rgba(35, 120, 182, ${Math.random() * 0.4 + 0.3})`
            });
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let c of circles) {
                ctx.beginPath();
                ctx.arc(c.x, c.y, c.radius, 0, Math.PI * 2);
                ctx.fillStyle = c.color;
                ctx.fill();
                c.x += c.dx;
                c.y += c.dy;

                if (c.x < 0 || c.x > canvas.width) c.dx *= -1;
                if (c.y < 0 || c.y > canvas.height) c.dy *= -1;
            }
            requestAnimationFrame(animate);
        }

        animate();
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>

</body>

</html>