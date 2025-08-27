<?php
    // Inicia la sesión antes de cualquier salida
    session_start();
    
    // Verifica si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['correo'])) {
        header("Location: Login.php"); // Redirige al login si no está autenticado
        exit;
    }
    
    // Obtén los datos del usuario desde la sesión
    $usuario = $_SESSION['usuario'];
    $correo = $_SESSION['correo'];
    ?>
    <style>
    
      .hover-message3 {
                display: none;
                background-color: rgba(51, 51, 51, 0.6); /* Fondo #333 con opacidad de 60% */
                color: white; /* Letras blancas */
                font-size: 15px;
                border-radius: 5px;
                padding: 4px;
                position: absolute;
                top: 50px; /* Ajustar según sea necesario */
                left: 40%;
                transform: translateX(-50%);
                z-index: 1;
                opacity: 0.9;
            }
    
            
            .hover-container3:hover .hover-message3 {
                display: block;
            }
    
            .container {
                margin: 0em 0em 0em 0em;
            }
    
                .logos {
                position: absolute;
                margin-top:30px;
                right: 20px;
                display: flex;
                gap: 20px; /* Espacio entre los logos */
            }
    .hover-container {
                position: relative;
                cursor: pointer;
                z-index: 1;
            }
            .hover-container img {
                width: 40px;
                height: 40px;
                transition: transform 0.3s ease;
            }
            .hover-container:hover img {
                transform: scale(1.2);
                z-index: 2;
            }
          .hover-container3 {
                position: relative;
                display: inline-block;
            }
            
    
    
    
    
     .menu-container {
          display: none;
          position: absolute;
          top: 80px;
          background-color: white;
          border: 1px solid #ccc;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
          z-index: 1;
          padding: 10px;
        }
    
        .menu-option {
          padding: 20px;
          cursor: pointer;
        }
    
        .menu-option:hover {
          background-color: #f0f0f0;
        }
    
    
    
            .logos a {
                display: inline-block;
            }
    
            .logos img {
                width: 40px;
                height: 40px;
            }
    
            .modalt {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 10px;
                width: 100%;
                height: 100%;
                overflow: auto;
                
                padding-top: 20px;
            }
    
            .modalt-contente {
                background-color: #fff;
                opacity: 90%;
                margin: 3% auto;
                padding: 20px;
                
                width: 70%;
                max-width: 600px;
                 border-radius: 15px;
                  border: 1px solid #2378b6;
        box-shadow: rgba(5,2,5,5.14902) 0px 2px 6px 0px;
                animation: zoom 0.3s;
            }
    
            @keyframes zoom {
                from {transform: scale(0)} 
                to {transform: scale(1)}
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
    
        </style>
    
    
    <div class="container">
        <div class="logos">
            <div class="hover-container3">
                <a href="#" id="userProfileButton">
                    <img src="usuario.png" alt="Perfil">
                    <div class="hover-message3">Perfil</div>
                </a>
            </div>
        </div>
    </div>
    <br>
    <div id="userProfileModal" class="modal">
        <div class="modalt-contente">
            <span class="close" id="closeProfile">&times;</span>
            <div align="left">
                <h2>Perfil de Usuario</h2>
                <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($correo); ?></p>
       
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal para el perfil de usuario
        const userProfileButton = document.getElementById('userProfileButton');
        const userProfileModal = document.getElementById('userProfileModal');
        const closeProfile = document.getElementById('closeProfile');
        userProfileButton.onclick = function() {
            userProfileModal.style.display = "block";
        }
        closeProfile.onclick = function() {
            userProfileModal.style.display = "none";
        }
    
       
        window.onclick = function(event) {
            if (event.target == userProfileModal) {
                userProfileModal.style.display = "none";
            }
         
        }
    </script>